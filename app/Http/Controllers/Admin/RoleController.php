<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        // Get roles using Spatie Permission
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();
        
        // Manually count users for each role
        foreach ($roles as $role) {
            $role->users_count = $role->users()->count();
        }
        
        // Get statistics
        $stats = [
            'total_roles' => $roles->count(),
            'total_users' => \App\Models\User::count(),
            'total_permissions' => $permissions->count(),
        ];
        
        return view('admin.roles.index', compact('roles', 'permissions', 'stats'));
    }

    /**
     * Get roles for datatable
     */
    public function datatable(Request $request)
    {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        
        $data = [];
        foreach ($roles as $role) {
            $data[] = [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->name, // Spatie uses 'name' not 'slug'
                'users_count' => $role->users()->count(),
                'permissions_count' => $role->permissions->count(),
                'created_at' => $role->created_at->format('Y-m-d'),
            ];
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Get users with roles for datatable
     */
    public function usersDatatable(Request $request)
    {
        $users = User::with(['roles', 'permissions'])
            ->withCount(['bookings', 'roles', 'permissions'])
            ->get();
        
        $data = [];
        foreach ($users as $user) {
            $roleNames = $user->roles->pluck('name')->join(', ');
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $roleNames ?: 'No Role',
                'bookings_count' => $user->bookings_count,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/assets/img/avatars/1.png'),
                'status' => $user->email_verified_at ? 'active' : 'inactive',
            ];
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->where('guard_name', 'web')],
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->where('guard_name', 'web')->ignore($role->id)],
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete role with assigned users'
            ], 422);
        }

        $role->syncPermissions([]);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * Get role details for editing
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::all();
        
        return response()->json([
            'success' => true,
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    /**
     * Get all permissions
     */
    public function getPermissions()
    {
        $permissions = Permission::all();
        return response()->json(['permissions' => $permissions]);
    }

    /**
     * Export roles
     */
    public function export()
    {
        $roles = Role::with(['permissions'])->get();
        
        $filename = 'roles_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($roles) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Name', 'Guard Name', 'Users Count', 'Permissions Count', 'Permissions', 'Created At']);
            
            // Data
            foreach ($roles as $role) {
                fputcsv($file, [
                    $role->id,
                    $role->name,
                    $role->guard_name,
                    $role->users()->count(),
                    $role->permissions->count(),
                    $role->permissions->pluck('name')->join(', '),
                    $role->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

