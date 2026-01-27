@extends('admin.layouts.app')

@section('title', 'Edit Vehicle - Lau Paradise Adventures')
@section('description', 'Edit vehicle details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Vehicle: {{ $vehicle->make }} {{ $vehicle->model }}
                    </h4>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-label-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Vehicles
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Make <span class="text-danger">*</span></label>
                                <input type="text" name="make" class="form-control @error('make') is-invalid @enderror" value="{{ old('make', $vehicle->make) }}" required>
                                @error('make')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $vehicle->model) }}" required>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">License Plate <span class="text-danger">*</span></label>
                                <input type="text" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror" value="{{ old('license_plate', $vehicle->license_plate) }}" required>
                                @error('license_plate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="sedan" {{ old('vehicle_type', $vehicle->vehicle_type) == 'sedan' ? 'selected' : '' }}>Sedan</option>
                                    <option value="suv" {{ old('vehicle_type', $vehicle->vehicle_type) == 'suv' ? 'selected' : '' }}>SUV</option>
                                    <option value="van" {{ old('vehicle_type', $vehicle->vehicle_type) == 'van' ? 'selected' : '' }}>Van</option>
                                    <option value="bus" {{ old('vehicle_type', $vehicle->vehicle_type) == 'bus' ? 'selected' : '' }}>Bus</option>
                                    <option value="4x4" {{ old('vehicle_type', $vehicle->vehicle_type) == '4x4' ? 'selected' : '' }}>4x4</option>
                                    <option value="motorcycle" {{ old('vehicle_type', $vehicle->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Year</label>
                                <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $vehicle->year) }}" min="1900" max="{{ date('Y') + 1 }}">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Capacity (Seats) <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $vehicle->capacity) }}" min="1" required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="available" {{ old('status', $vehicle->status) == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="in_use" {{ old('status', $vehicle->status) == 'in_use' ? 'selected' : '' }}>In Use</option>
                                    <option value="maintenance" {{ old('status', $vehicle->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Color</label>
                                <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $vehicle->color) }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fuel Type</label>
                                <select name="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror">
                                    <option value="">Select Fuel Type</option>
                                    <option value="petrol" {{ old('fuel_type', $vehicle->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                    <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="electric" {{ old('fuel_type', $vehicle->fuel_type) == 'electric' ? 'selected' : '' }}>Electric</option>
                                    <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                @error('fuel_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Assigned Driver</label>
                                <select name="driver_id" class="form-select @error('driver_id') is-invalid @enderror">
                                    <option value="">No Driver Assigned</option>
                                    @foreach($drivers ?? [] as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id', $vehicle->driver_id) == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Maintenance</label>
                                <input type="date" name="last_maintenance" class="form-control @error('last_maintenance') is-invalid @enderror" value="{{ old('last_maintenance', $vehicle->last_maintenance?->format('Y-m-d')) }}">
                                @error('last_maintenance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Next Maintenance</label>
                                <input type="date" name="next_maintenance" class="form-control @error('next_maintenance') is-invalid @enderror" value="{{ old('next_maintenance', $vehicle->next_maintenance?->format('Y-m-d')) }}">
                                @error('next_maintenance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $vehicle->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Update Vehicle
                                </button>
                                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-label-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



