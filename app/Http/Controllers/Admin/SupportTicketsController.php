<?php

namespace App\Http\Controllers\Admin;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SupportTicketsController extends BaseAdminController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of support tickets
     */
    public function index(Request $request)
    {
        try {
            $query = SupportTicket::with(['user', 'booking', 'assignedTo', 'createdBy']);

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
                    $q->where('ticket_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $tickets = $query->latest()->paginate(20);

            $stats = [
                'total' => SupportTicket::count(),
                'open' => SupportTicket::whereIn('status', ['open', 'in_progress', 'waiting_customer'])->count(),
                'resolved' => SupportTicket::where('status', 'resolved')->count(),
                'closed' => SupportTicket::where('status', 'closed')->count(),
                'urgent' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
            ];
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $tickets = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $stats = [
                'total' => 0,
                'open' => 0,
                'resolved' => 0,
                'closed' => 0,
                'urgent' => 0,
            ];
        }

        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['System Administrator', 'Travel Consultant', 'Reservations Officer']);
        })->orderBy('name')->get();

        return view('admin.tickets.index', compact('tickets', 'stats', 'users'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        $bookings = Booking::where('status', 'confirmed')->latest()->limit(100)->get();
        $users = User::orderBy('name')->get();

        return view('admin.tickets.create', compact('bookings', 'users'));
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'category' => 'required|in:technical,billing,booking,refund,general,other',
            'priority' => 'required|in:low,normal,high,urgent',
            'description' => 'required|string|min:10',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['status'] = 'open';
        $validated['created_by'] = auth()->id();

        $ticket = SupportTicket::create($validated);

        // Notify assigned user or relevant staff
        if ($ticket->assigned_to) {
            $this->notificationService->notify(
                $ticket->assigned_to,
                "New support ticket assigned: {$ticket->ticket_number} - {$ticket->subject}",
                route('admin.tickets.show', $ticket->id),
                'New Support Ticket'
            );
        } else {
            $this->notificationService->notifyByRole(
                ['System Administrator', 'Travel Consultant'],
                "New support ticket created: {$ticket->ticket_number} - {$ticket->subject}",
                route('admin.tickets.show', $ticket->id),
                'New Support Ticket'
            );
        }

        return $this->successResponse('Support ticket created successfully!', route('admin.tickets.index'));
    }

    /**
     * Display the specified ticket
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'booking', 'assignedTo', 'createdBy', 'replies.user', 'replies.createdBy'])
            ->findOrFail($id);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket
     */
    public function edit($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $bookings = Booking::where('status', 'confirmed')->latest()->limit(100)->get();
        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['System Administrator', 'Travel Consultant', 'Reservations Officer']);
        })->orderBy('name')->get();

        return view('admin.tickets.edit', compact('ticket', 'bookings', 'users'));
    }

    /**
     * Update the specified ticket
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,waiting_customer,resolved,closed,cancelled',
            'priority' => 'required|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'category' => 'required|in:technical,billing,booking,refund,general,other',
            'resolution_notes' => 'nullable|string',
        ]);

        $oldAssignedTo = $ticket->assigned_to;
        $ticket->update($validated);

        // Handle status changes
        if ($validated['status'] === 'resolved' && !$ticket->resolved_at) {
            $ticket->markAsResolved($validated['resolution_notes'] ?? null);
        }

        if ($validated['status'] === 'closed' && !$ticket->closed_at) {
            $ticket->close();
        }

        // Notify if assignment changed
        if ($request->filled('assigned_to') && $oldAssignedTo != $ticket->assigned_to && $ticket->assignedTo) {
            $this->notificationService->notify(
                $ticket->assigned_to,
                "Support ticket assigned to you: {$ticket->ticket_number} - {$ticket->subject}",
                route('admin.tickets.show', $ticket->id),
                'Ticket Assignment'
            );
        }

        return $this->successResponse('Ticket updated successfully!', route('admin.tickets.show', $id));
    }

    /**
     * Remove the specified ticket
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        return $this->successResponse('Ticket deleted successfully!', route('admin.tickets.index'));
    }

    /**
     * Add reply to ticket
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string|min:10',
            'is_internal' => 'nullable|boolean',
            'update_status' => 'nullable|in:in_progress,waiting_customer',
        ]);

        $reply = SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $ticket->user_id,
            'reply_type' => 'staff',
            'message' => $validated['message'],
            'is_internal' => $request->filled('is_internal') && $request->is_internal,
            'created_by' => auth()->id(),
        ]);

        // Update ticket status if requested
        if ($request->filled('update_status')) {
            $ticket->update(['status' => $request->update_status]);
        }

        // Notify customer if not internal
        if (!$reply->is_internal && $ticket->customer_email) {
            try {
                $this->notificationService->notifyPhone(
                    $ticket->customer_phone,
                    "Reply to ticket {$ticket->ticket_number}: {$validated['message']}",
                    $ticket->customer_email,
                    "Re: {$ticket->subject}",
                    ['skip_sms' => false]
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send ticket reply notification: ' . $e->getMessage());
            }
        }

        return $this->successResponse('Reply added successfully!', route('admin.tickets.show', $id));
    }

    /**
     * Resolve ticket
     */
    public function resolve(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'resolution_notes' => 'required|string|min:10',
        ]);

        $ticket->markAsResolved($validated['resolution_notes'], auth()->id());

        // Notify customer
        if ($ticket->customer_email) {
            try {
                $this->notificationService->notifyPhone(
                    $ticket->customer_phone,
                    "Your support ticket {$ticket->ticket_number} has been resolved. Resolution: {$validated['resolution_notes']}",
                    $ticket->customer_email,
                    "Ticket {$ticket->ticket_number} Resolved",
                    ['skip_sms' => false]
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send ticket resolution notification: ' . $e->getMessage());
            }
        }

        return $this->successResponse('Ticket resolved successfully!', route('admin.tickets.show', $id));
    }

    /**
     * Bulk update tickets
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:support_tickets,id',
            'action' => 'required|in:assign,status,priority,delete',
            'value' => 'nullable',
        ]);

        $tickets = SupportTicket::whereIn('id', $validated['ticket_ids']);

        switch ($validated['action']) {
            case 'assign':
                $tickets->update(['assigned_to' => $validated['value']]);
                break;
            case 'status':
                $tickets->update(['status' => $validated['value']]);
                break;
            case 'priority':
                $tickets->update(['priority' => $validated['value']]);
                break;
            case 'delete':
                $tickets->delete();
                break;
        }

        return $this->successResponse('Tickets updated successfully!', route('admin.tickets.index'));
    }
}
