<div class="mb-3">
    <h5 class="mb-1">
        Tour Details: <strong>{{ $tour->name }}</strong>
    </h5>
    <p class="text-body-secondary mb-0">
        ID: {{ $tour->id }}
        @if($tour->destination)
            • Destination: <span class="badge bg-label-info">{{ $tour->destination->name }}</span>
        @endif
    </p>
</div>

<div class="row g-4 mb-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-2">Overview</h6>
                <p class="small mb-1">
                    <strong>Duration:</strong>
                    {{ $stats['duration_days'] ?? $tour->duration_days ?? 0 }} days
                </p>
                <p class="small mb-1">
                    <strong>Base Price:</strong>
                    {{ $stats['avg_price'] !== null ? number_format($stats['avg_price'], 2) : 'N/A' }}
                </p>
                <p class="small mb-0">
                    <strong>Rating:</strong>
                    {{ $tour->rating !== null ? number_format($tour->rating, 1) . '/5' : 'Not rated' }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-2">Bookings</h6>
                <p class="small mb-1">
                    <strong>Total Bookings:</strong>
                    {{ $stats['total_bookings'] ?? 0 }}
                </p>
                <p class="small mb-1">
                    <strong>Upcoming Bookings:</strong>
                    {{ $stats['upcoming_bookings'] ?? 0 }}
                </p>
                <p class="small mb-0">
                    <strong>Total Travelers:</strong>
                    {{ $stats['total_travelers'] ?? 0 }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-2">Status</h6>
                <p class="small mb-1">
                    <strong>Featured:</strong>
                    @if($tour->is_featured)
                        <span class="badge bg-label-warning">Yes</span>
                    @else
                        <span class="badge bg-label-secondary">No</span>
                    @endif
                </p>
                <p class="small mb-0">
                    <strong>Created At:</strong>
                    {{ $tour->created_at?->format('Y-m-d H:i') ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Description</h6>
    </div>
    <div class="card-body">
        @if($tour->description)
            <p class="small mb-0">{{ $tour->description }}</p>
        @else
            <p class="small text-muted mb-0">No description available for this tour.</p>
        @endif
    </div>
    @if($tour->excerpt)
        <div class="card-footer">
            <small class="text-body-secondary">
                Excerpt: {{ $tour->excerpt }}
            </small>
        </div>
    @endif
</div>






