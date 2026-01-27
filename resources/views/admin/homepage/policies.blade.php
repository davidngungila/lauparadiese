@extends('admin.layouts.app')

@section('title', 'Company Policies - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-file-text-line me-2"></i>Company Policies Management
                    </h4>
                    <a href="{{ route('admin.homepage.policies.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Create New Policy
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
                    <form method="GET" action="{{ route('admin.homepage.policies') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by title or content..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Policy Type</label>
                            <select name="policy_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($policyTypes as $type)
                                    <option value="{{ $type }}" {{ request('policy_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.homepage.policies') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Policies Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($policies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Short Description</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Footer</th>
                                        <th>Effective Date</th>
                                        <th>Version</th>
                                        <th>Display Order</th>
                                        <th width="150" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($policies as $policy)
                                        <tr>
                                            <td>
                                                <div class="fw-medium">{{ Str::limit($policy->title, 40) }}</div>
                                                @if($policy->slug)
                                                    <small class="text-muted">/{{ $policy->slug }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($policy->policy_type)
                                                    <span class="badge bg-label-info">{{ $policy->policy_type }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($policy->short_description)
                                                    {{ Str::limit($policy->short_description, 50) }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-label-{{ $policy->is_active ? 'success' : 'danger' }}">
                                                    {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($policy->is_featured)
                                                    <span class="badge bg-label-primary">
                                                        <i class="ri-star-fill me-1"></i>Featured
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($policy->show_in_footer)
                                                    <span class="badge bg-label-success">
                                                        <i class="ri-check-line me-1"></i>Yes
                                                    </span>
                                                @else
                                                    <span class="text-muted">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($policy->effective_date)
                                                    {{ \Carbon\Carbon::parse($policy->effective_date)->format('M d, Y') }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($policy->version)
                                                    <span class="badge bg-label-secondary">{{ $policy->version }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-label-primary">{{ $policy->display_order }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('admin.homepage.policies.edit', $policy->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <form action="{{ route('admin.homepage.policies.destroy', $policy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this policy?');">
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
                            {{ $policies->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-file-text-line" style="font-size: 64px; color: #ccc;"></i>
                            <h5 class="mt-3">No Policies Found</h5>
                            <p class="text-muted">Get started by creating your first company policy.</p>
                            <a href="{{ route('admin.homepage.policies.create') }}" class="btn btn-primary mt-3">
                                <i class="ri-add-line me-1"></i>Create First Policy
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
