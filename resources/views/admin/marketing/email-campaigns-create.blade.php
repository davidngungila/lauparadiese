@extends('admin.layouts.app')

@section('title', 'Create Email Campaign')
@section('description', 'Create a new email marketing campaign')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Create Email Campaign</h5>
                <a href="{{ route('admin.marketing.email-campaigns') }}" class="btn btn-outline-secondary">
                    <i class="icon-base ri ri-arrow-left-line me-2"></i>Back to List
                </a>
            </div>
            <div class="card-body">
                <form id="formEmailCampaign" method="POST" action="{{ route('admin.marketing.email-campaigns.store') }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required />
                                <label for="name">Campaign Name <span class="text-danger">*</span></label>
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" required />
                                <label for="subject">Email Subject <span class="text-danger">*</span></label>
                            </div>
                            @error('subject')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                                <label for="content">Email Content <span class="text-danger">*</span></label>
                            </div>
                            <small class="text-body-secondary">You can use HTML for formatting</small>
                            @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="recipient_type" name="recipient_type" class="form-select" required>
                                    <option value="">Select Recipient Type</option>
                                    <option value="all" {{ old('recipient_type') == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="customers" {{ old('recipient_type') == 'customers' ? 'selected' : '' }}>Customers Only</option>
                                    <option value="subscribers" {{ old('recipient_type') == 'subscribers' ? 'selected' : '' }}>Subscribers Only</option>
                                    <option value="custom" {{ old('recipient_type') == 'custom' ? 'selected' : '' }}>Custom List</option>
                                </select>
                                <label for="recipient_type">Recipient Type <span class="text-danger">*</span></label>
                            </div>
                            @error('recipient_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="status" name="status" class="form-select" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                </select>
                                <label for="status">Status <span class="text-danger">*</span></label>
                            </div>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6" id="scheduledAtField" style="display: none;">
                            <div class="form-floating form-floating-outline">
                                <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" />
                                <label for="scheduled_at">Scheduled Date & Time</label>
                            </div>
                            @error('scheduled_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Create Campaign</button>
                                <a href="{{ route('admin.marketing.email-campaigns') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusField = document.getElementById('status');
    const scheduledAtField = document.getElementById('scheduledAtField');
    
    if (statusField && scheduledAtField) {
        statusField.addEventListener('change', function() {
            if (this.value === 'scheduled') {
                scheduledAtField.style.display = 'block';
            } else {
                scheduledAtField.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
