<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

// Manages roles and permission assignments
class RoleController extends Controller
{

    // List all roles with permissions
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    // Show role creation form with permissions grouped by module
    public function create()
    {
        $permissions = Permission::orderBy('name')->get()
            ->groupBy(function ($perm) {
                return explode('-', $perm->name)[0];
            });

        $operations = Permission::all()->map(function ($perm) {
            $parts = explode('-', $perm->name);
            return array_pop($parts);
        })->unique()->sort()->values();

        return view('roles.create', compact('permissions', 'operations'));
    }

    // Create new role with selected permissions
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    // Display role with its permissions
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('roles.show', compact('role'));
    }

    // Show role edit form with permission options
    public function edit($id)
    {
        try {
            $roleId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404); // invalid or tampered ID
        }

        $role = Role::findOrFail($roleId);

        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[0];
        });

        $operations = Permission::all()->map(function ($perm) {
            $parts = explode('-', $perm->name);
            return array_pop($parts);
        })->unique()->sort()->values();

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact(
            'role',
            'permissions',
            'operations',
            'rolePermissions'
        ));
    }

    // Update role name and permissions (protects admin role)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);

            $role->update([
                'name' => $request->name
            ]);

            $permissions = $request->permissions ?? [];

            // Preserve admin role's core permissions
            if (strtolower($role->name) === 'admin') {
                $existingPerms = $role->permissions->pluck('name')->toArray();
                $protectedModules = ['role', 'user', 'permission'];

                foreach ($existingPerms as $perm) {
                    $module = explode('-', $perm)[0];
                    if (in_array($module, $protectedModules)) {
                        if (!in_array($perm, $permissions)) {
                            $permissions[] = $perm;
                        }
                    }
                }
            }

            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while updating role');
        }
    }

    // Delete role (prevents admin role deletion)
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if ($role->name === 'Admin') {
                return redirect()->back()
                    ->with('error', 'Admin role cannot be deleted');
            }

            $role->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong');
        }
    }
}
