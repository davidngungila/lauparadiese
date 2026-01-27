@extends('admin.layouts.app')

@section('title', 'Sent Quotations - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-send-plane-line me-2"></i>Sent Quotations
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
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Quotation #</th>
                            <th>Customer</th>
                            <th>Tour</th>
                            <th>Departure</th>
                            <th>Travelers</th>
                            <th>Amount</th>
                            <th>Valid Until</th>
                            <th>Sent Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotations as $quotation)
                        <tr>
                            <td><strong>{{ $quotation->quotation_number }}</strong></td>
                            <td>
                                <div>
                                    <strong>{{ $quotation->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $quotation->customer_email }}</small>
                                </div>
                            </td>
                            <td>{{ $quotation->tour ? $quotation->tour->name : $quotation->tour_name }}</td>
                            <td>{{ $quotation->departure_date ? $quotation->departure_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $quotation->travelers }}</td>
                            <td>${{ number_format($quotation->total_price, 2) }}</td>
                            <td>
                                @if($quotation->valid_until)
                                    <span class="{{ $quotation->isExpired() ? 'text-danger' : '' }}">
                                        {{ $quotation->valid_until->format('M d, Y') }}
                                    </span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $quotation->updated_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-icon btn-outline-primary view-quotation" data-id="{{ $quotation->id }}" data-bs-toggle="modal" data-bs-target="#viewQuotationModal">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-success accept-quotation" data-id="{{ $quotation->id }}">
                                        <i class="ri-check-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-outline-info send-quotation" data-id="{{ $quotation->id }}">
                                        <i class="ri-send-plane-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">No sent quotations</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $quotations->links() }}
            </div>
        </div>
    </div>
</div>

@include('admin.quotations.modals.view')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Quotation
    document.querySelectorAll('.view-quotation').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/admin/quotations/${id}`)
                .then(res => res.json())
                .then(data => populateViewModal(data));
        });
    });

    // Accept Quotation
    document.querySelectorAll('.accept-quotation').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to mark this quotation as accepted?')) {
                fetch(`/admin/quotations/${id}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    });

    // Resend Quotation
    document.querySelectorAll('.send-quotation').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to resend this quotation to the customer?')) {
                fetch(`/admin/quotations/${id}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Quotation resent successfully');
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection




