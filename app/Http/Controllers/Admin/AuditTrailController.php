<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AuditTrail;
use App\Models\User;
use Carbon\Carbon;

class AuditTrailController extends BaseAdminController
{
    /**
     * Display audit trails
     */
    public function index(Request $request)
    {
        try {
            $query = AuditTrail::with('user');
            
            // Filters
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }
            
            if ($request->filled('module')) {
                $query->where('module', $request->module);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('model_type', 'like', "%{$search}%")
                      ->orWhere('model_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('route', 'like', "%{$search}%");
                });
            }
            
            $audits = $query->orderBy('created_at', 'desc')->paginate(50);
            
            // Statistics
            $stats = [
                'total' => AuditTrail::count(),
                'today' => AuditTrail::whereDate('created_at', today())->count(),
                'this_week' => AuditTrail::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                'this_month' => AuditTrail::whereMonth('created_at', Carbon::now()->month)->count(),
                'by_action' => AuditTrail::select('action', DB::raw('COUNT(*) as count'))
                    ->groupBy('action')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
                'by_module' => AuditTrail::select('module', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('module')
                    ->groupBy('module')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
                'top_users' => AuditTrail::select('user_id', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('user_id')
                    ->groupBy('user_id')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->with('user')
                    ->get(),
            ];
            
            $users = User::orderBy('name')->get();
            $actions = AuditTrail::distinct()->pluck('action')->sort()->values();
            $modules = AuditTrail::whereNotNull('module')->distinct()->pluck('module')->sort()->values();
            
            return view('admin.settings.audit-trails', compact('audits', 'users', 'stats', 'actions', 'modules'));
        } catch (\Exception $e) {
            // Table doesn't exist yet
            $audits = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                50,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $users = User::orderBy('name')->get();
            $stats = [
                'total' => 0,
                'today' => 0,
                'this_week' => 0,
                'this_month' => 0,
                'by_action' => collect([]),
                'by_module' => collect([]),
                'top_users' => collect([]),
            ];
            $actions = collect([]);
            $modules = collect([]);
            
            return view('admin.settings.audit-trails', compact('audits', 'users', 'stats', 'actions', 'modules'))->with('error', 'Audit trail table does not exist. Please run migrations.');
        }
    }

    /**
     * Show audit trail details
     */
    public function show($id)
    {
        $audit = AuditTrail::with('user')->findOrFail($id);
        return view('admin.settings.audit-trail-show', compact('audit'));
    }

    /**
     * Export audit trails
     */
    public function export(Request $request)
    {
        // Similar query as index but without pagination
        $query = AuditTrail::with('user');
        
        // Apply same filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $audits = $query->orderBy('created_at', 'desc')->limit(10000)->get();
        
        $filename = 'audit_trails_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($audits) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Date', 'User', 'Action', 'Module', 'Model', 'Description', 'Status', 'IP Address', 'Route']);
            
            // Data
            foreach ($audits as $audit) {
                fputcsv($file, [
                    $audit->id,
                    $audit->created_at->format('Y-m-d H:i:s'),
                    $audit->user ? $audit->user->name : 'System',
                    $audit->action,
                    $audit->module ?? 'N/A',
                    $audit->model_name ?? ($audit->model_type ?? 'N/A'),
                    $audit->description ?? 'N/A',
                    $audit->status,
                    $audit->ip_address ?? 'N/A',
                    $audit->route ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}



