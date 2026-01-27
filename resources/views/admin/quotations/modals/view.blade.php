<!-- View Quotation Modal -->
<div class="modal fade" id="viewQuotationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quotation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewQuotationContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-outline-info" id="viewQuotationPDFBtn" target="_blank">
                    <i class="ri-eye-line me-1"></i>View PDF
                </a>
                <a href="#" class="btn btn-primary" id="downloadQuotationBtn">
                    <i class="ri-download-line me-1"></i>Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function populateViewModal(data) {
    const content = document.getElementById('viewQuotationContent');
    const statusClass = {
        'accepted': 'success',
        'pending': 'warning',
        'sent': 'info',
        'rejected': 'danger',
        'expired': 'secondary'
    }[data.status] || 'secondary';

    content.innerHTML = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>Quotation Number:</strong>
                <p class="mb-0">${data.quotation_number || 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Status:</strong>
                <p class="mb-0"><span class="badge bg-label-${statusClass}">${data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1) : 'N/A'}</span></p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Customer Name:</strong>
                <p class="mb-0">${data.customer_name || 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Email:</strong>
                <p class="mb-0">${data.customer_email || 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Phone:</strong>
                <p class="mb-0">${data.customer_phone || 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Address:</strong>
                <p class="mb-0">${data.customer_address || 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Tour:</strong>
                <p class="mb-0">${data.tour_name || (data.tour ? data.tour.name : 'N/A')}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Travelers:</strong>
                <p class="mb-0">${data.travelers || 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Departure Date:</strong>
                <p class="mb-0">${data.departure_date ? new Date(data.departure_date).toLocaleDateString() : 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Duration:</strong>
                <p class="mb-0">${data.duration_days ? data.duration_days + ' days' : 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Tour Price:</strong>
                <p class="mb-0">$${data.tour_price ? parseFloat(data.tour_price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Add-ons Total:</strong>
                <p class="mb-0">$${data.addons_total ? parseFloat(data.addons_total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Discount:</strong>
                <p class="mb-0">$${data.discount ? parseFloat(data.discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Tax:</strong>
                <p class="mb-0">$${data.tax ? parseFloat(data.tax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Total Price:</strong>
                <p class="mb-0"><strong>$${data.total_price ? parseFloat(data.total_price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'}</strong></p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Valid Until:</strong>
                <p class="mb-0">${data.valid_until ? new Date(data.valid_until).toLocaleDateString() : 'N/A'}</p>
            </div>
            ${data.included ? `
            <div class="col-12 mb-3">
                <strong>Included:</strong>
                <p class="mb-0">${data.included}</p>
            </div>
            ` : ''}
            ${data.excluded ? `
            <div class="col-12 mb-3">
                <strong>Excluded:</strong>
                <p class="mb-0">${data.excluded}</p>
            </div>
            ` : ''}
            ${data.terms_conditions ? `
            <div class="col-12 mb-3">
                <strong>Terms & Conditions:</strong>
                <p class="mb-0">${data.terms_conditions}</p>
            </div>
            ` : ''}
            ${data.notes ? `
            <div class="col-12 mb-3">
                <strong>Notes:</strong>
                <p class="mb-0">${data.notes}</p>
            </div>
            ` : ''}
            <div class="col-md-6 mb-3">
                <strong>Created At:</strong>
                <p class="mb-0">${data.created_at ? new Date(data.created_at).toLocaleString() : 'N/A'}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Created By:</strong>
                <p class="mb-0">${data.creator ? data.creator.name : 'N/A'}</p>
            </div>
        </div>
    `;

    // Set PDF buttons
    if (data.id) {
        document.getElementById('downloadQuotationBtn').href = `/admin/quotations/${data.id}/pdf`;
        document.getElementById('viewQuotationPDFBtn').href = `/admin/quotations/${data.id}/view`;
    }
}
</script>

