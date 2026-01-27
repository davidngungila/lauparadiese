<!-- Edit Quotation Modal -->
<div class="modal fade" id="editQuotationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Quotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editQuotationForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="quotation_id" id="editQuotationId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tour <span class="text-danger">*</span></label>
                            <select name="tour_id" id="editTourSelect" class="form-select" required>
                                <option value="">Select Tour</option>
                                @foreach($tours as $tour)
                                    <option value="{{ $tour->id }}">{{ $tour->name }} - ${{ number_format($tour->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quotation Number</label>
                            <input type="text" id="editQuotationNumber" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="editCustomerName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" id="editCustomerEmail" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" id="editCustomerPhone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="customer_address" id="editCustomerAddress" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Number of Travelers <span class="text-danger">*</span></label>
                            <input type="number" name="travelers" id="editTravelers" class="form-control" min="1" max="50" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Departure Date <span class="text-danger">*</span></label>
                            <input type="date" name="departure_date" id="editDepartureDate" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration (Days)</label>
                            <input type="number" name="duration_days" id="editDurationDays" class="form-control" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tour Price <span class="text-danger">*</span></label>
                            <input type="number" name="tour_price" id="editTourPrice" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Add-ons Total</label>
                            <input type="number" name="addons_total" id="editAddonsTotal" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Discount</label>
                            <input type="number" name="discount" id="editDiscount" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tax</label>
                            <input type="number" name="tax" id="editTax" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Price <span class="text-danger">*</span></label>
                            <input type="number" name="total_price" id="editTotalPrice" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                            <input type="date" name="valid_until" id="editValidUntil" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Included</label>
                            <textarea name="included" id="editIncluded" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Excluded</label>
                            <textarea name="excluded" id="editExcluded" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Terms & Conditions</label>
                            <textarea name="terms_conditions" id="editTermsConditions" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="editNotes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Quotation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function populateEditModal(data) {
    document.getElementById('editQuotationId').value = data.id;
    document.getElementById('editQuotationNumber').value = data.quotation_number || '';
    document.getElementById('editTourSelect').value = data.tour_id || '';
    document.getElementById('editCustomerName').value = data.customer_name || '';
    document.getElementById('editCustomerEmail').value = data.customer_email || '';
    document.getElementById('editCustomerPhone').value = data.customer_phone || '';
    document.getElementById('editCustomerAddress').value = data.customer_address || '';
    document.getElementById('editTravelers').value = data.travelers || 1;
    document.getElementById('editDepartureDate').value = data.departure_date ? data.departure_date.split('T')[0] : '';
    document.getElementById('editDurationDays').value = data.duration_days || '';
    document.getElementById('editTourPrice').value = data.tour_price || 0;
    document.getElementById('editAddonsTotal').value = data.addons_total || 0;
    document.getElementById('editDiscount').value = data.discount || 0;
    document.getElementById('editTax').value = data.tax || 0;
    document.getElementById('editTotalPrice').value = data.total_price || 0;
    document.getElementById('editValidUntil').value = data.valid_until ? data.valid_until.split('T')[0] : '';
    document.getElementById('editIncluded').value = data.included || '';
    document.getElementById('editExcluded').value = data.excluded || '';
    document.getElementById('editTermsConditions').value = data.terms_conditions || '';
    document.getElementById('editNotes').value = data.notes || '';
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editQuotationForm');

    // Calculate total when prices change
    ['editTourPrice', 'editAddonsTotal', 'editDiscount', 'editTax'].forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            const tourPrice = parseFloat(document.getElementById('editTourPrice').value) || 0;
            const addons = parseFloat(document.getElementById('editAddonsTotal').value) || 0;
            const discount = parseFloat(document.getElementById('editDiscount').value) || 0;
            const tax = parseFloat(document.getElementById('editTax').value) || 0;
            
            const subtotal = tourPrice + addons;
            const afterDiscount = subtotal - discount;
            const total = afterDiscount + tax;
            
            document.getElementById('editTotalPrice').value = total.toFixed(2);
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const quotationId = document.getElementById('editQuotationId').value;
        const formData = new FormData(form);
        
        fetch(`/admin/quotations/${quotationId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editQuotationModal')).hide();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to update quotation'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the quotation');
        });
    });
});
</script>




