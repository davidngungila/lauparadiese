<!-- Create Quotation Modal -->
<div class="modal fade" id="createQuotationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Quotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createQuotationForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tour <span class="text-danger">*</span></label>
                            <select name="tour_id" id="createTourSelect" class="form-select" required>
                                <option value="">Select Tour</option>
                                @foreach($tours as $tour)
                                    <option value="{{ $tour->id }}" data-price="{{ $tour->price }}" data-duration="{{ $tour->duration_days }}">{{ $tour->name }} - ${{ number_format($tour->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Generate from Booking (Optional)</label>
                            <select name="booking_id" id="bookingSelect" class="form-select">
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
                            <input type="text" name="customer_name" id="createCustomerName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" id="createCustomerEmail" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" id="createCustomerPhone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="customer_address" id="createCustomerAddress" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Number of Travelers <span class="text-danger">*</span></label>
                            <input type="number" name="travelers" id="createTravelers" class="form-control" min="1" max="50" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Departure Date <span class="text-danger">*</span></label>
                            <input type="date" name="departure_date" id="createDepartureDate" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration (Days)</label>
                            <input type="number" name="duration_days" id="createDurationDays" class="form-control" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tour Price <span class="text-danger">*</span></label>
                            <input type="number" name="tour_price" id="createTourPrice" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Add-ons Total</label>
                            <input type="number" name="addons_total" id="createAddonsTotal" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Discount</label>
                            <input type="number" name="discount" id="createDiscount" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tax</label>
                            <input type="number" name="tax" id="createTax" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Price <span class="text-danger">*</span></label>
                            <input type="number" name="total_price" id="createTotalPrice" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                            <input type="date" name="valid_until" id="createValidUntil" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Included</label>
                            <textarea name="included" id="createIncluded" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Excluded</label>
                            <textarea name="excluded" id="createExcluded" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Terms & Conditions</label>
                            <textarea name="terms_conditions" id="createTermsConditions" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="createNotes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Quotation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createQuotationForm');
    const tourSelect = document.getElementById('createTourSelect');
    const bookingSelect = document.getElementById('bookingSelect');

    // Load tour details when selected
    tourSelect.addEventListener('change', function() {
        const tourId = this.value;
        if (tourId) {
            fetch(`/admin/quotations/tour/${tourId}/details`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('createTourPrice').value = data.price || 0;
                    document.getElementById('createDurationDays').value = data.duration_days || '';
                    document.getElementById('createIncluded').value = data.included || '';
                    document.getElementById('createExcluded').value = data.excluded || '';
                    calculateTotal();
                });
        }
    });

    // Load booking data when selected
    bookingSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value && option.dataset.booking) {
            const booking = JSON.parse(option.dataset.booking);
            document.getElementById('createCustomerName').value = booking.customer_name || '';
            document.getElementById('createCustomerEmail').value = booking.customer_email || '';
            document.getElementById('createCustomerPhone').value = booking.customer_phone || '';
            document.getElementById('createTravelers').value = booking.travelers || 1;
            document.getElementById('createDepartureDate').value = booking.departure_date || '';
            if (booking.tour_id) {
                document.getElementById('createTourSelect').value = booking.tour_id;
                document.getElementById('createTourSelect').dispatchEvent(new Event('change'));
            }
        }
    });

    // Calculate total when prices change
    ['createTourPrice', 'createAddonsTotal', 'createDiscount', 'createTax'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculateTotal);
    });

    function calculateTotal() {
        const tourPrice = parseFloat(document.getElementById('createTourPrice').value) || 0;
        const addons = parseFloat(document.getElementById('createAddonsTotal').value) || 0;
        const discount = parseFloat(document.getElementById('createDiscount').value) || 0;
        const tax = parseFloat(document.getElementById('createTax').value) || 0;
        
        const subtotal = tourPrice + addons;
        const afterDiscount = subtotal - discount;
        const total = afterDiscount + tax;
        
        document.getElementById('createTotalPrice').value = total.toFixed(2);
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.quotations.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('createQuotationModal')).hide();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to create quotation'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the quotation');
        });
    });
});
</script>




