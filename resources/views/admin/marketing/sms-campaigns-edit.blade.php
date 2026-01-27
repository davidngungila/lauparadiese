@extends('admin.layouts.app')

@section('title', 'Edit SMS Campaign')
@section('description', 'Edit SMS marketing campaign')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Edit SMS Campaign</h5>
                <a href="{{ route('admin.marketing.sms-campaigns') }}" class="btn btn-outline-secondary">
                    <i class="icon-base ri ri-arrow-left-line me-2"></i>Back to List
                </a>
            </div>
            <div class="card-body">
                <form id="formSmsCampaign" method="POST" action="{{ route('admin.marketing.sms-campaigns.update', $campaign->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $campaign->name) }}" required />
                                <label for="name">Campaign Name <span class="text-danger">*</span></label>
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="message" name="message" rows="4" maxlength="160" required>{{ old('message', $campaign->message) }}</textarea>
                                <label for="message">Message <span class="text-danger">*</span> (Max 160 characters)</label>
                            </div>
                            <small class="text-body-secondary">Characters: <span id="charCount">0</span>/160</small>
                            @error('message')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="recipient_type" name="recipient_type" class="form-select" required>
                                    <option value="">Select Recipient Type</option>
                                    <option value="all" {{ old('recipient_type', $campaign->recipient_type) == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="customers" {{ old('recipient_type', $campaign->recipient_type) == 'customers' ? 'selected' : '' }}>Customers Only</option>
                                    <option value="subscribers" {{ old('recipient_type', $campaign->recipient_type) == 'subscribers' ? 'selected' : '' }}>Subscribers Only</option>
                                    <option value="custom" {{ old('recipient_type', $campaign->recipient_type) == 'custom' ? 'selected' : '' }}>Custom List</option>
                                </select>
                                <label for="recipient_type">Recipient Type <span class="text-danger">*</span></label>
                            </div>
                            @error('recipient_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="status" name="status" class="form-select" required>
                                    <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="scheduled" {{ old('status', $campaign->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="sending" {{ old('status', $campaign->status) == 'sending' ? 'selected' : '' }}>Sending</option>
                                    <option value="sent" {{ old('status', $campaign->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="cancelled" {{ old('status', $campaign->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <label for="status">Status <span class="text-danger">*</span></label>
                            </div>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6" id="scheduledAtField" style="display: {{ old('status', $campaign->status) == 'scheduled' ? 'block' : 'none' }};">
                            <div class="form-floating form-floating-outline">
                                <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at', $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d\TH:i') : '') }}" />
                                <label for="scheduled_at">Scheduled Date & Time</label>
                            </div>
                            @error('scheduled_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update Campaign</button>
                                <a href="{{ route('admin.marketing.sms-campaigns') }}" class="btn btn-outline-secondary">Cancel</a>
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
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const statusField = document.getElementById('status');
    const scheduledAtField = document.getElementById('scheduledAtField');
    
    // Character counter
    if (messageField && charCount) {
        charCount.textContent = messageField.value.length;
        messageField.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            if (this.value.length > 160) {
                charCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-danger');
            }
        });
    }
    
    // Show/hide scheduled date based on status
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






