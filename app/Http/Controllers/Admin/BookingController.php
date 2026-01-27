<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TourOperation;
use App\Models\Vehicle;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends BaseAdminController
{
    /**
     * Display all bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['tour', 'user', 'assignedStaff', 'payments', 'invoice']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }

        if ($request->filled('date_from')) {
            $query->where('departure_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('departure_date', '<=', $request->date_to);
        }

        if ($request->filled('booking_source')) {
            $query->where('booking_source', $request->booking_source);
        }

        if ($request->filled('customer')) {
            $search = $request->customer;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(20);
        $tours = Tour::orderBy('name')->get();
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'Customer');
        })->orWhereDoesntHave('roles')->orderBy('name')->get();
        
        // Get staff/agents for assignment
        $staff = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Travel Consultant', 'Reservations Officer', 'System Administrator']);
        })->orderBy('name')->get();
        
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending_payment')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'tours', 'users', 'staff', 'stats'));
    }

    /**
     * Show booking details
     */
    public function show(Request $request, $id)
    {
        try {
            $booking = Booking::with([
                'tour.destination', 
                'tour.itineraries', 
                'user', 
                'assignedStaff',
                'payments',
                'invoice',
                'approver',
                'rejector'
            ])->findOrFail($id);
            
            // Check if request wants JSON (AJAX or API request)
            $acceptHeader = $request->header('Accept', '');
            $wantsJson = $request->ajax() || 
                        $request->wantsJson() || 
                        $request->expectsJson() ||
                        $acceptHeader === 'application/json' ||
                        str_contains($acceptHeader, 'application/json') ||
                        $request->header('X-Requested-With') === 'XMLHttpRequest';
            
            // Prepare booking data
            $bookingData = [
                'id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'status' => $booking->status,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'customer_phone' => $booking->customer_phone,
                'customer_country' => $booking->customer_country,
                'tour' => $booking->tour ? [
                    'id' => $booking->tour->id,
                    'name' => $booking->tour->name,
                ] : null,
                'departure_date' => $booking->departure_date ? $booking->departure_date->toDateString() : null,
                'travelers' => $booking->travelers,
                'total_price' => $booking->total_price,
                'deposit_amount' => $booking->deposit_amount,
                'balance_amount' => $booking->balance_amount,
                'payment_method' => $booking->payment_method,
                'addons' => $booking->addons ? (is_string($booking->addons) ? json_decode($booking->addons, true) : $booking->addons) : [],
                'special_requirements' => $booking->special_requirements,
                'emergency_contact_name' => $booking->emergency_contact_name,
                'emergency_contact_phone' => $booking->emergency_contact_phone,
                'notes' => $booking->notes,
                'cancellation_reason' => $booking->cancellation_reason,
                'created_at' => $booking->created_at ? $booking->created_at->toDateTimeString() : null,
                'confirmed_at' => $booking->confirmed_at ? $booking->confirmed_at->toDateTimeString() : null,
                'cancelled_at' => $booking->cancelled_at ? $booking->cancelled_at->toDateTimeString() : null,
            ];
            
            // Return JSON for AJAX requests
            if ($wantsJson) {
                return response()->json($bookingData);
            }
            
            // Check if edit mode is requested
            $editMode = $request->has('edit') && $request->get('edit') == '1';
            
            // Get tours for edit dropdown
            $tours = \App\Models\Tour::select('id', 'name')->orderBy('name')->get();
            
            return view('admin.bookings.show', compact('booking', 'editMode', 'tours'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'error' => 'Booking not found'
                ], 404);
            }
            abort(404, 'Booking not found');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'error' => 'An error occurred while loading booking details',
                    'message' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Download booking as PDF
     */
    public function downloadPDF($id)
    {
        $booking = Booking::with(['tour.destination', 'tour.itineraries', 'user', 'payments', 'invoice'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.bookings.pdf', compact('booking'));
        
        return $pdf->download('booking-' . $booking->booking_reference . '.pdf');
    }

    /**
     * View booking PDF in browser
     */
    public function viewPDF($id)
    {
        $booking = Booking::with(['tour.destination', 'tour.itineraries', 'user', 'payments', 'invoice'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.bookings.pdf', compact('booking'));
        
        return $pdf->stream('booking-' . $booking->booking_reference . '.pdf');
    }

    /**
     * Create new booking
     */
    public function create()
    {
        $tours = Tour::orderBy('name')->get();
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'Customer');
        })->orWhereDoesntHave('roles')->orderBy('name')->get();
        
        // Get staff/agents for assignment
        $staff = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Travel Consultant', 'Reservations Officer', 'System Administrator']);
        })->orderBy('name')->get();
        
        return view('admin.bookings.create', compact('tours', 'users', 'staff'));
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tour_id' => 'required|exists:tours,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:50',
            'number_of_adults' => 'required|integer|min:1|max:50',
            'number_of_children' => 'nullable|integer|min:0|max:50',
            'travelers' => 'nullable|integer|min:1|max:50', // For backward compatibility
            'departure_date' => 'required|date|after_or_equal:today',
            'travel_end_date' => 'nullable|date|after_or_equal:departure_date',
            'accommodation_level' => 'nullable|in:budget,midrange,luxury',
            'pickup_location' => 'nullable|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'balance_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:3',
            'status' => 'required|in:pending_payment,confirmed,cancelled,completed,in_progress',
            'payment_method' => 'nullable|string|max:255',
            'payment_status' => 'nullable|in:unpaid,partial,paid',
            'amount_paid' => 'nullable|numeric|min:0',
            'booking_source' => 'nullable|in:website,manual,whatsapp,referral,agent',
            'assigned_staff_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user_id;
        $data['addons'] = $request->addons ?? [];
        
        // Set default currency if not provided
        if (empty($data['currency'])) {
            $data['currency'] = 'USD';
        }
        
        // Calculate travelers if not provided
        if (!isset($data['travelers'])) {
            $data['travelers'] = ($data['number_of_adults'] ?? 1) + ($data['number_of_children'] ?? 0);
        }
        
        // Calculate balance if not provided
        if (!isset($data['balance_amount']) && isset($data['total_price']) && isset($data['amount_paid'])) {
            $data['balance_amount'] = max(0, $data['total_price'] - ($data['amount_paid'] ?? 0));
        }
        
        // Set payment status if not provided
        if (!isset($data['payment_status'])) {
            $amountPaid = $data['amount_paid'] ?? 0;
            if ($amountPaid >= $data['total_price']) {
                $data['payment_status'] = 'paid';
            } elseif ($amountPaid > 0) {
                $data['payment_status'] = 'partial';
            } else {
                $data['payment_status'] = 'unpaid';
            }
        }
        
        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('booking-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
            $data['attachments'] = $attachments;
        }
        
        if ($data['status'] === 'confirmed') {
            $data['confirmed_at'] = now();
            $data['approval_status'] = 'approved';
            $data['approved_by'] = Auth::id();
            $data['approved_at'] = now();
        }
        
        // Set initial approval status
        if (!isset($data['approval_status'])) {
            $data['approval_status'] = $request->input('initial_approval_status', 'pending');
        }

        $booking = Booking::create($data);

        $this->notifySuccess('Booking created successfully', 'New Booking', route('admin.bookings.show', $booking->id));

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'booking' => $booking->load(['tour', 'user', 'assignedStaff'])
        ]);
    }

    /**
     * Update booking
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tour_id' => 'sometimes|nullable|exists:tours,id',
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'sometimes|email|max:255',
            'customer_phone' => 'sometimes|nullable|string|max:20',
            'customer_country' => 'sometimes|nullable|string|max:255',
            'travelers' => 'sometimes|integer|min:1|max:50',
            'departure_date' => 'sometimes|nullable|date',
            'total_price' => 'sometimes|nullable|numeric|min:0',
            'deposit_amount' => 'sometimes|nullable|numeric|min:0',
            'payment_method' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|in:pending_payment,confirmed,cancelled,completed,in_progress',
            'special_requirements' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Recalculate balance_amount if total_price or deposit_amount is updated
        if (isset($data['total_price']) || isset($data['deposit_amount'])) {
            $totalPrice = $data['total_price'] ?? $booking->total_price ?? 0;
            $depositAmount = $data['deposit_amount'] ?? $booking->deposit_amount ?? 0;
            $data['balance_amount'] = max(0, $totalPrice - $depositAmount);
        }

        // Handle status changes
        if (isset($data['status'])) {
            if ($data['status'] === 'confirmed' && $booking->status !== 'confirmed') {
                $data['confirmed_at'] = now();
            }
            if ($data['status'] === 'cancelled' && $booking->status !== 'cancelled') {
                $data['cancelled_at'] = now();
                $data['cancellation_reason'] = $request->cancellation_reason ?? null;
            }
        }

        $booking->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'booking' => $booking->load(['tour', 'user'])
        ]);
    }

    /**
     * Update booking status
     * Supports both GET (for viewing/redirecting) and POST (for updating)
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // If GET request, redirect to booking show page
        if ($request->isMethod('get')) {
            return redirect()->route('admin.bookings.show', $id)
                ->with('info', 'Please use the action buttons to update booking status.');
        }

        $request->validate([
            'status' => 'required|in:pending_payment,confirmed,cancelled,completed,in_progress',
            'cancellation_reason' => 'required_if:status,cancelled|string|max:500',
            'confirm_payment' => 'sometimes|boolean',
            'payment_amount' => 'required_if:confirm_payment,true|numeric|min:0',
            'payment_method' => 'required_if:confirm_payment,true|string|max:255',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $booking->status;
        $booking->status = $request->status;

        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            $booking->confirmed_at = now();
        }

        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            $booking->cancelled_at = now();
            $booking->cancellation_reason = $request->cancellation_reason;
        }

        // Handle payment confirmation
        if ($request->boolean('confirm_payment')) {
            $paymentAmount = $request->get('payment_amount', $booking->total_price);
            
            // Update booking payment status
            $booking->amount_paid = ($booking->amount_paid ?? 0) + $paymentAmount;
            $booking->balance_amount = max(0, ($booking->total_price ?? 0) - $booking->amount_paid);
            
            if ($booking->balance_amount <= 0) {
                $booking->payment_status = 'paid';
            } else {
                $booking->payment_status = 'partial';
            }
            
            if ($request->filled('payment_method')) {
                $booking->payment_method = $request->payment_method;
            }
            
            // Create payment record
            try {
                $payment = \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'invoice_id' => $booking->invoice?->id,
                    'payment_reference' => \App\Models\Payment::generatePaymentReference(),
                    'payment_method' => $request->payment_method ?? 'manual',
                    'amount' => $paymentAmount,
                    'currency' => $booking->currency ?? 'USD',
                    'status' => 'completed',
                    'paid_at' => now(),
                    'notes' => $request->payment_notes ?? 'Payment confirmed manually',
                    'gateway_response' => [
                        'confirmed_by' => auth()->user()->name ?? 'Admin',
                        'confirmed_at' => now()->toDateTimeString(),
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to create payment record: ' . $e->getMessage());
            }
        }

        $booking->save();

        return response()->json([
            'success' => true,
            'message' => $request->boolean('confirm_payment') 
                ? 'Booking status updated and payment confirmed successfully' 
                : 'Booking status updated successfully',
            'booking' => $booking->load(['tour', 'user', 'payments'])
        ]);
    }

    /**
     * Confirm booking
     */
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully',
            'booking' => $booking->load(['tour', 'user'])
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->cancellation_reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'booking' => $booking->load(['tour', 'user'])
        ]);
    }

    /**
     * Delete booking
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }

    /**
     * Pending approvals - bookings that need approval
     */
    public function pendingApprovals()
    {
        $bookings = Booking::with(['tour', 'user', 'assignedStaff', 'approver', 'rejector', 'payments', 'invoice'])
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.bookings.pending-approvals', compact('bookings'));
    }

    /**
     * Approve booking
     */
    public function approve(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // If status is pending, change to confirmed after approval
        if ($booking->status === 'pending_payment') {
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);
        }

        $this->notifySuccess('Booking approved successfully', 'Booking Approved', route('admin.bookings.show', $id));

        return response()->json([
            'success' => true,
            'message' => 'Booking approved successfully',
            'booking' => $booking->load(['tour', 'user', 'approver'])
        ]);
    }

    /**
     * Reject booking
     */
    public function reject(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $booking->update([
            'approval_status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        $this->notifyWarning('Booking rejected', 'Booking Rejected', route('admin.bookings.show', $id));

        return response()->json([
            'success' => true,
            'message' => 'Booking rejected successfully',
            'booking' => $booking->load(['tour', 'user', 'rejector'])
        ]);
    }

    /**
     * Pending bookings (old method for backward compatibility)
     */
    public function pending()
    {
        $bookings = Booking::with(['tour', 'user', 'payments', 'invoice'])
            ->where('status', 'pending_payment')
            ->latest()
            ->paginate(20);

        return view('admin.bookings.pending', compact('bookings'));
    }

    /**
     * Confirmed bookings
     */
    public function confirmed()
    {
        $bookings = Booking::with(['tour', 'user', 'payments', 'invoice'])
            ->where('status', 'confirmed')
            ->latest()
            ->paginate(20);

        return view('admin.bookings.confirmed', compact('bookings'));
    }

    /**
     * Cancelled bookings
     */
    public function cancelled()
    {
        $bookings = Booking::with(['tour', 'user', 'assignedStaff', 'payments', 'invoice'])
            ->where('status', 'cancelled')
            ->latest()
            ->paginate(20);

        return view('admin.bookings.cancelled', compact('bookings'));
    }

    /**
     * Restore cancelled booking
     */
    public function restore($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Only cancelled bookings can be restored'
            ], 422);
        }

        $booking->update([
            'status' => 'pending_payment',
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ]);

        $this->notifySuccess('Booking restored successfully', 'Booking Restored', route('admin.bookings.show', $id));

        return response()->json([
            'success' => true,
            'message' => 'Booking restored successfully',
            'booking' => $booking->load(['tour', 'user'])
        ]);
    }

    /**
     * Booking calendar view (admin) with summary stats
     */
    public function calendarView()
    {
        $stats = [
            'total' => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending_payment')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'today' => Booking::whereDate('departure_date', today())->count(),
        ];

        return view('admin.bookings.calendar', compact('stats'));
    }

    /**
     * Booking calendar data (AJAX)
     */
    public function calendarData(Request $request)
    {
        $start = $request->get('start', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end', Carbon::now()->endOfMonth()->toDateString());

        $query = Booking::with(['tour', 'user', 'payments', 'invoice']);

        // Date range filter - use departure_date or created_at
        if ($request->filled('dateFrom') && $request->filled('dateTo')) {
            $query->whereBetween('departure_date', [$request->get('dateFrom'), $request->get('dateTo')]);
        } else {
            $query->whereBetween('departure_date', [$start, $end]);
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $statuses = explode(',', $request->status);
            $query->whereIn('status', $statuses);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('tour', function($tourQuery) use ($search) {
                      $tourQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Payment status filter
        if ($request->filled('paymentStatus')) {
            $query->where('payment_status', $request->get('paymentStatus'));
        }

        $bookings = $query->get();

        $events = $bookings->map(function($booking) {
            $color = match($booking->status) {
                'confirmed' => '#3ea572',
                'pending_payment' => '#ffc107',
                'cancelled' => '#dc3545',
                'completed' => '#17a2b8',
                'in_progress' => '#6cbe8f',
                default => '#6c757d'
            };

            return [
                'id' => $booking->id,
                'title' => $booking->tour ? $booking->tour->name : 'Tour #' . $booking->tour_id,
                'start' => $booking->departure_date->format('Y-m-d'),
                'end' => $booking->travel_end_date ? $booking->travel_end_date->format('Y-m-d') : $booking->departure_date->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => [
                    'booking_reference' => $booking->booking_reference,
                    'customer_name' => $booking->customer_name,
                    'customer_email' => $booking->customer_email,
                    'customer_phone' => $booking->customer_phone,
                    'travelers' => $booking->travelers,
                    'number_of_adults' => $booking->number_of_adults,
                    'number_of_children' => $booking->number_of_children,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'total_price' => $booking->total_price,
                    'currency' => $booking->currency ?? 'USD',
                    'pickup_location' => $booking->pickup_location,
                    'dropoff_location' => $booking->dropoff_location,
                    'notes' => $booking->notes,
                    'special_requirements' => $booking->special_requirements,
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Get booking statistics
     */
    public function stats()
    {
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending_payment')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
            'this_month' => Booking::whereMonth('created_at', Carbon::now()->month)->count(),
            'this_month_revenue' => Booking::where('status', 'confirmed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price'),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk actions for bookings
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,cancel,assign_staff,export',
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id',
        ]);

        $bookings = Booking::whereIn('id', $request->booking_ids)->get();

        switch ($request->action) {
            case 'approve':
                foreach ($bookings as $booking) {
                    $booking->update([
                        'approval_status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]);
                }
                $message = count($bookings) . ' bookings approved successfully';
                break;

            case 'cancel':
                $request->validate([
                    'cancellation_reason' => 'required|string|max:500',
                ]);
                foreach ($bookings as $booking) {
                    $booking->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancellation_reason' => $request->cancellation_reason,
                    ]);
                }
                $message = count($bookings) . ' bookings cancelled successfully';
                break;

            case 'assign_staff':
                $request->validate([
                    'staff_id' => 'required|exists:users,id',
                ]);
                foreach ($bookings as $booking) {
                    $booking->update(['assigned_staff_id' => $request->staff_id]);
                }
                $message = count($bookings) . ' bookings assigned successfully';
                break;

            case 'export':
                return $this->export($request);
        }

        $this->notifySuccess($message);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Export bookings to Excel/PDF
     */
    public function export(Request $request)
    {
        $query = Booking::with(['tour', 'user', 'assignedStaff', 'payments', 'invoice']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }
        if ($request->filled('date_from')) {
            $query->where('departure_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('departure_date', '<=', $request->date_to);
        }

        $bookings = $query->get();
        $format = $request->get('format', 'excel');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.bookings.export-pdf', compact('bookings'));
            return $pdf->download('bookings-export-' . date('Y-m-d') . '.pdf');
        } else {
            // Excel export would require Laravel Excel package
            // For now, return CSV
            $filename = 'bookings-export-' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Booking ID', 'Customer', 'Email', 'Phone', 'Tour', 'Travel Dates', 'People', 'Amount', 'Status', 'Payment Status', 'Agent']);
                
                foreach ($bookings as $booking) {
                    fputcsv($file, [
                        $booking->booking_reference,
                        $booking->customer_name,
                        $booking->customer_email,
                        $booking->customer_phone,
                        $booking->tour ? $booking->tour->name : 'N/A',
                        ($booking->departure_date ? $booking->departure_date->format('Y-m-d') : '') . 
                        ($booking->travel_end_date ? ' - ' . $booking->travel_end_date->format('Y-m-d') : ''),
                        ($booking->number_of_adults ?? $booking->travelers) . ' Adults' . 
                        ($booking->number_of_children ? ', ' . $booking->number_of_children . ' Children' : ''),
                        $booking->total_price,
                        ucfirst(str_replace('_', ' ', $booking->status)),
                        ucfirst($booking->payment_status ?? 'unpaid'),
                        $booking->assignedStaff ? $booking->assignedStaff->name : 'N/A',
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }

    /**
     * Convert booking to invoice
     */
    public function convertToInvoice(Request $request, $id)
    {
        try {
            $booking = Booking::with(['tour', 'user', 'assignedStaff', 'payments', 'invoice'])->findOrFail($id);

            // Check if invoice already exists
            $existingInvoice = Invoice::where('booking_id', $booking->id)->first();
            if ($existingInvoice) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Invoice already exists for this booking',
                        'invoice' => $existingInvoice,
                        'redirect' => route('admin.finance.invoices.show', $existingInvoice->id)
                    ]);
                }
                return redirect()->route('admin.finance.invoices.show', $existingInvoice->id)
                    ->with('info', 'Invoice already exists for this booking');
            }

            // Generate invoice number using new format
            $invoiceNumber = Invoice::generateInvoiceNumber();

            // Calculate amounts
            $subtotal = $booking->total_price ?? 0;
            $discountAmount = $booking->discount_amount ?? 0;
            $taxAmount = 0; // Can be configured from settings
            $totalAmount = $subtotal - $discountAmount + $taxAmount;

            // Prepare customer address (nullable field)
            $customerAddress = null;
            if ($booking->city || $booking->customer_country) {
                $parts = array_filter([$booking->city, $booking->customer_country]);
                if (!empty($parts)) {
                    $customerAddress = implode(', ', $parts);
                }
            }

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'customer_phone' => $booking->customer_phone,
                'customer_address' => $customerAddress,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => $booking->currency ?? 'USD',
                'invoice_date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
                'status' => 'draft', // draft, sent, paid, overdue, cancelled (will be sent when emailed)
                'notes' => 'Invoice generated from booking ' . $booking->booking_reference . '. Tour: ' . ($booking->tour ? $booking->tour->name : 'N/A'),
                'terms' => 'Payment due within 30 days. Late payments may incur additional fees.',
            ]);

            $this->notifySuccess('Invoice generated successfully', 'Invoice Created', route('admin.finance.invoices.show', $invoice->id));

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice generated successfully',
                    'invoice' => $invoice->load(['booking', 'user']),
                    'redirect' => route('admin.finance.invoices.show', $invoice->id)
                ]);
            }

            return redirect()->route('admin.finance.invoices.show', $invoice->id)
                ->with('success', 'Invoice generated successfully');
                
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Invoice generation database error: ' . $e->getMessage());
            $errorMessage = 'Database error occurred. Please check if all required fields exist.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->back()->with('error', $errorMessage);
            
        } catch (\Exception $e) {
            \Log::error('Invoice generation error: ' . $e->getMessage(), [
                'booking_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'An error occurred while generating the invoice: ' . $e->getMessage();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => config('app.debug') ? $e->getMessage() : 'Please check the logs for details'
                ], 500);
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    /**
     * Record payment for booking
     */
    public function recordPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $amountPaid = $request->amount_paid;
        $totalPaid = ($booking->amount_paid ?? 0) + $amountPaid;
        
        // Determine payment status
        $paymentStatus = 'unpaid';
        if ($totalPaid >= $booking->total_price) {
            $paymentStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'partial';
        }

        $data = [
            'amount_paid' => $totalPaid,
            'payment_status' => $paymentStatus,
            'payment_method' => $request->payment_method,
            'balance_amount' => max(0, $booking->total_price - $totalPaid),
        ];

        // Handle receipt upload
        if ($request->hasFile('payment_receipt')) {
            $path = $request->file('payment_receipt')->store('payment-receipts', 'public');
            $data['payment_receipt_path'] = $path;
        }

        $booking->update($data);

        $this->notifySuccess('Payment recorded successfully', 'Payment Recorded', route('admin.bookings.show', $id));

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'booking' => $booking->fresh()
        ]);
    }

    /**
     * Add itinerary to booking
     */
    public function addItinerary(Request $request, $id)
    {
        $booking = Booking::with('tour')->findOrFail($id);

        if (!$booking->tour_id) {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be associated with a tour to add itinerary'
            ], 422);
        }

        // Itinerary is already part of the tour, so we just return success
        // In future, you can add custom itinerary items per booking
        return response()->json([
            'success' => true,
            'message' => 'Itinerary is available from the tour. View tour details to see full itinerary.',
            'redirect' => route('admin.tours.show', $booking->tour_id)
        ]);
    }

    /**
     * Add transport assignment to booking
     */
    public function addTransport(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:users,id',
            'operation_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if (!$booking->tour_id) {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be associated with a tour'
            ], 422);
        }

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        
        // Create or update tour operation
        $tourOperation = TourOperation::firstOrNew([
            'booking_id' => $booking->id,
        ]);
        $tourOperation->tour_id = $booking->tour_id;
        $tourOperation->vehicle_id = $request->vehicle_id;
        $tourOperation->driver_id = $request->driver_id;
        $tourOperation->operation_date = $request->operation_date ?? $booking->departure_date;
        $tourOperation->status = 'scheduled';
        if ($request->filled('notes')) {
            $tourOperation->notes = $request->notes;
        }
        $tourOperation->save();

        // Update vehicle status
        $vehicle->status = 'in_use';
        $vehicle->save();

        $this->notifySuccess('Transport assigned successfully', 'Transport Assignment', route('admin.bookings.show', $booking->id));

        return response()->json([
            'success' => true,
            'message' => 'Transport assigned successfully',
            'booking' => $booking->fresh()
        ]);
    }

    /**
     * Add guide assignment to booking
     */
    public function addGuide(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'guide_id' => 'required|exists:users,id',
            'operation_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if (!$booking->tour_id) {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be associated with a tour'
            ], 422);
        }

        // Create or update tour operation
        $tourOperation = TourOperation::firstOrNew([
            'booking_id' => $booking->id,
        ]);
        $tourOperation->tour_id = $booking->tour_id;
        $tourOperation->guide_id = $request->guide_id;
        $tourOperation->operation_date = $request->operation_date ?? $booking->departure_date;
        $tourOperation->status = 'scheduled';
        if ($request->filled('notes')) {
            $tourOperation->notes = $request->notes;
        }
        $tourOperation->save();

        $this->notifySuccess('Guide assigned successfully', 'Guide Assignment', route('admin.bookings.show', $booking->id));

        return response()->json([
            'success' => true,
            'message' => 'Guide assigned successfully',
            'booking' => $booking->fresh()
        ]);
    }

    /**
     * Send travel voucher via email
     */
    public function sendVoucher(Request $request, $id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);

        if (!$booking->customer_email) {
            return response()->json([
                'success' => false,
                'message' => 'Customer email is required to send voucher'
            ], 422);
        }

        try {
            $notificationService = app(NotificationService::class);
            
            $subject = 'Travel Voucher - ' . $booking->booking_reference;
            
            // Prepare email content
            $org = \App\Models\OrganizationSetting::getSettings();
            $emailContent = '<p>Your <span class="highlight-blue">travel voucher</span> for booking <span class="highlight-red">' . $booking->booking_reference . '</span> is attached.</p>';
            
            $emailContent .= '<div class="info-box">';
            $emailContent .= '<p><strong>Important:</strong> Please present this voucher upon arrival at the service location.</p>';
            $emailContent .= '</div>';
            
            $emailContent .= '<table class="details-table">';
            if ($booking->tour) {
                $emailContent .= '<tr><td>Tour:</td><td><span class="highlight-blue">' . $booking->tour->name . '</span></td></tr>';
            }
            if ($booking->departure_date) {
                $emailContent .= '<tr><td>Travel Date:</td><td><span class="highlight-blue">' . $booking->departure_date->format('F d, Y') . '</span></td></tr>';
            }
            $emailContent .= '<tr><td>Travelers:</td><td><span class="highlight-blue">' . $booking->travelers . ' ' . \Illuminate\Support\Str::plural('Person', $booking->travelers) . '</span></td></tr>';
            $emailContent .= '</table>';
            
            // Prepare buttons
            $buttons = [
                [
                    'text' => '📄 View Booking',
                    'url' => route('admin.bookings.show', $booking->id),
                    'class' => 'cta-button'
                ],
                [
                    'text' => '📋 View Itinerary',
                    'url' => $booking->tour ? route('admin.tours.show', $booking->tour->id) : '#',
                    'class' => 'cta-button-secondary'
                ]
            ];
            
            // Set stream context to disable SSL verification
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);

            // Send email with PDF attachment
            $pdf = Pdf::loadView('admin.bookings.pdf', compact('booking'));
            $pdfContent = $pdf->output();

            Mail::send('emails.document-email', [
                'recipientName' => $booking->customer_name,
                'emailContent' => $emailContent,
                'attachmentName' => 'Travel Voucher - ' . $booking->booking_reference . '.pdf',
                'documentType' => 'Travel Voucher',
                'buttons' => $buttons,
                'quote' => 'Adventure awaits! Present this voucher and let the journey begin.',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($mail) use ($booking, $subject, $pdfContent) {
                $mail->to($booking->customer_email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'voucher-' . $booking->booking_reference . '.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });

            $this->notifySuccess('Travel voucher sent successfully', 'Voucher Sent', route('admin.bookings.show', $booking->id));

            return response()->json([
                'success' => true,
                'message' => 'Travel voucher sent successfully to ' . $booking->customer_email
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send travel voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send voucher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send booking details via WhatsApp
     */
    public function sendWhatsApp(Request $request, $id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);

        if (!$booking->customer_phone) {
            return response()->json([
                'success' => false,
                'message' => 'Customer phone number is required to send WhatsApp message'
            ], 422);
        }

        try {
            $notificationService = app(NotificationService::class);
            
            $message = "Hello {$booking->customer_name},\n\n";
            $message .= "Your booking confirmation:\n";
            $message .= "Booking ID: {$booking->booking_reference}\n";
            $message .= "Tour: " . ($booking->tour ? $booking->tour->name : 'N/A') . "\n";
            $message .= "Travel Date: " . ($booking->departure_date ? $booking->departure_date->format('F d, Y') : 'N/A') . "\n";
            $message .= "Amount: " . ($booking->currency ?? 'USD') . " " . number_format($booking->total_price, 2) . "\n\n";
            $message .= "Thank you for choosing Lau Paradise Adventures!";

            // Send SMS/WhatsApp via notification service
            $notificationService->notifyPhone(
                $booking->customer_phone,
                $message,
                $booking->customer_email,
                'Booking Confirmation - ' . $booking->booking_reference
            );

            $this->notifySuccess('WhatsApp message sent successfully', 'Message Sent', route('admin.bookings.show', $booking->id));

            return response()->json([
                'success' => true,
                'message' => 'WhatsApp message sent successfully to ' . $booking->customer_phone
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send WhatsApp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send WhatsApp message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark booking as completed
     */
    public function markCompleted(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot mark cancelled booking as completed'
            ], 422);
        }

        $booking->update([
            'status' => 'completed',
        ]);

        $this->notifySuccess('Booking marked as completed', 'Status Updated', route('admin.bookings.show', $booking->id));

        return response()->json([
            'success' => true,
            'message' => 'Booking marked as completed successfully',
            'booking' => $booking->fresh()
        ]);
    }

    /**
     * Mark booking as in-progress
     */
    public function markInProgress(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot mark cancelled booking as in-progress'
            ], 422);
        }

        $booking->update([
            'status' => 'in_progress',
        ]);

        $this->notifySuccess('Booking marked as in-progress', 'Status Updated', route('admin.bookings.show', $booking->id));

        return response()->json([
            'success' => true,
            'message' => 'Booking marked as in-progress successfully',
            'booking' => $booking->fresh()
        ]);
    }

    /**
     * Confirm payment for a booking
     */
    public function confirmPayment(Request $request, $id)
    {
        $booking = Booking::with(['tour', 'user', 'invoice', 'payments'])->findOrFail($id);

        $request->validate([
            'payment_amount' => 'required_without:mark_as_paid|nullable|numeric|min:0|max:' . ($booking->balance_amount ?? $booking->total_price),
            'payment_method' => 'required|string|in:cash,bank_transfer,credit_card,mobile_money,paypal,manual',
            'payment_notes' => 'nullable|string|max:500',
            'mark_as_paid' => 'sometimes|boolean', // If true, mark full balance as paid
        ]);

        // Calculate payment amount
        $paymentAmount = $request->boolean('mark_as_paid') 
            ? ($booking->balance_amount ?? $booking->total_price) 
            : ($request->get('payment_amount') ?? 0);

        if ($paymentAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount must be greater than 0'
            ], 422);
        }

        // Update booking payment status
        $currentAmountPaid = $booking->amount_paid ?? 0;
        $newAmountPaid = $currentAmountPaid + $paymentAmount;
        $totalPrice = $booking->total_price ?? 0;
        
        $booking->amount_paid = $newAmountPaid;
        $booking->balance_amount = max(0, $totalPrice - $newAmountPaid);
        
        if ($booking->balance_amount <= 0) {
            $booking->payment_status = 'paid';
        } else {
            $booking->payment_status = 'partial';
        }
        
        $booking->payment_method = $request->payment_method;
        $booking->save();

        // Create payment record
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'invoice_id' => $booking->invoice?->id,
                'payment_reference' => Payment::generatePaymentReference(),
                'payment_method' => $request->payment_method,
                'amount' => $paymentAmount,
                'currency' => $booking->currency ?? 'USD',
                'status' => 'completed',
                'paid_at' => now(),
                'notes' => $request->payment_notes ?? 'Payment confirmed manually by ' . (auth()->user()->name ?? 'Admin'),
                'gateway_response' => [
                    'confirmed_by' => auth()->user()->name ?? 'Admin',
                    'confirmed_by_id' => auth()->id(),
                    'confirmed_at' => now()->toDateTimeString(),
                    'booking_reference' => $booking->booking_reference,
                ],
            ]);

            // If payment is full, update booking status to confirmed if it's pending
            if ($booking->balance_amount <= 0 && $booking->status === 'pending_payment') {
                $booking->status = 'confirmed';
                $booking->confirmed_at = now();
                $booking->save();
            }

            $this->notifySuccess(
                'Payment confirmed successfully', 
                'Payment Confirmed', 
                route('admin.bookings.show', $booking->id)
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'payment' => $payment,
                'booking' => $booking->load(['tour', 'user', 'payments', 'invoice'])
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to confirm payment: ' . $e->getMessage(), [
                'booking_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
