@extends('admin.layouts.app')

@section('title', 'Create New Booking - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-add-line me-2"></i>Create New Booking
                    </h4>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="createBookingPageForm" enctype="multipart/form-data">
                @csrf
                
                <!-- A. Customer Information -->
                <h5 class="mb-3"><i class="ri-user-line me-2"></i>A. Customer Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Customer (Optional - Existing User)</label>
                        <select name="user_id" class="form-select">
                            <option value="">New Customer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">If customer exists in system, select here. Otherwise fill details below.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="customer_email" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <input type="text" name="customer_country" class="form-control" value="Tanzania">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Passport Number (Optional)</label>
                        <input type="text" name="passport_number" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Emergency Contact Name (Optional)</label>
                        <input type="text" name="emergency_contact_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Emergency Contact Phone (Optional)</label>
                        <input type="text" name="emergency_contact_phone" class="form-control">
                    </div>
                </div>

                <!-- B. Booking Details -->
                <h5 class="mb-3"><i class="ri-calendar-line me-2"></i>B. Booking Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Package / Tour / Destination <span class="text-danger">*</span></label>
                        <select name="tour_id" class="form-select" required>
                            <option value="">Select Tour</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->id }}" data-price="{{ $tour->price }}">{{ $tour->name }} - ${{ number_format($tour->price, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Travel Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="departure_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Travel End Date</label>
                        <input type="date" name="travel_end_date" class="form-control" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Number of Adults <span class="text-danger">*</span></label>
                        <input type="number" name="number_of_adults" class="form-control" min="1" max="50" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Number of Children</label>
                        <input type="number" name="number_of_children" class="form-control" min="0" max="50" value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Accommodation Level</label>
                        <select name="accommodation_level" class="form-select">
                            <option value="">Select Level</option>
                            <option value="budget">Budget</option>
                            <option value="midrange">Midrange</option>
                            <option value="luxury">Luxury</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Number of Travelers (Auto)</label>
                        <input type="number" name="travelers" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pickup Location</label>
                        <input type="text" name="pickup_location" class="form-control" placeholder="e.g., Airport, Hotel name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Drop-off Location</label>
                        <input type="text" name="dropoff_location" class="form-control" placeholder="e.g., Airport, Hotel name">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Special Requirements</label>
                        <textarea name="special_requirements" class="form-control" rows="3" placeholder="Any special requests or requirements..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Attachments (Passport/ID, Travel docs)</label>
                        <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">You can upload multiple files (PDF, JPG, PNG). Max 5MB per file.</small>
                    </div>
                </div>

                <!-- C. Payment Details -->
                <h5 class="mb-3"><i class="ri-money-dollar-circle-line me-2"></i>C. Payment Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Currency</label>
                        <select name="currency" class="form-select">
                            <option value="USD" selected>USD</option>
                            <option value="TZS">TZS</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Cost <span class="text-danger">*</span></label>
                        <input type="number" name="total_price" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Discount Amount</label>
                        <input type="number" name="discount_amount" class="form-control" step="0.01" min="0" id="discount_amount">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Discount %</label>
                        <input type="number" name="discount_percentage" class="form-control" step="0.01" min="0" max="100" id="discount_percentage">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Net Total</label>
                        <input type="number" name="net_total" class="form-control" step="0.01" readonly id="net_total">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="">Select Method</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="tigopesa">TigoPesa</option>
                            <option value="airtel">Airtel Money</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Collected</label>
                        <select name="payment_collected" class="form-select" id="payment_collected">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount Paid</label>
                        <input type="number" name="amount_paid" class="form-control" step="0.01" min="0" value="0" id="amount_paid">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Balance</label>
                        <input type="number" name="balance_amount" class="form-control" step="0.01" readonly id="balance_amount">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Upload Payment Receipt (Optional)</label>
                        <input type="file" name="payment_receipt" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>

                <!-- D. Administration Options -->
                <h5 class="mb-3"><i class="ri-settings-3-line me-2"></i>D. Administration Options</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Assign Booking Staff / Agent</label>
                        <select name="assigned_staff_id" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach($staff ?? [] as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Set Booking Source</label>
                        <select name="booking_source" class="form-select">
                            <option value="manual" selected>Manual</option>
                            <option value="website">Website</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="referral">Referral</option>
                            <option value="agent">Agent</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Initial Booking Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="pending_payment">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Initial Approval Status</label>
                        <select name="initial_approval_status" class="form-select">
                            <option value="pending" selected>Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Booking Notes (Internal Only)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Internal notes about this booking..."></textarea>
                    </div>
                </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" name="action" value="save">
                        <i class="ri-save-line me-1"></i>Save Booking
                    </button>
                    <button type="submit" class="btn btn-success" name="action" value="save_and_email">
                        <i class="ri-mail-send-line me-1"></i>Save & Send Confirmation Email
                    </button>
                    <button type="submit" class="btn btn-info" name="action" value="save_and_invoice">
                        <i class="ri-file-list-3-line me-1"></i>Save & Generate Invoice
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-label-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Calculate travelers automatically
function calculateTravelers() {
    const adults = parseInt(document.querySelector('input[name="number_of_adults"]').value || 1);
    const children = parseInt(document.querySelector('input[name="number_of_children"]').value || 0);
    document.querySelector('input[name="travelers"]').value = adults + children;
}

document.querySelector('input[name="number_of_adults"]').addEventListener('input', calculateTravelers);
document.querySelector('input[name="number_of_children"]').addEventListener('input', calculateTravelers);

// Calculate net total with discount
function calculateNetTotal() {
    const total = parseFloat(document.querySelector('input[name="total_price"]').value || 0);
    const discountAmount = parseFloat(document.querySelector('#discount_amount').value || 0);
    const discountPercent = parseFloat(document.querySelector('#discount_percentage').value || 0);
    
    let discount = discountAmount;
    if (discountPercent > 0) {
        discount = total * (discountPercent / 100);
        document.querySelector('#discount_amount').value = discount.toFixed(2);
    }
    
    const netTotal = Math.max(0, total - discount);
    document.querySelector('#net_total').value = netTotal.toFixed(2);
    
    // Update balance
    const amountPaid = parseFloat(document.querySelector('#amount_paid').value || 0);
    document.querySelector('#balance_amount').value = Math.max(0, netTotal - amountPaid).toFixed(2);
}

document.querySelector('input[name="total_price"]').addEventListener('input', calculateNetTotal);
document.querySelector('#discount_amount').addEventListener('input', function() {
    document.querySelector('#discount_percentage').value = '';
    calculateNetTotal();
});
document.querySelector('#discount_percentage').addEventListener('input', function() {
    document.querySelector('#discount_amount').value = '';
    calculateNetTotal();
});
document.querySelector('#amount_paid').addEventListener('input', calculateNetTotal);

// Auto-calculate price from tour
document.querySelector('select[name="tour_id"]')?.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const price = parseFloat(selected.dataset.price || 0);
    const adults = parseInt(document.querySelector('input[name="number_of_adults"]').value || 1);
    const children = parseInt(document.querySelector('input[name="number_of_children"]').value || 0);
    const total = price * (adults + children);
    document.querySelector('input[name="total_price"]').value = total.toFixed(2);
    calculateNetTotal();
});

// Form submission
document.getElementById('createBookingPageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    // Calculate final values
    calculateTravelers();
    calculateNetTotal();
    
    // Update form data with calculated values
    formData.set('travelers', document.querySelector('input[name="travelers"]').value);
    formData.set('balance_amount', document.querySelector('#balance_amount').value);
    
    fetch('{{ route("admin.bookings.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("admin.bookings.index") }}';
        } else {
            if (data.errors) {
                let errorMsg = 'Validation errors:\n';
                Object.keys(data.errors).forEach(key => {
                    errorMsg += key + ': ' + data.errors[key][0] + '\n';
                });
                alert(errorMsg);
            } else {
                alert('Error: ' + (data.message || 'Failed to create booking'));
            }
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
    });
});
</script>
@endpush
@endsection
