@extends('admin.layouts.app')

@section('title', 'Vehicle Details - Lau Paradise Adventures')
@section('description', 'View vehicle details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-eye-line me-2"></i>Vehicle Details: {{ $vehicle->make }} {{ $vehicle->model }}
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-info">
                            <i class="ri-edit-line me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-label-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Vehicles
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <h5 class="mb-3">Vehicle Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">Make & Model:</th>
                                    <td><strong>{{ $vehicle->make }} {{ $vehicle->model }}</strong></td>
                                </tr>
                                <tr>
                                    <th>License Plate:</th>
                                    <td><strong class="text-primary">{{ $vehicle->license_plate }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Vehicle Type:</th>
                                    <td><span class="badge bg-label-info">{{ ucfirst($vehicle->vehicle_type) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Year:</th>
                                    <td>{{ $vehicle->year ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Capacity:</th>
                                    <td>{{ $vehicle->capacity }} seats</td>
                                </tr>
                                <tr>
                                    <th>Color:</th>
                                    <td>{{ $vehicle->color ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fuel Type:</th>
                                    <td>{{ ucfirst($vehicle->fuel_type ?? 'N/A') }}</td>
                                </tr>
                                <tr>
                                    <th>Assigned Driver:</th>
                                    <td>
                                        @if($vehicle->driver)
                                            <span class="badge bg-label-primary">{{ $vehicle->driver->name }}</span>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-label-{{ $vehicle->status == 'available' ? 'success' : ($vehicle->status == 'in_use' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $vehicle->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($vehicle->last_maintenance)
                                <tr>
                                    <th>Last Maintenance:</th>
                                    <td>{{ $vehicle->last_maintenance->format('M d, Y') }}</td>
                                </tr>
                                @endif
                                @if($vehicle->next_maintenance)
                                <tr>
                                    <th>Next Maintenance:</th>
                                    <td>{{ $vehicle->next_maintenance->format('M d, Y') }}</td>
                                </tr>
                                @endif
                            </table>
                            
                            @if($vehicle->notes)
                            <div class="mt-4">
                                <h6>Notes</h6>
                                <p class="text-muted">{{ $vehicle->notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-info">
                                            <i class="ri-edit-line me-1"></i>Edit Vehicle
                                        </a>
                                        <a href="{{ route('admin.vehicles.availability', ['status' => $vehicle->status]) }}" class="btn btn-outline-primary">
                                            <i class="ri-calendar-line me-1"></i>View Availability
                                        </a>
                                        <a href="{{ route('admin.vehicles.assign-driver') }}" class="btn btn-outline-info">
                                            <i class="ri-user-line me-1"></i>Assign Driver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



