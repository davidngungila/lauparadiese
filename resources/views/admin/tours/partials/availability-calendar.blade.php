@php
    use Carbon\Carbon;
    $grouped = collect($availability)->groupBy(function ($item) {
        return Carbon::parse($item['date'])->format('F Y');
    });
@endphp

<div class="mb-3">
    <h5 class="mb-1">
        Availability Calendar for: <strong>{{ $tour->name }}</strong>
    </h5>
    <p class="text-body-secondary mb-0">
        Tour ID: {{ $tour->id }}
        @if($tour->destination)
            • Destination: <span class="badge bg-label-info">{{ $tour->destination->name }}</span>
        @endif
    </p>
</div>

@foreach($grouped as $month => $days)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ $month }}</h6>
            <small class="text-body-secondary">
                Showing {{ $days->count() }} day(s)
            </small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 140px;">Date</th>
                            <th style="width: 120px;">Day</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 120px;">Booked</th>
                            <th style="width: 140px;">Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($days as $item)
                            @php
                                $date = Carbon::parse($item['date']);
                                $status = $item['status'];
                                $badgeClass = $status === 'full' ? 'bg-label-danger' : 'bg-label-success';
                            @endphp
                            <tr>
                                <td class="small text-nowrap">{{ $date->format('Y-m-d') }}</td>
                                <td class="small text-nowrap">{{ $date->format('l') }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="small">
                                    <span class="badge bg-label-warning">{{ $item['booked'] }}</span>
                                </td>
                                <td class="small">
                                    <span class="badge bg-label-primary">{{ $item['available'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach

@if($grouped->isEmpty())
    <div class="text-center py-4">
        <i class="ri-calendar-check-line ri-48px text-muted mb-2"></i>
        <p class="mb-0 text-muted">No availability data found for this tour.</p>
    </div>
@endif






