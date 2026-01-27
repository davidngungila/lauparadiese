@extends('admin.layouts.app')

@section('title', 'Create Promo Code')
@section('description', 'Create a new promotional code or discount')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Create Promo Code</h5>
                <a href="{{ route('admin.marketing.promo-codes') }}" class="btn btn-outline-secondary">
                    <i class="icon-base ri ri-arrow-left-line me-2"></i>Back to List
                </a>
            </div>
            <div class="card-body">
                <form id="formPromoCode" method="POST" action="{{ route('admin.marketing.promo-codes.store') }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required />
                                <label for="code">Promo Code <span class="text-danger">*</span></label>
                            </div>
                            <small class="text-body-secondary">Code will be converted to uppercase</small>
                            @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required />
                                <label for="name">Name <span class="text-danger">*</span></label>
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                <label for="description">Description</label>
                            </div>
                            @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="discount_type" name="discount_type" class="form-select" required>
                                    <option value="">Select Discount Type</option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                                <label for="discount_type">Discount Type <span class="text-danger">*</span></label>
                            </div>
                            @error('discount_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number" step="0.01" min="0" class="form-control" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" required />
                                <label for="discount_value">Discount Value <span class="text-danger">*</span></label>
                            </div>
                            @error('discount_value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number" step="0.01" min="0" class="form-control" id="min_purchase" name="min_purchase" value="{{ old('min_purchase') }}" />
                                <label for="min_purchase">Minimum Purchase</label>
                            </div>
                            @error('min_purchase')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number" step="0.01" min="0" class="form-control" id="max_discount" name="max_discount" value="{{ old('max_discount') }}" />
                                <label for="max_discount">Maximum Discount</label>
                            </div>
                            @error('max_discount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number" min="1" class="form-control" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" />
                                <label for="usage_limit">Usage Limit</label>
                            </div>
                            <small class="text-body-secondary">Leave empty for unlimited</small>
                            @error('usage_limit')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="applicable_to" name="applicable_to" class="form-select" required>
                                    <option value="">Select Applicable To</option>
                                    <option value="all" {{ old('applicable_to') == 'all' ? 'selected' : '' }}>All Products</option>
                                    <option value="tours" {{ old('applicable_to') == 'tours' ? 'selected' : '' }}>Tours Only</option>
                                    <option value="hotels" {{ old('applicable_to') == 'hotels' ? 'selected' : '' }}>Hotels Only</option>
                                    <option value="specific" {{ old('applicable_to') == 'specific' ? 'selected' : '' }}>Specific Items</option>
                                </select>
                                <label for="applicable_to">Applicable To <span class="text-danger">*</span></label>
                            </div>
                            @error('applicable_to')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" class="form-control" id="valid_from" name="valid_from" value="{{ old('valid_from') }}" />
                                <label for="valid_from">Valid From</label>
                            </div>
                            @error('valid_from')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" class="form-control" id="valid_until" name="valid_until" value="{{ old('valid_until') }}" />
                                <label for="valid_until">Valid Until</label>
                            </div>
                            @error('valid_until')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }} />
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Create Promo Code</button>
                                <a href="{{ route('admin.marketing.promo-codes') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
