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
        ]);

        $data = $request->all();
        $data['role_id'] = Role::where('role_name', 'staff')->value('id');
        $data['password'] = bcrypt($request->password);

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
        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
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
}
