@extends('admin.layouts.app')

@section('title', 'Add Testimonial - Lau Paradise Adventures')
@section('description', 'Add a new testimonial')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-add-line me-2"></i>Add Testimonial
                    </h4>
                    <a href="{{ route('admin.homepage.testimonials') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Testimonials
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage.testimonials.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Author Name <span class="text-danger">*</span></label>
                                <input type="text" name="author_name" class="form-control @error('author_name') is-invalid @enderror" value="{{ old('author_name') }}" required>
                                @error('author_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author Title</label>
                                <input type="text" name="author_title" class="form-control @error('author_title') is-invalid @enderror" value="{{ old('author_title') }}" placeholder="e.g., Travel Enthusiast">
                                @error('author_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author Image URL</label>
                                <input type="url" name="author_image_url" class="form-control @error('author_image_url') is-invalid @enderror" value="{{ old('author_image_url') }}" placeholder="https://example.com/avatar.jpg">
                                @error('author_image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rating <span class="text-danger">*</span></label>
                                <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                    <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Related Tour</label>
                                <select name="tour_id" class="form-select @error('tour_id') is-invalid @enderror">
                                    <option value="">No Tour (General)</option>
                                    @foreach($tours ?? [] as $tour)
                                        <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                                            {{ $tour->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tour_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order') }}" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Testimonial Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_approved" id="is_approved" value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_approved">
                                        Approved
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Add Testimonial
                            </button>
                            <a href="{{ route('admin.homepage.testimonials') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



