@extends('admin.layouts.app')

@section('title', 'Edit Policy - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Policy
                    </h4>
                    <a href="{{ route('admin.homepage.policies') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Policies
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.policies.update', $policy->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $policy->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Policy Type</label>
                                <select name="policy_type" class="form-select @error('policy_type') is-invalid @enderror">
                                    <option value="">Select Type</option>
                                    <option value="terms" {{ old('policy_type', $policy->policy_type) == 'terms' ? 'selected' : '' }}>Terms & Conditions</option>
                                    <option value="privacy" {{ old('policy_type', $policy->policy_type) == 'privacy' ? 'selected' : '' }}>Privacy Policy</option>
                                    <option value="refund" {{ old('policy_type', $policy->policy_type) == 'refund' ? 'selected' : '' }}>Refund Policy</option>
                                    <option value="cancellation" {{ old('policy_type', $policy->policy_type) == 'cancellation' ? 'selected' : '' }}>Cancellation Policy</option>
                                    <option value="booking" {{ old('policy_type', $policy->policy_type) == 'booking' ? 'selected' : '' }}>Booking Policy</option>
                                    <option value="other" {{ old('policy_type', $policy->policy_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('policy_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $policy->slug) }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2">{{ old('short_description', $policy->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="12" required>{{ old('content', $policy->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $policy->display_order) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Version</label>
                                <input type="text" name="version" class="form-control @error('version') is-invalid @enderror" value="{{ old('version', $policy->version) }}">
                                @error('version')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Effective Date</label>
                                <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror" value="{{ old('effective_date', $policy->effective_date ? $policy->effective_date->format('Y-m-d') : '') }}">
                                @error('effective_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date', $policy->expiry_date ? $policy->expiry_date->format('Y-m-d') : '') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $policy->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $policy->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="show_in_footer" id="show_in_footer" value="1" {{ old('show_in_footer', $policy->show_in_footer) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_in_footer">
                                        Show in Footer
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Update Policy
                                </button>
                                <a href="{{ route('admin.homepage.policies') }}" class="btn btn-label-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



