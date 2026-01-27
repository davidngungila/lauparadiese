@extends('admin.layouts.app')

@section('title', ($tour ? 'Itinerary Builder – ' . $tour->name : 'Itinerary Builder') . ' - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-route-line me-2"></i>Itinerary Builder
                        @if($tour)
                            <span class="text-muted">– {{ $tour->name }}</span>
                        @endif
                    </h4>
                    <div class="d-flex gap-2">
                        @if($tour)
                            <a href="{{ route('admin.tours.show', $tour->id) }}" class="btn btn-outline-primary" target="_blank">
                                <i class="ri-eye-line me-1"></i>Preview Tour Page
                            </a>
                            <a href="{{ route('admin.tours.edit', $tour->id) }}" class="btn btn-outline-secondary">
                                <i class="ri-settings-3-line me-1"></i>Edit Tour
                            </a>
                        @endif
                        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Tours
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tour)
        <!-- 🧱 1. Tour Summary Panel -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($tour->image_url)
                                    @php
                                        $imageUrl = str_starts_with($tour->image_url, 'http://') || str_starts_with($tour->image_url, 'https://') 
                                            ? $tour->image_url 
                                            : asset($tour->image_url);
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $tour->name }}" class="img-fluid rounded" style="max-height: 120px; object-fit: cover; width: 100%;" onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                        <i class="ri-image-line" style="font-size: 48px; color: #ccc;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-1">{{ $tour->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="ri-map-pin-line me-1"></i>
                                    @if($tour->destination)
                                        {{ $tour->destination->name }}
                                    @endif
                                    @if($tour->categories->count() > 0)
                                        | 
                                        @foreach($tour->categories as $category)
                                            {{ $category->name }}@if(!$loop->last), @endif
                                        @endforeach
                                    @endif
                                </p>
                                <div class="d-flex gap-4 text-muted small">
                                    <span><i class="ri-calendar-line me-1"></i>{{ $tour->duration_days ?? 0 }} Days / {{ $tour->duration_nights ?? 0 }} Nights</span>
                                    @if($tour->start_location)
                                        <span><i class="ri-map-pin-2-line me-1"></i>Start: {{ $tour->start_location }}</span>
                                    @endif
                                    @if($tour->end_location)
                                        <span><i class="ri-map-pin-2-line me-1"></i>End: {{ $tour->end_location }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="mb-2">
                                    @php
                                        $publishStatus = strtolower($tour->publish_status ?? 'draft');
                                        $statusClass = match($publishStatus) {
                                            'published' => 'success',
                                            'draft' => 'warning',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-label-{{ $statusClass }}">
                                        {{ ucfirst($tour->publish_status ?? 'Draft') }}
                                    </span>
                                </div>
                                <div class="d-flex gap-2 justify-content-end">
                                    <form action="{{ route('admin.tours.update-status', $tour->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        @php
                                            $currentStatus = strtolower($tour->publish_status ?? 'draft');
                                            $newStatus = $currentStatus == 'published' ? 'draft' : 'published';
                                        @endphp
                                        <input type="hidden" name="publish_status" value="{{ $newStatus }}">
                                        <button type="submit" class="btn btn-sm btn-{{ $currentStatus == 'published' ? 'warning' : 'success' }}">
                                            <i class="ri-{{ $currentStatus == 'published' ? 'eye-off' : 'eye' }}-line me-1"></i>
                                            {{ $currentStatus == 'published' ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tour Selection (if needed) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.tours.itinerary-builder') }}" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Select Tour</label>
                                <select name="tour_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Select a tour...</option>
                                    @foreach($tours as $t)
                                        <option value="{{ $t->id }}" {{ $tour->id == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDayModal">
                                        <i class="ri-add-line me-1"></i>Add New Day
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="toggleReorderBtn" onclick="toggleReorderMode()">
                                        <i class="ri-drag-move-2-line me-1"></i>Reorder Days
                                    </button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#copyItineraryModal"><i class="ri-file-copy-line me-2"></i>Copy from Another Tour</a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.tours.itinerary.export', $tour->id) }}" target="_blank"><i class="ri-download-line me-2"></i>Export JSON</a></li>
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importItineraryModal"><i class="ri-upload-line me-2"></i>Import JSON</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#resetItineraryModal"><i class="ri-delete-bin-line me-2"></i>Reset Entire Itinerary</a></li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('admin.tours.itinerary.print', $tour->id) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="ri-printer-line me-1"></i>Print Itinerary
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📗 2. Itinerary Days List (Sortable) -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ri-calendar-check-line me-2"></i>Itinerary Days
                            <span class="badge bg-label-primary ms-2">{{ $tour->itineraries->count() }} Days</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($tour->itineraries->count() > 0)
                            <div id="itineraryDaysList" class="sortable-list">
                                @foreach($tour->itineraries as $day)
                                    <div class="card mb-3 itinerary-day-card" data-id="{{ $day->id }}" data-day-number="{{ $day->day_number }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="drag-handle me-3" style="cursor: move;">
                                                    <i class="ri-drag-move-2-line" style="font-size: 20px; color: #ccc;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <span class="badge bg-label-primary me-2">Day {{ $day->day_number }}</span>
                                                                {{ $day->title }}
                                                            </h6>
                                                            @if($day->short_summary)
                                                                <p class="text-muted small mb-0">{{ Str::limit($day->short_summary, 100) }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-icon btn-outline-primary edit-day-btn" data-id="{{ $day->id }}" title="Edit">
                                                                <i class="ri-pencil-line"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-icon btn-outline-success clone-day-btn" data-id="{{ $day->id }}" title="Duplicate">
                                                                <i class="ri-file-copy-line"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-icon btn-outline-danger delete-day-btn" data-id="{{ $day->id }}" title="Delete">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="row g-2 small text-muted">
                                                        <div class="col-auto">
                                                            <i class="ri-map-pin-line me-1"></i>{{ $day->location ?? 'N/A' }}
                                                        </div>
                                                        @if($day->meals_included && count($day->meals_included) > 0)
                                                            <div class="col-auto">
                                                                <i class="ri-restaurant-line me-1"></i>{{ implode(', ', $day->meals_included) }}
                                                            </div>
                                                        @endif
                                                        @if($day->accommodation_name)
                                                            <div class="col-auto">
                                                                <i class="ri-hotel-line me-1"></i>{{ $day->accommodation_name }}
                                                            </div>
                                                        @endif
                                                        @if($day->activities && count($day->activities) > 0)
                                                            <div class="col-auto">
                                                                <i class="ri-run-line me-1"></i>{{ count($day->activities) }} Activities
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-route-line" style="font-size: 48px; color: #ccc;"></i>
                                <p class="text-muted mt-3">No itinerary days added yet</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDayModal">
                                    <i class="ri-add-line me-1"></i>Add First Day
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 📄 6. Day Preview Panel -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ri-eye-line me-2"></i>Day Preview</h5>
                    </div>
                    <div class="card-body" id="dayPreviewPanel">
                        <p class="text-muted text-center py-4">Select a day to preview</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No Tour Selected -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ri-route-line" style="font-size: 64px; color: #ccc;"></i>
                        <h5 class="mt-3">Select a Tour</h5>
                        <p class="text-muted">Please select a tour from the dropdown to build its itinerary</p>
                        <form method="GET" action="{{ route('admin.tours.itinerary-builder') }}" class="d-inline-block mt-3">
                            <select name="tour_id" class="form-select" onchange="this.form.submit()" style="min-width: 300px;">
                                <option value="">Select a tour...</option>
                                @foreach($tours as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- ➕ 3. Add/Edit Day Modal -->
<div class="modal fade" id="addDayModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dayModalTitle">Add New Day</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="dayForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tour_id" value="{{ $tour->id ?? '' }}">
                <input type="hidden" name="itinerary_id" id="editItineraryId">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- A. Basic Details -->
                    <h6 class="mb-3"><i class="ri-file-text-line me-2"></i>A. Basic Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Day Number <span class="text-danger">*</span></label>
                            <input type="number" name="day_number" class="form-control" min="1" required id="dayNumberInput">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Day Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Arrival in Arusha" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g., Arusha Town">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Short Summary (1-2 lines)</label>
                            <input type="text" name="short_summary" class="form-control" placeholder="Brief summary of the day">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Full Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" required placeholder="Detailed description of the day's activities..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Main Image for this Day</label>
                            <input type="file" name="image" class="form-control" accept="image/*" id="dayImageInput">
                            <small class="text-muted">Max 5MB. Formats: JPG, PNG, GIF, WebP</small>
                            <div id="dayImagePreview" class="mt-2"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">You can select multiple images</small>
                            <div id="galleryPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- B. Meals -->
                    <h6 class="mb-3"><i class="ri-restaurant-line me-2"></i>B. Meals</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="meals_included[]" value="Breakfast" id="meal_breakfast">
                                <label class="form-check-label" for="meal_breakfast">Breakfast</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="meals_included[]" value="Lunch" id="meal_lunch">
                                <label class="form-check-label" for="meal_lunch">Lunch</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="meals_included[]" value="Dinner" id="meal_dinner">
                                <label class="form-check-label" for="meal_dinner">Dinner</label>
                            </div>
                        </div>
                    </div>

                    <!-- C. Accommodation -->
                    <h6 class="mb-3"><i class="ri-hotel-line me-2"></i>C. Accommodation</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Accommodation Type</label>
                            <select name="accommodation_type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="Camp">Camp</option>
                                <option value="Lodge">Lodge</option>
                                <option value="Hotel">Hotel</option>
                                <option value="Guest House">Guest House</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Accommodation Name</label>
                            <input type="text" name="accommodation_name" class="form-control" placeholder="e.g., Serengeti Lodge">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Accommodation Location</label>
                            <input type="text" name="accommodation_location" class="form-control" placeholder="Location">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Accommodation Image</label>
                            <input type="file" name="accommodation_image" class="form-control" accept="image/*">
                            <div id="accommodationImagePreview" class="mt-2"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Accommodation Rating</label>
                            <select name="accommodation_rating" class="form-select">
                                <option value="">No Rating</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                    </div>

                    <!-- D. Activities -->
                    <h6 class="mb-3"><i class="ri-run-line me-2"></i>D. Activities of the Day</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Select Activities</label>
                            <div class="row">
                                @php
                                    $commonActivities = [
                                        'Game Drive', 'Hiking', 'Walking Safari', 'Beach', 'Cultural Visit',
                                        'Wildlife Viewing', 'Photography', 'Bird Watching', 'Camping',
                                        'Boat Ride', 'Snorkeling', 'Diving', 'Fishing', 'Mountain Climbing'
                                    ];
                                @endphp
                                @foreach($commonActivities as $activity)
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input activity-checkbox" type="checkbox" name="activities[]" value="{{ $activity }}" id="activity_{{ Str::slug($activity) }}">
                                            <label class="form-check-label" for="activity_{{ Str::slug($activity) }}">{{ $activity }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Add Custom Activity</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="customActivityInput" placeholder="Enter custom activity name">
                                    <button type="button" class="btn btn-outline-primary" onclick="addCustomActivity()">
                                        <i class="ri-add-line"></i>Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- E. Transport -->
                    <h6 class="mb-3"><i class="ri-truck-line me-2"></i>E. Transport</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Type</label>
                            <input type="text" name="vehicle_type" class="form-control" placeholder="e.g., 4x4 Land Cruiser, Minibus">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Driver/Guide Notes</label>
                            <textarea name="driver_guide_notes" class="form-control" rows="2" placeholder="Special instructions for driver/guide..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Transfer Info</label>
                            <textarea name="transfer_info" class="form-control" rows="2" placeholder="Transfer details if applicable..."></textarea>
                        </div>
                    </div>

                    <!-- F. Advanced -->
                    <h6 class="mb-3"><i class="ri-settings-3-line me-2"></i>F. Advanced</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Day Notes (Admin Only)</label>
                            <textarea name="day_notes" class="form-control" rows="3" placeholder="Internal notes about this day..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Custom Icons (JSON format)</label>
                            <textarea name="custom_icons" class="form-control" rows="2" placeholder='{"icon": "🚙", "color": "#3ea572"}'></textarea>
                            <small class="text-muted">Optional: Custom icons for frontend display</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-primary" id="saveAndAddAnotherBtn" style="display: none;">
                        <i class="ri-add-line me-1"></i>Save & Add Another
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save Day
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Copy Itinerary Modal -->
<div class="modal fade" id="copyItineraryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Copy Itinerary from Another Tour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="copyItineraryForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Source Tour <span class="text-danger">*</span></label>
                        <select name="from_tour_id" class="form-select" required>
                            <option value="">Select Tour...</option>
                            @foreach($allTours ?? [] as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="to_tour_id" value="{{ $tour->id ?? '' }}">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="replace" id="replaceItinerary" value="1">
                        <label class="form-check-label" for="replaceItinerary">
                            Replace existing itinerary (will delete current days)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Copy Itinerary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Itinerary Modal -->
<div class="modal fade" id="importItineraryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Itinerary from JSON</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="importItineraryForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">JSON File <span class="text-danger">*</span></label>
                        <input type="file" name="json_file" class="form-control" accept=".json" required>
                        <small class="text-muted">Select a JSON file exported from another tour</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="replace" id="replaceOnImport" value="1">
                        <label class="form-check-label" for="replaceOnImport">
                            Replace existing itinerary (will delete current days)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Itinerary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Itinerary Modal -->
<div class="modal fade" id="resetItineraryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Reset Entire Itinerary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="resetItineraryForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Warning!</strong> This will permanently delete all itinerary days for this tour. This action cannot be undone.
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm" id="confirmReset" value="1" required>
                        <label class="form-check-label" for="confirmReset">
                            I understand this will delete all itinerary days
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reset Itinerary</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
.sortable-list {
    min-height: 100px;
}
.itinerary-day-card {
    transition: all 0.3s ease;
}
.itinerary-day-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.itinerary-day-card.sortable-ghost {
    opacity: 0.4;
    background: #f0f0f0;
}
.drag-handle {
    cursor: move;
}
.drag-handle:hover {
    color: #3ea572 !important;
}
#dayPreviewPanel img {
    max-width: 100%;
    border-radius: 8px;
    margin-bottom: 10px;
}
.image-preview-item {
    position: relative;
    display: inline-block;
    margin: 5px;
}
.image-preview-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
}
.image-preview-item .remove-image {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let sortableInstance = null;
let isReorderMode = false;
const tourId = {{ $tour->id ?? 'null' }};

// Initialize Sortable when in reorder mode
function toggleReorderMode() {
    isReorderMode = !isReorderMode;
    const btn = document.getElementById('toggleReorderBtn');
    const cards = document.querySelectorAll('.itinerary-day-card');
    
    if (isReorderMode) {
        btn.innerHTML = '<i class="ri-check-line me-1"></i>Save Order';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        
        // Show drag handles
        cards.forEach(card => {
            card.querySelector('.drag-handle').style.display = 'block';
        });
        
        // Initialize Sortable
        if (!sortableInstance) {
            sortableInstance = Sortable.create(document.getElementById('itineraryDaysList'), {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    // Visual feedback
                    updateDayNumbers();
                }
            });
        }
    } else {
        btn.innerHTML = '<i class="ri-drag-move-2-line me-1"></i>Reorder Days';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-primary');
        
        // Save order
        saveOrder();
    }
}

function updateDayNumbers() {
    const cards = document.querySelectorAll('.itinerary-day-card');
    cards.forEach((card, index) => {
        const dayNumber = index + 1;
        const badge = card.querySelector('.badge');
        if (badge) {
            badge.textContent = 'Day ' + dayNumber;
        }
        card.setAttribute('data-day-number', dayNumber);
    });
}

function saveOrder() {
    const cards = document.querySelectorAll('.itinerary-day-card');
    const itineraryIds = Array.from(cards).map(card => card.getAttribute('data-id'));
    
    fetch('{{ route("admin.tours.itinerary.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            tour_id: tourId,
            itinerary_ids: itineraryIds
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message, 'Reordered');
            location.reload();
        } else {
            showErrorToast(data.message || 'Failed to save order', 'Error');
        }
    })
    .catch(err => {
        console.error(err);
        showErrorToast('An error occurred while saving order', 'Error');
    });
}

// Add Custom Activity
function addCustomActivity() {
    const input = document.getElementById('customActivityInput');
    const value = input.value.trim();
    if (!value) return;
    
    const container = document.querySelector('.activity-checkbox').closest('.col-md-3').parentElement;
    const newCol = document.createElement('div');
    newCol.className = 'col-md-3 mb-2';
    newCol.innerHTML = `
        <div class="form-check">
            <input class="form-check-input activity-checkbox" type="checkbox" name="activities[]" value="${value}" id="activity_${value.toLowerCase().replace(/\s+/g, '_')}" checked>
            <label class="form-check-label" for="activity_${value.toLowerCase().replace(/\s+/g, '_')}">${value}</label>
        </div>
    `;
    container.appendChild(newCol);
    input.value = '';
}

// Image Preview
document.getElementById('dayImageInput')?.addEventListener('change', function(e) {
    const preview = document.getElementById('dayImagePreview');
    preview.innerHTML = '';
    if (e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail';
            img.style.maxWidth = '200px';
            preview.appendChild(img);
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Gallery Preview
document.querySelector('input[name="gallery_images[]"]')?.addEventListener('change', function(e) {
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';
    Array.from(e.target.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'image-preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

// Day Form Submit
document.getElementById('dayForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const itineraryId = document.getElementById('editItineraryId').value;
    const url = itineraryId 
        ? `/admin/tours/itinerary/${itineraryId}`
        : '{{ route("admin.tours.itinerary.store") }}';
    const method = itineraryId ? 'PUT' : 'POST';
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Saving...';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message, 'Success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showErrorToast(data.message || 'Failed to save day', 'Error');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(err => {
        console.error(err);
        showErrorToast('An error occurred. Please try again.', 'Error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Edit Day
document.querySelectorAll('.edit-day-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        fetch(`/admin/tours/itinerary/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    populateDayForm(data.itinerary);
                    document.getElementById('dayModalTitle').textContent = 'Edit Day';
                    document.getElementById('editItineraryId').value = id;
                    document.getElementById('saveAndAddAnotherBtn').style.display = 'none';
                    new bootstrap.Modal(document.getElementById('addDayModal')).show();
                }
            })
            .catch(err => {
                console.error(err);
                showErrorToast('Failed to load day data', 'Error');
            });
    });
});

// Clone Day
document.querySelectorAll('.clone-day-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        if (!confirm('Duplicate this day?')) return;
        
        fetch(`/admin/tours/itinerary/${id}/clone`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(data.message, 'Duplicated');
                location.reload();
            } else {
                showErrorToast(data.message || 'Failed to duplicate day', 'Error');
            }
        })
        .catch(err => {
            console.error(err);
            showErrorToast('An error occurred', 'Error');
        });
    });
});

// Delete Day
document.querySelectorAll('.delete-day-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        if (!confirm('Are you sure you want to delete this day? This will automatically renumber remaining days.')) return;
        
        fetch(`/admin/tours/itinerary/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(data.message, 'Deleted');
                location.reload();
            } else {
                showErrorToast(data.message || 'Failed to delete day', 'Error');
            }
        })
        .catch(err => {
            console.error(err);
            showErrorToast('An error occurred', 'Error');
        });
    });
});

// Copy Itinerary Form
document.getElementById('copyItineraryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Copying...';
    
    fetch('{{ route("admin.tours.itinerary.copy") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message + ` (${data.count} days copied)`, 'Copied');
            bootstrap.Modal.getInstance(document.getElementById('copyItineraryModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showErrorToast(data.message || 'Failed to copy itinerary', 'Error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Copy Itinerary';
        }
    })
    .catch(err => {
        console.error(err);
        showErrorToast('An error occurred', 'Error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Copy Itinerary';
    });
});

// Import Itinerary Form
document.getElementById('importItineraryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Importing...';
    
    fetch('{{ route("admin.tours.itinerary.import", $tour->id ?? 0) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message + ` (${data.count} days imported)`, 'Imported');
            bootstrap.Modal.getInstance(document.getElementById('importItineraryModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showErrorToast(data.message || 'Failed to import itinerary', 'Error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Import Itinerary';
        }
    })
    .catch(err => {
        console.error(err);
        showErrorToast('An error occurred', 'Error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Import Itinerary';
    });
});

// Reset Itinerary Form
document.getElementById('resetItineraryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i>Resetting...';
    
    fetch('{{ route("admin.tours.itinerary.reset", $tour->id ?? 0) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showWarningToast(data.message, 'Reset');
            bootstrap.Modal.getInstance(document.getElementById('resetItineraryModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showErrorToast(data.message || 'Failed to reset itinerary', 'Error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Reset Itinerary';
        }
    })
    .catch(err => {
        console.error(err);
        showErrorToast('An error occurred', 'Error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Reset Itinerary';
    });
});

// Populate Day Form for Editing
function populateDayForm(day) {
    // Basic fields
    const dayNumberInput = document.querySelector('input[name="day_number"]');
    if (dayNumberInput) dayNumberInput.value = day.day_number || '';
    
    const titleInput = document.querySelector('input[name="title"]');
    if (titleInput) titleInput.value = day.title || '';
    
    const shortSummaryInput = document.querySelector('input[name="short_summary"]');
    if (shortSummaryInput) shortSummaryInput.value = day.short_summary || '';
    
    const descriptionTextarea = document.querySelector('textarea[name="description"]');
    if (descriptionTextarea) descriptionTextarea.value = day.description || '';
    
    const locationInput = document.querySelector('input[name="location"]');
    if (locationInput) locationInput.value = day.location || '';
    
    // Accommodation fields
    const accommodationTypeSelect = document.querySelector('select[name="accommodation_type"]');
    if (accommodationTypeSelect) accommodationTypeSelect.value = day.accommodation_type || '';
    
    const accommodationNameInput = document.querySelector('input[name="accommodation_name"]');
    if (accommodationNameInput) accommodationNameInput.value = day.accommodation_name || '';
    
    const accommodationLocationInput = document.querySelector('input[name="accommodation_location"]');
    if (accommodationLocationInput) accommodationLocationInput.value = day.accommodation_location || '';
    
    // Accommodation rating - handle both string and number
    const accommodationRatingSelect = document.querySelector('select[name="accommodation_rating"]');
    if (accommodationRatingSelect) {
        const rating = day.accommodation_rating ? parseFloat(day.accommodation_rating).toString() : '';
        accommodationRatingSelect.value = rating;
    }
    
    // Transport fields
    const vehicleTypeInput = document.querySelector('input[name="vehicle_type"]');
    if (vehicleTypeInput) vehicleTypeInput.value = day.vehicle_type || '';
    
    const driverGuideNotesTextarea = document.querySelector('textarea[name="driver_guide_notes"]');
    if (driverGuideNotesTextarea) driverGuideNotesTextarea.value = day.driver_guide_notes || '';
    
    const transferInfoTextarea = document.querySelector('textarea[name="transfer_info"]');
    if (transferInfoTextarea) transferInfoTextarea.value = day.transfer_info || '';
    
    const dayNotesTextarea = document.querySelector('textarea[name="day_notes"]');
    if (dayNotesTextarea) dayNotesTextarea.value = day.day_notes || '';
    
    const customIconsTextarea = document.querySelector('textarea[name="custom_icons"]');
    if (customIconsTextarea) {
        customIconsTextarea.value = day.custom_icons ? (typeof day.custom_icons === 'string' ? day.custom_icons : JSON.stringify(day.custom_icons)) : '';
    }
    
    // Meals - uncheck all first
    document.querySelectorAll('input[name="meals_included[]"]').forEach(cb => cb.checked = false);
    if (day.meals_included && Array.isArray(day.meals_included)) {
        day.meals_included.forEach(meal => {
            const cb = document.querySelector(`input[name="meals_included[]"][value="${meal}"]`);
            if (cb) cb.checked = true;
        });
    }
    
    // Activities - Handle both string and object formats
    document.querySelectorAll('input[name="activities[]"]').forEach(cb => cb.checked = false);
    if (day.activities && Array.isArray(day.activities)) {
        day.activities.forEach(activity => {
            // Handle both string format and object format {name, icon}
            const activityName = typeof activity === 'string' ? activity : (activity.name || activity);
            const activityValue = activityName;
            
            if (!activityValue) return; // Skip if no name
            
            const cb = document.querySelector(`input[name="activities[]"][value="${activityValue}"]`);
            if (cb) {
                cb.checked = true;
            } else {
                // Add custom activity
                addCustomActivityToForm(activityValue);
            }
        });
    }
    
    // Handle image previews if images exist
    if (day.image) {
        const imagePreview = document.getElementById('dayImagePreview');
        if (imagePreview) {
            imagePreview.innerHTML = `<img src="/storage/${day.image}" alt="Day image" class="img-thumbnail" style="max-width: 200px;">`;
        }
    }
    
    if (day.accommodation_image) {
        const accommodationImagePreview = document.getElementById('accommodationImagePreview');
        if (accommodationImagePreview) {
            accommodationImagePreview.innerHTML = `<img src="/storage/${day.accommodation_image}" alt="Accommodation image" class="img-thumbnail" style="max-width: 200px;">`;
        }
    }
    
    if (day.gallery_images && Array.isArray(day.gallery_images) && day.gallery_images.length > 0) {
        const galleryPreview = document.getElementById('galleryPreview');
        if (galleryPreview) {
            galleryPreview.innerHTML = day.gallery_images.map(img => 
                `<img src="/storage/${img}" alt="Gallery" class="img-thumbnail" style="max-width: 100px; max-height: 100px; object-fit: cover;">`
            ).join('');
        }
    }
}

function addCustomActivityToForm(activity) {
    // Get the activities container (the row that contains activity checkboxes)
    const activitiesContainer = document.querySelector('input[name="activities[]"]')?.closest('.row');
    if (!activitiesContainer) return;
    
    // Check if activity already exists
    const existingCheckbox = activitiesContainer.querySelector(`input[name="activities[]"][value="${activity}"]`);
    if (existingCheckbox) {
        existingCheckbox.checked = true;
        return;
    }
    
    // Create new checkbox for custom activity
    const newCol = document.createElement('div');
    newCol.className = 'col-md-3 mb-2';
    const activityId = activity.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    newCol.innerHTML = `
        <div class="form-check">
            <input class="form-check-input activity-checkbox" type="checkbox" name="activities[]" value="${activity}" id="activity_${activityId}" checked>
            <label class="form-check-label" for="activity_${activityId}">${activity}</label>
        </div>
    `;
    activitiesContainer.appendChild(newCol);
}

// Reset form when modal is closed
document.getElementById('addDayModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('dayForm').reset();
    document.getElementById('editItineraryId').value = '';
    document.getElementById('dayModalTitle').textContent = 'Add New Day';
    document.getElementById('dayImagePreview').innerHTML = '';
    document.getElementById('galleryPreview').innerHTML = '';
    document.getElementById('accommodationImagePreview').innerHTML = '';
    document.getElementById('saveAndAddAnotherBtn').style.display = 'none';
    
    // Auto-calculate next day number
    if (tourId) {
        const maxDay = Math.max(...Array.from(document.querySelectorAll('.itinerary-day-card')).map(c => parseInt(c.getAttribute('data-day-number')) || 0), 0);
        document.getElementById('dayNumberInput').value = maxDay + 1;
    }
});

// Day Preview on Click
document.querySelectorAll('.itinerary-day-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (e.target.closest('.btn') || e.target.closest('.drag-handle')) return;
        const id = this.getAttribute('data-id');
        fetch(`/admin/tours/itinerary/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showDayPreview(data.itinerary);
                }
            });
    });
});

function showDayPreview(day) {
    const panel = document.getElementById('dayPreviewPanel');
    const meals = day.meals_included ? day.meals_included.join(', ') : 'None';
    const activities = day.activities && day.activities.length > 0 
        ? day.activities.map(a => typeof a === 'object' ? a.name : a).join(', ')
        : 'None';
    
    panel.innerHTML = `
        <h6>Day ${day.day_number} – ${day.title}</h6>
        ${day.short_summary ? `<p class="text-muted">${day.short_summary}</p>` : ''}
        ${day.image ? `<img src="/storage/${day.image}" alt="${day.title}" class="img-fluid rounded mb-3">` : ''}
        <div class="mb-3">
            <strong>Location:</strong> ${day.location || 'N/A'}<br>
            <strong>Meals:</strong> ${meals}<br>
            ${day.accommodation_name ? `<strong>Accommodation:</strong> ${day.accommodation_name} (${day.accommodation_type || 'N/A'})<br>` : ''}
            <strong>Activities:</strong> ${activities}
        </div>
        <div class="border-top pt-3">
            <strong>Description:</strong>
            <p class="small">${day.description || 'No description'}</p>
        </div>
    `;
}

// Toast notification functions (using existing system)
function showSuccessToast(message, title = 'Success') {
    if (typeof showToast === 'function') {
        showToast(message, 'success');
    } else if (typeof showSuccessToast === 'function') {
        showSuccessToast(message, title);
    } else {
        alert(message);
    }
}

function showErrorToast(message, title = 'Error') {
    if (typeof showToast === 'function') {
        showToast(message, 'error');
    } else if (typeof showErrorToast === 'function') {
        showErrorToast(message, title);
    } else {
        alert(message);
    }
}

function showWarningToast(message, title = 'Warning') {
    if (typeof showToast === 'function') {
        showToast(message, 'warning');
    } else if (typeof showWarningToast === 'function') {
        showWarningToast(message, title);
    } else {
        alert(message);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate next day number when opening add modal
    document.getElementById('addDayModal')?.addEventListener('show.bs.modal', function() {
        if (tourId) {
            const maxDay = Math.max(...Array.from(document.querySelectorAll('.itinerary-day-card')).map(c => parseInt(c.getAttribute('data-day-number')) || 0), 0);
            document.getElementById('dayNumberInput').value = maxDay + 1;
        }
    });
    
    // Hide drag handles initially
    document.querySelectorAll('.drag-handle').forEach(handle => {
        handle.style.display = 'none';
    });
});
</script>
@endpush
