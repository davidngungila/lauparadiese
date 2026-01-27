@extends('admin.layouts.app')

@section('title', 'Financial Statements - Lau Paradise Adventures')
@section('description', 'View financial statements')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-file-chart-line me-2"></i>Financial Statements
                    </h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="ri-printer-line me-1"></i>Print Statement
                        </button>
                        <button class="btn btn-success" onclick="exportStatement()">
                            <i class="ri-download-line me-1"></i>Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.finance.statements') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i>Generate Statement
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-arrow-down-line"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0">${{ number_format($revenue ?? 0, 2) }}</h4>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-border-shadow-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ri-arrow-up-line"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0">${{ number_format($expenses ?? 0, 2) }}</h4>
                            <small class="text-muted">Total Expenses</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-border-shadow-{{ ($profit ?? 0) >= 0 ? 'primary' : 'warning' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-{{ ($profit ?? 0) >= 0 ? 'primary' : 'warning' }}">
                                <i class="ri-calculator-line"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0">${{ number_format($profit ?? 0, 2) }}</h4>
                            <small class="text-muted">Net Profit/Loss</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statement -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Financial Statement Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3">Revenue Breakdown</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Total Payments Received</td>
                            <td class="text-end"><strong>${{ number_format($revenue ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Completed Bookings</td>
                            <td class="text-end">-</td>
                        </tr>
                        <tr class="table-success">
                            <td><strong>Total Revenue</strong></td>
                            <td class="text-end"><strong>${{ number_format($revenue ?? 0, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3">Expenses Breakdown</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Operational Expenses</td>
                            <td class="text-end"><strong>${{ number_format($expenses ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Refunds Processed</td>
                            <td class="text-end">-</td>
                        </tr>
                        <tr class="table-danger">
                            <td><strong>Total Expenses</strong></td>
                            <td class="text-end"><strong>${{ number_format($expenses ?? 0, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered">
                        <tr class="table-{{ ($profit ?? 0) >= 0 ? 'success' : 'danger' }}">
                            <td width="80%"><strong>Net Profit / Loss</strong></td>
                            <td class="text-end"><strong>${{ number_format($profit ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Profit Margin</td>
                            <td class="text-end">
                                <strong>
                                    @if($revenue > 0)
                                        {{ number_format(($profit / $revenue) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportStatement() {
    // TODO: Implement PDF export
    alert('PDF export functionality will be implemented here');
}
</script>
@endpush
@endsection
