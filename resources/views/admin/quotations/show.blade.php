@extends('admin.layouts.app')

@section('title', 'Quotation Details - ' . $quotation->quotation_number . ' - Lau Paradise Adventures')

@push('styles')
<style>
    .quotation-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }
    .quotation-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.6; }
    }
    .quotation-status-badge {
        display: inline-block;
        padding: 0.6rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .quotation-status-badge:hover {
        transform: scale(1.05);
    }
    .status-pending { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; }
    .status-under_review { background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%); color: #1e40af; }
    .status-sent { background: linear-gradient(135deg, #d1fae5 0%, #6ee7b7 100%); color: #065f46; }
    .status-approved { background: linear-gradient(135deg, #d1fae5 0%, #34d399 100%); color: #065f46; }
    .status-rejected { background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%); color: #991b1b; }
    .status-closed { background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); color: #374151; }
    
    .info-card {
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }
    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .info-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #e9ecef;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
    }
    .info-card .card-header h5 {
        color: #495057;
        margin: 0;
    }
    .price-highlight {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .timeline-item {
        position: relative;
        padding-left: 3rem;
        padding-bottom: 2.5rem;
        animation: fadeInUp 0.5s ease-out;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 0.875rem;
        top: 2.5rem;
        bottom: -2.5rem;
        width: 3px;
        background: linear-gradient(180deg, #2563eb 0%, #e5e7eb 100%);
        border-radius: 2px;
    }
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0.25rem;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border: 4px solid white;
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        z-index: 1;
    }
    .timeline-dot::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
    }
    .cost-breakdown {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #e9ecef;
    }
    .cost-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s;
    }
    .cost-item:hover {
        background: rgba(37, 99, 235, 0.05);
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        border-radius: 6px;
    }
    .cost-item:last-child {
        border-bottom: none;
        border-top: 2px solid #2563eb;
        margin-top: 0.5rem;
        padding-top: 1.5rem;
    }
    .cost-total {
        font-weight: 700;
        font-size: 1.5rem;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .action-btn {
        transition: all 0.3s;
        border-radius: 8px;
        font-weight: 500;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .note-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .note-content {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #2563eb;
        margin-top: 0.5rem;
    }
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border-bottom: none;
        padding: 1.5rem;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #5568d3 0%, #653a8f 100%);
    }
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    .toast-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }
    .toast-error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Quotation Header -->
    <div class="quotation-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-2">
                    <i class="ri-file-text-line me-2"></i>Quotation: {{ $quotation->quotation_number }}
                </h3>
                <p class="mb-0 opacity-90">
                    Created on {{ $quotation->created_at->format('F d, Y') }} by {{ $quotation->creator->name ?? 'System' }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="quotation-status-badge status-{{ $quotation->status }}">
                    {{ ucfirst(str_replace('_', ' ', $quotation->status)) }}
                </span>
                <div class="mt-3">
                    <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                        <a href="{{ route('admin.quotations.pdf', $quotation->id) }}" class="btn btn-light btn-sm">
                            <i class="ri-download-line me-1"></i>Download PDF
                        </a>
                        <a href="{{ route('admin.quotations.print', $quotation->id) }}" target="_blank" class="btn btn-light btn-sm">
                            <i class="ri-printer-line me-1"></i>Print
                        </a>
                        @if($quotation->status == 'pending' || $quotation->status == 'under_review')
                            <button type="button" class="btn btn-light btn-sm action-btn" id="sendQuotationBtn" data-quotation-id="{{ $quotation->id }}">
                                <i class="ri-send-plane-line me-1"></i>Send
                            </button>
                        @endif
                        <form action="{{ route('admin.quotations.duplicate', $quotation->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm">
                                <i class="ri-file-copy-line me-1"></i>Duplicate
                            </button>
                        </form>
                        @if($quotation->status == 'approved')
                            <form action="{{ route('admin.quotations.convert-to-booking', $quotation->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-light btn-sm" onclick="return confirm('Convert this quotation to a booking?')">
                                    <i class="ri-calendar-check-line me-1"></i>Convert to Booking
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.quotations.edit', $quotation->id) }}" class="btn btn-light btn-sm">
                            <i class="ri-pencil-line me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Customer Information -->
        <div class="col-md-6">
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-user-line me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <p class="mb-0">{{ $quotation->customer_name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">
                            <a href="mailto:{{ $quotation->customer_email }}">{{ $quotation->customer_email }}</a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <p class="mb-0">
                            @if($quotation->customer_phone)
                                <a href="tel:{{ $quotation->customer_phone }}">{{ $quotation->customer_phone }}</a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $quotation->customer_phone) }}" target="_blank" class="btn btn-sm btn-success ms-2">
                                    <i class="ri-whatsapp-line"></i> WhatsApp
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    @if($quotation->customer_address)
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p class="mb-0">{{ $quotation->customer_address }}</p>
                    </div>
                    @endif
                    @if($quotation->customer_country || $quotation->customer_city)
                    <div class="mb-3">
                        <strong>Location:</strong>
                        <p class="mb-0">
                            @if($quotation->customer_city){{ $quotation->customer_city }}, @endif
                            @if($quotation->customer_country){{ $quotation->customer_country }}@endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Travel Information -->
        <div class="col-md-6">
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-map-pin-line me-2"></i>Travel Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Destination/Package:</strong>
                        <p class="mb-0">{{ $quotation->tour ? $quotation->tour->name : $quotation->tour_name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Departure Date:</strong>
                        <p class="mb-0">{{ $quotation->departure_date ? $quotation->departure_date->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    @if($quotation->end_date)
                    <div class="mb-3">
                        <strong>End Date:</strong>
                        <p class="mb-0">{{ $quotation->end_date->format('F d, Y') }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>Duration:</strong>
                        <p class="mb-0">{{ $quotation->duration_days ?? 'N/A' }} {{ $quotation->duration_days == 1 ? 'Day' : 'Days' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Travelers:</strong>
                        <p class="mb-0">
                            {{ $quotation->adults ?? $quotation->travelers }} Adult(s)
                            @if($quotation->children)
                                , {{ $quotation->children }} Child(ren)
                            @endif
                        </p>
                    </div>
                    @if($quotation->accommodation_type)
                    <div class="mb-3">
                        <strong>Accommodation:</strong>
                        <p class="mb-0">{{ ucfirst(str_replace('-', ' ', $quotation->accommodation_type)) }}</p>
                    </div>
                    @endif
                    @if($quotation->airport_pickup)
                    <div class="mb-3">
                        <strong>Airport Pickup:</strong>
                        <p class="mb-0"><span class="badge bg-label-success">Yes</span></p>
                    </div>
                    @endif
                    @if($quotation->valid_until)
                    <div class="mb-3">
                        <strong>Valid Until:</strong>
                        <p class="mb-0 {{ $quotation->isExpired() ? 'text-danger' : '' }}">
                            {{ $quotation->valid_until->format('F d, Y') }}
                            @if($quotation->isExpired())
                                <span class="badge bg-label-danger">Expired</span>
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cost Breakdown -->
        <div class="col-12">
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-money-dollar-circle-line me-2"></i>Cost Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="cost-breakdown">
                                @if($quotation->tour_price > 0)
                                <div class="cost-item">
                                    <span>Tour Price</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->tour_price, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->accommodation_cost > 0)
                                <div class="cost-item">
                                    <span>Accommodation</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->accommodation_cost, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->transport_cost > 0)
                                <div class="cost-item">
                                    <span>Transport</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->transport_cost, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->park_fees > 0)
                                <div class="cost-item">
                                    <span>Park Fees</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->park_fees, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->guide_fees > 0)
                                <div class="cost-item">
                                    <span>Guide Fees</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->guide_fees, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->meals_cost > 0)
                                <div class="cost-item">
                                    <span>Meals</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->meals_cost, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->activities_cost > 0)
                                <div class="cost-item">
                                    <span>Activities</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->activities_cost, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->service_charges > 0)
                                <div class="cost-item">
                                    <span>Service Charges</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->service_charges, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->addons_total > 0)
                                <div class="cost-item">
                                    <span>Add-ons</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->addons_total, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->discount > 0)
                                <div class="cost-item text-danger">
                                    <span>Discount @if($quotation->discount_percentage)({{ $quotation->discount_percentage }}%)@endif</span>
                                    <strong>-{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->discount, 2) }}</strong>
                                </div>
                                @endif
                                @if($quotation->tax > 0)
                                <div class="cost-item">
                                    <span>Tax</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->tax, 2) }}</strong>
                                </div>
                                @endif
                                <div class="cost-item cost-total">
                                    <span>Total Amount</span>
                                    <strong>{{ $quotation->currency ?? 'USD' }} {{ number_format($quotation->total_price, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Requests & Notes -->
        @if($quotation->special_requests || $quotation->notes || $quotation->admin_notes)
        <div class="col-12">
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-file-text-line me-2"></i>Additional Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($quotation->special_requests)
                    <div class="mb-3">
                        <strong>Special Requests:</strong>
                        <p class="mb-0">{{ $quotation->special_requests }}</p>
                    </div>
                    @endif
                    @if($quotation->notes)
                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <p class="mb-0">{{ $quotation->notes }}</p>
                    </div>
                    @endif
                    @if($quotation->admin_notes)
                    <div class="mb-3">
                        <strong>Admin Notes:</strong>
                        <p class="mb-0">{{ $quotation->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Communication History -->
        <div class="col-12">
            <div class="card info-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-message-3-line me-2"></i>Communication History
                    </h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                        <i class="ri-add-line me-1"></i>Add Note
                    </button>
                </div>
                <div class="card-body">
                    @php
                        // Use the relationship method to get notes, not the field
                        // Check if relationship is loaded, if not load it
                        if (!$quotation->relationLoaded('notes')) {
                            $quotation->load('notes.user');
                        }
                        $quotationNotes = $quotation->getRelation('notes');
                    @endphp
                    @if($quotationNotes && $quotationNotes->count() > 0)
                        <div class="timeline">
                            @foreach($quotationNotes->sortByDesc('created_at') as $note)
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center">
                                                @if($note->user && $note->user->avatar)
                                                    <img src="{{ asset('storage/' . $note->user->avatar) }}" alt="{{ $note->user->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.875rem; font-weight: 600;">
                                                        {{ substr($note->user->name ?? 'S', 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $note->user->name ?? 'System' }}</strong>
                                                <span class="note-badge bg-label-{{ $note->type == 'admin_note' ? 'primary' : ($note->type == 'customer_reply' ? 'success' : ($note->type == 'email_sent' ? 'info' : 'secondary')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $note->type)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $note->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <div class="note-content">
                                        <p class="mb-0">{{ $note->note }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No communication history yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attachments -->
        @if($quotation->itinerary_file || ($quotation->attachment_files && count($quotation->attachment_files) > 0))
        <div class="col-12">
            <div class="card info-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-attachment-line me-2"></i>Attachments
                    </h5>
                </div>
                <div class="card-body">
                    @if($quotation->itinerary_file)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $quotation->itinerary_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="ri-file-pdf-line me-1"></i>View Itinerary PDF
                        </a>
                    </div>
                    @endif
                    @if($quotation->attachment_files && count($quotation->attachment_files) > 0)
                        @foreach($quotation->attachment_files as $file)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="ri-file-line me-1"></i>{{ basename($file) }}
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addNoteForm" action="{{ route('admin.quotations.add-note', $quotation->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Note Type</label>
                        <select name="type" id="noteType" class="form-select" style="border-radius: 8px;">
                            <option value="admin_note">Admin Note</option>
                            <option value="customer_reply">Customer Reply</option>
                            <option value="system">System</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Note <span class="text-danger">*</span></label>
                        <textarea name="note" id="noteText" class="form-control" rows="5" required style="border-radius: 8px; resize: vertical;" placeholder="Enter your note here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; padding: 1.5rem;">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitNoteBtn">
                        <i class="ri-add-line me-1"></i>Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} show`;
        toast.style.cssText = 'min-width: 300px; padding: 1rem 1.5rem; margin-bottom: 1rem;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="ri-${type === 'success' ? 'check' : 'error-warning'}-line me-2" style="font-size: 1.25rem;"></i>
                <span>${message}</span>
            </div>
        `;
        
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Handle Send Quotation
    const sendBtn = document.getElementById('sendQuotationBtn');
    if (sendBtn) {
        sendBtn.addEventListener('click', function() {
            if (!confirm('Send this quotation to the customer via email?')) {
                return;
            }
            
            const btn = this;
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line me-1 animate-spin"></i>Sending...';
            
            fetch(`{{ route('admin.quotations.send', $quotation->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message || 'Quotation sent successfully!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'Failed to send quotation');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || 'An error occurred while sending the quotation', 'error');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        });
    }

    // Handle Add Note Form
    const addNoteForm = document.getElementById('addNoteForm');
    if (addNoteForm) {
        addNoteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitNoteBtn');
            const originalHtml = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line me-1 animate-spin"></i>Adding...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message || 'Note added successfully!', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addNoteModal'));
                    modal.hide();
                    this.reset();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to add note');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || 'An error occurred while adding the note', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            });
        });
    }

    // Handle form submissions (duplicate, convert-to-booking)
    document.querySelectorAll('form[action*="duplicate"], form[action*="convert-to-booking"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            const originalHtml = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line me-1 animate-spin"></i>Processing...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message || 'Operation completed successfully!', 'success');
                    if (data.booking) {
                        setTimeout(() => {
                            window.location.href = '/admin/bookings/' + data.booking.id;
                        }, 1500);
                    } else {
                        setTimeout(() => location.reload(), 1500);
                    }
                } else {
                    throw new Error(data.message || 'Operation failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || 'An error occurred', 'error');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        });
    });

    // Add spin animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
@endsection

