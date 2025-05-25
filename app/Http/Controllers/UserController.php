<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 🔐 Protect this controller with authentication and permissions
    public function __construct()
    {
//        $this->middleware(['auth', 'role:admin']);
    }

    // 📄 Display all users
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // ➕ Show form to create new user
    public function create()
    {
        return view('users.create');
    }

    // 💾 Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // 👁️ View single user (optional)
    public function show(User $user)
    {
        return view(view: 'users.show', data: compact('user'));
    }

    // ✏️ Edit user form
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 🔄 Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // ❌ Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
