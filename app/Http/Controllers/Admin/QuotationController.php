<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quotation;
use App\Models\QuotationNote;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class QuotationController extends BaseAdminController
{
    /**
     * Display all quotations with advanced filters
     */
    public function index(Request $request)
    {
        $query = Quotation::with(['tour', 'booking', 'creator', 'agent']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Destination/Package filter
        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Travel date filter
        if ($request->filled('travel_date_from')) {
            $query->where('departure_date', '>=', $request->travel_date_from);
        }

        if ($request->filled('travel_date_to')) {
            $query->where('departure_date', '<=', $request->travel_date_to);
        }

        // Travel month filter
        if ($request->filled('travel_month')) {
            $query->whereMonth('departure_date', $request->travel_month);
        }

        // Agent filter
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('total_price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('total_price', '<=', $request->price_max);
        }

        // Number of people filter
        if ($request->filled('people_min')) {
            $query->where('travelers', '>=', $request->people_min);
        }

        if ($request->filled('people_max')) {
            $query->where('travelers', '<=', $request->people_max);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('tour_name', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $quotations = $query->latest()->paginate($request->get('per_page', 20));
        $tours = Tour::orderBy('name')->get();
        $agents = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['System Administrator', 'Travel Consultant', 'Reservations Officer']);
        })->orderBy('name')->get();
        
        $stats = [
            'total' => Quotation::count(),
            'pending' => Quotation::where('status', 'pending')->count(),
            'under_review' => Quotation::where('status', 'under_review')->count(),
            'sent' => Quotation::where('status', 'sent')->count(),
            'approved' => Quotation::where('status', 'approved')->count(),
            'rejected' => Quotation::where('status', 'rejected')->count(),
            'closed' => Quotation::where('status', 'closed')->count(),
        ];

        return view('admin.quotations.index', compact('quotations', 'tours', 'agents', 'stats'));
    }

    /**
     * Show quotation details
     */
    public function show($id)
    {
        $quotation = Quotation::with(['tour.destination', 'booking', 'creator', 'agent', 'notes.user'])->findOrFail($id);
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
        return response()->json($quotation);
        }
        
        return view('admin.quotations.show', compact('quotation'));
    }

    /**
     * Create new quotation
     */
    public function create()
    {
        $tours = Tour::orderBy('name')->get();
        $bookings = Booking::with('tour')->where('status', 'pending_payment')->latest()->get();
        $agents = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['System Administrator', 'Travel Consultant', 'Reservations Officer']);
        })->orderBy('name')->get();
        
        return view('admin.quotations.create', compact('tours', 'bookings', 'agents'));
    }

    /**
     * Edit quotation
     */
    public function edit($id)
    {
        $quotation = Quotation::with(['tour', 'booking', 'creator', 'agent'])->findOrFail($id);
        $tours = Tour::orderBy('name')->get();
        $bookings = Booking::with('tour')->where('status', 'pending_payment')->latest()->get();
        $agents = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['System Administrator', 'Travel Consultant', 'Reservations Officer']);
        })->orderBy('name')->get();
        
        return view('admin.quotations.edit', compact('quotation', 'tours', 'bookings', 'agents'));
    }

    /**
     * Store new quotation with all advanced fields
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tour_id' => 'required|exists:tours,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'customer_country' => 'nullable|string|max:100',
            'customer_city' => 'nullable|string|max:100',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'departure_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:departure_date',
            'duration_days' => 'nullable|integer|min:1',
            'accommodation_type' => 'nullable|in:budget,mid-range,luxury',
            'airport_pickup' => 'nullable|boolean',
            'special_requests' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'tour_price' => 'required|numeric|min:0',
            'accommodation_cost' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'park_fees' => 'nullable|numeric|min:0',
            'guide_fees' => 'nullable|numeric|min:0',
            'meals_cost' => 'nullable|numeric|min:0',
            'activities_cost' => 'nullable|numeric|min:0',
            'service_charges' => 'nullable|numeric|min:0',
            'addons_total' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'included' => 'nullable|string',
            'excluded' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'valid_until' => 'required|date|after:today',
            'status' => 'nullable|in:pending,under_review,sent,approved,rejected,closed',
            'agent_id' => 'nullable|exists:users,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'itinerary_file' => 'nullable|file|mimes:pdf|max:10240',
            'attachment_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['quotation_number'] = Quotation::generateQuotationNumber();
        $data['tour_name'] = Tour::find($data['tour_id'])->name;
        $data['travelers'] = ($data['adults'] ?? 0) + ($data['children'] ?? 0);
        $data['created_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'pending';
        $data['currency'] = $data['currency'] ?? 'USD';

        // Handle file uploads
        if ($request->hasFile('itinerary_file')) {
            $data['itinerary_file'] = $request->file('itinerary_file')->store('quotations/itineraries', 'public');
        }

        if ($request->hasFile('attachment_files')) {
            $files = [];
            foreach ($request->file('attachment_files') as $file) {
                $files[] = $file->store('quotations/attachments', 'public');
            }
            $data['attachment_files'] = $files;
        }

        $quotation = Quotation::create($data);

        // Add initial note if admin_notes provided
        if (!empty($data['admin_notes'])) {
            QuotationNote::create([
                'quotation_id' => $quotation->id,
                'user_id' => auth()->id(),
                'type' => 'admin_note',
                'note' => $data['admin_notes'],
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Quotation created successfully',
                'quotation' => $quotation->load(['tour', 'booking', 'creator', 'agent'])
        ]);
        }

        return redirect()->route('admin.quotations.index')
            ->with('success', 'Quotation created successfully');
    }

    /**
     * Update quotation with all advanced fields
     */
    public function update(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tour_id' => 'required|exists:tours,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'customer_country' => 'nullable|string|max:100',
            'customer_city' => 'nullable|string|max:100',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'departure_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:departure_date',
            'duration_days' => 'nullable|integer|min:1',
            'accommodation_type' => 'nullable|in:budget,mid-range,luxury',
            'airport_pickup' => 'nullable|boolean',
            'special_requests' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'tour_price' => 'required|numeric|min:0',
            'accommodation_cost' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'park_fees' => 'nullable|numeric|min:0',
            'guide_fees' => 'nullable|numeric|min:0',
            'meals_cost' => 'nullable|numeric|min:0',
            'activities_cost' => 'nullable|numeric|min:0',
            'service_charges' => 'nullable|numeric|min:0',
            'addons_total' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'included' => 'nullable|string',
            'excluded' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'valid_until' => 'required|date',
            'status' => 'nullable|in:pending,under_review,sent,approved,rejected,closed',
            'agent_id' => 'nullable|exists:users,id',
            'itinerary_file' => 'nullable|file|mimes:pdf|max:10240',
            'attachment_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['tour_name'] = Tour::find($data['tour_id'])->name;
        $data['travelers'] = ($data['adults'] ?? 0) + ($data['children'] ?? 0);

        // Handle file uploads
        if ($request->hasFile('itinerary_file')) {
            // Delete old file
            if ($quotation->itinerary_file) {
                Storage::disk('public')->delete($quotation->itinerary_file);
            }
            $data['itinerary_file'] = $request->file('itinerary_file')->store('quotations/itineraries', 'public');
        }

        if ($request->hasFile('attachment_files')) {
            // Delete old files
            if ($quotation->attachment_files) {
                foreach ($quotation->attachment_files as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            $files = [];
            foreach ($request->file('attachment_files') as $file) {
                $files[] = $file->store('quotations/attachments', 'public');
            }
            $data['attachment_files'] = $files;
        }

        $quotation->update($data);

        if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Quotation updated successfully',
                'quotation' => $quotation->load(['tour', 'booking', 'creator', 'agent'])
        ]);
        }

        return redirect()->route('admin.quotations.show', $quotation->id)
            ->with('success', 'Quotation updated successfully');
    }

    /**
     * Delete quotation
     */
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quotation deleted successfully'
        ]);
    }

    /**
     * Update quotation status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,under_review,sent,approved,rejected,closed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quotation = Quotation::findOrFail($id);
        $oldStatus = $quotation->status;
        $updateData = ['status' => $request->status];

        // Set timestamps based on status
        if ($request->status === 'sent' && $oldStatus !== 'sent') {
            $updateData['sent_at'] = now();
        } elseif ($request->status === 'approved' && $oldStatus !== 'approved') {
            $updateData['approved_at'] = now();
        } elseif ($request->status === 'rejected' && $oldStatus !== 'rejected') {
            $updateData['rejected_at'] = now();
        }

        $quotation->update($updateData);

        // Add note about status change
        QuotationNote::create([
            'quotation_id' => $quotation->id,
            'user_id' => auth()->id(),
            'type' => 'system',
            'note' => "Status changed from {$oldStatus} to {$request->status}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quotation status updated successfully',
            'quotation' => $quotation->load(['tour', 'booking', 'creator', 'agent'])
        ]);
    }

    /**
     * Send quotation to customer via email
     */
    public function send(Request $request, $id)
    {
        $quotation = Quotation::with(['tour', 'booking'])->findOrFail($id);
        
        // Update status to sent
        $quotation->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // Add note
        QuotationNote::create([
            'quotation_id' => $quotation->id,
            'user_id' => auth()->id(),
            'type' => 'email_sent',
            'note' => 'Quotation sent to customer via email',
            'metadata' => [
                'email' => $quotation->customer_email,
                'sent_by' => auth()->user()->name,
            ],
        ]);

        // TODO: Send email notification here
        // Mail::to($quotation->customer_email)->send(new QuotationEmail($quotation));

        return response()->json([
            'success' => true,
            'message' => 'Quotation sent to customer successfully',
            'quotation' => $quotation->load(['tour', 'booking', 'creator', 'agent'])
        ]);
    }

    /**
     * Send quotation via WhatsApp
     */
    public function sendWhatsApp(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);
        
        // Add note
        QuotationNote::create([
            'quotation_id' => $quotation->id,
            'user_id' => auth()->id(),
            'type' => 'whatsapp_sent',
            'note' => 'Quotation sent to customer via WhatsApp',
            'metadata' => [
                'phone' => $quotation->customer_phone,
                'sent_by' => auth()->user()->name,
            ],
        ]);

        // TODO: Send WhatsApp message here

        return response()->json([
            'success' => true,
            'message' => 'Quotation sent via WhatsApp successfully',
        ]);
    }

    /**
     * Add note to quotation
     */
    public function addNote(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string',
            'type' => 'nullable|in:admin_note,customer_reply,system',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quotation = Quotation::findOrFail($id);

        $note = QuotationNote::create([
            'quotation_id' => $quotation->id,
            'user_id' => auth()->id(),
            'type' => $request->type ?? 'admin_note',
            'note' => $request->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully',
            'note' => $note->load('user'),
        ]);
    }

    /**
     * Duplicate quotation
     */
    public function duplicate($id)
    {
        $original = Quotation::with(['tour', 'booking'])->findOrFail($id);
        
        $newQuotation = $original->replicate();
        $newQuotation->quotation_number = Quotation::generateQuotationNumber();
        $newQuotation->status = 'pending';
        $newQuotation->created_by = auth()->id();
        $newQuotation->sent_at = null;
        $newQuotation->approved_at = null;
        $newQuotation->rejected_at = null;
        $newQuotation->save();

        return response()->json([
            'success' => true,
            'message' => 'Quotation duplicated successfully',
            'quotation' => $newQuotation->load(['tour', 'booking', 'creator']),
        ]);
    }

    /**
     * Convert quotation to booking
     */
    public function convertToBooking($id)
    {
        $quotation = Quotation::with(['tour'])->findOrFail($id);

        // Create booking from quotation
        $booking = Booking::create([
            'booking_reference' => Booking::generateBookingReference(),
            'customer_name' => $quotation->customer_name,
            'customer_email' => $quotation->customer_email,
            'customer_phone' => $quotation->customer_phone,
            'customer_address' => $quotation->customer_address,
            'tour_id' => $quotation->tour_id,
            'departure_date' => $quotation->departure_date,
            'travelers' => $quotation->travelers,
            'total_price' => $quotation->total_price,
            'deposit_amount' => $quotation->total_price * 0.3, // 30% deposit
            'balance_amount' => $quotation->total_price * 0.7,
            'status' => 'pending_payment',
            'notes' => 'Converted from quotation: ' . $quotation->quotation_number,
        ]);

        // Update quotation status
        $quotation->update([
            'status' => 'approved',
            'approved_at' => now(),
            'booking_id' => $booking->id,
        ]);

        // Add note
        QuotationNote::create([
            'quotation_id' => $quotation->id,
            'user_id' => auth()->id(),
            'type' => 'system',
            'note' => "Quotation converted to booking: {$booking->booking_reference}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quotation converted to booking successfully',
            'booking' => $booking,
        ]);
    }

    /**
     * Accept quotation
     */
    public function accept($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->update(['status' => 'accepted']);

        return response()->json([
            'success' => true,
            'message' => 'Quotation accepted successfully',
            'quotation' => $quotation->load(['tour', 'booking', 'creator'])
        ]);
    }

    /**
     * Pending quotations
     */
    public function pending(Request $request)
    {
        $query = Quotation::with(['tour', 'booking', 'creator', 'agent'])
            ->where('status', 'pending');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $quotations = $query->latest()->paginate(20);
        $tours = Tour::orderBy('name')->get();
        $stats = [
            'total' => Quotation::where('status', 'pending')->count(),
        ];

        return view('admin.quotations.pending', compact('quotations', 'tours', 'stats'));
    }

    /**
     * Sent quotations
     */
    public function sent(Request $request)
    {
        $query = Quotation::with(['tour', 'booking', 'creator', 'agent'])
            ->where('status', 'sent');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $quotations = $query->latest()->paginate(20);
        $tours = Tour::orderBy('name')->get();
        $stats = [
            'total' => Quotation::where('status', 'sent')->count(),
        ];

        return view('admin.quotations.sent', compact('quotations', 'tours', 'stats'));
    }

    /**
     * Accepted/Approved quotations
     */
    public function accepted(Request $request)
    {
        $query = Quotation::with(['tour', 'booking', 'creator', 'agent'])
            ->where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $quotations = $query->latest()->paginate(20);
        $tours = Tour::orderBy('name')->get();
        $stats = [
            'total' => Quotation::where('status', 'approved')->count(),
        ];

        return view('admin.quotations.accepted', compact('quotations', 'tours', 'stats'));
    }

    /**
     * Export quotations to Excel
     */
    public function export(Request $request)
    {
        $query = Quotation::with(['tour', 'creator', 'agent']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $quotations = $query->latest()->get();

        // Generate CSV/Excel data
        $filename = 'quotations-export-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($quotations) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Quotation ID',
                'Customer Name',
                'Email',
                'Phone',
                'Destination/Package',
                'Travel Date',
                'No. of People',
                'Status',
                'Total Amount',
                'Currency',
                'Created At',
                'Valid Until',
            ]);

            // Data
            foreach ($quotations as $quotation) {
                fputcsv($file, [
                    $quotation->quotation_number,
                    $quotation->customer_name,
                    $quotation->customer_email,
                    $quotation->customer_phone,
                    $quotation->tour_name,
                    $quotation->departure_date?->format('Y-m-d'),
                    $quotation->travelers,
                    $quotation->status,
                    $quotation->total_price,
                    $quotation->currency ?? 'USD',
                    $quotation->created_at->format('Y-m-d H:i:s'),
                    $quotation->valid_until?->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get tour details for quotation form
     */
    public function getTourDetails($id)
    {
        $tour = Tour::with('destination')->findOrFail($id);
        return response()->json([
            'name' => $tour->name,
            'price' => $tour->price,
            'duration_days' => $tour->duration_days,
            'included' => $tour->included,
            'excluded' => $tour->excluded,
        ]);
    }

    /**
     * Generate and download quotation PDF
     */
    public function downloadPDF($id)
    {
        $quotation = Quotation::with(['tour.destination', 'booking', 'creator', 'agent'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.quotations.pdf', compact('quotation'));
        
        return $pdf->download('quotation-' . $quotation->quotation_number . '.pdf');
    }

    /**
     * View quotation PDF in browser
     */
    public function viewPDF($id)
    {
        $quotation = Quotation::with(['tour.destination', 'booking', 'creator', 'agent'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.quotations.pdf', compact('quotation'));
        
        return $pdf->stream('quotation-' . $quotation->quotation_number . '.pdf');
    }

    /**
     * Print quotation
     */
    public function print($id)
    {
        $quotation = Quotation::with(['tour.destination', 'booking', 'creator', 'agent', 'notes.user'])->findOrFail($id);
        
        return view('admin.quotations.print', compact('quotation'));
    }
}
