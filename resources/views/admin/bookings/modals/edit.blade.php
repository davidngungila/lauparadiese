<!-- Edit Booking Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBookingForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="booking_id" id="editBookingId">
                <div class="modal-body" id="editBookingContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function populateEditModal(booking) {
    document.getElementById('editBookingId').value = booking.id;
    const content = document.getElementById('editBookingContent');
    
    // Use tours from global variable or fetch
    let toursHtml = '<option value="">Select Tour</option>';
    const tours = typeof toursData !== 'undefined' ? toursData : [];
    tours.forEach(tour => {
        toursHtml += `<option value="${tour.id}" ${booking.tour_id == tour.id ? 'selected' : ''}>${tour.name}</option>`;
    });
    
    content.innerHTML = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tour</label>
                <select name="tour_id" class="form-select">
                    ${toursHtml}
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending_payment" ${booking.status == 'pending_payment' ? 'selected' : ''}>Pending Payment</option>
                    <option value="confirmed" ${booking.status == 'confirmed' ? 'selected' : ''}>Confirmed</option>
                    <option value="cancelled" ${booking.status == 'cancelled' ? 'selected' : ''}>Cancelled</option>
                    <option value="completed" ${booking.status == 'completed' ? 'selected' : ''}>Completed</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" value="${booking.customer_name || ''}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="customer_email" class="form-control" value="${booking.customer_email || ''}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Phone</label>
                <input type="text" name="customer_phone" class="form-control" value="${booking.customer_phone || ''}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Number of Travelers</label>
                <input type="number" name="travelers" class="form-control" min="1" max="50" value="${booking.travelers || 1}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Departure Date</label>
                <input type="date" name="departure_date" class="form-control" value="${booking.departure_date ? booking.departure_date.split('T')[0] : ''}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Total Price</label>
                <input type="number" name="total_price" class="form-control" step="0.01" min="0" value="${booking.total_price || 0}">
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">${booking.notes || ''}</textarea>
            </div>
        </div>
    `;
}

document.getElementById('editBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('editBookingId').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    delete data.booking_id;
    
    fetch(`/admin/bookings/${id}`, {
        method: 'PUT',
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
            bootstrap.Modal.getInstance(document.getElementById('editBookingModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update booking'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
    });
});
</script>

