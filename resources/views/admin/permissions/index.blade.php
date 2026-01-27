@extends('admin.layouts.app')

@section('title', 'Permissions')

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
                        <span class="text-heading fw-medium">Total Permissions</span>
                        <h4 class="mb-0 mt-2">{{ $permissions ? $permissions->count() : 0 }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
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
                        <span class="text-heading fw-medium">Assigned Permissions</span>
                        <h4 class="mb-0 mt-2">{{ $permissions ? $permissions->filter(function($p) { return $p->roles->count() > 0; })->count() : 0 }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="ri-checkbox-circle-line ri-24px"></i>
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
                        <span class="text-heading fw-medium">Unassigned</span>
                        <h4 class="mb-0 mt-2">{{ $permissions ? $permissions->filter(function($p) { return $p->roles->count() === 0; })->count() : 0 }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <i class="ri-close-circle-line ri-24px"></i>
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
                        <span class="text-heading fw-medium">Total Roles</span>
                        <h4 class="mb-0 mt-2">{{ \Spatie\Permission\Models\Role::count() }}</h4>
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

<!-- Permission Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Permissions Management</h5>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary btn-sm" onclick="exportPermissions()">
        <i class="ri-download-line me-1"></i> Export
      </button>
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
        <i class="ri-add-line me-1"></i> Add Permission
      </button>
    </div>
  </div>
  <div class="card-datatable">
    <table class="datatables-permissions table">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th>Name</th>
          <th>Assigned To</th>
          <th>Roles Count</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<!--/ Permission Table -->

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-simple">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="mb-2">Add New Permission</h4>
          <p>Permissions you may use and assign to your users.</p>
        </div>
        <form id="addPermissionForm" class="row" onsubmit="return false">
          <div class="col-12 form-control-validation mb-4">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalPermissionName" name="modalPermissionName" class="form-control" placeholder="Permission Name" autofocus />
              <label for="modalPermissionName">Permission Name</label>
            </div>
          </div>
          <div class="col-12 mb-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="corePermission" />
              <label class="form-check-label" for="corePermission"> Set as core permission </label>
            </div>
          </div>
          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-3">Create Permission</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Discard</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add Permission Modal -->

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-simple modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="mb-2">Edit Permission</h4>
          <p>Edit permission as per your requirements.</p>
        </div>
        <div class="alert alert-warning d-flex align-items-start" role="alert">
          <span class="alert-icon me-4 rounded-1 p-1"><i class="ri-alert-line ri-22px"></i></span>
          <div>
            <h5 class="alert-heading mb-1">Warning</h5>
            <p class="mb-0">By editing the permission name, you might break the system permissions functionality. Please ensure you're absolutely certain before proceeding.</p>
          </div>
        </div>
        <form id="editPermissionForm" class="row pt-2 gx-4" onsubmit="return false">
          <input type="hidden" id="editPermissionId" name="permission_id" value="">
          <div class="col-sm-9 form-control-validation mb-4">
            <input type="text" id="editPermissionName" name="editPermissionName" class="form-control form-control-sm" placeholder="Permission Name" />
          </div>
          <div class="col-sm-3 mb-4">
            <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
          </div>
          <div class="col-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="editCorePermission" />
              <label class="form-check-label" for="editCorePermission"> Set as core permission </label>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Permission Modal -->
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
    
    // Check if table exists
    if (!$('.datatables-permissions').length) {
        console.error('Permissions table element not found');
        return;
    }

    // Check if DataTable is loaded
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables library not loaded');
        return;
    }

    // Check if already initialized
    if ($.fn.DataTable.isDataTable('.datatables-permissions')) {
        $('.datatables-permissions').DataTable().destroy();
    }

    // Initialize DataTable
    const permissionsTable = $('.datatables-permissions').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("admin.permissions.index") }}',
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            dataSrc: function(json) {
                console.log('Permissions DataTable Response:', json);
                return json.data || json;
            },
            error: function(xhr, error, thrown) {
                console.error('Permissions DataTable Error:', error, thrown);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);
                alert('Error loading permissions. Please check console for details.');
            }
        },
        columns: [
            { data: 'id', visible: false },
            { 
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<button class="btn btn-sm btn-icon" data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>';
                }
            },
            { data: 'name' },
            { 
                data: 'assigned_to',
                render: function(data) {
                    if (data === 'No roles') {
                        return '<span class="badge bg-label-warning">No roles</span>';
                    }
                    const roles = data.split(', ');
                    return roles.map(role => `<span class="badge bg-label-info me-1">${role}</span>`).join('');
                }
            },
            {
                data: 'roles_count',
                render: function(data) {
                    return `<span class="badge bg-label-primary">${data}</span>`;
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
                            <a href="javascript:;" class="btn btn-sm btn-icon edit-permission" data-permission-id="${row.id}">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-sm btn-icon delete-permission" data-permission-id="${row.id}">
                                <i class="ri-delete-bin-line"></i>
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

    // Handle add permission form
    $('#addPermissionForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: $('#modalPermissionName').val(),
            _token: csrfToken
        };

        $.ajax({
            url: '{{ route("admin.permissions.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#addPermissionModal').modal('hide');
                    permissionsTable.ajax.reload();
                    $('#addPermissionForm')[0].reset();
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                }
            }
        });
    });

    // Handle edit permission
    $(document).on('click', '.edit-permission', function() {
        const permissionId = $(this).data('permission-id');
        
        $.ajax({
            url: `/admin/permissions/${permissionId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#editPermissionId').val(response.permission.id);
                    $('#editPermissionName').val(response.permission.name);
                    $('#editPermissionModal').modal('show');
                }
            }
        });
    });

    // Handle edit permission form
    $('#editPermissionForm').on('submit', function(e) {
        e.preventDefault();
        
        const permissionId = $('#editPermissionId').val();
        const formData = {
            name: $('#editPermissionName').val(),
            _token: csrfToken
        };

        formData._method = 'PUT';
        $.ajax({
            url: `/admin/permissions/${permissionId}`,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editPermissionModal').modal('hide');
                    permissionsTable.ajax.reload();
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                }
            }
        });
    });

    // Handle delete permission
    $(document).on('click', '.delete-permission', function() {
        const permissionId = $(this).data('permission-id');
        
        if (confirm('Are you sure you want to delete this permission?')) {
            $.ajax({
                url: `/admin/permissions/${permissionId}`,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        permissionsTable.ajax.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    }
                }
            });
        }
    });

    // Export permissions
    window.exportPermissions = function() {
        window.location.href = '{{ route("admin.permissions.export") }}';
    };
});
</script>
@endpush

