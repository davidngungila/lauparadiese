@extends('admin.layouts.app')

@section('title', 'Permissions - Lau Paradise Adventures')
@section('description', 'Manage permissions')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="ri-lock-line me-2"></i>Permissions</h4>
        </div>
        <div class="card-body">
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
@endsection



