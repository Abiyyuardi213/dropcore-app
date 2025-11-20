<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'asc')->with('role')->get();
        return view('user.userList', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'role_id' => 'required|exists:role,id',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $data['profile_picture'] = $filename;
        }

        User::createPengguna($data);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'role_id' => 'required|exists:role,id',
        ]);

        $data = $request->only([
            'name', 'username', 'email', 'no_telepon', 'password', 'role_id'
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $data['profile_picture'] = $filename;
        }

        $user->updatePengguna($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('user.show', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->deletePengguna();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    public function profil()
    {
        $user = auth()->user();
        return view('user.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => ['required', 'string', Rule::unique('users','username')->ignore($user->id)],
            'email'      => ['nullable','email', Rule::unique('users','email')->ignore($user->id)],
            'no_telepon' => 'nullable|string|max:20',
            'password'   => 'nullable|min:6',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->only('name','username','email','no_telepon');

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && file_exists(public_path('uploads/profile/'.$user->profile_picture))) {
                unlink(public_path('uploads/profile/'.$user->profile_picture));
            }

            $file = $request->file('profile_picture');
            $filename = uniqid().'.jpg'; // pakai jpg hasil crop
            $file->move(public_path('uploads/profile'), $filename);

            $data['profile_picture'] = $filename;
        }

        $user->update($data);

        return redirect()->route('user.profil')->with('success','Profil berhasil diperbarui');
    }

    public function profilCustomer()
    {
        $user = auth()->user();

        if ($user->role->role_name !== 'customer') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('customer.profil-client', compact('user'));
    }

    public function updateProfilCustomer(Request $request)
    {
        $user = auth()->user();

        if ($user->role->role_name !== 'customer') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', Rule::unique('users','username')->ignore($user->id)],
            'email' => ['nullable','email', Rule::unique('users','email')->ignore($user->id)],
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
            'cropped_image' => 'nullable|string',
        ]);

        $data = $request->only('name','username','email','no_telepon');

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->filled('cropped_image')) {
            $croppedImage = $request->input('cropped_image');

            list($type, $croppedImage) = explode(';', $croppedImage);
            list(, $croppedImage) = explode(',', $croppedImage);

            $croppedImage = base64_decode($croppedImage);

            $filename = uniqid() . '.png';
            $path = public_path('uploads/profile') . '/' . $filename;

            if (!file_exists(public_path('uploads/profile'))) {
                mkdir(public_path('uploads/profile'), 0777, true);
            }

            if (file_put_contents($path, $croppedImage)) {
                if ($user->profile_picture && file_exists(public_path('uploads/profile/'.$user->profile_picture))) {
                    unlink(public_path('uploads/profile/'.$user->profile_picture));
                }

                $data['profile_picture'] = $filename;
            }
        }

        $user->update($data);
        return redirect()->route('customer.profil')->with('success', 'Profil berhasil diperbarui');
    }
}
