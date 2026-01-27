<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\Booking;
use App\Models\TourOperation;
use App\Models\DriverProfile;
use App\Models\TransportBooking;
use App\Models\VehicleMaintenance;
use App\Models\VehicleDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VehicleController extends BaseAdminController
{
    /**
     * Display a listing of vehicles
     */
    public function index(Request $request)
    {
        $query = Vehicle::with(['driver', 'currentBooking']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('vehicle_type', $request->type);
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vehicle_name', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%")
                  ->orWhere('vehicle_code', 'like', "%{$search}%");
            });
        }
        
        $vehicles = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('status', 'active')->count(),
            'in_maintenance' => Vehicle::where('status', 'in_maintenance')->count(),
            'not_available' => Vehicle::where('status', 'not_available')->count(),
        ];

        $drivers = User::whereHas('roles', function($q) {
            $q->where('name', 'Driver/Guide');
        })->orderBy('name')->get();
        
        return view('admin.vehicles.index', compact('vehicles', 'stats', 'drivers'));
    }

    /**
     * Show create vehicle form
     */
    public function create()
    {
        $drivers = User::whereHas('roles', function($q) {
            $q->where('name', 'Driver/Guide');
        })->orderBy('name')->get();
        return view('admin.vehicles.create', compact('drivers'));
    }

    /**
     * Store a newly created vehicle
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'vehicle_type' => 'required|string|in:Safari Jeep,Van,Minibus,Coaster Bus,Sedan,VIP SUV',
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
            'registration_no' => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1',
            'fuel_type' => 'nullable|string|in:Diesel,Petrol',
            'transmission' => 'nullable|string|in:Auto,Manual',
            'features' => 'nullable|array',
            'features.*' => 'in:Pop-up Roof,AC,Charging Ports,Cooler Box,4x4,Tracking Device',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'driver_id' => 'nullable|exists:users,id',
            'status' => 'required|string|in:active,in_maintenance,not_available,out_of_service',
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date',
            'odometer_reading' => 'nullable|integer|min:0',
            'service_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('vehicles', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('vehicles/gallery', 'public');
            }
            $validated['gallery_images'] = $gallery;
        }

        $vehicle = Vehicle::create($validated);

        // Handle documents if provided
        if ($request->hasFile('insurance_document')) {
            VehicleDocument::create([
                'vehicle_id' => $vehicle->id,
                'document_type' => 'insurance',
                'document_name' => 'Insurance Document',
                'file_path' => $request->file('insurance_document')->store('vehicle-documents', 'public'),
            ]);
        }

        if ($request->hasFile('vehicle_license')) {
            VehicleDocument::create([
                'vehicle_id' => $vehicle->id,
                'document_type' => 'license',
                'document_name' => 'Vehicle License',
                'file_path' => $request->file('vehicle_license')->store('vehicle-documents', 'public'),
            ]);
        }

        if ($request->hasFile('road_permit')) {
            VehicleDocument::create([
                'vehicle_id' => $vehicle->id,
                'document_type' => 'road_permit',
                'document_name' => 'Road Permit',
                'file_path' => $request->file('road_permit')->store('vehicle-documents', 'public'),
            ]);
        }

        if ($request->hasFile('inspection_certificate')) {
            VehicleDocument::create([
                'vehicle_id' => $vehicle->id,
                'document_type' => 'inspection_certificate',
                'document_name' => 'Inspection Certificate',
                'file_path' => $request->file('inspection_certificate')->store('vehicle-documents', 'public'),
            ]);
        }

        if ($request->input('save_and_add_another')) {
            return $this->successResponse('Vehicle created successfully!', route('admin.vehicles.create'));
        }

        return $this->successResponse('Vehicle created successfully!', route('admin.vehicles.index'));
    }

    /**
     * Display the specified vehicle
     */
    public function show($id)
    {
        $vehicle = Vehicle::with(['driver', 'currentBooking', 'maintenances.performedBy', 'documents'])->findOrFail($id);
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $drivers = User::whereHas('roles', function($q) {
            $q->where('name', 'Driver/Guide');
        })->orderBy('name')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'drivers'));
    }

    /**
     * Update the specified vehicle
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $validated = $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'vehicle_type' => 'required|string|in:Safari Jeep,Van,Minibus,Coaster Bus,Sedan,VIP SUV',
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $vehicle->id,
            'registration_no' => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1',
            'fuel_type' => 'nullable|string|in:Diesel,Petrol',
            'transmission' => 'nullable|string|in:Auto,Manual',
            'features' => 'nullable|array',
            'features.*' => 'in:Pop-up Roof,AC,Charging Ports,Cooler Box,4x4,Tracking Device',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'driver_id' => 'nullable|exists:users,id',
            'status' => 'required|string|in:active,in_maintenance,not_available,out_of_service',
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date',
            'odometer_reading' => 'nullable|integer|min:0',
            'service_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            if ($vehicle->cover_image) {
                Storage::disk('public')->delete($vehicle->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('vehicles', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $gallery = $vehicle->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('vehicles/gallery', 'public');
            }
            $validated['gallery_images'] = $gallery;
        }
        
        $vehicle->update($validated);
        
        return $this->successResponse('Vehicle updated successfully!', route('admin.vehicles.index'));
    }

    /**
     * Remove the specified vehicle
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        
        return $this->successResponse('Vehicle deleted successfully!', route('admin.vehicles.index'));
    }

    /**
     * Display drivers and guides
     */
    public function drivers(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'Driver/Guide');
        })->with(['vehicles' => function($q) {
            $q->where('status', 'in_use');
        }]);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'available') {
                $query->whereDoesntHave('vehicles', function($q) {
                    $q->where('status', 'in_use');
                });
            } elseif ($request->status == 'assigned') {
                $query->whereHas('vehicles', function($q) {
                    $q->where('status', 'in_use');
                });
            }
        }
        
        $drivers = $query->orderBy('name')->paginate(20);
        
        $stats = [
            'total' => User::whereHas('roles', function($q) {
                $q->where('name', 'Driver/Guide');
            })->count(),
            'available' => User::whereHas('roles', function($q) {
                $q->where('name', 'Driver/Guide');
            })->whereDoesntHave('vehicles', function($q) {
                $q->where('status', 'in_use');
            })->count(),
            'assigned' => User::whereHas('roles', function($q) {
                $q->where('name', 'Driver/Guide');
            })->whereHas('vehicles', function($q) {
                $q->where('status', 'in_use');
            })->count(),
        ];
        
        return view('admin.vehicles.drivers', compact('drivers', 'stats'));
    }

    /**
     * Display assign driver to trip
     */
    public function assignDriver(Request $request)
    {
        $bookingId = $request->get('booking_id');
        $booking = $bookingId ? Booking::with(['tour', 'tourOperations.driver', 'tourOperations.vehicle'])->find($bookingId) : null;
        
        $query = Booking::whereIn('status', ['confirmed', 'pending_payment'])->with('tour');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('tour', function($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $bookings = $query->latest()->paginate(20);
        
        $drivers = User::whereHas('roles', function($q) {
            $q->where('name', 'Driver/Guide');
        })->orderBy('name')->get();
        $vehicles = Vehicle::whereIn('status', ['available', 'in_use'])->orderBy('model')->get();
        
        // Get existing operations for the selected booking
        $operations = $booking ? TourOperation::with(['driver', 'vehicle', 'guide'])
            ->where('booking_id', $booking->id)
            ->orderBy('operation_date', 'desc')
            ->get() : collect();
        
        return view('admin.vehicles.assign-driver', compact('booking', 'bookings', 'drivers', 'vehicles', 'operations'));
    }
    
    /**
     * Store driver and vehicle assignment
     */
    public function storeAssignDriver(Request $request)
    {
        // Handle notify_driver checkbox before validation
        // Checkbox sends "1" when checked, nothing when unchecked
        $notifyDriver = $request->has('notify_driver') && $request->input('notify_driver') == '1';
        
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'operation_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        // Add notify_driver as boolean
        $validated['notify_driver'] = $notifyDriver;
        
        $booking = Booking::with('tour')->findOrFail($validated['booking_id']);
        
        if (!$booking->tour_id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking must be associated with a tour!'
                ], 422);
            }
            return $this->errorResponse('Booking must be associated with a tour!', route('admin.vehicles.assign-driver'));
        }
        
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $vehicle->driver_id = $validated['driver_id'];
        $vehicle->status = 'in_use';
        if ($request->filled('notes')) {
            $vehicle->notes = ($vehicle->notes ? $vehicle->notes . "\n" : '') . 
                '[' . now()->format('Y-m-d H:i') . '] Assignment: ' . $validated['notes'];
        }
        $vehicle->save();
        
        // Create or update tour operation record
        $tourOperation = TourOperation::firstOrNew([
            'booking_id' => $validated['booking_id'],
        ]);
        $tourOperation->tour_id = $booking->tour_id; // Fix: Add tour_id
        $tourOperation->driver_id = $validated['driver_id'];
        $tourOperation->vehicle_id = $validated['vehicle_id'];
        $tourOperation->operation_date = $validated['operation_date'] ?? $booking->departure_date;
        $tourOperation->status = 'scheduled';
        if ($request->filled('notes')) {
            $tourOperation->notes = $validated['notes'];
        }
        $tourOperation->save();
        
        // Send notification to driver if requested
        if ($validated['notify_driver']) {
            try {
                $driver = User::find($validated['driver_id']);
                if ($driver && $driver->email) {
                    $notificationService = app(\App\Services\NotificationService::class);
                    $message = "You have been assigned to drive for booking {$booking->booking_reference}. ";
                    $message .= "Vehicle: {$vehicle->make} {$vehicle->model} ({$vehicle->license_plate}). ";
                    if ($request->filled('notes')) {
                        $message .= "Notes: {$validated['notes']}";
                    }
                    
                    $notificationService->notify(
                        $driver->id,
                        $message,
                        route('admin.bookings.show', $booking->id),
                        'Driver Assignment - ' . $booking->booking_reference
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Failed to notify driver: ' . $e->getMessage());
                // Don't fail the assignment if notification fails
            }
        }
        
        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Driver and vehicle assigned successfully!',
                'redirect' => route('admin.vehicles.assign-driver', ['booking_id' => $booking->id])
            ]);
        }
        
        return $this->successResponse('Driver and vehicle assigned successfully!', route('admin.vehicles.assign-driver', ['booking_id' => $booking->id]));
    }

    /**
     * Display fleet availability
     */
    public function availability(Request $request)
    {
        $query = Vehicle::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('vehicle_type', $request->type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('next_maintenance', '>=', $request->date_from);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }
        
        $vehicles = $query->with('driver')->orderBy('status')->orderBy('model')->paginate(20);
        
        $stats = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('status', 'available')->count(),
            'in_use' => Vehicle::where('status', 'in_use')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
        ];
        
        return view('admin.vehicles.availability', compact('vehicles', 'stats'));
    }
    
    /**
     * Update vehicle status
     */
    public function updateStatus(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|string|in:available,in_use,maintenance',
            'notes' => 'nullable|string',
        ]);
        
        $vehicle->update($validated);
        
        return $this->successResponse('Vehicle status updated successfully!', route('admin.vehicles.availability'));
    }
    
    /**
     * Assign vehicle to driver
     */
    public function assignVehicle(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);
        
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $vehicle->driver_id = $validated['driver_id'];
        $vehicle->status = 'in_use';
        if ($request->filled('notes')) {
            $vehicle->notes = ($vehicle->notes ? $vehicle->notes . "\n" : '') . $validated['notes'];
        }
        $vehicle->save();
        
        return $this->successResponse('Vehicle assigned successfully!', route('admin.vehicles.availability'));
    }
    
    /**
     * Get driver vehicles (AJAX)
     */
    public function getDriverVehicles($id)
    {
        $driver = User::with('vehicles')->findOrFail($id);
        return response()->json(['vehicles' => $driver->vehicles]);
    }

    /**
     * Display transport bookings
     */
    public function bookings(Request $request)
    {
        $query = Booking::with('tour');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->where('departure_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('departure_date', '<=', $request->date_to);
        }
        
        $bookings = $query->latest()->paginate(20);
        $vehicles = Vehicle::orderBy('model')->get();
        
        return view('admin.vehicles.bookings', compact('bookings', 'vehicles'));
    }
    
    /**
     * Get tour operation details (AJAX)
     */
    public function getOperationDetails($id)
    {
        $operation = TourOperation::with(['booking.tour', 'driver', 'vehicle', 'guide'])
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'operation' => $operation,
        ]);
    }
    
    /**
     * Update tour operation
     */
    public function updateOperation(Request $request, $id)
    {
        $operation = TourOperation::findOrFail($id);
        
        $validated = $request->validate([
            'driver_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'guide_id' => 'nullable|exists:users,id',
            'operation_date' => 'nullable|date',
            'status' => 'required|string|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'checklist' => 'nullable|array',
            'daily_log' => 'nullable|string',
        ]);
        
        $operation->update($validated);
        
        // Update vehicle status if operation is completed or cancelled
        if (in_array($validated['status'], ['completed', 'cancelled']) && $operation->vehicle_id) {
            $vehicle = Vehicle::find($operation->vehicle_id);
            if ($vehicle) {
                $vehicle->status = 'available';
                $vehicle->save();
            }
        }
        
        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Operation updated successfully!',
                'redirect' => route('admin.vehicles.assign-driver', ['booking_id' => $operation->booking_id])
            ]);
        }
        
        return $this->successResponse('Operation updated successfully!', route('admin.vehicles.assign-driver', ['booking_id' => $operation->booking_id]));
    }
    
    /**
     * Delete tour operation
     */
    public function destroyOperation(Request $request, $id)
    {
        $operation = TourOperation::findOrFail($id);
        $bookingId = $operation->booking_id;
        
        // Release vehicle if assigned
        if ($operation->vehicle_id) {
            $vehicle = Vehicle::find($operation->vehicle_id);
            if ($vehicle && $vehicle->driver_id == $operation->driver_id) {
                $vehicle->status = 'available';
                $vehicle->driver_id = null;
                $vehicle->save();
            }
        }
        
        $operation->delete();
        
        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Operation deleted successfully!',
                'redirect' => route('admin.vehicles.assign-driver', ['booking_id' => $bookingId])
            ]);
        }
        
        return $this->successResponse('Operation deleted successfully!', route('admin.vehicles.assign-driver', ['booking_id' => $bookingId]));
    }

    /**
     * Store maintenance log
     */
    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'required|string|max:255',
            'service_date' => 'required|date',
            'next_service_date' => 'nullable|date|after:service_date',
            'odometer_reading' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
            'service_notes' => 'nullable|string',
            'parts_replaced' => 'nullable|array',
            'service_provider' => 'nullable|string|max:255',
            'performed_by' => 'nullable|exists:users,id',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('vehicle-maintenance', 'public');
            }
        }
        $validated['attachments'] = $attachments;

        $maintenance = VehicleMaintenance::create($validated);

        // Update vehicle's last maintenance date
        $vehicle = Vehicle::find($validated['vehicle_id']);
        $vehicle->last_maintenance = $validated['service_date'];
        $vehicle->next_maintenance = $validated['next_service_date'] ?? null;
        if ($validated['odometer_reading']) {
            $vehicle->odometer_reading = $validated['odometer_reading'];
        }
        $vehicle->save();

        return $this->successResponse('Maintenance log created successfully!', route('admin.vehicles.show', $validated['vehicle_id']));
    }

    /**
     * Store vehicle document
     */
    public function storeDocument(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'document_type' => 'required|string|in:insurance,license,road_permit,inspection_certificate',
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['file_path'] = $request->file('file')->store('vehicle-documents', 'public');

        VehicleDocument::create($validated);

        return $this->successResponse('Document uploaded successfully!', route('admin.vehicles.show', $validated['vehicle_id']));
    }

    /**
     * Delete vehicle document
     */
    public function deleteDocument($id)
    {
        $document = VehicleDocument::findOrFail($id);
        $vehicleId = $document->vehicle_id;
        
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();

        return $this->successResponse('Document deleted successfully!', route('admin.vehicles.show', $vehicleId));
    }

    /**
     * Export fleet data
     */
    public function exportFleet(Request $request)
    {
        $vehicles = Vehicle::with(['driver', 'currentBooking'])->get();

        $filename = 'fleet-export-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($vehicles) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Vehicle ID',
                'Vehicle Name',
                'Type',
                'Make',
                'Model',
                'Year',
                'Registration No',
                'Capacity',
                'Status',
                'Assigned Driver',
                'Current Booking',
                'Last Service Date',
                'Next Service Date'
            ]);

            // Data
            foreach ($vehicles as $vehicle) {
                fputcsv($file, [
                    $vehicle->vehicle_code ?? 'N/A',
                    $vehicle->display_name,
                    $vehicle->vehicle_type,
                    $vehicle->make,
                    $vehicle->model,
                    $vehicle->year,
                    $vehicle->license_plate,
                    $vehicle->capacity,
                    $vehicle->status,
                    $vehicle->driver ? $vehicle->driver->name : 'Unassigned',
                    $vehicle->currentBooking ? $vehicle->currentBooking->booking_reference : 'None',
                    $vehicle->last_maintenance ? $vehicle->last_maintenance->format('Y-m-d') : 'N/A',
                    $vehicle->next_maintenance ? $vehicle->next_maintenance->format('Y-m-d') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get maintenance log for vehicle
     */
    public function getMaintenanceLog($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $maintenances = VehicleMaintenance::where('vehicle_id', $id)
            ->with('performedBy')
            ->orderBy('service_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'maintenances' => $maintenances
        ]);
    }

    /**
     * Display transport bookings
     */
    public function transportBookings(Request $request)
    {
        $query = TransportBooking::with(['vehicle', 'driver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('travel_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('travel_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transport_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(20);
        $vehicles = Vehicle::whereIn('status', ['active', 'available'])->orderBy('vehicle_name')->get();
        $drivers = User::whereHas('roles', function($q) {
            $q->where('name', 'Driver/Guide');
        })->orderBy('name')->get();

        return view('admin.vehicles.transport-bookings', compact('bookings', 'vehicles', 'drivers'));
    }

    /**
     * Create transport booking
     */
    public function createTransportBooking()
    {
        $vehicles = Vehicle::whereIn('status', ['active', 'available'])->orderBy('vehicle_name')->get();
        $drivers = User::whereHas('roles', function($q) {
            $q->where('name', 'Driver/Guide');
        })->orderBy('name')->get();

        return view('admin.vehicles.create-transport-booking', compact('vehicles', 'drivers'));
    }

    /**
     * Store transport booking
     */
    public function storeTransportBooking(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'travel_date' => 'required|date|after_or_equal:today',
            'number_of_passengers' => 'required|integer|min:1',
            'luggage_info' => 'nullable|string',
            'preferred_vehicle_type' => 'nullable|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:users,id',
            'base_price' => 'required|numeric|min:0',
            'addons_price' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,approved,driver_assigned,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $validated['final_price'] = ($validated['base_price'] ?? 0) + 
                                    ($validated['addons_price'] ?? 0) - 
                                    ($validated['discount_amount'] ?? 0);
        $validated['status'] = $validated['status'] ?? 'pending';

        $booking = TransportBooking::create($validated);

        // Update vehicle status if assigned
        if ($validated['vehicle_id']) {
            $vehicle = Vehicle::find($validated['vehicle_id']);
            if ($vehicle) {
                $vehicle->status = 'in_use';
                $vehicle->save();
            }
        }

        return $this->successResponse('Transport booking created successfully!', route('admin.vehicles.transport-bookings'));
    }

    /**
     * Update transport booking
     */
    public function updateTransportBooking(Request $request, $id)
    {
        $booking = TransportBooking::findOrFail($id);
        
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'travel_date' => 'required|date',
            'number_of_passengers' => 'required|integer|min:1',
            'luggage_info' => 'nullable|string',
            'preferred_vehicle_type' => 'nullable|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:users,id',
            'base_price' => 'required|numeric|min:0',
            'addons_price' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,driver_assigned,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $validated['final_price'] = ($validated['base_price'] ?? 0) + 
                                    ($validated['addons_price'] ?? 0) - 
                                    ($validated['discount_amount'] ?? 0);

        // Release old vehicle if changed
        if ($booking->vehicle_id && $booking->vehicle_id != $validated['vehicle_id']) {
            $oldVehicle = Vehicle::find($booking->vehicle_id);
            if ($oldVehicle) {
                $oldVehicle->status = 'active';
                $oldVehicle->save();
            }
        }

        // Update new vehicle status
        if ($validated['vehicle_id']) {
            $vehicle = Vehicle::find($validated['vehicle_id']);
            if ($vehicle) {
                $vehicle->status = 'in_use';
                $vehicle->save();
            }
        }

        $booking->update($validated);

        return $this->successResponse('Transport booking updated successfully!', route('admin.vehicles.transport-bookings'));
    }

    /**
     * Fleet availability calendar (JSON data)
     */
    public function getCalendarData(Request $request)
    {
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());

        $events = [];

        // Vehicle availability
        $vehicles = Vehicle::with('driver')->get();
        foreach ($vehicles as $vehicle) {
            $color = match($vehicle->status) {
                'active', 'available' => '#28a745', // Green
                'in_maintenance' => '#ffc107', // Yellow
                'in_use', 'not_available' => '#dc3545', // Red
                'out_of_service' => '#6c757d', // Gray
                default => '#6c757d'
            };

            $events[] = [
                'id' => 'vehicle-' . $vehicle->id,
                'title' => $vehicle->display_name . ' (' . $vehicle->license_plate . ')',
                'start' => $start,
                'end' => $end,
                'color' => $color,
                'type' => 'vehicle',
                'vehicle_id' => $vehicle->id,
            ];
        }

        // Bookings
        $bookings = Booking::whereBetween('departure_date', [$start, $end])
            ->with(['tour', 'tourOperations.vehicle', 'tourOperations.driver'])
            ->get();

        foreach ($bookings as $booking) {
            if ($booking->departure_date) {
                $events[] = [
                    'id' => 'booking-' . $booking->id,
                    'title' => 'Booking: ' . $booking->booking_reference,
                    'start' => $booking->departure_date->toDateString(),
                    'end' => $booking->travel_end_date ? $booking->travel_end_date->toDateString() : $booking->departure_date->toDateString(),
                    'color' => '#007bff', // Blue
                    'type' => 'booking',
                    'booking_id' => $booking->id,
                ];
            }
        }

        // Transport bookings
        $transportBookings = TransportBooking::whereBetween('travel_date', [$start, $end])
            ->with(['vehicle', 'driver'])
            ->get();

        foreach ($transportBookings as $booking) {
            $events[] = [
                'id' => 'transport-' . $booking->id,
                'title' => 'Transport: ' . $booking->transport_id,
                'start' => $booking->travel_date->toDateString(),
                'color' => '#17a2b8', // Cyan
                'type' => 'transport',
                'transport_id' => $booking->id,
            ];
        }

        return response()->json($events);
    }
}
