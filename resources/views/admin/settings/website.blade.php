@extends('admin.layouts.app')

@section('title', 'Website Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ri-global-line me-2"></i>Website Settings
                    </h4>
                </div>
                <div class="card-body">
                    <form id="websiteSettingsForm" method="POST" action="{{ route('admin.settings.website.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Site Name <span class="text-danger">*</span></label>
                                <input type="text" name="site_name" class="form-control" 
                                       value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}" required>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Site Description</label>
                                <textarea name="site_description" class="form-control" rows="3">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Site Keywords</label>
                                <input type="text" name="site_keywords" class="form-control" 
                                       value="{{ old('site_keywords', $settings['site_keywords'] ?? '') }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3">Contact Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Contact Email <span class="text-danger">*</span></label>
                                <input type="email" name="contact_email" class="form-control" 
                                       value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" 
                                       value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Contact Address</label>
                                <textarea name="contact_address" class="form-control" rows="2">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3 mt-4">Social Media Links</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Facebook URL</label>
                                <input type="url" name="social_facebook" class="form-control" 
                                       value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" 
                                       placeholder="https://facebook.com/yourpage">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Twitter URL</label>
                                <input type="url" name="social_twitter" class="form-control" 
                                       value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" 
                                       placeholder="https://twitter.com/yourhandle">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Instagram URL</label>
                                <input type="url" name="social_instagram" class="form-control" 
                                       value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" 
                                       placeholder="https://instagram.com/yourhandle">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="social_linkedin" class="form-control" 
                                       value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}" 
                                       placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


