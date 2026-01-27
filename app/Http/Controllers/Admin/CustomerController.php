<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\CustomerGroup;
use App\Models\CustomerFeedback;
use App\Models\CustomerMessage;
use App\Models\Booking;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends BaseAdminController
{
    /**
     * Display all customers (CRM Dashboard)
     */
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'Customer');
        })->orWhereDoesntHave('roles')
        ->with(['customerGroups', 'bookings', 'assignedConsultant']);
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('passport_number', 'like', "%{$search}%");
            });
        }
        
        // Filters
        if ($request->filled('group_id')) {
            $query->whereHas('customerGroups', function($q) use ($request) {
                $q->where('customer_groups.id', $request->group_id);
            });
        }
        
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where(function($q) {
                    $q->where('customer_status', 'active')
                      ->orWhere(function($q2) {
                          $q2->whereNull('customer_status')->whereNotNull('email_verified_at');
                      });
                });
            } elseif ($request->status == 'inactive') {
                $query->where(function($q) {
                    $q->where('customer_status', 'inactive')
                      ->orWhere(function($q2) {
                          $q2->whereNull('customer_status')->whereNull('email_verified_at');
                      });
                });
            } else {
                $query->where('customer_status', $request->status);
            }
        }
        
        if ($request->filled('high_value')) {
            $query->withSum('bookings', 'total_price')
                  ->having('bookings_sum_total_price', '>=', 10000); // High value threshold
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $customers = $query->latest()->paginate(20);
        
        // Stats
        $stats = [
            'total' => User::whereHas('roles', function($q) {
                $q->where('name', 'Customer');
            })->orWhereDoesntHave('roles')->count(),
            'active' => User::whereHas('roles', function($q) {
                $q->where('name', 'Customer');
            })->where(function($q) {
                $q->where('customer_status', 'active')
                  ->orWhere(function($q2) {
                      $q2->whereNull('customer_status')->whereNotNull('email_verified_at');
                  });
            })->count(),
            'inactive' => User::whereHas('roles', function($q) {
                $q->where('name', 'Customer');
            })->where(function($q) {
                $q->where('customer_status', 'inactive')
                  ->orWhere(function($q2) {
                      $q2->whereNull('customer_status')->whereNull('email_verified_at');
                  });
            })->count(),
        ];
        
        $groups = CustomerGroup::where('is_active', true)->orderBy('name')->get();
        $countries = User::whereNotNull('country')->distinct()->pluck('country')->sort();
        $consultants = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Travel Consultant', 'System Administrator']);
        })->get();
        
        return view('admin.customers.index', compact('customers', 'stats', 'groups', 'countries', 'consultants'));
    }

    /**
     * Show create customer form
     */
    public function create()
    {
        $groups = CustomerGroup::where('is_active', true)->orderBy('name')->get();
        $consultants = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Travel Consultant', 'System Administrator']);
        })->get();
        $destinations = Destination::orderBy('name')->get();
        
        return view('admin.customers.create', compact('groups', 'consultants', 'destinations'));
    }

    /**
     * Store a new customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'nullable|string|max:255', // Auto-generated if not provided
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'passport_expiry' => 'nullable|date|after:today',
            
            // Contact Information
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            
            // Travel Preferences
            'preferred_destination' => 'nullable|string|max:255',
            'preferred_tour_type' => 'nullable|string|max:255',
            'preferred_budget' => 'nullable|numeric|min:0',
            'special_needs' => 'nullable|string',
            
            // System Controls
            'customer_groups' => 'nullable|array',
            'customer_groups.*' => 'exists:customer_groups,id',
            'customer_status' => 'nullable|in:active,inactive,suspended',
            'assigned_consultant_id' => 'nullable|exists:users,id',
            'internal_notes' => 'nullable|string',
            
            // Account
            'password' => 'required|min:8',
        ]);
        
        // Generate name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['middle_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        }
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();
        $validated['customer_status'] = $validated['customer_status'] ?? 'active';
        
        $groups = $validated['customer_groups'] ?? [];
        unset($validated['customer_groups']);
        
        DB::beginTransaction();
        try {
            $customer = User::create($validated);
            
            // Assign Customer role
            $customer->assignRole('Customer');
            
            // Assign to groups
            if (!empty($groups)) {
                $customer->customerGroups()->attach($groups);
            }
            
            DB::commit();
            
            $this->notifySuccess('Customer created successfully!', 'Customer Created', route('admin.customers.show', $customer->id));
            
            if ($request->has('save_and_add_another')) {
                return redirect()->route('admin.customers.create')->with('success', 'Customer created successfully!');
            }
            
            return redirect()->route('admin.customers.show', $customer->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating customer: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    /**
     * Display customer details (Full Profile)
     */
    public function show($id)
    {
        $customer = User::with([
            'customerGroups',
            'bookings.tour',
            'bookings.payments',
            'bookings.invoice',
            'bookings.assignedStaff',
            'feedback.tour',
            'messages.assignedStaff',
            'assignedConsultant',
            'invoices',
            'invoices.payments',
            'payments'
        ])->findOrFail($id);
        
        // Calculate stats
        $totalBookings = $customer->bookings()->count();
        $totalSpend = $customer->bookings()->where('status', '!=', 'cancelled')->sum('total_price') ?? 0;
        $totalPaid = $customer->payments()->where('payments.status', 'completed')->sum('amount') ?? 0;
        $pendingBalance = $customer->invoices()->sum('total_amount') - $totalPaid;
        
        return view('admin.customers.show', compact('customer', 'totalBookings', 'totalSpend', 'totalPaid', 'pendingBalance'));
    }

    /**
     * Show edit customer form
     */
    public function edit($id)
    {
        $customer = User::with('customerGroups')->findOrFail($id);
        $groups = CustomerGroup::where('is_active', true)->orderBy('name')->get();
        $consultants = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Travel Consultant', 'System Administrator']);
        })->get();
        $destinations = Destination::orderBy('name')->get();
        
        return view('admin.customers.edit', compact('customer', 'groups', 'consultants', 'destinations'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $validated = $request->validate([
            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'passport_expiry' => 'nullable|date',
            
            // Contact Information
            'email' => ['required', 'email', Rule::unique('users')->ignore($customer->id)],
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            
            // Travel Preferences
            'preferred_destination' => 'nullable|string|max:255',
            'preferred_tour_type' => 'nullable|string|max:255',
            'preferred_budget' => 'nullable|numeric|min:0',
            'special_needs' => 'nullable|string',
            
            // System Controls
            'customer_groups' => 'nullable|array',
            'customer_groups.*' => 'exists:customer_groups,id',
            'customer_status' => 'nullable|in:active,inactive,suspended',
            'assigned_consultant_id' => 'nullable|exists:users,id',
            'internal_notes' => 'nullable|string',
            
            // Account
            'password' => 'nullable|min:8',
        ]);
        
        // Generate name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['middle_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        }
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $groups = $validated['customer_groups'] ?? [];
        unset($validated['customer_groups']);
        
        DB::beginTransaction();
        try {
            $customer->update($validated);
            
            // Sync groups
            if (isset($groups)) {
                $customer->customerGroups()->sync($groups);
            }
            
            DB::commit();
            
            $this->notifySuccess('Customer updated successfully!', 'Customer Updated', route('admin.customers.show', $customer->id));
            
            return redirect()->route('admin.customers.show', $customer->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating customer: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    /**
     * Delete customer
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        // Check permissions
        if (!auth()->user()->hasRole('System Administrator')) {
            return $this->errorResponse('You do not have permission to delete customers.', route('admin.customers.index'));
        }
        
        // Check if customer has bookings
        if ($customer->bookings()->count() > 0) {
            return $this->errorResponse('Cannot delete customer with existing bookings. Please cancel or complete bookings first.', route('admin.customers.index'));
        }
        
        $customer->delete();
        
        return $this->successResponse('Customer deleted successfully!', route('admin.customers.index'));
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        // Handle JSON requests
        if ($request->isJson()) {
            $data = $request->json()->all();
            $customerIds = $data['customer_ids'] ?? [];
            $action = $data['action'] ?? '';
        } else {
            $request->validate([
                'action' => 'required|in:assign_group,send_email,send_sms,export,deactivate,activate',
                'customer_ids' => 'required',
            ]);
            
            // Handle JSON string or array
            $customerIds = is_string($request->customer_ids) 
                ? json_decode($request->customer_ids, true) 
                : $request->customer_ids;
            $action = $request->action;
        }
        
        if (empty($customerIds) || !is_array($customerIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No customers selected'
            ], 422);
        }
        
        $request->validate([
            'action' => 'required|in:assign_group,send_email,send_sms,export,deactivate,activate',
        ]);
        
        $customerIds = array_filter($customerIds);
        $action = $request->action;
        
        DB::beginTransaction();
        try {
            switch ($action) {
                case 'assign_group':
                    $request->validate(['group_id' => 'required|exists:customer_groups,id']);
                    foreach ($customerIds as $customerId) {
                        $customer = User::find($customerId);
                        if ($customer) {
                            $customer->customerGroups()->syncWithoutDetaching([$request->group_id]);
                        }
                    }
                    $message = count($customerIds) . ' customer(s) assigned to group successfully!';
                    break;
                    
                case 'send_email':
                    // TODO: Implement email sending
                    $message = 'Email sending will be implemented';
                    break;
                    
                case 'send_sms':
                    // TODO: Implement SMS sending
                    $message = 'SMS sending will be implemented';
                    break;
                    
                case 'export':
                    return $this->export($request);
                    
                case 'deactivate':
                    User::whereIn('id', $customerIds)->update(['customer_status' => 'inactive']);
                    $message = count($customerIds) . ' customer(s) deactivated successfully!';
                    break;
                    
                case 'activate':
                    User::whereIn('id', $customerIds)->update(['customer_status' => 'active']);
                    $message = count($customerIds) . ' customer(s) activated successfully!';
                    break;
            }
            
            DB::commit();
            
            if ($request->isJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return $this->successResponse($message, route('admin.customers.index'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk action error: ' . $e->getMessage());
            
            if ($request->isJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to perform bulk action: ' . $e->getMessage()
                ], 500);
            }
            
            return $this->errorResponse('Failed to perform bulk action: ' . $e->getMessage(), route('admin.customers.index'));
        }
    }

    /**
     * Export customers
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $customerIds = $request->get('customer_ids', []);
        
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'Customer');
        })->orWhereDoesntHave('roles')
        ->with(['customerGroups', 'bookings']);
        
        if (!empty($customerIds)) {
            $query->whereIn('id', $customerIds);
        }
        
        $customers = $query->get();
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.customers.export-pdf', compact('customers'));
            return $pdf->download('customers-' . date('Y-m-d') . '.pdf');
        } else {
            // Excel export would go here
            return response()->json(['message' => 'Excel export not yet implemented']);
        }
    }

    /**
     * Display customer groups
     */
    public function groups(Request $request)
    {
        $query = CustomerGroup::withCount('customers');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }
        
        $groups = $query->orderBy('display_order')->orderBy('name')->paginate(20);
        
        return view('admin.customers.groups', compact('groups'));
    }

    /**
     * Store customer group
     */
    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:customer_groups,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $group = CustomerGroup::create($validated);
        
        return $this->successResponse('Customer group created successfully!', route('admin.customers.groups'));
    }

    /**
     * Update customer group
     */
    public function updateGroup(Request $request, $id)
    {
        $group = CustomerGroup::findOrFail($id);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('customer_groups')->ignore($group->id)],
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $group->update($validated);
        
        return $this->successResponse('Customer group updated successfully!', route('admin.customers.groups'));
    }

    /**
     * Delete customer group
     */
    public function destroyGroup($id)
    {
        $group = CustomerGroup::findOrFail($id);
        $group->delete();
        
        return $this->successResponse('Customer group deleted successfully!', route('admin.customers.groups'));
    }

    /**
     * Display customer feedback
     */
    public function feedback(Request $request)
    {
        $query = CustomerFeedback::with(['customer', 'tour', 'booking', 'responder']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('feedback_type')) {
            $query->where('feedback_type', $request->feedback_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        $feedback = $query->latest()->paginate(20);
        
        $stats = [
            'total' => CustomerFeedback::count(),
            'pending' => CustomerFeedback::where('status', 'pending')->count(),
            'approved' => CustomerFeedback::where('status', 'approved')->count(),
            'average_rating' => CustomerFeedback::avg('rating') ?? 0,
        ];
        
        return view('admin.customers.feedback', compact('feedback', 'stats'));
    }

    /**
     * Update feedback status
     */
    public function updateFeedback(Request $request, $id)
    {
        $feedback = CustomerFeedback::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,resolved',
            'staff_response' => 'nullable|string',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'is_serious_complaint' => 'boolean',
            'admin_notes' => 'nullable|string',
        ]);
        
        $validated['is_public'] = $request->has('is_public');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_serious_complaint'] = $request->has('is_serious_complaint');
        
        if ($request->filled('staff_response')) {
            $validated['responded_by'] = auth()->id();
            $validated['responded_at'] = now();
        }
        
        $feedback->update($validated);
        
        return $this->successResponse('Feedback updated successfully!', route('admin.customers.feedback'));
    }

    /**
     * Display customer messages
     */
    public function messages(Request $request)
    {
        $query = CustomerMessage::with(['customer', 'assignedStaff', 'booking', 'replies'])
            ->whereNull('parent_message_id'); // Only show parent messages
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_staff_id', $request->assigned_to);
        }
        
        if ($request->filled('unread_only')) {
            $query->where('is_read', false);
        }
        
        if ($request->filled('important_only')) {
            $query->where('is_important', true);
        }
        
        $messages = $query->latest()->paginate(20);
        
        $stats = [
            'total' => CustomerMessage::whereNull('parent_message_id')->count(),
            'new' => CustomerMessage::whereNull('parent_message_id')->where('status', 'new')->count(),
            'unread' => CustomerMessage::whereNull('parent_message_id')->where('is_read', false)->count(),
            'important' => CustomerMessage::whereNull('parent_message_id')->where('is_important', true)->count(),
        ];
        
        $staff = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Travel Consultant', 'System Administrator', 'Reservations Officer']);
        })->get();
        
        return view('admin.customers.messages', compact('messages', 'stats', 'staff'));
    }

    /**
     * Reply to message
     */
    public function replyMessage(Request $request, $id)
    {
        $parentMessage = CustomerMessage::findOrFail($id);
        
        $validated = $request->validate([
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
            'status' => 'nullable|in:new,open,in_progress,waiting_customer,resolved,closed',
        ]);
        
        $message = CustomerMessage::create([
            'customer_id' => $parentMessage->customer_id,
            'assigned_staff_id' => auth()->id(),
            'booking_id' => $parentMessage->booking_id,
            'message' => $validated['message'],
            'message_type' => $parentMessage->message_type,
            'priority' => $parentMessage->priority,
            'status' => $validated['status'] ?? 'open',
            'parent_message_id' => $parentMessage->id,
            'thread_depth' => $parentMessage->thread_depth + 1,
            'channel' => 'website',
        ]);
        
        // Update parent message status
        if ($validated['status']) {
            $parentMessage->update(['status' => $validated['status']]);
        }
        
        return $this->successResponse('Reply sent successfully!', route('admin.customers.messages'));
    }
}
