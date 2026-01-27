@extends('admin.layouts.app')

@section('title', 'Banners & Popups')
@section('description', 'Manage website banners and popups')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Banners & Popups</h5>
                <a href="{{ route('admin.marketing.banners.create') }}" class="btn btn-primary">
                    <i class="icon-base ri ri-add-line me-2"></i>Create Banner
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.marketing.banners') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="banner" {{ request('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                                <option value="popup" {{ request('type') == 'popup' ? 'selected' : '' }}>Popup</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="position" class="form-select">
                                <option value="">All Positions</option>
                                <option value="header" {{ request('position') == 'header' ? 'selected' : '' }}>Header</option>
                                <option value="sidebar" {{ request('position') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                <option value="footer" {{ request('position') == 'footer' ? 'selected' : '' }}>Footer</option>
                                <option value="popup" {{ request('position') == 'popup' ? 'selected' : '' }}>Popup</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Display Order</th>
                                <th>Valid Period</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $banner)
                            <tr>
                                <td><strong>{{ $banner->title }}</strong></td>
                                <td>
                                    @if($banner->type == 'banner')
                                        <span class="badge bg-label-primary">Banner</span>
                                    @else
                                        <span class="badge bg-label-info">Popup</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($banner->position) }}</td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $banner->display_order }}</td>
                                <td>
                                    @if($banner->start_date && $banner->end_date)
                                        {{ $banner->start_date->format('M d') }} - {{ $banner->end_date->format('M d, Y') }}
                                    @else
                                        Always
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.marketing.banners.edit', $banner->id) }}" class="btn btn-sm btn-icon">
                                            <i class="icon-base ri ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.marketing.banners.toggle', $banner->id) }}" method="POST" class="toggle-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon {{ $banner->is_active ? 'text-warning' : 'text-success' }}">
                                                <i class="icon-base ri {{ $banner->is_active ? 'ri-eye-off-line' : 'ri-eye-line' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.marketing.banners.destroy', $banner->id) }}" method="POST" class="delete-form">
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
                                        <i class="icon-base ri ri-image-line icon-48px mb-2 d-block"></i>
                                        <p>No banners found</p>
                                        <a href="{{ route('admin.marketing.banners.create') }}" class="btn btn-primary btn-sm">Create Your First Banner</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($banners->hasPages())
                <div class="mt-4">
                    {{ $banners->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
