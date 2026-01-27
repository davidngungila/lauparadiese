@extends('admin.layouts.app')

@section('title', 'Add Vehicle - Transport & Fleet Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-car-line me-2"></i>Add New Vehicle
                    </h4>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>Back to Fleet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- A. Vehicle Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="ri-information-line me-2"></i>A. Vehicle Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Vehicle Name <span class="text-danger">*</span></label>
                        <input type="text" name="vehicle_name" class="form-control @error('vehicle_name') is-invalid @enderror" value="{{ old('vehicle_name') }}" placeholder="e.g. Toyota Land Cruiser" required>
                        @error('vehicle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                        <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="Safari Jeep" {{ old('vehicle_type') == 'Safari Jeep' ? 'selected' : '' }}>Safari Jeep</option>
                            <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
                            <option value="Minibus" {{ old('vehicle_type') == 'Minibus' ? 'selected' : '' }}>Minibus</option>
                            <option value="Coaster Bus" {{ old('vehicle_type') == 'Coaster Bus' ? 'selected' : '' }}>Coaster Bus</option>
                            <option value="Sedan" {{ old('vehicle_type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="VIP SUV" {{ old('vehicle_type') == 'VIP SUV' ? 'selected' : '' }}>VIP SUV</option>
                        </select>
                        @error('vehicle_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Registration / Plate Number <span class="text-danger">*</span></label>
                        <input type="text" name="license_plate" class="form-control @error('license_plate') is-invalid @enderror" value="{{ old('license_plate') }}" required>
                        @error('license_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Registration No. (Alternative)</label>
                        <input type="text" name="registration_no" class="form-control @error('registration_no') is-invalid @enderror" value="{{ old('registration_no') }}">
                        @error('registration_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Make</label>
                        <input type="text" name="make" class="form-control @error('make') is-invalid @enderror" value="{{ old('make') }}" placeholder="e.g. Toyota">
                        @error('make')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Model & Year</label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" placeholder="e.g. Land Cruiser">
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Year</label>
                        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Chassis Number</label>
                        <input type="text" name="chassis_number" class="form-control @error('chassis_number') is-invalid @enderror" value="{{ old('chassis_number') }}">
                        @error('chassis_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Seating Capacity <span class="text-danger">*</span></label>
                        <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" min="1" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fuel Type</label>
                        <select name="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror">
                            <option value="">Select Fuel Type</option>
                            <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                        </select>
                        @error('fuel_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Transmission</label>
                        <select name="transmission" class="form-select @error('transmission') is-invalid @enderror">
                            <option value="">Select Transmission</option>
                            <option value="Auto" {{ old('transmission') == 'Auto' ? 'selected' : '' }}>Auto</option>
                            <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('transmission')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Colour</label>
                        <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color') }}">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Features</label>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="Pop-up Roof" id="feature_popup" {{ in_array('Pop-up Roof', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_popup">Pop-up Roof</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="AC" id="feature_ac" {{ in_array('AC', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_ac">AC</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="Charging Ports" id="feature_charging" {{ in_array('Charging Ports', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_charging">Charging Ports</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="Cooler Box" id="feature_cooler" {{ in_array('Cooler Box', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_cooler">Cooler Box</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="4x4" id="feature_4x4" {{ in_array('4x4', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_4x4">4x4</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="Tracking Device" id="feature_tracking" {{ in_array('Tracking Device', old('features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_tracking">Tracking Device</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- B. Vehicle Documents -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="ri-file-line me-2"></i>B. Vehicle Documents</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Insurance Document</label>
                        <input type="file" name="insurance_document" class="form-control @error('insurance_document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF or Image (Max 5MB)</small>
                        @error('insurance_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vehicle License</label>
                        <input type="file" name="vehicle_license" class="form-control @error('vehicle_license') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF or Image (Max 5MB)</small>
                        @error('vehicle_license')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Road Permit</label>
                        <input type="file" name="road_permit" class="form-control @error('road_permit') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF or Image (Max 5MB)</small>
                        @error('road_permit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Inspection Certificate</label>
                        <input type="file" name="inspection_certificate" class="form-control @error('inspection_certificate') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF or Image (Max 5MB)</small>
                        @error('inspection_certificate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- C. Vehicle Photos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="ri-image-line me-2"></i>C. Vehicle Photos</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Cover Image</label>
                        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Main vehicle image (JPG, PNG, WebP - Max 5MB)</small>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gallery Images</label>
                        <input type="file" name="gallery_images[]" class="form-control @error('gallery_images.*') is-invalid @enderror" accept="image/*" multiple>
                        <small class="text-muted">Multiple images allowed (JPG, PNG, WebP - Max 5MB each)</small>
                        @error('gallery_images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- D. Maintenance Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="ri-tools-line me-2"></i>D. Maintenance Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Last Service Date</label>
                        <input type="date" name="last_maintenance" class="form-control @error('last_maintenance') is-invalid @enderror" value="{{ old('last_maintenance') }}">
                        @error('last_maintenance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Next Service Date</label>
                        <input type="date" name="next_maintenance" class="form-control @error('next_maintenance') is-invalid @enderror" value="{{ old('next_maintenance') }}">
                        @error('next_maintenance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Odometer Reading</label>
                        <input type="number" name="odometer_reading" class="form-control @error('odometer_reading') is-invalid @enderror" value="{{ old('odometer_reading') }}" min="0">
                        @error('odometer_reading')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Service Notes</label>
                        <textarea name="service_notes" class="form-control @error('service_notes') is-invalid @enderror" rows="3">{{ old('service_notes') }}</textarea>
                        @error('service_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- E. Status & Assignment -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="ri-settings-3-line me-2"></i>E. Status & Assignment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="in_maintenance" {{ old('status') == 'in_maintenance' ? 'selected' : '' }}>In Maintenance</option>
                            <option value="not_available" {{ old('status') == 'not_available' ? 'selected' : '' }}>Not Available</option>
                            <option value="out_of_service" {{ old('status') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assigned Driver</label>
                        <select name="driver_id" class="form-select @error('driver_id') is-invalid @enderror">
                            <option value="">No Driver Assigned</option>
                            @foreach($drivers ?? [] as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Vehicle
                    </button>
                    <button type="submit" name="save_and_add_another" value="1" class="btn btn-outline-primary">
                        <i class="ri-add-line me-1"></i>Save & Add Another
                    </button>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-label-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
