@extends('admin.layouts.app')

@section('title', 'Roles & Permissions')

@push('styles')
@if(file_exists(public_path('assets/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')))
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@else
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
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
                        <span class="text-heading fw-medium">Total Roles</span>
                        <h4 class="mb-0 mt-2">{{ $roles->count() }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="ri-shield-user-line ri-24px"></i>
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
                        <span class="text-heading fw-medium">Total Users</span>
                        <h4 class="mb-0 mt-2">{{ $roles->sum(function($role) { return $role->users()->count(); }) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
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
                        <span class="text-heading fw-medium">Total Permissions</span>
                        <h4 class="mb-0 mt-2">{{ \Spatie\Permission\Models\Permission::count() }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="ri-lock-line ri-24px"></i>
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
                        <span class="text-heading fw-medium">Active Roles</span>
                        <h4 class="mb-0 mt-2">{{ $roles->count() }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="ri-checkbox-circle-line ri-24px"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-1">Roles List</h4>
<p class="mb-6">A role provided access to predefined menus and features so that depending on assigned role an administrator can have access to what user needs.</p>

<!-- Role cards -->
<div class="row g-6" id="rolesContainer">
  @foreach($roles as $role)
  @php
    $users = $role->users()->get();
    $usersCount = $users->count();
  @endphp
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <p class="mb-0">Total {{ $usersCount }} users</p>
          <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
            @foreach($users->take(3) as $user)
            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar pull-up">
              @if($user->avatar)
                <img class="rounded-circle" src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" />
              @else
                <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($user->name, 0, 1) }}</span>
              @endif
            </li>
            @endforeach
            @if($usersCount > 3)
            <li class="avatar">
              <span class="avatar-initial rounded-circle pull-up text-body" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $usersCount - 3 }} more">+{{ $usersCount - 3 }}</span>
            </li>
            @endif
          </ul>
        </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="role-heading">
              <h5 class="mb-1">{{ $role->name }}</h5>
              <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-label-info">{{ $role->permissions->count() }} Permissions</span>
                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#addRoleModal" class="role-edit-modal" data-role-id="{{ $role->id }}">
                  <small class="text-primary">Edit Role</small>
                </a>
              </div>
            </div>
            <div class="dropdown">
              <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                <i class="ri-more-2-line"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item role-edit-modal" href="javascript:;" data-bs-toggle="modal" data-bs-target="#addRoleModal" data-role-id="{{ $role->id }}"><i class="ri-pencil-line me-2"></i>Edit</a></li>
                <li><a class="dropdown-item" href="javascript:;" onclick="duplicateRole({{ $role->id }})"><i class="ri-file-copy-line me-2"></i>Duplicate</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="javascript:;" onclick="deleteRole({{ $role->id }})"><i class="ri-delete-bin-line me-2"></i>Delete</a></li>
              </ul>
            </div>
          </div>
      </div>
    </div>
  </div>
  @endforeach
  
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card h-100">
      <div class="row h-100">
        <div class="col-5">
          <div class="d-flex align-items-end h-100 justify-content-center">
            <img src="{{ asset('assets/assets/img/illustrations/tree-3.png') }}" class="img-fluid" alt="Image" width="80" />
          </div>
        </div>
        <div class="col-7">
          <div class="card-body text-sm-end text-center ps-sm-0">
            <button data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">Add New Role</button>
            <p class="mb-0">Add role, if it does not exist</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-12 mt-6">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-1">Total users with their roles</h4>
      <p class="mb-0">Find all of your company's administrator accounts and their associate roles.</p>
    </div>
    <a href="{{ route('admin.roles.export') }}" class="btn btn-outline-primary btn-sm">
      <i class="ri-download-line me-1"></i>Export Roles
    </a>
  </div>
</div>

<div class="col-12 mt-4">
  <!-- Role Table -->
  <div class="card">
    <div class="card-datatable table-responsive datatable-roles">
      <table class="datatables-users table">
        <thead>
          <tr>
            <th></th>
            <th></th>
            <th>User</th>
            <th>email</th>
            <th>Role</th>
            <th>Bookings</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <!--/ Role Table -->
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="role-title mb-2 pb-0">Add New Role</h4>
          <p>Set role permissions</p>
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" class="row g-3" onsubmit="return false">
          <input type="hidden" id="roleId" name="role_id" value="">
          <div class="col-12 form-control-validation mb-3">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalRoleName" name="modalRoleName" class="form-control" placeholder="Enter a role name" />
              <label for="modalRoleName">Role Name</label>
            </div>
          </div>
          <div class="col-12">
            <h5>Role Permissions</h5>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody id="permissionsTableBody">
                  <!-- Permissions will be loaded here -->
                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-3">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->
@endsection

@push('scripts')
@if(file_exists(public_path('assets/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')))
<script src="{{ asset('assets/assets/vendor/libs/datatables-bs5/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-responsive-bs5/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.min.js') }}"></script>
@else
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
@endif
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Initialize DataTable for users
    const usersTable = $('.datatables-users').DataTable({
        ajax: {
            url: '{{ route("admin.roles.users.datatable") }}',
            type: 'GET',
        },
        columns: [
            { data: 'id', visible: false },
            { 
                data: 'avatar',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<img src="${row.avatar}" alt="Avatar" class="rounded-circle" width="32" height="32" />`;
                }
            },
            { data: 'name' },
            { data: 'email' },
            { 
                data: 'role',
                render: function(data) {
                    const roles = data.split(', ').filter(r => r !== 'No Role');
                    if (roles.length === 0) return '<span class="badge bg-label-secondary">No Role</span>';
                    return roles.map(role => `<span class="badge bg-label-info me-1">${role}</span>`).join('');
                }
            },
            {
                data: 'bookings_count',
                render: function(data) {
                    return `<span class="badge bg-label-primary">${data || 0}</span>`;
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
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-icon">
                                <i class="ri-more-2-line"></i>
                            </a>
                        </div>
                    `;
                }
            }
        ],
        order: [[2, 'asc']],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/en.json'
        }
    });

    // Load permissions when modal opens
    $('#addRoleModal').on('show.bs.modal', function(e) {
        const button = $(e.relatedTarget);
        const roleId = button.data('role-id');
        
        if (roleId) {
            // Edit mode
            loadRoleForEdit(roleId);
        } else {
            // Add mode
            $('#roleId').val('');
            $('#modalRoleName').val('');
            $('#addRoleForm')[0].reset();
            loadPermissions();
        }
    });

    // Load permissions
    function loadPermissions() {
        return $.ajax({
            url: '{{ route("admin.roles.permissions") }}',
            type: 'GET',
            success: function(response) {
                window.permissions = response.permissions;
                renderPermissionsTable(response.permissions, []);
            }
        });
    }

    // Load role for editing
    function loadRoleForEdit(roleId) {
        $.ajax({
            url: `/admin/roles/${roleId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#roleId').val(response.role.id);
                    $('#modalRoleName').val(response.role.name);
                    const selectedPermissions = response.role.permissions.map(p => p.id);
                    // Load all permissions and render with selected ones
                    $.ajax({
                        url: '{{ route("admin.roles.permissions") }}',
                        type: 'GET',
                        success: function(permsResponse) {
                            renderPermissionsTable(permsResponse.permissions, selectedPermissions);
                        }
                    });
                }
            }
        });
    }

    // Render permissions table
    function renderPermissionsTable(permissions, selectedPermissions) {
        const tbody = $('#permissionsTableBody');
        tbody.empty();
        
        // Group permissions by module
        const grouped = {};
        permissions.forEach(perm => {
            const module = perm.module || 'General';
            if (!grouped[module]) grouped[module] = [];
            grouped[module].push(perm);
        });

        // Add Select All row
        tbody.append(`
            <tr>
                <td class="text-nowrap fw-medium">Administrator Access <i class="ri-information-line" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i></td>
                <td>
                    <div class="d-flex justify-content-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" />
                            <label class="form-check-label" for="selectAll"> Select All </label>
                        </div>
                    </div>
                </td>
            </tr>
        `);

        // Render grouped permissions
        Object.keys(grouped).forEach(module => {
            grouped[module].forEach(perm => {
                const isChecked = selectedPermissions.includes(perm.id);
                tbody.append(`
                    <tr>
                        <td class="text-nowrap fw-medium">${perm.name}</td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="${perm.id}" id="perm_${perm.id}" ${isChecked ? 'checked' : ''} />
                                    <label class="form-check-label" for="perm_${perm.id}"> Assign </label>
                                </div>
                            </div>
                        </td>
                    </tr>
                `);
            });
        });

        // Select All functionality
        $('#selectAll').on('change', function() {
            $('.permission-checkbox').prop('checked', this.checked);
        });
    }

    // Handle form submission
    $('#addRoleForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: $('#modalRoleName').val(),
            permissions: $('input[name="permissions[]"]:checked').map(function() {
                return $(this).val();
            }).get(),
            _token: csrfToken
        };

        const roleId = $('#roleId').val();
        const url = roleId ? `/admin/roles/${roleId}` : '{{ route("admin.roles.store") }}';
        
        if (roleId) {
            formData._method = 'PUT';
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                }
            }
        });
    });

    // Handle role edit click
    $('.role-edit-modal').on('click', function() {
        const roleId = $(this).data('role-id');
        if (roleId) {
            loadRoleForEdit(roleId);
        }
    });

    // Duplicate role
    window.duplicateRole = function(roleId) {
        if (confirm('Duplicate this role?')) {
            $.ajax({
                url: `/admin/roles/${roleId}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#modalRoleName').val(response.role.name + ' (Copy)');
                        $('#roleId').val('');
                        const selectedPermissions = response.role.permissions.map(p => p.id);
                        loadPermissions().then(function() {
                            renderPermissionsTable(window.permissions, selectedPermissions);
                        });
                        $('#addRoleModal').modal('show');
                    }
                }
            });
        }
    };

    // Delete role
    window.deleteRole = function(roleId) {
        if (confirm('Are you sure you want to delete this role?')) {
            $.ajax({
                url: `/admin/roles/${roleId}`,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Error deleting role');
                }
            });
        }
    };
});
</script>
@endpush

