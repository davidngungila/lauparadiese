<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogController extends BaseAdminController
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        // Check if activity_log table exists (from spatie/laravel-activitylog)
        try {
            $query = DB::table('activity_log');
            
            if ($request->filled('causer_id')) {
                $query->where('causer_id', $request->causer_id);
            }
            
            if ($request->filled('log_name')) {
                $query->where('log_name', $request->log_name);
            }
            
            if ($request->filled('subject_type')) {
                $query->where('subject_type', $request->subject_type);
            }
            
            if ($request->filled('event')) {
                $query->where('event', $request->event);
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
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('subject_type', 'like', "%{$search}%")
                      ->orWhere('properties', 'like', "%{$search}%");
                });
            }
            
            $activities = $query->orderBy('created_at', 'desc')->paginate(50);
            
            // Get user names for causer_id
            $causerIds = $activities->pluck('causer_id')->filter()->unique();
            $usersMap = User::whereIn('id', $causerIds)->pluck('name', 'id');
            
            // Statistics
            $stats = [
                'total' => DB::table('activity_log')->count(),
                'today' => DB::table('activity_log')->whereDate('created_at', today())->count(),
                'this_week' => DB::table('activity_log')
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->count(),
                'this_month' => DB::table('activity_log')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->count(),
                'by_log_name' => DB::table('activity_log')
                    ->select('log_name', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('log_name')
                    ->groupBy('log_name')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
                'by_event' => DB::table('activity_log')
                    ->select('event', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('event')
                    ->groupBy('event')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
                'top_users' => DB::table('activity_log')
                    ->select('causer_id', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('causer_id')
                    ->groupBy('causer_id')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        $user = User::find($item->causer_id);
                        return [
                            'user_id' => $item->causer_id,
                            'user_name' => $user ? $user->name : 'Unknown',
                            'count' => $item->count,
                        ];
                    }),
            ];
            
            $users = User::orderBy('name')->get();
            $logNames = DB::table('activity_log')->distinct()->pluck('log_name')->filter()->sort()->values();
            $events = DB::table('activity_log')->distinct()->pluck('event')->filter()->sort()->values();
            $subjectTypes = DB::table('activity_log')->distinct()->pluck('subject_type')->filter()->sort()->values();
            
            return view('admin.settings.activity-logs', compact('activities', 'users', 'stats', 'logNames', 'events', 'subjectTypes', 'usersMap'));
        } catch (\Exception $e) {
            // Table doesn't exist
            $activities = new \Illuminate\Pagination\LengthAwarePaginator(
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
                'by_log_name' => collect([]),
                'by_event' => collect([]),
                'top_users' => collect([]),
            ];
            $logNames = collect([]);
            $events = collect([]);
            $subjectTypes = collect([]);
            $usersMap = collect([]);
            
            return view('admin.settings.activity-logs', compact('activities', 'users', 'stats', 'logNames', 'events', 'subjectTypes', 'usersMap'))
                ->with('error', 'Activity log table does not exist. Please install spatie/laravel-activitylog package.');
        }
    }

    /**
     * Show activity log details
     */
    public function show($id)
    {
        $activity = DB::table('activity_log')->find($id);
        
        if (!$activity) {
            return $this->errorResponse('Activity log not found!', route('admin.settings.activity-logs'));
        }
        
        $user = $activity->causer_id ? User::find($activity->causer_id) : null;
        
        return view('admin.settings.activity-log-show', compact('activity', 'user'));
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $query = DB::table('activity_log');
        
        // Apply filters
        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }
        
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $activities = $query->orderBy('created_at', 'desc')->limit(10000)->get();
        
        $filename = 'activity_logs_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Date', 'User', 'Log Name', 'Event', 'Subject Type', 'Description']);
            
            // Data
            foreach ($activities as $activity) {
                $user = $activity->causer_id ? User::find($activity->causer_id) : null;
                fputcsv($file, [
                    $activity->id,
                    Carbon::parse($activity->created_at)->format('Y-m-d H:i:s'),
                    $user ? $user->name : 'System',
                    $activity->log_name ?? 'N/A',
                    $activity->event ?? 'N/A',
                    $activity->subject_type ?? 'N/A',
                    $activity->description ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}



