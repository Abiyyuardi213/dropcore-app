<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = User::whereHas('role', function($q){
                $q->where('role_name', 'staff');
            })
            ->with(['role','divisi','jabatan'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        $divisis = Divisi::all();
        $jabatans = Jabatan::all();

        $role_staff_id = Role::where('role_name', 'staff')->value('id');

        return view('pegawai.create', compact('divisis', 'jabatans', 'role_staff_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'nik'               => 'nullable|unique:users,nik',
            'username'          => 'required|unique:users,username',
            'email'             => 'nullable|email|unique:users,email',
            'password'          => 'required|min:6',
            'divisi_id'         => 'nullable|exists:divisi,id',
            'jabatan_id'        => 'nullable|exists:jabatan,id',
            'tanggal_bergabung' => 'nullable|date',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'status_kepegawaian'=> 'nullable|in:aktif,nonaktif',
            'profile_picture'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cropped_image'     => 'nullable|string'
        ]);

        $data = $request->except(['profile_picture', 'cropped_image']);
        $data['role_id'] = Role::where('role_name', 'staff')->value('id');
        $data['password'] = bcrypt($request->password);

        if ($request->filled('cropped_image')) {

            $cropped = $request->cropped_image;
            list($type, $cropped) = explode(';', $cropped);
            list(, $cropped) = explode(',', $cropped);

            $cropped = base64_decode($cropped);
            $filename = uniqid().'.png';

            $path = public_path('uploads/profile/'.$filename);
            if (!file_exists(public_path('uploads/profile'))) {
                mkdir(public_path('uploads/profile'), 0777, true);
            }

            file_put_contents($path, $cropped);

            $data['profile_picture'] = $filename;

        } elseif ($request->hasFile('profile_picture')) {

            $file = $request->file('profile_picture');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);

            $data['profile_picture'] = $filename;
        }

        User::createPengguna($data);

        return redirect()->route('pegawai.index')
            ->with('success','Pegawai berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pegawai = User::findOrFail($id);

        $divisis = Divisi::all();
        $jabatans = Jabatan::all();

        return view('pegawai.edit', compact('pegawai','divisis','jabatans'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $request->validate([
            'name'      => 'required',
            'nik'       => 'nullable|unique:users,nik,' . $pegawai->id,
            'username'  => 'required|unique:users,username,' . $pegawai->id,
            'email'     => 'nullable|email|unique:users,email,' . $pegawai->id,
            'divisi_id' => 'nullable|exists:divisi,id',
            'jabatan_id'=> 'nullable|exists:jabatan,id',
            'tanggal_bergabung' => 'nullable|date',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'status_kepegawaian'=> 'nullable|in:aktif,nonaktif',
            'profile_picture'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cropped_image'     => 'nullable|string'
        ]);

        $data = $request->except(['password','profile_picture','cropped_image']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->filled('cropped_image')) {

            $cropped = $request->cropped_image;
            list($type, $cropped) = explode(';', $cropped);
            list(, $cropped) = explode(',', $cropped);

            $cropped = base64_decode($cropped);
            $filename = uniqid().'.png';

            if (!file_exists(public_path('uploads/profile'))) {
                mkdir(public_path('uploads/profile'), 0777, true);
            }

            $path = public_path('uploads/profile/'.$filename);
            file_put_contents($path, $cropped);

            if ($pegawai->profile_picture && file_exists(public_path('uploads/profile/'.$pegawai->profile_picture))) {
                unlink(public_path('uploads/profile/'.$pegawai->profile_picture));
            }

            $data['profile_picture'] = $filename;

        } elseif ($request->hasFile('profile_picture')) {

            if ($pegawai->profile_picture && file_exists(public_path('uploads/profile/'.$pegawai->profile_picture))) {
                unlink(public_path('uploads/profile/'.$pegawai->profile_picture));
            }

            $file = $request->file('profile_picture');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);

            $data['profile_picture'] = $filename;
        }

        $pegawai->updatePengguna($data);

        return redirect()->route('pegawai.index')
            ->with('success','Pegawai berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pegawai = User::findOrFail($id);
        $pegawai->deletePengguna();

        return redirect()->route('pegawai.index')
            ->with('success','Pegawai berhasil dihapus');
    }

    public function show($id)
    {
        $pegawai = User::with(['role','divisi','jabatan'])->findOrFail($id);
        return view('pegawai.show', compact('pegawai'));
    }
}
