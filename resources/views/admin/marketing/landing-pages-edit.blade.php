@extends('admin.layouts.app')

@section('title', 'Edit Landing Page')
@section('description', 'Edit landing page')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Edit Landing Page</h5>
                <a href="{{ route('admin.marketing.landing-pages') }}" class="btn btn-outline-secondary">
                    <i class="icon-base ri ri-arrow-left-line me-2"></i>Back to List
                </a>
            </div>
            <div class="card-body">
                <form id="formLandingPage" method="POST" action="{{ route('admin.marketing.landing-pages.update', $page->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $page->title) }}" required />
                                <label for="title">Title <span class="text-danger">*</span></label>
                            </div>
                            @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required />
                                <label for="slug">Slug <span class="text-danger">*</span></label>
                            </div>
                            <small class="text-body-secondary">URL-friendly version (e.g., summer-sale-2024)</small>
                            @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="content" name="content" rows="15" required>{{ old('content', $page->content) }}</textarea>
                                <label for="content">Content <span class="text-danger">*</span></label>
                            </div>
                            <small class="text-body-secondary">You can use HTML for formatting</small>
                            @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" />
                                <label for="meta_title">Meta Title (SEO)</label>
                            </div>
                            @error('meta_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="status" name="status" class="form-select" required>
                                    <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                <label for="status">Status <span class="text-danger">*</span></label>
                            </div>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                                <label for="meta_description">Meta Description (SEO)</label>
                            </div>
                            @error('meta_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update Landing Page</button>
                                <a href="{{ route('admin.marketing.landing-pages') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection






