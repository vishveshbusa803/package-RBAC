<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Manages permissions for role-based access control (module-operation format)
class PermissionController extends Controller
{
    // List all permissions
    public function index()
    {
        $permissions = Permission::get();
        return view('permissions.index', compact('permissions'));
    }

    // Show permission creation form
    public function create()
    {
        return view('permissions.create');
    }

    // Create permissions for a module with multiple operations
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'operations' => 'required|array|min:1',
            'operations.*' => 'required|string|max:255|regex:/^[a-z]+$/'
        ], [
            'operations.*.regex' => 'Each operation must contain only lowercase letters.',
        ]);

        $permissionName = $request->input('name');
        $operations = array_filter($request->input('operations', []));

        $adminRole = Role::where('name', 'admin')->first();

        // Create permission for each operation and assign to admin
        foreach ($operations as $operation) {
            $name = $permissionName . '-' . $operation;
            $permission = Permission::firstOrCreate(['name' => $name]);

            if ($adminRole) {
                $adminRole->givePermissionTo($permission);
            }
        }

        return redirect()->route('permissions.index')->with('success', 'Permissions created successfully.');
    }

    // Show permission edit form with operations
    public function edit($id)
    {
        try {
            $permissionId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404); // invalid or tampered ID
        }

        $permission = Permission::findOrFail($permissionId);

        $parts = explode('-', $permission->name);
        $operation = array_pop($parts);
        $baseName = implode('-', $parts);

        $basePermissions = Permission::where('name', 'like', $baseName . '-%')->get();
        $operations = $basePermissions->map(function ($perm) use ($baseName) {
            return str_replace($baseName . '-', '', $perm->name);
        })->toArray();

        return view('permissions.edit', compact('permission', 'baseName', 'operation', 'operations'));
    }

    // Update permission module and operations with assignment validation
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'operations' => 'required|array|min:1',
            'operations.*' => 'required|string|max:255|regex:/^[a-z]+$/'
        ], [
            'operations.*.regex' => 'Each operation must contain only lowercase letters.',
        ]);

        $oldBaseName = explode('-', $permission->name)[0];
        $newBaseName = $request->input('name');
        $operations = array_filter($request->input('operations', []));

        $oldPermissions = Permission::where('name', 'like', $oldBaseName . '-%')->get();

        // If module name changed, check for assignments before deleting
        if ($oldBaseName !== $newBaseName) {
            foreach ($oldPermissions as $oldPerm) {
                if ($oldPerm->roles()->exists() || $oldPerm->users()->exists()) {
                    return redirect()->route('permissions.index')->with('error', 'Cannot update permission(s) that are assigned to roles or users. Please remove the permission from all roles and users first.');
                }
            }
            Permission::where('name', 'like', $oldBaseName . '-%')->delete();
        } else {
            // Remove operations no longer in list
            $newPermissionNames = array_map(fn($op) => $newBaseName . '-' . $op, $operations);
            foreach ($oldPermissions as $oldPerm) {
                if (!in_array($oldPerm->name, $newPermissionNames)) {
                    if ($oldPerm->roles()->exists() || $oldPerm->users()->exists()) {
                        return redirect()->route('permissions.index')->with('error', 'Cannot delete permission "' . $oldPerm->name . '" that is assigned to roles or users. Please remove it first.');
                    }
                    $oldPerm->delete();
                }
            }
        }

        // Create new permissions and assign to admin role
        $adminRole = Role::where('name', 'admin')->first();
        foreach ($operations as $operation) {
            $name = $newBaseName . '-' . $operation;
            $newPermission = Permission::firstOrCreate(['name' => $name]);

            if ($adminRole && !$newPermission->roles->contains($adminRole)) {
                $adminRole->givePermissionTo($newPermission);
            }
        }

        return redirect()->route('permissions.index')->with('success', 'Permissions updated successfully.');
    }

    // Delete permission and all related operations if not assigned
    public function destroy(Permission $permission)
    {
        $parts = explode('-', $permission->name);
        $operation = array_pop($parts);
        $baseName = implode('-', $parts);

        $permissionsToDelete = Permission::where('name', 'like', $baseName . '-%')->get();

        // Prevent deletion if assigned to roles or users
        foreach ($permissionsToDelete as $perm) {
            if ($perm->roles()->exists() || $perm->users()->exists()) {
                return redirect()->route('permissions.index')->with('error', 'Cannot delete permission(s) that are assigned to roles or users. Please remove the permission from all roles and users first.');
            }
        }

        Permission::where('name', 'like', $baseName . '-%')->delete();

        return redirect()->route('permissions.index')->with('success', 'Permissions deleted successfully.');
    }
}
