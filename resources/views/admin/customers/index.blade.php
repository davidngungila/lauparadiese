@extends('admin.layouts.app')

@section('title', 'All Customers - CRM Dashboard - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-user-line me-2"></i>Customer Management (CRM Dashboard)
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.customers.groups') }}" class="btn btn-outline-primary">
                            <i class="ri-group-line me-1"></i>Customer Groups
                        </a>
                        <a href="{{ route('admin.customers.feedback') }}" class="btn btn-outline-info">
                            <i class="ri-feedback-line me-1"></i>Feedback
                        </a>
                        <a href="{{ route('admin.customers.messages') }}" class="btn btn-outline-secondary">
                            <i class="ri-message-line me-1"></i>Messages
                        </a>
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-user-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['total'] ?? 0) }}</h5>
                            <small class="text-muted">Total Customers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-check-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['active'] ?? 0) }}</h5>
                            <small class="text-muted">Active Customers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($stats['inactive'] ?? 0) }}</h5>
                            <small class="text-muted">Inactive Customers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ number_format($customers->sum(function($c) { return $c->total_spend ?? 0; })) }}</h5>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.customers.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, Email, Phone, Passport..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Customer Group</label>
                        <select name="group_id" class="form-select">
                            <option value="">All Groups</option>
                            @foreach($groups ?? [] as $group)
                                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Country</label>
                        <select name="country" class="form-select">
                            <option value="">All Countries</option>
                            @foreach($countries ?? [] as $country)
                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date Registered</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="high_value" id="high_value" value="1" {{ request('high_value') ? 'checked' : '' }}>
                            <label class="form-check-label" for="high_value">
                                High Value Only
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card mb-4" id="bulkActionsCard" style="display: none;">
        <div class="card-body">
            <form id="bulkActionForm" method="POST" action="{{ route('admin.customers.bulk-action') }}">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Bulk Action</label>
                        <select name="action" class="form-select" id="bulkActionSelect" required>
                            <option value="">Select Action...</option>
                            <option value="assign_group">Assign to Group</option>
                            <option value="send_email">Send Email</option>
                            <option value="send_sms">Send SMS</option>
                            <option value="export">Export</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="groupSelectContainer" style="display: none;">
                        <label class="form-label">Select Group</label>
                        <select name="group_id" class="form-select">
                            <option value="">Select Group...</option>
                            @foreach($groups ?? [] as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-check-line me-1"></i>Apply to <span id="selectedCount">0</span> Selected
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                            Clear
                        </button>
                    </div>
                </div>
                <input type="hidden" name="customer_ids" id="bulkCustomerIds">
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th>Customer ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Country</th>
                                <th>Customer Group</th>
                                <th>Total Bookings</th>
                                <th>Total Spend</th>
                                <th>Registration Date</th>
                                <th>Status</th>
                                <th width="200" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="customer-checkbox" value="{{ $customer->id }}" onchange="updateBulkActions()">
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary">#{{ $customer->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                @if($customer->avatar)
                                                    <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->full_name }}" class="rounded">
                                                @else
                                                    <span class="avatar-initial rounded bg-label-primary">
                                                        {{ strtoupper(substr($customer->full_name ?? $customer->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $customer->full_name ?? $customer->name }}</strong>
                                                @if($customer->assignedConsultant)
                                                    <br><small class="text-muted">Consultant: {{ $customer->assignedConsultant->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $customer->email }}</td>
                                    <td>
                                        {{ $customer->phone ?? $customer->mobile ?? 'N/A' }}
                                        @if($customer->whatsapp_number)
                                            <br><small class="text-success"><i class="ri-whatsapp-line"></i> {{ $customer->whatsapp_number }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $customer->country ?? 'N/A' }}</td>
                                    <td>
                                        @if($customer->customerGroups->count() > 0)
                                            @foreach($customer->customerGroups as $group)
                                                <span class="badge" style="background-color: {{ $group->color ?? '#3ea572' }}; color: white;">{{ $group->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $customer->bookings->count() ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($customer->total_spend ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $customer->created_at->format('M d, Y') }}</small>
                                        <br><small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $status = $customer->customer_status ?? ($customer->email_verified_at ? 'active' : 'inactive');
                                            $statusClass = match($status) {
                                                'active' => 'success',
                                                'inactive' => 'warning',
                                                'suspended' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-label-{{ $statusClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="View Profile">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-icon btn-outline-info" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ri-more-2-line"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route('admin.bookings.index', ['customer_id' => $customer->id]) }}"><i class="ri-calendar-check-line me-2"></i>View Bookings</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.customers.messages', ['customer_id' => $customer->id]) }}"><i class="ri-message-line me-2"></i>Send Message</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @if($customer->customer_status != 'inactive')
                                                        <li><a class="dropdown-item text-warning" href="#" onclick="deactivateCustomer({{ $customer->id }})"><i class="ri-user-unfollow-line me-2"></i>Deactivate</a></li>
                                                    @else
                                                        <li><a class="dropdown-item text-success" href="#" onclick="activateCustomer({{ $customer->id }})"><i class="ri-user-follow-line me-2"></i>Activate</a></li>
                                                    @endif
                                                    @can('delete customers')
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteCustomer({{ $customer->id }}, '{{ $customer->full_name ?? $customer->name }}')"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-user-line" style="font-size: 64px; color: #ccc;"></i>
                    <h5 class="mt-3">No Customers Found</h5>
                    <p class="text-muted">Get started by adding your first customer.</p>
                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary mt-3">
                        <i class="ri-add-line me-1"></i>Add First Customer
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete customer <strong id="deleteCustomerName"></strong>?</p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCustomerForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedCustomers = [];

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.customer-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        if (selectAll.checked) {
            if (!selectedCustomers.includes(parseInt(cb.value))) {
                selectedCustomers.push(parseInt(cb.value));
            }
        } else {
            selectedCustomers = [];
        }
    });
    updateBulkActions();
}

