@extends('admin.layouts.app')

@section('title', 'Roles & Permissions - Lau Paradise Adventures')
@section('description', 'Manage roles and permissions')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="ri-shield-user-line me-2"></i>Roles & Permissions</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Roles</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($roles ?? collect()) as $role)
                                <tr>
                                    <td><strong>{{ $role->name }}</strong></td>
                                    <td>{{ $role->permissions->count() }} permissions</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <p class="text-muted mb-0">No roles found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Permissions</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Permission Name</th>
                                    <th>Guard</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($permissions ?? collect())->flatten() as $permission)
                                <tr>
                                    <td><strong>{{ $permission->name }}</strong></td>
                                    <td>{{ $permission->guard_name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <p class="text-muted mb-0">No permissions found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



