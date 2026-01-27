<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelBookingForm">
                @csrf
                <input type="hidden" name="booking_id" id="cancelBookingId">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
                        Are you sure you want to cancel this booking? This action cannot be undone.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                        <textarea name="cancellation_reason" class="form-control" rows="4" required placeholder="Please provide a reason for cancellation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">No, Keep Booking</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('cancelBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('cancelBookingId').value;
    const formData = new FormData(this);
    const data = {
        cancellation_reason: formData.get('cancellation_reason')
    };
    
    fetch(`/admin/bookings/${id}/cancel`, {
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
            bootstrap.Modal.getInstance(document.getElementById('cancelBookingModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to cancel booking'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
    });
});
</script>




