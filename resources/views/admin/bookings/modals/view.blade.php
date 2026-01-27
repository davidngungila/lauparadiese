<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewBookingContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromViewBtn">Edit Booking</button>
            </div>
        </div>
    </div>
</div>

<script>
function populateViewModal(booking) {
    const content = document.getElementById('viewBookingContent');
    const statusClass = booking.status === 'confirmed' ? 'success' : 
                       booking.status === 'pending_payment' ? 'warning' : 
                       booking.status === 'cancelled' ? 'danger' : 'info';
    
    content.innerHTML = `
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Booking Reference:</strong>
                <p class="mb-0">${booking.booking_reference || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <strong>Status:</strong>
                <p class="mb-0"><span class="badge bg-label-${statusClass}">${booking.status ? booking.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'N/A'}</span></p>
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Customer Name:</strong>
                <p class="mb-0">${booking.customer_name || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <strong>Email:</strong>
                <p class="mb-0">${booking.customer_email || 'N/A'}</p>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Phone:</strong>
                <p class="mb-0">${booking.customer_phone || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <strong>Country:</strong>
                <p class="mb-0">${booking.customer_country || 'N/A'}</p>
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Tour:</strong>
                <p class="mb-0">${booking.tour ? booking.tour.name : 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <strong>Departure Date:</strong>
                <p class="mb-0">${booking.departure_date ? new Date(booking.departure_date).toLocaleDateString() : 'N/A'}</p>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Number of Travelers:</strong>
                <p class="mb-0">${booking.travelers || 0}</p>
            </div>
            <div class="col-md-6">
                <strong>Payment Method:</strong>
                <p class="mb-0">${booking.payment_method ? booking.payment_method.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'N/A'}</p>
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Total Price:</strong>
                <p class="mb-0 h5 text-primary">$${parseFloat(booking.total_price || 0).toFixed(2)}</p>
            </div>
            <div class="col-md-4">
                <strong>Deposit:</strong>
                <p class="mb-0">$${parseFloat(booking.deposit_amount || 0).toFixed(2)}</p>
            </div>
            <div class="col-md-4">
                <strong>Balance:</strong>
                <p class="mb-0">$${parseFloat(booking.balance_amount || 0).toFixed(2)}</p>
            </div>
        </div>
        ${booking.addons && booking.addons.length > 0 ? `
        <div class="row mb-3">
            <div class="col-12">
                <strong>Add-ons:</strong>
                <p class="mb-0">${booking.addons.map(a => a.replace(/\b\w/g, l => l.toUpperCase())).join(', ')}</p>
            </div>
        </div>
        ` : ''}
        ${booking.special_requirements ? `
        <div class="row mb-3">
            <div class="col-12">
                <strong>Special Requirements:</strong>
                <p class="mb-0">${booking.special_requirements}</p>
            </div>
        </div>
        ` : ''}
        ${booking.emergency_contact_name ? `
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Emergency Contact:</strong>
                <p class="mb-0">${booking.emergency_contact_name}</p>
            </div>
            <div class="col-md-6">
                <strong>Emergency Phone:</strong>
                <p class="mb-0">${booking.emergency_contact_phone}</p>
            </div>
        </div>
        ` : ''}
        ${booking.notes ? `
        <div class="row mb-3">
            <div class="col-12">
                <strong>Notes:</strong>
                <p class="mb-0">${booking.notes}</p>
            </div>
        </div>
        ` : ''}
        ${booking.cancellation_reason ? `
        <div class="row mb-3">
            <div class="col-12">
                <strong>Cancellation Reason:</strong>
                <p class="mb-0 text-danger">${booking.cancellation_reason}</p>
            </div>
        </div>
        ` : ''}
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Created At:</strong>
                <p class="mb-0">${booking.created_at ? new Date(booking.created_at).toLocaleString() : 'N/A'}</p>
            </div>
            ${booking.confirmed_at ? `
            <div class="col-md-6">
                <strong>Confirmed At:</strong>
                <p class="mb-0">${new Date(booking.confirmed_at).toLocaleString()}</p>
            </div>
            ` : ''}
        </div>
    `;
    
    // Set edit button action
    document.getElementById('editFromViewBtn').onclick = function() {
        bootstrap.Modal.getInstance(document.getElementById('viewBookingModal')).hide();
        setTimeout(() => {
            document.querySelector(`.edit-booking[data-id="${booking.id}"]`).click();
        }, 300);
    };
}
</script>




