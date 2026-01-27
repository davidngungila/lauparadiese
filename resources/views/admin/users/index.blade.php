@extends('admin.layouts.app')

@section('title', 'Users Management')

@push('styles')
@if(file_exists(public_path('assets/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')))
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
@else
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />
@endif
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-heading fw-medium">Total Users</span>
                        <h4 class="mb-0 mt-2">{{ $stats['total'] ?? 0 }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="ri-user-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-heading fw-medium">Active Users</span>
                        <h4 class="mb-0 mt-2">{{ $stats['active'] ?? 0 }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="ri-user-check-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-heading fw-medium">Inactive/Dormant</span>
                        <h4 class="mb-0 mt-2">{{ $stats['inactive'] ?? 0 }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="ri-user-unfollow-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-heading fw-medium">Roles</span>
                        <h4 class="mb-0 mt-2">{{ $roles->count() }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="ri-shield-user-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table with Tabs -->
<div class="card">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Users Management</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.export') }}" class="btn btn-outline-primary btn-sm">
                    <i class="ri-download-line me-1"></i>Export
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i>Add User
                </a>
            </div>
        </div>
        <!-- Tabs -->
        <ul class="nav nav-tabs nav-tabs-borderless" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" data-status="all" data-bs-toggle="tab" data-bs-target="#all-users" role="tab" aria-selected="true">
                    <i class="ri-user-line me-1"></i>All Users
                    <span class="badge bg-label-primary ms-2">{{ $stats['total'] ?? 0 }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-status="active" data-bs-toggle="tab" data-bs-target="#active-users" role="tab" aria-selected="false">
                    <i class="ri-user-check-line me-1"></i>Active Users
                    <span class="badge bg-label-success ms-2">{{ $stats['active'] ?? 0 }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-status="inactive" data-bs-toggle="tab" data-bs-target="#inactive-users" role="tab" aria-selected="false">
                    <i class="ri-user-unfollow-line me-1"></i>Inactive/Dormant
                    <span class="badge bg-label-warning ms-2">{{ $stats['inactive'] ?? 0 }}</span>
                </button>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="all-users" role="tabpanel">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top" data-status="all">
                    <thead>
                        <tr>
                            <th></th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Roles</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="active-users" role="tabpanel">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top" data-status="active">
                    <thead>
                        <tr>
                            <th></th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Roles</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="inactive-users" role="tabpanel">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top" data-status="inactive">
                    <thead>
                        <tr>
                            <th></th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Roles</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkActionForm" method="POST" action="{{ route('admin.users.bulk-action') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="action" id="bulkActionType">
                    <div class="mb-3">
                        <label class="form-label">Action</label>
                        <select class="form-select" id="bulkActionSelect" required>
                            <option value="">Select Action</option>
                            <option value="activate">Activate Users</option>
                            <option value="deactivate">Deactivate Users</option>
                            <option value="assign_role">Assign Role</option>
                            <option value="remove_role">Remove Role</option>
                            <option value="delete">Delete Users</option>
                        </select>
                    </div>
                    <div class="mb-3" id="roleSelectContainer" style="display: none;">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" id="bulkRoleSelect">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <strong id="selectedCount">0</strong> user(s) selected
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Action</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(file_exists(public_path('assets/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')))
<script src="{{ asset('assets/assets/vendor/libs/datatables-bs5/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-responsive-bs5/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-buttons-bs5/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
@else
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
@endif
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let selectedUsers = [];
    let tables = {};

    // Initialize DataTable for each tab
    function initDataTable(status) {
        const tableId = `.datatables-users[data-status="${status}"]`;
        
        if (tables[status]) {
            return tables[status];
        }

        // Check if table element exists
        if (!$(tableId).length) {
            console.error('Table element not found:', tableId);
            return null;
        }

        // Check if DataTable is loaded
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables library not loaded');
            return null;
        }

        // Check if table is already initialized
        if ($.fn.DataTable.isDataTable(tableId)) {
            tables[status] = $(tableId).DataTable();
            return tables[status];
        }

        const table = $(tableId).DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route("admin.users.index") }}',
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: function(d) {
                    const filterRole = $(`.filter-role[data-status="${status}"]`).val();
                    d.role = filterRole || '';
                    d.status = status === 'all' ? '' : status;
                    d.draw = 1; // Add draw parameter
                },
                dataSrc: function(json) {
                    console.log('DataTable Response:', json);
                    // Handle both formats
                    if (json.data) {
                        return json.data;
                    }
                    return json;
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTable Error:', error, thrown);
                    console.error('Status:', xhr.status);
                    console.error('Response:', xhr.responseText);
                    alert('Error loading users. Please check console for details.');
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="form-check-input user-checkbox" value="${row.id}">`;
                    }
                },
                {
                    data: 'name',
                    render: function(data, type, row) {
                        const avatar = row.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(data)}&background=random`;
                        return `
                            <div class="d-flex align-items-center">
                                <div class="avatar me-2">
                                    <img src="${avatar}" alt="${data}" class="rounded-circle" width="32" height="32">
                                </div>
                                <div>
                                    <strong>${data}</strong>
                                </div>
                            </div>
                        `;
                    }
                },
                { data: 'email' },
                { data: 'phone' },
                {
                    data: 'roles',
                    render: function(data, type, row) {
                        const roles = data.split(', ').filter(r => r !== 'No Role');
                        if (roles.length === 0) return '<span class="badge bg-label-secondary">No Role</span>';
                        return roles.map(role => `<span class="badge bg-label-info me-1">${role}</span>`).join('');
                    }
                },
                {
                    data: 'bookings_count',
                    render: function(data) {
                        return `<span class="badge bg-label-primary">${data}</span>`;
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        const badge = data === 'active' ? 'success' : 'warning';
                        const text = data === 'active' ? 'Active' : 'Inactive';
                        return `<span class="badge bg-label-${badge}">${text}</span>`;
                    }
                },
                { data: 'created_at' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-inline-block">
                                <a href="/admin/users/${row.id}/edit" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-user" data-id="${row.id}" data-name="${row.name}" title="Delete">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
        order: [[7, 'desc']],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>><"row"<"col-sm-12"B>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        buttons: [
            {
                text: '<i class="ri-checkbox-multiple-line me-1"></i> Select All',
                className: 'btn btn-sm btn-outline-primary',
                action: function() {
                    $('.user-checkbox').prop('checked', true);
                    updateSelectedUsers();
                }
            },
            {
                text: '<i class="ri-close-line me-1"></i> Deselect All',
                className: 'btn btn-sm btn-outline-secondary',
                action: function() {
                    $('.user-checkbox').prop('checked', false);
                    selectedUsers = [];
                    updateSelectedUsers();
                }
            },
            {
                text: '<i class="ri-settings-3-line me-1"></i> Bulk Actions',
                className: 'btn btn-sm btn-primary',
                action: function() {
                    if (selectedUsers.length === 0) {
                        alert('Please select at least one user');
                        return;
                    }
                    $('#bulkActionModal').modal('show');
                }
            }
        ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/en.json'
            }
        });

        tables[status] = table;
        return table;
    }

    // Filter controls - add to each tab BEFORE initializing tables
    $('.tab-pane').each(function() {
        const status = $(this).find('.datatables-users').data('status');
        $(this).find('.card-datatable').before(`
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Role</label>
                        <select class="form-select filter-role" data-status="${status}">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control filter-search" data-status="${status}" placeholder="Search users...">
                    </div>
                </div>
            </div>
        `);
    });

    // Initialize only the active tab initially - with a small delay to ensure DOM is ready
    setTimeout(function() {
        initDataTable('all');
    }, 100);

    // Tab change handler
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        const status = $(e.target).data('status');
        if (!tables[status]) {
            setTimeout(function() {
                initDataTable(status);
            }, 100);
        }
    });

    // Filter handlers
    $(document).on('change', '.filter-role', function() {
        const status = $(this).data('status');
        if (tables[status]) {
            tables[status].ajax.reload();
        }
    });

    $(document).on('keyup', '.filter-search', function() {
        const status = $(this).data('status');
        if (tables[status]) {
            tables[status].search($(this).val()).draw();
        }
    });

    // Checkbox handling
    $(document).on('change', '.user-checkbox', function() {
        updateSelectedUsers();
    });

    function updateSelectedUsers() {
        selectedUsers = [];
        $('.user-checkbox:checked').each(function() {
            selectedUsers.push($(this).val());
        });
        $('#selectedCount').text(selectedUsers.length);
        
        // Add hidden inputs for selected users
        $('#bulkActionForm input[name="user_ids[]"]').remove();
        selectedUsers.forEach(id => {
            $('#bulkActionForm').append(`<input type="hidden" name="user_ids[]" value="${id}">`);
        });
    }

    // Update all tables when needed
    function reloadAllTables() {
        Object.keys(tables).forEach(status => {
            if (tables[status]) {
                tables[status].ajax.reload();
            }
        });
    }

    // Bulk action form
    $('#bulkActionSelect').on('change', function() {
        const action = $(this).val();
        $('#bulkActionType').val(action);
        
        if (action === 'assign_role' || action === 'remove_role') {
            $('#roleSelectContainer').show();
            $('#bulkRoleSelect').prop('required', true);
        } else {
            $('#roleSelectContainer').hide();
            $('#bulkRoleSelect').prop('required', false);
        }
    });

    // Delete user
    $(document).on('click', '.delete-user', function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        
        if (confirm(`Are you sure you want to delete user "${userName}"?`)) {
            $.ajax({
                url: `/admin/users/${userId}`,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function() {
                    reloadAllTables();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Error deleting user');
                }
            });
        }
    });
});
</script>
@endpush
