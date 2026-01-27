<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomerQuery;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class CustomerQueriesController extends BaseAdminController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of customer queries
     */
    public function index(Request $request)
    {
        try {
            $query = CustomerQuery::with(['user', 'assignedTo', 'repliedBy']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('assigned_to')) {
                $query->where('assigned_to', $request->assigned_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $queries = $query->latest()->paginate(20);

            $stats = [
                'total' => CustomerQuery::count(),
                'new' => CustomerQuery::where('status', 'new')->count(),
                'replied' => CustomerQuery::where('status', 'replied')->count(),
                'resolved' => CustomerQuery::where('status', 'resolved')->count(),
                'urgent' => CustomerQuery::where('priority', 'urgent')->whereIn('status', ['new', 'read'])->count(),
            ];
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $queries = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $stats = [
                'total' => 0,
                'new' => 0,
                'replied' => 0,
                'resolved' => 0,
                'urgent' => 0,
            ];
        }

        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['System Administrator', 'Travel Consultant', 'Reservations Officer']);
        })->orderBy('name')->get();

        return view('admin.queries.index', compact('queries', 'stats', 'users'));
    }

    /**
     * Show the form for creating a new query
     */
    public function create()
    {
        return view('admin.queries.create');
    }

    /**
     * Store a newly created query
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'category' => 'required|in:booking,tour,custom,support,partnership,other',
            'message' => 'required|string|min:10',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'tour_interest' => 'nullable|string',
        ]);

        $validated['status'] = 'new';
        $validated['priority'] = $validated['priority'] ?? 'normal';

        $customerQuery = CustomerQuery::create($validated);

        // Notify relevant staff
        $this->notificationService->notifyByRole(
            ['System Administrator', 'Travel Consultant'],
            "New customer query received: {$validated['subject']}",
            route('admin.queries.show', $customerQuery->id),
            'New Customer Query'
        );

        return $this->successResponse('Customer query created successfully!', route('admin.queries.index'));
    }

    /**
     * Display the specified query
     */
    public function show($id)
    {
        $query = CustomerQuery::with(['user', 'assignedTo', 'repliedBy'])->findOrFail($id);
        
        // Mark as read if new
        if ($query->status === 'new') {
            $query->markAsRead();
        }

        return view('admin.queries.show', compact('query'));
    }

    /**
     * Show the form for editing the specified query
     */
    public function edit($id)
    {
        $query = CustomerQuery::findOrFail($id);
        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['System Administrator', 'Travel Consultant', 'Reservations Officer']);
        })->orderBy('name')->get();

        return view('admin.queries.edit', compact('query', 'users'));
    }

    /**
     * Update the specified query
     */
    public function update(Request $request, $id)
    {
        $query = CustomerQuery::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,resolved,archived',
            'priority' => 'required|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string',
        ]);

        $query->update($validated);

        // Notify assigned user if changed
        if ($request->filled('assigned_to') && $query->assignedTo) {
            $this->notificationService->notify(
                $query->assigned_to,
                "You have been assigned a customer query: {$query->subject}",
                route('admin.queries.show', $query->id),
                'Query Assignment'
            );
        }

        return $this->successResponse('Query updated successfully!', route('admin.queries.show', $id));
    }

    /**
     * Remove the specified query
     */
    public function destroy($id)
    {
        $query = CustomerQuery::findOrFail($id);
        $query->delete();

        return $this->successResponse('Query deleted successfully!', route('admin.queries.index'));
    }

    /**
     * Reply to query
     */
    public function reply(Request $request, $id)
    {
        $query = CustomerQuery::findOrFail($id);

        $validated = $request->validate([
            'reply_message' => 'required|string|min:10',
            'send_email' => 'nullable|boolean',
        ]);

        $query->markAsReplied(auth()->id());

        // Send email reply if requested
        if ($request->filled('send_email') && $request->send_email) {
            try {
                $this->notificationService->notifyPhone(
                    $query->phone,
                    $validated['reply_message'],
                    $query->email,
                    "Re: {$query->subject}",
                    ['skip_sms' => false]
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send query reply email: ' . $e->getMessage());
            }
        }

        return $this->successResponse('Reply sent successfully!', route('admin.queries.show', $id));
    }

    /**
     * Bulk update queries
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'query_ids' => 'required|array',
            'query_ids.*' => 'exists:customer_queries,id',
            'action' => 'required|in:assign,status,priority,delete',
            'value' => 'nullable',
        ]);

        $queries = CustomerQuery::whereIn('id', $validated['query_ids']);

        switch ($validated['action']) {
            case 'assign':
                $queries->update(['assigned_to' => $validated['value']]);
                break;
            case 'status':
                $queries->update(['status' => $validated['value']]);
                break;
            case 'priority':
                $queries->update(['priority' => $validated['value']]);
                break;
            case 'delete':
                $queries->delete();
                break;
        }

        return $this->successResponse('Queries updated successfully!', route('admin.queries.index'));
    }
}
