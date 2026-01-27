@extends('admin.layouts.app')

@section('title', 'Edit Social Media Post')
@section('description', 'Edit social media post')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Edit Social Media Post</h5>
                <a href="{{ route('admin.marketing.social-media') }}" class="btn btn-outline-secondary">
                    <i class="icon-base ri ri-arrow-left-line me-2"></i>Back to List
                </a>
            </div>
            <div class="card-body">
                <form id="formSocialMedia" method="POST" action="{{ route('admin.marketing.social-media.update', $post->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="platform" name="platform" class="form-select" required>
                                    <option value="">Select Platform</option>
                                    <option value="facebook" {{ old('platform', $post->platform) == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="twitter" {{ old('platform', $post->platform) == 'twitter' ? 'selected' : '' }}>Twitter</option>
                                    <option value="instagram" {{ old('platform', $post->platform) == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="linkedin" {{ old('platform', $post->platform) == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                </select>
                                <label for="platform">Platform <span class="text-danger">*</span></label>
                            </div>
                            @error('platform')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="status" name="status" class="form-select" required>
                                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                <label for="status">Status <span class="text-danger">*</span></label>
                            </div>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="content" name="content" rows="6" required>{{ old('content', $post->content) }}</textarea>
                                <label for="content">Content <span class="text-danger">*</span></label>
                            </div>
                            @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="url" class="form-control" id="media_url" name="media_url" value="{{ old('media_url', $post->media_url) }}" />
                                <label for="media_url">Media URL (Image/Video)</label>
                            </div>
                            @error('media_url')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6" id="scheduledAtField" style="display: {{ old('status', $post->status) == 'scheduled' ? 'block' : 'none' }};">
                            <div class="form-floating form-floating-outline">
                                <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at', $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}" />
                                <label for="scheduled_at">Scheduled Date & Time</label>
                            </div>
                            @error('scheduled_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update Post</button>
                                <a href="{{ route('admin.marketing.social-media') }}" class="btn btn-outline-secondary">Cancel</a>
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






