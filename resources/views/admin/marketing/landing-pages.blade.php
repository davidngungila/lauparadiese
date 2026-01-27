@extends('admin.layouts.app')

@section('title', 'Landing Pages')
@section('description', 'Manage landing pages')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Landing Pages</h5>
                <a href="{{ route('admin.marketing.landing-pages.create') }}" class="btn btn-primary">
                    <i class="icon-base ri ri-add-line me-2"></i>Create Landing Page
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.marketing.landing-pages') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search pages..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.marketing.landing-pages') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Conversions</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                            <tr>
                                <td><strong>{{ $page->title }}</strong></td>
                                <td><code>{{ $page->slug }}</code></td>
                                <td>
                                    @if($page->status == 'published')
                                        <span class="badge bg-label-success">Published</span>
                                    @else
                                        <span class="badge bg-label-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ number_format($page->views ?? 0) }}</td>
                                <td>{{ number_format($page->conversions ?? 0) }}</td>
                                <td>{{ $page->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.marketing.landing-pages.edit', $page->id) }}" class="btn btn-sm btn-icon">
                                            <i class="icon-base ri ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.marketing.landing-pages.destroy', $page->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="icon-base ri ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="icon-base ri ri-file-text-line icon-48px mb-2 d-block"></i>
                                        <p>No landing pages found</p>
                                        <a href="{{ route('admin.marketing.landing-pages.create') }}" class="btn btn-primary btn-sm">Create Your First Landing Page</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($pages->hasPages())
                <div class="mt-4">
                    {{ $pages->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
