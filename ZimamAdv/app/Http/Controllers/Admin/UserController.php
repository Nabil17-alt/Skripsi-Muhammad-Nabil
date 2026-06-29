<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderByDesc('created_at')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $currentUserRole = auth()->user()->role->name ?? '';
        if ($currentUserRole === 'admin') {
            $roles = Role::where('name', 'customer')->get();
        } elseif ($currentUserRole === 'pimpinan') {
            $roles = Role::where('name', 'admin')->get();
        } else {
            $roles = collect();
        }

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $currentUserRole = auth()->user()->role->name ?? '';

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:tb_users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:tb_roles,id',
            'allowed_tenor' => 'required|in:3,6,12',
        ]);

        $targetRole = Role::findOrFail($request->role_id);

        if ($currentUserRole === 'admin' && $targetRole->name !== 'customer') {
            return back()->withErrors(['role_id' => 'Admin hanya diperbolehkan membuat akun Customer.'])->withInput();
        }

        if ($currentUserRole === 'pimpinan' && $targetRole->name !== 'admin') {
            return back()->withErrors(['role_id' => 'Pimpinan hanya diperbolehkan membuat akun Admin.'])->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->boolean('is_active', true),
            'allowed_tenor' => $request->input('allowed_tenor', 3),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $this->checkAuthority($user);

        $currentUserRole = auth()->user()->role->name ?? '';
        if ($currentUserRole === 'admin') {
            $roles = Role::where('name', 'customer')->get();
        } elseif ($currentUserRole === 'pimpinan') {
            $roles = Role::where('name', 'admin')->get();
        } else {
            $roles = collect();
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function show(User $user)
    {
        $this->checkAuthority($user);

        $roles = Role::all();
        $orders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.users.show', compact('user', 'roles', 'orders'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAuthority($user);

        $currentUserRole = auth()->user()->role->name ?? '';

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:tb_users,email,' . $user->id,
            'role_id' => 'required|exists:tb_roles,id',
            'allowed_tenor' => 'required|in:3,6,12',
        ]);

        $targetRole = Role::findOrFail($request->role_id);

        if ($currentUserRole === 'admin' && $targetRole->name !== 'customer') {
            return back()->withErrors(['role_id' => 'Admin hanya diperbolehkan mengedit akun ke role Customer.'])->withInput();
        }

        if ($currentUserRole === 'pimpinan' && $targetRole->name !== 'admin') {
            return back()->withErrors(['role_id' => 'Pimpinan hanya diperbolehkan mengedit akun ke role Admin.'])->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'is_active' => $request->boolean('is_active', true),
            'allowed_tenor' => $request->input('allowed_tenor', 3),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->checkAuthority($user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Akun berhasil dihapus.');
    }

    public function resetPassword(User $user)
    {
        $this->checkAuthority($user);

        $newPassword = 'password123';

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'Password berhasil direset ke: ' . $newPassword);
    }

    private function checkAuthority(User $user)
    {
        $currentUserRole = auth()->user()->role->name ?? '';
        $targetUserRole = $user->role->name ?? '';

        if ($currentUserRole === 'admin') {
            if ($targetUserRole !== 'customer') {
                abort(403, 'Anda tidak memiliki wewenang untuk mengelola akun ini.');
            }
        } elseif ($currentUserRole === 'pimpinan') {
            if ($targetUserRole !== 'admin') {
                abort(403, 'Anda tidak memiliki wewenang untuk mengelola akun ini.');
            }
        } else {
            abort(403);
        }
    }
}
