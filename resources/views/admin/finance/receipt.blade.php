@extends('admin.layouts.app')

@section('title', 'Generate Receipt - Lau Paradise Adventures')
@section('description', 'View and generate receipt')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="ri-file-text-line me-2"></i>Generate Receipt</h4>
        </div>
        <div class="card-body">
            @if(isset($payment))
                <div class="row">
                    <div class="col-md-6">
                        <h5>Payment Details</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>Payment ID:</th>
                                <td>#{{ $payment->id }}</td>
                            </tr>
                            <tr>
                                <th>Customer:</th>
                                <td>{{ $payment->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Amount:</th>
                                <td><strong>${{ number_format($payment->amount ?? 0, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-label-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="ri-printer-line me-1"></i>Print Receipt
                    </button>
                </div>
            @else
                <p class="text-muted">Payment not found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
