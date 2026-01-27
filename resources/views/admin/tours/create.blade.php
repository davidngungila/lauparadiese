@extends('admin.layouts.app')

@section('title', 'Create Tour - Lau Paradise Adventures')
@section('description', 'Create a new tour')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-add-line me-2"></i>Create New Tour
                    </h4>
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Tours
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tours.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tour Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Destination <span class="text-danger">*</span></label>
                                <select name="destination_id" class="form-select @error('destination_id') is-invalid @enderror" required>
                                    <option value="">Select Destination</option>
                                    @foreach($destinations ?? [] as $destination)
                                        <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Excerpt</label>
                                <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="2" maxlength="500">{{ old('excerpt') }}</textarea>
                                <small class="text-muted">Brief summary (max 500 characters)</small>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror" value="{{ old('duration_days') }}" min="1" required>
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rating</label>
                                <input type="number" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating') }}" step="0.1" min="0" max="5">
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fitness Level</label>
                                <select name="fitness_level" class="form-select @error('fitness_level') is-invalid @enderror">
                                    <option value="">Select Level</option>
                                    <option value="Easy" {{ old('fitness_level') == 'Easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="Moderate" {{ old('fitness_level') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="Challenging" {{ old('fitness_level') == 'Challenging' ? 'selected' : '' }}>Challenging</option>
                                    <option value="Strenuous" {{ old('fitness_level') == 'Strenuous' ? 'selected' : '' }}>Strenuous</option>
                                </select>
                                @error('fitness_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image URL</label>
                                <input type="text" name="image_url" class="form-control @error('image_url') is-invalid @enderror" value="{{ old('image_url') }}" placeholder="images/tours/image.jpg or https://example.com/image.jpg">
                                <small class="text-muted">Enter full URL (http://...) or relative path (images/...)</small>
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured Tour
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Create Tour
                            </button>
                            <a href="{{ route('admin.tours.index') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



