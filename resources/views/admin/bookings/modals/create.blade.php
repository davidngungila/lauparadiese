<!-- Create Booking Modal -->
<div class="modal fade" id="createBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createBookingForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tour <span class="text-danger">*</span></label>
                            <select name="tour_id" class="form-select" required>
                                <option value="">Select Tour</option>
                                @foreach($tours as $tour)
                                    <option value="{{ $tour->id }}" data-price="{{ $tour->price }}">{{ $tour->name }} - ${{ number_format($tour->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer (Optional)</label>
                            <select name="user_id" class="form-select" id="userSelect">
                                <option value="">New Customer</option>
                                @if(isset($users) && $users->count() > 0)
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">Or enter customer details below</small>
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
                            <label class="form-label">Number of Travelers <span class="text-danger">*</span></label>
                            <input type="number" name="travelers" class="form-control" min="1" max="50" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Departure Date <span class="text-danger">*</span></label>
                            <input type="date" name="departure_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Price <span class="text-danger">*</span></label>
                            <input type="number" name="total_price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Deposit Amount</label>
                            <input type="number" name="deposit_amount" class="form-control" step="0.01" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Balance Amount</label>
                            <input type="number" name="balance_amount" class="form-control" step="0.01" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="pending_payment">Pending Payment</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Select Method</option>
                                <option value="card">Credit Card</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="tigopesa">TigoPesa</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="later">Pay Later</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Phone</label>
                            <input type="text" name="emergency_contact_phone" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Add-ons</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="addons[]" value="insurance" id="addon_insurance">
                                <label class="form-check-label" for="addon_insurance">Travel Insurance (+$150)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="addons[]" value="gear" id="addon_gear">
                                <label class="form-check-label" for="addon_gear">Gear Rental (+$80)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Special Requirements</label>
                            <textarea name="special_requirements" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('createBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    data.addons = formData.getAll('addons[]');
    
    fetch('{{ route("admin.bookings.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('createBookingModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to create booking'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
    });
});

// Auto-calculate price based on tour and travelers
document.querySelector('select[name="tour_id"]').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const price = parseFloat(selected.dataset.price || 0);
    const travelers = parseInt(document.querySelector('input[name="travelers"]').value || 1);
    document.querySelector('input[name="total_price"]').value = (price * travelers).toFixed(2);
});

document.querySelector('input[name="travelers"]').addEventListener('input', function() {
    const tourSelect = document.querySelector('select[name="tour_id"]');
    const selected = tourSelect.options[tourSelect.selectedIndex];
    const price = parseFloat(selected.dataset.price || 0);
    const travelers = parseInt(this.value || 1);
    document.querySelector('input[name="total_price"]').value = (price * travelers).toFixed(2);
});
</script>

