<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        // For AJAX datatable requests
        if ($request->ajax() || $request->wantsJson()) {
            return $this->datatable($request);
        }
        
        $permissions = Permission::with('roles')->get();
        $roles = Role::all();
        return view('admin.permissions.index', compact('permissions', 'roles'));
    }

    /**
     * Get permissions for datatable
     */
    public function datatable(Request $request)
    {
        $permissions = Permission::with('roles')->get();
        
        $data = [];
        foreach ($permissions as $permission) {
            $roleNames = $permission->roles->pluck('name')->join(', ');
            $data[] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'slug' => $permission->name, // Spatie uses 'name' not 'slug'
                'module' => 'General', // Spatie doesn't have module field
                'assigned_to' => $roleNames ?: 'No roles',
                'roles_count' => $permission->roles->count(),
                'created_at' => $permission->created_at->format('Y-m-d'),
            ];
        }

        // Return data in format expected by DataTables
        return response()->json(['data' => $data]);
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->where('guard_name', 'web')],
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->where('guard_name', 'web')->ignore($permission->id)],
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
        ]);

        $permission->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'permission' => $permission
        ]);
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to roles
        if ($permission->roles()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete permission assigned to roles'
            ], 422);
        }

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }

    /**
     * Get permission details for editing
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        
        return response()->json([
            'success' => true,
            'permission' => $permission
        ]);
    }

    /**
     * Export permissions
     */
    public function export()
    {
        $permissions = Permission::with('roles')->get();
        
        $filename = 'permissions_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($permissions) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Name', 'Guard Name', 'Assigned Roles', 'Roles Count', 'Created At']);
            
            // Data
            foreach ($permissions as $permission) {
                fputcsv($file, [
                    $permission->id,
                    $permission->name,
                    $permission->guard_name,
                    $permission->roles->pluck('name')->join(', '),
                    $permission->roles->count(),
                    $permission->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

