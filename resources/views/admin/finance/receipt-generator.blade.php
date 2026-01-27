@extends('admin.layouts.app')

@section('title', 'Generate Receipt - Lau Paradise Adventures')
@section('description', 'Advanced receipt generation system')

@push('styles')
<style>
    .receipt-generator {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .receipt-preview {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .receipt-header {
        border-bottom: 3px solid #667eea;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .receipt-logo {
        width: 120px;
        height: 120px;
        background: #667eea;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        margin: 0 auto 1rem;
    }
    
    .receipt-body {
        margin-bottom: 2rem;
    }
    
    .receipt-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .receipt-item:last-child {
        border-bottom: none;
    }
    
    .receipt-total {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1.5rem;
    }
    
    .receipt-footer {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px dashed #e5e7eb;
        text-align: center;
        color: #6b7280;
    }
    
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .form-section-title {
        font-weight: 600;
        color: #667eea;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .item-row {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .btn-add-item {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-add-item:hover {
        background: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .template-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .template-card {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .template-card:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .template-card.active {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .template-icon {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 1rem;
    }
    
    .qr-code {
        width: 150px;
        height: 150px;
        background: #f3f4f6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 1rem auto;
        border: 2px dashed #d1d5db;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .receipt-preview {
            box-shadow: none;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="receipt-generator">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2 text-white">
                    <i class="ri-receipt-line me-2"></i>Generate Receipt
                </h2>
                <p class="text-white-50 mb-0">Create professional receipts for payments and transactions</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-light me-2 no-print" onclick="window.print()">
                    <i class="ri-printer-line me-2"></i>Print Receipt
                </button>
                <button class="btn btn-light no-print" onclick="downloadReceipt()">
                    <i class="ri-download-2-line me-2"></i>Download PDF
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Receipt Form -->
        <div class="col-lg-5 mb-4">
            <!-- Template Selection -->
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="ri-layout-line me-2"></i>Select Template
                </h5>
                <div class="template-selector">
                    <div class="template-card active" data-template="standard">
                        <div class="template-icon">
                            <i class="ri-file-text-line"></i>
                        </div>
                        <h6>Standard</h6>
                    </div>
                    <div class="template-card" data-template="modern">
                        <div class="template-icon">
                            <i class="ri-file-edit-line"></i>
                        </div>
                        <h6>Modern</h6>
                    </div>
                    <div class="template-card" data-template="minimal">
                        <div class="template-icon">
                            <i class="ri-file-list-line"></i>
                        </div>
                        <h6>Minimal</h6>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="ri-building-line me-2"></i>Company Information
                </h5>
                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="companyName" value="Lau Paradise Adventures" onchange="updateReceipt()">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" id="companyAddress" rows="2" onchange="updateReceipt()">123 Adventure Street, Arusha, Tanzania</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="companyPhone" value="+255 123 456 789" onchange="updateReceipt()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="companyEmail" value="info@lauparadise.com" onchange="updateReceipt()">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tax ID / Registration</label>
                    <input type="text" class="form-control" id="companyTaxId" value="TAX-123456789" onchange="updateReceipt()">
                </div>
            </div>

            <!-- Customer Information -->
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="ri-user-line me-2"></i>Customer Information
                </h5>
                <div class="mb-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="customerName" value="John Doe" onchange="updateReceipt()">
                </div>
                <div class="mb-3">
                    <label class="form-label">Customer Address</label>
                    <textarea class="form-control" id="customerAddress" rows="2" onchange="updateReceipt()">456 Customer Street, City, Country</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="customerPhone" value="+255 987 654 321" onchange="updateReceipt()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="customerEmail" value="customer@example.com" onchange="updateReceipt()">
                    </div>
                </div>
            </div>

            <!-- Receipt Details -->
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="ri-file-info-line me-2"></i>Receipt Details
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Receipt Number</label>
                        <input type="text" class="form-control" id="receiptNumber" value="REC-{{ date('Ymd') }}-001" onchange="updateReceipt()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="receiptDate" value="{{ date('Y-m-d') }}" onchange="updateReceipt()">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod" onchange="updateReceipt()">
                        <option value="Cash">Cash</option>
                        <option value="Credit Card" selected>Credit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Check">Check</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Reference Number</label>
                    <input type="text" class="form-control" id="referenceNumber" value="REF-{{ strtoupper(Str::random(8)) }}" onchange="updateReceipt()">
                </div>
            </div>

            <!-- Items -->
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="ri-shopping-cart-line me-2"></i>Items
                </h5>
                <div id="itemsContainer">
                    <div class="item-row">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Item name" value="Safari Package" onchange="updateReceipt()">
                            </div>
                            <div class="col-md-3 mb-2">
                                <input type="number" class="form-control form-control-sm" placeholder="Qty" value="1" min="1" onchange="updateReceipt()">
                            </div>
                            <div class="col-md-3 mb-2">
                                <input type="number" class="form-control form-control-sm" placeholder="Price" value="1500" step="0.01" onchange="updateReceipt()">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-add-item w-100" onclick="addItem()">
                    <i class="ri-add-line me-2"></i>Add Item
                </button>
            </div>

            <!-- Additional Notes -->
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="ri-file-text-line me-2"></i>Additional Notes
                </h5>
                <textarea class="form-control" id="notes" rows="3" placeholder="Additional notes or terms..." onchange="updateReceipt()">Thank you for your business!</textarea>
            </div>
        </div>

        <!-- Receipt Preview -->
        <div class="col-lg-7 mb-4">
            <div class="receipt-preview" id="receiptPreview">
                <div class="receipt-header text-center">
                    <div class="receipt-logo">
                        <i class="ri-plane-line"></i>
                    </div>
                    <h3 id="previewCompanyName">Lau Paradise Adventures</h3>
                    <p class="text-muted mb-1" id="previewCompanyAddress">123 Adventure Street, Arusha, Tanzania</p>
                    <p class="text-muted mb-0">
                        <span id="previewCompanyPhone">+255 123 456 789</span> | 
                        <span id="previewCompanyEmail">info@lauparadise.com</span>
                    </p>
                    <p class="text-muted mb-0">Tax ID: <span id="previewCompanyTaxId">TAX-123456789</span></p>
                </div>

                <div class="receipt-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">RECEIPT TO:</h6>
                            <p class="mb-1 fw-semibold" id="previewCustomerName">John Doe</p>
                            <p class="text-muted small mb-1" id="previewCustomerAddress">456 Customer Street, City, Country</p>
                            <p class="text-muted small mb-0">
                                <span id="previewCustomerPhone">+255 987 654 321</span> | 
                                <span id="previewCustomerEmail">customer@example.com</span>
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6 class="text-muted mb-2">RECEIPT DETAILS:</h6>
                            <p class="mb-1"><strong>Receipt #:</strong> <span id="previewReceiptNumber">REC-20250128-001</span></p>
                            <p class="mb-1"><strong>Date:</strong> <span id="previewReceiptDate">{{ date('M d, Y') }}</span></p>
                            <p class="mb-1"><strong>Payment Method:</strong> <span id="previewPaymentMethod">Credit Card</span></p>
                            <p class="mb-0"><strong>Reference:</strong> <span id="previewReferenceNumber">REF-XXXXXXXX</span></p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-center" style="width: 80px;">Qty</th>
                                    <th class="text-end" style="width: 120px;">Price</th>
                                    <th class="text-end" style="width: 120px;">Total</th>
                                </tr>
                            </thead>
                            <tbody id="previewItems">
                                <tr>
                                    <td>Safari Package</td>
                                    <td class="text-center">1</td>
                                    <td class="text-end">$1,500.00</td>
                                    <td class="text-end"><strong>$1,500.00</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="receipt-total">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="previewSubtotal">$1,500.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (18%):</span>
                            <strong id="previewTax">$270.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <strong id="previewDiscount">$0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top border-2">
                            <span class="fs-5 fw-bold">Total:</span>
                            <span class="fs-5 fw-bold text-primary" id="previewTotal">$1,770.00</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted small mb-0" id="previewNotes">Thank you for your business!</p>
                    </div>
                </div>

                <div class="receipt-footer">
                    <div class="qr-code">
                        <i class="ri-qr-code-line" style="font-size: 4rem; color: #9ca3af;"></i>
                    </div>
                    <p class="mb-0 small">This is a computer-generated receipt. No signature required.</p>
                    <p class="mb-0 small">Generated on {{ date('F d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemCount = 1;

function updateReceipt() {
    // Update company info
    document.getElementById('previewCompanyName').textContent = document.getElementById('companyName').value;
    document.getElementById('previewCompanyAddress').textContent = document.getElementById('companyAddress').value;
    document.getElementById('previewCompanyPhone').textContent = document.getElementById('companyPhone').value;
    document.getElementById('previewCompanyEmail').textContent = document.getElementById('companyEmail').value;
    document.getElementById('previewCompanyTaxId').textContent = document.getElementById('companyTaxId').value;

    // Update customer info
    document.getElementById('previewCustomerName').textContent = document.getElementById('customerName').value;
    document.getElementById('previewCustomerAddress').textContent = document.getElementById('customerAddress').value;
    document.getElementById('previewCustomerPhone').textContent = document.getElementById('customerPhone').value;
    document.getElementById('previewCustomerEmail').textContent = document.getElementById('customerEmail').value;

    // Update receipt details
    document.getElementById('previewReceiptNumber').textContent = document.getElementById('receiptNumber').value;
    const date = new Date(document.getElementById('receiptDate').value);
    document.getElementById('previewReceiptDate').textContent = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    document.getElementById('previewPaymentMethod').textContent = document.getElementById('paymentMethod').value;
    document.getElementById('previewReferenceNumber').textContent = document.getElementById('referenceNumber').value;

    // Update items
    const itemsContainer = document.getElementById('itemsContainer');
    const previewItems = document.getElementById('previewItems');
    previewItems.innerHTML = '';
    
    let subtotal = 0;
    const itemRows = itemsContainer.querySelectorAll('.item-row');
    
    itemRows.forEach(row => {
        const inputs = row.querySelectorAll('input');
        if (inputs.length >= 3) {
            const name = inputs[0].value || 'Item';
            const qty = parseFloat(inputs[1].value) || 0;
            const price = parseFloat(inputs[2].value) || 0;
            const total = qty * price;
            subtotal += total;
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${name}</td>
                <td class="text-center">${qty}</td>
                <td class="text-end">$${price.toFixed(2).toLocaleString()}</td>
                <td class="text-end"><strong>$${total.toFixed(2).toLocaleString()}</strong></td>
            `;
            previewItems.appendChild(tr);
        }
    });

    // Calculate totals
    const tax = subtotal * 0.18;
    const discount = 0;
    const total = subtotal + tax - discount;

    document.getElementById('previewSubtotal').textContent = '$' + subtotal.toFixed(2).toLocaleString();
    document.getElementById('previewTax').textContent = '$' + tax.toFixed(2).toLocaleString();
    document.getElementById('previewDiscount').textContent = '$' + discount.toFixed(2).toLocaleString();
    document.getElementById('previewTotal').textContent = '$' + total.toFixed(2).toLocaleString();

    // Update notes
    document.getElementById('previewNotes').textContent = document.getElementById('notes').value || 'Thank you for your business!';
}

function addItem() {
    itemCount++;
    const itemsContainer = document.getElementById('itemsContainer');
    const newItem = document.createElement('div');
    newItem.className = 'item-row';
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" class="form-control form-control-sm" placeholder="Item name" onchange="updateReceipt()">
            </div>
            <div class="col-md-3 mb-2">
                <input type="number" class="form-control form-control-sm" placeholder="Qty" value="1" min="1" onchange="updateReceipt()">
            </div>
            <div class="col-md-3 mb-2">
                <input type="number" class="form-control form-control-sm" placeholder="Price" value="0" step="0.01" onchange="updateReceipt()">
            </div>
        </div>
        <button class="btn btn-sm btn-outline-danger mt-2" onclick="removeItem(this)">
            <i class="ri-delete-bin-line"></i> Remove
        </button>
    `;
    itemsContainer.appendChild(newItem);
}

function removeItem(btn) {
    btn.closest('.item-row').remove();
    updateReceipt();
}

function downloadReceipt() {
    // Implement PDF download functionality
    window.print();
}

// Template selection
document.querySelectorAll('.template-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.template-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        // Implement template switching logic
    });
});

// Initialize
updateReceipt();
</script>
@endpush





