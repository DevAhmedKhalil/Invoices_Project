<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name')->all(); // نستخدم اسم الدور فقط
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|same:confirm-password',
            'confirm-password' => 'required|string|min:6',
            'status' => 'required|in:مفعل,غير مفعل',
            'roles' => 'required|array|min:1'
        ]);

        // استخرج فقط الحقول المسموح بها
        $data = $request->only(['name', 'email', 'password', 'status']);
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // أضف الصلاحيات للمستخدم
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')->with('notif', [
            'msg' => 'تم إنشاء المستخدم بنجاح',
            'type' => 'success'
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name')->all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|same:confirm-password',
            'confirm-password' => 'nullable|string|min:6',
            'status' => 'required|in:مفعل,غير مفعل',
            'roles' => 'required|array|min:1'
        ]);

        $user = User::findOrFail($id);

        // استخرج فقط الحقول المسموح بها
        $data = $request->only(['name', 'email', 'password', 'status']);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // حدث الصلاحيات
        $user->syncRoles($request->input('roles'));

        return redirect()->route('users.index')->with('notif', [
            'msg' => 'تم تعديل المستخدم بنجاح',
            'type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('users.index')->with('notif', [
            'msg' => 'تم حذف المستخدم بنجاح',
            'type' => 'success'
        ]);
    }
}
