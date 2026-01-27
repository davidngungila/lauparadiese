@extends('admin.layouts.app')

@section('title', 'SEO Management - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-search-line me-2"></i>SEO Settings Management
                    </h4>
                    <a href="{{ route('admin.homepage.seo.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Create New SEO Setting
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.homepage.seo') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by title, description, or identifier..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Page Type</label>
                            <select name="page_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($pageTypes as $key => $label)
                                    <option value="{{ $key }}" {{ request('page_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.homepage.seo') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Settings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($seoSettings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Page Type</th>
                                        <th>Page Identifier</th>
                                        <th>Meta Title</th>
                                        <th>Meta Description</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                        <th width="150" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seoSettings as $seo)
                                        <tr>
                                            <td>
                                                <span class="badge bg-label-primary">{{ $pageTypes[$seo->page_type] ?? $seo->page_type }}</span>
                                            </td>
                                            <td>
                                                @if($seo->page_identifier)
                                                    <code>{{ Str::limit($seo->page_identifier, 30) }}</code>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($seo->meta_title)
                                                    <div class="fw-medium">{{ Str::limit($seo->meta_title, 40) }}</div>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($seo->meta_description)
                                                    <small>{{ Str::limit($seo->meta_description, 60) }}</small>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-label-{{ $seo->is_active ? 'success' : 'danger' }}">
                                                    {{ $seo->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $seo->updated_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('admin.homepage.seo.edit', $seo->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <form action="{{ route('admin.homepage.seo.destroy', $seo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this SEO setting?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $seoSettings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-search-line" style="font-size: 64px; color: #ccc;"></i>
                            <h5 class="mt-3">No SEO Settings Found</h5>
                            <p class="text-muted">Get started by creating your first SEO setting for a page.</p>
                            <a href="{{ route('admin.homepage.seo.create') }}" class="btn btn-primary mt-3">
                                <i class="ri-add-line me-1"></i>Create First SEO Setting
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="display-6 text-primary">{{ $seoSettings->where('is_active', true)->count() }}</div>
                    <div class="text-muted">Active Settings</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="display-6 text-info">{{ $seoSettings->where('page_type', 'homepage')->count() }}</div>
                    <div class="text-muted">Homepage</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="display-6 text-success">{{ $seoSettings->where('page_type', 'tours')->count() }}</div>
                    <div class="text-muted">Tours Pages</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="display-6 text-warning">{{ $seoSettings->whereNotNull('meta_title')->count() }}</div>
                    <div class="text-muted">With Meta Title</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
