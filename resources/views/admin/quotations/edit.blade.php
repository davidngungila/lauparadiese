@extends('admin.layouts.app')

@section('title', 'Edit Quotation - ' . $quotation->quotation_number . ' - Lau Paradise Adventures')

@push('styles')
<style>
    .quotation-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
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
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="quotation-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-white">
                            <i class="ri-file-edit-line me-2"></i>Edit Quotation: {{ $quotation->quotation_number }}
                        </h4>
                        <p class="mb-0 text-white-50">Update quotation details and information</p>
                    </div>
                    <a href="{{ route('admin.quotations.show', $quotation->id) }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i>Back to Quotation
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="editQuotationForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tour <span class="text-danger">*</span></label>
                        <select name="tour_id" id="editTourSelect" class="form-select" required>
                            <option value="">Select Tour</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->id }}" 
                                    data-price="{{ $tour->price }}" 
                                    data-duration="{{ $tour->duration_days }}"
                                    {{ $quotation->tour_id == $tour->id ? 'selected' : '' }}>
                                    {{ $tour->name }} - ${{ number_format($tour->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Generate from Booking (Optional)</label>
                        <select name="booking_id" id="editBookingSelect" class="form-select">
                            <option value="">No Booking</option>
                            @if(isset($bookings) && $bookings->count() > 0)
                                @foreach($bookings as $booking)
                                    <option value="{{ $booking->id }}" 
                                        data-booking='@json($booking)'
                                        {{ $quotation->booking_id == $booking->id ? 'selected' : '' }}>
                                        {{ $booking->booking_reference }} - {{ $booking->customer_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" id="editCustomerName" class="form-control" value="{{ old('customer_name', $quotation->customer_name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="customer_email" id="editCustomerEmail" class="form-control" value="{{ old('customer_email', $quotation->customer_email) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" id="editCustomerPhone" class="form-control" value="{{ old('customer_phone', $quotation->customer_phone) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <input type="text" name="customer_country" id="editCustomerCountry" class="form-control" value="{{ old('customer_country', $quotation->customer_country) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="customer_city" id="editCustomerCity" class="form-control" value="{{ old('customer_city', $quotation->customer_city) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="customer_address" id="editCustomerAddress" class="form-control" rows="2">{{ old('customer_address', $quotation->customer_address) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Adults <span class="text-danger">*</span></label>
                        <input type="number" name="adults" id="editAdults" class="form-control" min="1" max="50" value="{{ old('adults', $quotation->adults ?? 1) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Children</label>
                        <input type="number" name="children" id="editChildren" class="form-control" min="0" max="50" value="{{ old('children', $quotation->children ?? 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Departure Date <span class="text-danger">*</span></label>
                        <input type="date" name="departure_date" id="editDepartureDate" class="form-control" value="{{ old('departure_date', $quotation->departure_date ? $quotation->departure_date->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="editEndDate" class="form-control" value="{{ old('end_date', $quotation->end_date ? $quotation->end_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (Days)</label>
                        <input type="number" name="duration_days" id="editDurationDays" class="form-control" min="1" value="{{ old('duration_days', $quotation->duration_days) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Accommodation Type</label>
                        <select name="accommodation_type" id="editAccommodationType" class="form-select">
                            <option value="">Select Type</option>
                            <option value="budget" {{ old('accommodation_type', $quotation->accommodation_type) == 'budget' ? 'selected' : '' }}>Budget</option>
                            <option value="mid-range" {{ old('accommodation_type', $quotation->accommodation_type) == 'mid-range' ? 'selected' : '' }}>Mid-Range</option>
                            <option value="luxury" {{ old('accommodation_type', $quotation->accommodation_type) == 'luxury' ? 'selected' : '' }}>Luxury</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Airport Pickup</label>
                        <select name="airport_pickup" id="editAirportPickup" class="form-select">
                            <option value="0" {{ old('airport_pickup', $quotation->airport_pickup) == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('airport_pickup', $quotation->airport_pickup) == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tour Price <span class="text-danger">*</span></label>
                        <input type="number" name="tour_price" id="editTourPrice" class="form-control" step="0.01" min="0" value="{{ old('tour_price', $quotation->tour_price) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Accommodation Cost</label>
                        <input type="number" name="accommodation_cost" id="editAccommodationCost" class="form-control" step="0.01" min="0" value="{{ old('accommodation_cost', $quotation->accommodation_cost ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Transport Cost</label>
                        <input type="number" name="transport_cost" id="editTransportCost" class="form-control" step="0.01" min="0" value="{{ old('transport_cost', $quotation->transport_cost ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Park Fees</label>
                        <input type="number" name="park_fees" id="editParkFees" class="form-control" step="0.01" min="0" value="{{ old('park_fees', $quotation->park_fees ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Guide Fees</label>
                        <input type="number" name="guide_fees" id="editGuideFees" class="form-control" step="0.01" min="0" value="{{ old('guide_fees', $quotation->guide_fees ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Meals Cost</label>
                        <input type="number" name="meals_cost" id="editMealsCost" class="form-control" step="0.01" min="0" value="{{ old('meals_cost', $quotation->meals_cost ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Activities Cost</label>
                        <input type="number" name="activities_cost" id="editActivitiesCost" class="form-control" step="0.01" min="0" value="{{ old('activities_cost', $quotation->activities_cost ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Service Charges</label>
                        <input type="number" name="service_charges" id="editServiceCharges" class="form-control" step="0.01" min="0" value="{{ old('service_charges', $quotation->service_charges ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Add-ons Total</label>
                        <input type="number" name="addons_total" id="editAddonsTotal" class="form-control" step="0.01" min="0" value="{{ old('addons_total', $quotation->addons_total ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Discount</label>
                        <input type="number" name="discount" id="editDiscount" class="form-control" step="0.01" min="0" value="{{ old('discount', $quotation->discount ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Discount %</label>
                        <input type="number" name="discount_percentage" id="editDiscountPercentage" class="form-control" step="0.01" min="0" max="100" value="{{ old('discount_percentage', $quotation->discount_percentage ?? 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tax</label>
                        <input type="number" name="tax" id="editTax" class="form-control" step="0.01" min="0" value="{{ old('tax', $quotation->tax ?? 0) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Price <span class="text-danger">*</span></label>
                        <input type="number" name="total_price" id="editTotalPrice" class="form-control" step="0.01" min="0" value="{{ old('total_price', $quotation->total_price) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Currency</label>
                        <input type="text" name="currency" id="editCurrency" class="form-control" maxlength="3" value="{{ old('currency', $quotation->currency ?? 'USD') }}" placeholder="USD">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="editStatus" class="form-select">
                            <option value="pending" {{ old('status', $quotation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ old('status', $quotation->status) == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="sent" {{ old('status', $quotation->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="approved" {{ old('status', $quotation->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $quotation->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="closed" {{ old('status', $quotation->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                        <input type="date" name="valid_until" id="editValidUntil" class="form-control" value="{{ old('valid_until', $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assigned Agent</label>
                        <select name="agent_id" id="editAgentId" class="form-select">
                            <option value="">No Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id', $quotation->agent_id) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Included</label>
                        <textarea name="included" id="editIncluded" class="form-control" rows="3">{{ old('included', $quotation->included) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Excluded</label>
                        <textarea name="excluded" id="editExcluded" class="form-control" rows="3">{{ old('excluded', $quotation->excluded) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Terms & Conditions</label>
                        <textarea name="terms_conditions" id="editTermsConditions" class="form-control" rows="3">{{ old('terms_conditions', $quotation->terms_conditions) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="editNotes" class="form-control" rows="3">{{ old('notes', $quotation->notes) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Special Requests</label>
                        <textarea name="special_requests" id="editSpecialRequests" class="form-control" rows="3">{{ old('special_requests', $quotation->special_requests) }}</textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="ri-save-line me-1"></i>Update Quotation
                    </button>
                    <a href="{{ route('admin.quotations.show', $quotation->id) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editQuotationForm');
    const tourSelect = document.getElementById('editTourSelect');
    const bookingSelect = document.getElementById('editBookingSelect');

    // Load tour details when selected
    tourSelect.addEventListener('change', function() {
        const tourId = this.value;
        if (tourId) {
            fetch(`/admin/quotations/tour/${tourId}/details`)
                .then(res => res.json())
                .then(data => {
                    if (!document.getElementById('editTourPrice').value || document.getElementById('editTourPrice').value == '0') {
                        document.getElementById('editTourPrice').value = data.price || 0;
                    }
                    if (!document.getElementById('editDurationDays').value) {
                        document.getElementById('editDurationDays').value = data.duration_days || '';
                    }
                    if (!document.getElementById('editIncluded').value) {
                        document.getElementById('editIncluded').value = data.included || '';
                    }
                    if (!document.getElementById('editExcluded').value) {
                        document.getElementById('editExcluded').value = data.excluded || '';
                    }
                    calculateEditTotal();
                });
        }
    });

    // Load booking data when selected
    bookingSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value && option.dataset.booking) {
            const booking = JSON.parse(option.dataset.booking);
            document.getElementById('editCustomerName').value = booking.customer_name || '';
            document.getElementById('editCustomerEmail').value = booking.customer_email || '';
            document.getElementById('editCustomerPhone').value = booking.customer_phone || '';
            const travelers = booking.travelers || 1;
            document.getElementById('editAdults').value = travelers;
            document.getElementById('editChildren').value = 0;
            document.getElementById('editDepartureDate').value = booking.departure_date || '';
            if (booking.tour_id) {
                document.getElementById('editTourSelect').value = booking.tour_id;
                document.getElementById('editTourSelect').dispatchEvent(new Event('change'));
            }
        }
    });

    // Calculate total when prices change
    ['editTourPrice', 'editAddonsTotal', 'editDiscount', 'editTax', 'editAccommodationCost', 'editTransportCost', 'editParkFees', 'editGuideFees', 'editMealsCost', 'editActivitiesCost', 'editServiceCharges'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', calculateEditTotal);
        }
    });

    function calculateEditTotal() {
        const tourPrice = parseFloat(document.getElementById('editTourPrice').value) || 0;
        const accommodation = parseFloat(document.getElementById('editAccommodationCost').value) || 0;
        const transport = parseFloat(document.getElementById('editTransportCost').value) || 0;
        const parkFees = parseFloat(document.getElementById('editParkFees').value) || 0;
        const guideFees = parseFloat(document.getElementById('editGuideFees').value) || 0;
        const meals = parseFloat(document.getElementById('editMealsCost').value) || 0;
        const activities = parseFloat(document.getElementById('editActivitiesCost').value) || 0;
        const serviceCharges = parseFloat(document.getElementById('editServiceCharges').value) || 0;
        const addons = parseFloat(document.getElementById('editAddonsTotal').value) || 0;
        const discount = parseFloat(document.getElementById('editDiscount').value) || 0;
        const tax = parseFloat(document.getElementById('editTax').value) || 0;
        
        const subtotal = tourPrice + accommodation + transport + parkFees + guideFees + meals + activities + serviceCharges + addons;
        const afterDiscount = subtotal - discount;
        const total = afterDiscount + tax;
        
        document.getElementById('editTotalPrice').value = total.toFixed(2);
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line me-1 animate-spin"></i>Updating...';
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.quotations.update", $quotation->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(async res => {
            const contentType = res.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return res.json();
            } else {
                const text = await res.text();
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.quotations.show", $quotation->id) }}';
            } else {
                let errorMsg = 'Failed to update quotation';
                if (data.errors) {
                    const errorList = Object.values(data.errors).flat().join('\n');
                    errorMsg = 'Validation errors:\n' + errorList;
                } else if (data.message) {
                    errorMsg = data.message;
                }
                alert(errorMsg);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the quotation. Please check the console for details.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
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