function updateBulkActions() {
    selectedCustomers = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(cb => parseInt(cb.value));
    const bulkCard = document.getElementById('bulkActionsCard');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedCustomers.length > 0) {
        bulkCard.style.display = 'block';
        selectedCount.textContent = selectedCustomers.length;
        document.getElementById('bulkCustomerIds').value = JSON.stringify(selectedCustomers);
    } else {
        bulkCard.style.display = 'none';
    }
}

function clearSelection() {
    selectedCustomers = [];
    document.querySelectorAll('.customer-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

document.getElementById('bulkActionSelect')?.addEventListener('change', function() {
    const groupContainer = document.getElementById('groupSelectContainer');
    if (this.value === 'assign_group') {
        groupContainer.style.display = 'block';
        groupContainer.querySelector('select').required = true;
    } else {
        groupContainer.style.display = 'none';
        groupContainer.querySelector('select').required = false;
    }
});

document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
    if (selectedCustomers.length === 0) {
        e.preventDefault();
        alert('Please select at least one customer');
        return false;
    }
    
    if (this.querySelector('[name="action"]').value === '') {
        e.preventDefault();
        alert('Please select an action');
        return false;
    }
    
    if (this.querySelector('[name="action"]').value === 'assign_group' && !this.querySelector('[name="group_id"]').value) {
        e.preventDefault();
        alert('Please select a group');
        return false;
    }
});

function deleteCustomer(id, name) {
    document.getElementById('deleteCustomerName').textContent = name;
    document.getElementById('deleteCustomerForm').action = '{{ route("admin.customers.destroy", ":id") }}'.replace(':id', id);
    new bootstrap.Modal(document.getElementById('deleteCustomerModal')).show();
}

function deactivateCustomer(id) {
    if (confirm('Deactivate this customer?')) {
        fetch('{{ route("admin.customers.bulk-action") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                action: 'deactivate',
                customer_ids: [id]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function activateCustomer(id) {
    if (confirm('Activate this customer?')) {
        fetch('{{ route("admin.customers.bulk-action") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                action: 'activate',
                customer_ids: [id]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endpush
