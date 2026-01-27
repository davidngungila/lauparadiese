@extends('admin.layouts.app')

@section('title', 'Create Quotation - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-file-add-line me-2"></i>Create New Quotation
                    </h4>
                    <a href="{{ route('admin.quotations.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>All Quotations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="createQuotationPageForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tour <span class="text-danger">*</span></label>
                        <select name="tour_id" id="pageTourSelect" class="form-select" required>
                            <option value="">Select Tour</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->id }}" data-price="{{ $tour->price }}" data-duration="{{ $tour->duration_days }}">{{ $tour->name }} - ${{ number_format($tour->price, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Generate from Booking (Optional)</label>
                        <select name="booking_id" id="pageBookingSelect" class="form-select">
                            <option value="">New Quotation</option>
                            @if(isset($bookings) && $bookings->count() > 0)
                                @foreach($bookings as $booking)
                                    <option value="{{ $booking->id }}" data-booking='@json($booking)'>{{ $booking->booking_reference }} - {{ $booking->customer_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" id="pageCustomerName" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="customer_email" id="pageCustomerEmail" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" id="pageCustomerPhone" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="customer_address" id="pageCustomerAddress" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Adults <span class="text-danger">*</span></label>
                        <input type="number" name="adults" id="pageAdults" class="form-control" min="1" max="50" value="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Children</label>
                        <input type="number" name="children" id="pageChildren" class="form-control" min="0" max="50" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Departure Date <span class="text-danger">*</span></label>
                        <input type="date" name="departure_date" id="pageDepartureDate" class="form-control" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (Days)</label>
                        <input type="number" name="duration_days" id="pageDurationDays" class="form-control" min="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tour Price <span class="text-danger">*</span></label>
                        <input type="number" name="tour_price" id="pageTourPrice" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Add-ons Total</label>
                        <input type="number" name="addons_total" id="pageAddonsTotal" class="form-control" step="0.01" min="0" value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Discount</label>
                        <input type="number" name="discount" id="pageDiscount" class="form-control" step="0.01" min="0" value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tax</label>
                        <input type="number" name="tax" id="pageTax" class="form-control" step="0.01" min="0" value="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Price <span class="text-danger">*</span></label>
                        <input type="number" name="total_price" id="pageTotalPrice" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                        <input type="date" name="valid_until" id="pageValidUntil" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Included</label>
                        <textarea name="included" id="pageIncluded" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Excluded</label>
                        <textarea name="excluded" id="pageExcluded" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Terms & Conditions</label>
                        <textarea name="terms_conditions" id="pageTermsConditions" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="pageNotes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Create Quotation
                    </button>
                    <a href="{{ route('admin.quotations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createQuotationPageForm');
    const tourSelect = document.getElementById('pageTourSelect');
    const bookingSelect = document.getElementById('pageBookingSelect');

    // Load tour details when selected
    tourSelect.addEventListener('change', function() {
        const tourId = this.value;
        if (tourId) {
            fetch(`/admin/quotations/tour/${tourId}/details`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('pageTourPrice').value = data.price || 0;
                    document.getElementById('pageDurationDays').value = data.duration_days || '';
                    document.getElementById('pageIncluded').value = data.included || '';
                    document.getElementById('pageExcluded').value = data.excluded || '';
                    calculatePageTotal();
                });
        }
    });

    // Load booking data when selected
    bookingSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value && option.dataset.booking) {
            const booking = JSON.parse(option.dataset.booking);
            document.getElementById('pageCustomerName').value = booking.customer_name || '';
            document.getElementById('pageCustomerEmail').value = booking.customer_email || '';
            document.getElementById('pageCustomerPhone').value = booking.customer_phone || '';
            // Set adults and children from travelers (assume all adults if not specified)
            const travelers = booking.travelers || 1;
            document.getElementById('pageAdults').value = travelers;
            document.getElementById('pageChildren').value = 0;
            document.getElementById('pageDepartureDate').value = booking.departure_date || '';
            if (booking.tour_id) {
                document.getElementById('pageTourSelect').value = booking.tour_id;
                document.getElementById('pageTourSelect').dispatchEvent(new Event('change'));
            }
        }
    });

    // Calculate total when prices change
    ['pageTourPrice', 'pageAddonsTotal', 'pageDiscount', 'pageTax'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculatePageTotal);
    });

    function calculatePageTotal() {
        const tourPrice = parseFloat(document.getElementById('pageTourPrice').value) || 0;
        const addons = parseFloat(document.getElementById('pageAddonsTotal').value) || 0;
        const discount = parseFloat(document.getElementById('pageDiscount').value) || 0;
        const tax = parseFloat(document.getElementById('pageTax').value) || 0;
        
        const subtotal = tourPrice + addons;
        const afterDiscount = subtotal - discount;
        const total = afterDiscount + tax;
        
        document.getElementById('pageTotalPrice').value = total.toFixed(2);
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.quotations.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(async res => {
            const contentType = res.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return res.json();
            } else {
                // If response is not JSON, it might be a redirect or HTML error
                const text = await res.text();
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.quotations.index") }}';
            } else {
                let errorMsg = 'Failed to create quotation';
                if (data.errors) {
                    // Display validation errors
                    const errorList = Object.values(data.errors).flat().join('\n');
                    errorMsg = 'Validation errors:\n' + errorList;
                } else if (data.message) {
                    errorMsg = data.message;
                }
                alert(errorMsg);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the quotation. Please check the console for details.');
        });
    });
});
</script>
@endpush
@endsection




