@extends('admin.layouts.app')

@section('title', 'Edit SEO Setting - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit SEO Setting
                    </h4>
                    <a href="{{ route('admin.homepage.seo') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to SEO
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.seo.update', $seo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Page Type <span class="text-danger">*</span></label>
                                <select name="page_type" class="form-select @error('page_type') is-invalid @enderror" required>
                                    <option value="">Select Page Type</option>
                                    @foreach($pageTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('page_type', $seo->page_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('page_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Page Identifier</label>
                                <input type="text" name="page_identifier" class="form-control @error('page_identifier') is-invalid @enderror" value="{{ old('page_identifier', $seo->page_identifier) }}">
                                @error('page_identifier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <h6 class="mb-3"><i class="ri-search-line me-2"></i>Meta Tags</h6>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $seo->meta_title) }}" maxlength="255">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="3" maxlength="500">{{ old('meta_description', $seo->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords', $seo->meta_keywords) }}">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <h6 class="mb-3 mt-4"><i class="ri-facebook-line me-2"></i>Open Graph (Facebook)</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">OG Title</label>
                                <input type="text" name="og_title" class="form-control @error('og_title') is-invalid @enderror" value="{{ old('og_title', $seo->og_title) }}" maxlength="255">
                                @error('og_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OG Type</label>
                                <select name="og_type" class="form-select @error('og_type') is-invalid @enderror">
                                    <option value="">Select Type</option>
                                    <option value="website" {{ old('og_type', $seo->og_type) == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="article" {{ old('og_type', $seo->og_type) == 'article' ? 'selected' : '' }}>Article</option>
                                    <option value="product" {{ old('og_type', $seo->og_type) == 'product' ? 'selected' : '' }}>Product</option>
                                </select>
                                @error('og_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">OG Description</label>
                                <textarea name="og_description" class="form-control @error('og_description') is-invalid @enderror" rows="2" maxlength="500">{{ old('og_description', $seo->og_description) }}</textarea>
                                @error('og_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">OG Image URL</label>
                                <input type="url" name="og_image" class="form-control @error('og_image') is-invalid @enderror" value="{{ old('og_image', $seo->og_image) }}">
                                @error('og_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <h6 class="mb-3 mt-4"><i class="ri-twitter-line me-2"></i>Twitter Card</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Twitter Card Type</label>
                                <select name="twitter_card" class="form-select @error('twitter_card') is-invalid @enderror">
                                    <option value="">Select Type</option>
                                    <option value="summary" {{ old('twitter_card', $seo->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
                                    <option value="summary_large_image" {{ old('twitter_card', $seo->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                                </select>
                                @error('twitter_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Twitter Title</label>
                                <input type="text" name="twitter_title" class="form-control @error('twitter_title') is-invalid @enderror" value="{{ old('twitter_title', $seo->twitter_title) }}" maxlength="255">
                                @error('twitter_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Twitter Description</label>
                                <textarea name="twitter_description" class="form-control @error('twitter_description') is-invalid @enderror" rows="2" maxlength="500">{{ old('twitter_description', $seo->twitter_description) }}</textarea>
                                @error('twitter_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Twitter Image URL</label>
                                <input type="url" name="twitter_image" class="form-control @error('twitter_image') is-invalid @enderror" value="{{ old('twitter_image', $seo->twitter_image) }}">
                                @error('twitter_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <h6 class="mb-3 mt-4"><i class="ri-settings-3-line me-2"></i>Advanced</h6>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Canonical URL</label>
                                <input type="url" name="canonical_url" class="form-control @error('canonical_url') is-invalid @enderror" value="{{ old('canonical_url', $seo->canonical_url) }}">
                                @error('canonical_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Robots</label>
                                <input type="text" name="robots" class="form-control @error('robots') is-invalid @enderror" value="{{ old('robots', $seo->robots) }}">
                                @error('robots')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Custom Head Code</label>
                                <textarea name="custom_head_code" class="form-control @error('custom_head_code') is-invalid @enderror" rows="4">{{ old('custom_head_code', $seo->custom_head_code) }}</textarea>
                                @error('custom_head_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Custom Footer Code</label>
                                <textarea name="custom_footer_code" class="form-control @error('custom_footer_code') is-invalid @enderror" rows="4">{{ old('custom_footer_code', $seo->custom_footer_code) }}</textarea>
                                @error('custom_footer_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $seo->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Update SEO Setting
                                </button>
                                <a href="{{ route('admin.homepage.seo') }}" class="btn btn-label-secondary">
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



