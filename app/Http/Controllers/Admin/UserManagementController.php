<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;

class UserManagementController extends BaseAdminController
{
    /**
     * Display all users with advanced features
     */
    public function index(Request $request)
    {
        $roles = SpatieRole::all();
        
        // For AJAX datatable requests
        if ($request->ajax() || $request->wantsJson()) {
            return $this->datatable($request);
        }
        
        // Get statistics
        $stats = [
            'total' => User::count(),
            'active' => User::whereNotNull('email_verified_at')->count(),
            'inactive' => User::whereNull('email_verified_at')->count(),
            'dormant' => User::whereNull('email_verified_at')
                ->orWhere(function($query) {
                    $query->where('email_verified_at', '<', now()->subMonths(6));
                })->count(),
            'by_role' => DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->select('roles.name', DB::raw('count(*) as count'))
                ->groupBy('roles.name')
                ->get(),
        ];
        
        return view('admin.users.index', compact('roles', 'stats'));
    }

    /**
     * Get users for DataTable
     */
    public function datatable(Request $request)
    {
        $query = User::with(['roles', 'permissions'])
            ->withCount(['bookings', 'roles', 'permissions']);

        // Search
        if ($request->filled('search.value')) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                // Active users: verified email
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                // Inactive/Dormant: unverified OR verified more than 6 months ago
                $query->where(function($q) {
                    $q->whereNull('email_verified_at')
                      ->orWhere('email_verified_at', '<', now()->subMonths(6));
                });
            }
        }

        // Ordering
        $orderColumn = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $columns = ['id', 'name', 'email', 'created_at', 'email_verified_at'];
        $orderBy = $columns[$orderColumn] ?? 'created_at';
        $query->orderBy($orderBy, $orderDir);

        $users = $query->get();

        $data = [];
        foreach ($users as $user) {
            $roleNames = $user->roles->pluck('name')->join(', ');
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '-',
                'roles' => $roleNames ?: 'No Role',
                'roles_count' => $user->roles_count,
                'permissions_count' => $user->permissions_count,
                'bookings_count' => $user->bookings_count,
                'status' => $user->email_verified_at ? 'active' : 'inactive',
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'created_at' => $user->created_at->format('Y-m-d H:i'),
                'last_login' => 'N/A',
            ];
        }

        // Return data array for DataTables (serverSide: false)
        return response()->json(['data' => $data]);
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $roles = SpatieRole::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            // Group by module (extract from permission name)
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'General';
        });
        
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'email_verified' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        if ($request->filled('email_verified')) {
            $validated['email_verified_at'] = now();
        }
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        $roles = $validated['roles'];
        $permissions = $validated['permissions'] ?? [];
        unset($validated['roles'], $validated['permissions'], $validated['email_verified']);
        
        $user = User::create($validated);
        $user->assignRole($roles);
        
        if (!empty($permissions)) {
            $user->givePermissionTo($permissions);
        }
        
        return $this->successResponse('User created successfully!', route('admin.users.index'));
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = User::with(['roles', 'permissions'])->findOrFail($id);
        $roles = SpatieRole::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'General';
        });
        
        // Get user statistics
        $stats = [
            'bookings' => Booking::where('user_id', $user->id)->count(),
            'total_spent' => Booking::where('user_id', $user->id)->sum('total_price'),
            'last_booking' => Booking::where('user_id', $user->id)->latest()->first(),
        ];
        
        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'stats'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'email_verified' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        if ($request->filled('email_verified')) {
            $validated['email_verified_at'] = now();
        } elseif ($request->has('email_verified') && !$request->email_verified) {
            $validated['email_verified_at'] = null;
        }
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        $roles = $validated['roles'];
        $permissions = $validated['permissions'] ?? [];
        unset($validated['roles'], $validated['permissions'], $validated['email_verified']);
        
        $user->update($validated);
        $user->syncRoles($roles);
        $user->syncPermissions($permissions);
        
        return $this->successResponse('User updated successfully!', route('admin.users.index'));
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return $this->errorResponse('You cannot delete your own account!');
        }
        
        // Delete avatar if exists
        if ($user->avatar) {
            \Storage::disk('public')->delete($user->avatar);
        }
        
        $user->delete();
        
        return $this->successResponse('User deleted successfully!', route('admin.users.index'));
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,assign_role,remove_role',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        $users = User::whereIn('id', $userIds);

        switch ($request->action) {
            case 'delete':
                // Prevent deleting yourself
                $users = $users->where('id', '!=', auth()->id())->get();
                foreach ($users as $user) {
                    if ($user->avatar) {
                        \Storage::disk('public')->delete($user->avatar);
                    }
                    $user->delete();
                }
                $message = count($users) . ' users deleted successfully!';
                break;

            case 'activate':
                $users->update(['email_verified_at' => now()]);
                $message = count($userIds) . ' users activated successfully!';
                break;

            case 'deactivate':
                $users->where('id', '!=', auth()->id())
                    ->update(['email_verified_at' => null]);
                $message = count($userIds) . ' users deactivated successfully!';
                break;

            case 'assign_role':
                $request->validate(['role' => 'required|exists:roles,name']);
                $role = SpatieRole::where('name', $request->role)->first();
                $users->get()->each->assignRole($role);
                $message = 'Role assigned to ' . count($userIds) . ' users successfully!';
                break;

            case 'remove_role':
                $request->validate(['role' => 'required|exists:roles,name']);
                $role = SpatieRole::where('name', $request->role)->first();
                $users->get()->each->removeRole($role);
                $message = 'Role removed from ' . count($userIds) . ' users successfully!';
                break;
        }

        return $this->successResponse($message, route('admin.users.index'));
    }

    /**
     * Export users
     */
    public function export(Request $request)
    {
        $users = User::with('roles')->get();
        
        $filename = 'users_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Roles', 'Status', 'Created At']);
            
            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? '',
                    $user->roles->pluck('name')->join(', '),
                    $user->email_verified_at ? 'Active' : 'Inactive',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get user details (AJAX)
     */
    public function show($id)
    {
        $user = User::with(['roles', 'permissions'])
            ->withCount(['bookings', 'roles', 'permissions'])
            ->findOrFail($id);
        
        $stats = [
            'bookings' => Booking::where('user_id', $user->id)->count(),
            'total_spent' => Booking::where('user_id', $user->id)->sum('total_price'),
            'last_booking' => Booking::where('user_id', $user->id)->latest()->first(),
        ];
        
        return response()->json([
            'success' => true,
            'user' => $user,
            'stats' => $stats
        ]);
    }
}
