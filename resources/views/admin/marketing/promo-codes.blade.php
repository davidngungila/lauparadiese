@extends('admin.layouts.app')

@section('title', 'Promo Codes / Discounts')
@section('description', 'Manage promotional codes and discounts')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Promo Codes / Discounts</h5>
                <a href="{{ route('admin.marketing.promo-codes.create') }}" class="btn btn-primary">
                    <i class="icon-base ri ri-add-line me-2"></i>Create Promo Code
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.marketing.promo-codes') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by code or name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.marketing.promo-codes') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Discount</th>
                                <th>Valid From</th>
                                <th>Valid Until</th>
                                <th>Usage</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promoCodes as $promoCode)
                            <tr>
                                <td><strong>{{ $promoCode->code }}</strong></td>
                                <td>{{ $promoCode->name }}</td>
                                <td>
                                    @if($promoCode->discount_type == 'percentage')
                                        {{ number_format($promoCode->discount_value, 0) }}%
                                    @else
                                        {{ number_format($promoCode->discount_value, 2) }} {{ config('app.currency', 'TZS') }}
                                    @endif
                                </td>
                                <td>{{ $promoCode->valid_from ? $promoCode->valid_from->format('M d, Y') : 'No limit' }}</td>
                                <td>{{ $promoCode->valid_until ? $promoCode->valid_until->format('M d, Y') : 'No limit' }}</td>
                                <td>
                                    {{ $promoCode->used_count ?? 0 }} / {{ $promoCode->usage_limit ?? '∞' }}
                                </td>
                                <td>
                                    @if($promoCode->isValid())
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.marketing.promo-codes.edit', $promoCode->id) }}" class="btn btn-sm btn-icon">
                                            <i class="icon-base ri ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.marketing.promo-codes.destroy', $promoCode->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="icon-base ri ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="icon-base ri ri-coupon-line icon-48px mb-2 d-block"></i>
                                        <p>No promo codes found</p>
                                        <a href="{{ route('admin.marketing.promo-codes.create') }}" class="btn btn-primary btn-sm">Create Your First Promo Code</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($promoCodes->hasPages())
                <div class="mt-4">
                    {{ $promoCodes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
