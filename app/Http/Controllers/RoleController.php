<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    // Apply middleware for permission-based access control
    public function __construct()
    {
        $this->middleware('permission:عرض صلاحية', ['only' => ['index']]);
        $this->middleware('permission:اضافة صلاحية', ['only' => ['create', 'store']]);
        $this->middleware('permission:تعديل صلاحية', ['only' => ['edit', 'update']]);
        $this->middleware('permission:حذف صلاحية', ['only' => ['destroy']]);
    }

    // Show list of roles with pagination
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5); // for index number in the view
    }

    // Show form for creating a new role
    public function create()
    {
        $permissions = Permission::all(); // get all permissions
        return view('roles.create', compact('permissions'));
    }

    // Store newly created role and assign permissions
    public function store(Request $request)
    {
        // Validate role name and permissions
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);

        // Create the role
        $role = Role::create(['name' => $request->input('name')]);

        // Sync selected permissions with the new role
        $permissions = Permission::whereIn('id', $request->input('permission'))->get();
        $role->syncPermissions($permissions);

        // Redirect with notification
        return redirect()->route('roles.index')->with('notif', [
            'msg' => 'Role created successfully.',
            'type' => 'success'
        ]);
    }

    // Show details of a specific role and its permissions
    public function show($id)
    {
        $role = Role::findOrFail($id);

        // Get permissions for this role using a join
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    // Show form to edit a role and its permissions
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        // Get permission IDs currently assigned to this role
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id')
            ->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update a role's name and its permissions
    public function update(Request $request, $id)
    {
        // Validate name and permissions
        $request->validate([
            'name' => 'required',
            'permission' => 'required|array',
        ]);

        // Update the role's name
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->input('name')]);

        // Sync new permissions
        $permissions = Permission::whereIn('id', $request->input('permission'))->get();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('notif', [
            'msg' => 'Role updated successfully.',
            'type' => 'success'
        ]);
    }

    // Delete a role
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return redirect()->route('roles.index')->with('notif', [
            'msg' => 'Role deleted successfully.',
            'type' => 'success'
        ]);
    }
}
