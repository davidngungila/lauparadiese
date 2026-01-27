@extends('admin.layouts.app')

@section('title', 'Booking Calendar - ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" />
<style>
  #calendar {
    min-height: 700px;
    font-family: inherit;
  }
  
  .app-calendar-wrapper {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  
  .app-calendar-sidebar {
    width: 320px;
    min-height: 100%;
    background: #f8f9fa;
    padding: 0;
  }
  
  .app-calendar-content {
    flex: 1;
    background: #fff;
  }
  
  /* FullCalendar Customization */
  .fc-header-toolbar {
    padding: 1.5rem;
    margin-bottom: 0 !important;
    border-bottom: 1px solid #dee2e6;
  }
  
  .fc-button {
    background-color: #3ea572 !important;
    border-color: #3ea572 !important;
    color: #fff !important;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 6px;
  }
  
  .fc-button:hover {
    background-color: #2d7a5f !important;
    border-color: #2d7a5f !important;
  }
  
  .fc-button-active {
    background-color: #1a4d3a !important;
    border-color: #1a4d3a !important;
  }
  
  .fc-today-button {
    background-color: #6cbe8f !important;
    border-color: #6cbe8f !important;
  }
  
  .fc-today-button:hover {
    background-color: #3ea572 !important;
  }
  
  .fc-day-today {
    background-color: #e6f4ed !important;
  }
  
  .fc-event {
    cursor: pointer;
    border-radius: 6px;
    padding: 4px 6px;
    border: none !important;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s;
  }
  
  .fc-event:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }
  
  .fc-event-title {
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .fc-event-time {
    font-size: 0.75rem;
    opacity: 0.9;
    margin-top: 2px;
  }
  
  /* Status Colors */
  .fc-event-status-confirmed {
    background-color: #3ea572 !important;
    color: #fff;
  }
  
  .fc-event-status-pending_payment {
    background-color: #ffc107 !important;
    color: #000;
  }
  
  .fc-event-status-cancelled {
    background-color: #dc3545 !important;
    color: #fff;
  }
  
  .fc-event-status-completed {
    background-color: #17a2b8 !important;
    color: #fff;
  }
  
  .fc-event-status-in_progress {
    background-color: #6cbe8f !important;
    color: #fff;
  }
  
  /* Stats Cards */
  .stat-card {
    border-radius: 10px;
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
  }
  
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  
  .stat-card .avatar {
    width: 48px;
    height: 48px;
  }
  
  .stat-card .avatar-initial {
    font-size: 1.5rem;
  }
  
  /* Sidebar */
  .calendar-sidebar-header {
    background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%);
    color: white;
    padding: 1.5rem;
    text-align: center;
  }
  
  .calendar-sidebar-header h4 {
    margin: 0;
    font-weight: 600;
    font-size: 1.25rem;
  }
  
  .calendar-sidebar-content {
    padding: 1.5rem;
  }
  
  .filter-section {
    margin-bottom: 2rem;
  }
  
  .filter-section-title {
    font-weight: 600;
    color: #343a40;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .form-check-input:checked {
    background-color: #3ea572;
    border-color: #3ea572;
  }
  
  .form-check-label {
    font-weight: 500;
    color: #495057;
    cursor: pointer;
  }
  
  /* Advanced Filters */
  .advanced-filters {
    background: #fff;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: 1px solid #dee2e6;
  }
  
  .advanced-filters .form-label {
    font-weight: 600;
    font-size: 0.875rem;
    color: #495057;
    margin-bottom: 0.5rem;
  }
  
  /* Booking Detail Modal */
  .booking-detail-row {
    border-bottom: 1px solid #e9ecef;
    padding: 12px 0;
  }
  
  .booking-detail-row:last-child {
    border-bottom: none;
  }
  
  .booking-detail-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 4px;
  }
  
  .booking-detail-value {
    color: #212529;
    font-size: 0.95rem;
  }
  
  .status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .quick-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 1rem;
  }
  
  .quick-action-btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    transition: all 0.2s;
  }
  
  .quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  @media (max-width: 991.98px) {
    .app-calendar-sidebar {
      display: none;
    }
    
    .stat-card {
      margin-bottom: 1rem;
    }
  }
  
  /* Loading State */
  .calendar-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
  }
  
  .spinner-border-sm {
    width: 1.5rem;
    height: 1.5rem;
    border-width: 0.2em;
  }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="fw-bold mb-1">
            <i class="ri-calendar-2-line me-2"></i>Booking Calendar
          </h4>
          <p class="text-muted mb-0">View and manage all bookings in calendar format</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="ri-list-check me-1"></i>List View
          </a>
          <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i>New Booking
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Stats -->
  <div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card stat-card card-border-shadow-primary">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="ri-calendar-check-line"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
              <small class="text-muted">Total Bookings</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card stat-card card-border-shadow-success">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-success">
                <i class="ri-checkbox-circle-line"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ $stats['confirmed'] ?? 0 }}</h5>
              <small class="text-muted">Confirmed</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card stat-card card-border-shadow-warning">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-warning">
                <i class="ri-time-line"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ $stats['pending'] ?? 0 }}</h5>
              <small class="text-muted">Pending</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card stat-card card-border-shadow-danger">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-danger">
                <i class="ri-close-circle-line"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ $stats['cancelled'] ?? 0 }}</h5>
              <small class="text-muted">Cancelled</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card stat-card card-border-shadow-info">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-info">
                <i class="ri-flag-line"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ $stats['completed'] ?? 0 }}</h5>
              <small class="text-muted">Completed</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card stat-card card-border-shadow-secondary">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar me-3">
              <span class="avatar-initial rounded bg-label-secondary">
                <i class="ri-calendar-event-line"></i>
              </span>
            </div>
            <div>
              <h5 class="mb-0">{{ $stats['today'] ?? 0 }}</h5>
              <small class="text-muted">Today</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Calendar -->
  <div class="card app-calendar-wrapper">
    <div class="row g-0">
      <!-- Calendar Sidebar -->
      <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
        <div class="calendar-sidebar-header">
          <h4><i class="ri-filter-3-line me-2"></i>Filters & Actions</h4>
        </div>
        
        <div class="calendar-sidebar-content">
          <!-- Quick Actions -->
          <div class="mb-4">
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary w-100 mb-2">
              <i class="ri-add-line me-1"></i>New Booking
            </a>
            <button class="btn btn-outline-secondary w-100" onclick="exportCalendar()">
              <i class="ri-download-line me-1"></i>Export Calendar
            </button>
          </div>

          <!-- Status Filters -->
          <div class="filter-section">
            <div class="filter-section-title">
              <i class="ri-checkbox-multiple-line me-1"></i>Booking Status
            </div>
            <div class="form-check form-check-secondary mb-3">
              <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked />
              <label class="form-check-label fw-semibold" for="selectAll">View All</label>
            </div>
            <div class="app-calendar-events-filter">
              <div class="form-check form-check-success mb-3">
                <input class="form-check-input input-filter" type="checkbox" id="select-confirmed" data-value="confirmed" checked />
                <label class="form-check-label" for="select-confirmed">
                  <i class="ri-checkbox-circle-line me-1"></i>Confirmed
                </label>
              </div>
              <div class="form-check form-check-warning mb-3">
                <input class="form-check-input input-filter" type="checkbox" id="select-pending" data-value="pending_payment" checked />
                <label class="form-check-label" for="select-pending">
                  <i class="ri-time-line me-1"></i>Pending Payment
                </label>
              </div>
              <div class="form-check form-check-info mb-3">
                <input class="form-check-input input-filter" type="checkbox" id="select-in-progress" data-value="in_progress" checked />
                <label class="form-check-label" for="select-in-progress">
                  <i class="ri-loader-4-line me-1"></i>In Progress
                </label>
              </div>
              <div class="form-check form-check-danger mb-3">
                <input class="form-check-input input-filter" type="checkbox" id="select-cancelled" data-value="cancelled" checked />
                <label class="form-check-label" for="select-cancelled">
                  <i class="ri-close-circle-line me-1"></i>Cancelled
                </label>
              </div>
              <div class="form-check form-check-info mb-3">
                <input class="form-check-input input-filter" type="checkbox" id="select-completed" data-value="completed" checked />
                <label class="form-check-label" for="select-completed">
                  <i class="ri-flag-line me-1"></i>Completed
                </label>
              </div>
            </div>
          </div>

          <!-- Advanced Filters -->
          <div class="filter-section">
            <div class="filter-section-title">
              <i class="ri-filter-3-line me-1"></i>Advanced Filters
            </div>
            <div class="advanced-filters">
              <div class="mb-3">
                <label class="form-label">Search Bookings</label>
                <input type="text" class="form-control form-control-sm" id="searchBookings" placeholder="Reference, customer, tour...">
              </div>
              <div class="mb-3">
                <label class="form-label">Date Range</label>
                <input type="date" class="form-control form-control-sm mb-2" id="filterDateFrom" placeholder="From">
                <input type="date" class="form-control form-control-sm" id="filterDateTo" placeholder="To">
              </div>
              <div class="mb-3">
                <label class="form-label">Payment Status</label>
                <select class="form-select form-select-sm" id="filterPaymentStatus">
                  <option value="">All</option>
                  <option value="paid">Paid</option>
                  <option value="partial">Partial</option>
                  <option value="unpaid">Unpaid</option>
                </select>
              </div>
              <button class="btn btn-sm btn-outline-primary w-100" onclick="applyAdvancedFilters()">
                <i class="ri-search-line me-1"></i>Apply Filters
              </button>
              <button class="btn btn-sm btn-outline-secondary w-100 mt-2" onclick="resetFilters()">
                <i class="ri-refresh-line me-1"></i>Reset
              </button>
            </div>
          </div>

          <!-- Legend -->
          <div class="filter-section">
            <div class="filter-section-title">
              <i class="ri-palette-line me-1"></i>Color Legend
            </div>
            <div class="small">
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-success me-2" style="width: 20px; height: 20px;"></span>
                <span>Confirmed</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-warning me-2" style="width: 20px; height: 20px;"></span>
                <span>Pending Payment</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-info me-2" style="width: 20px; height: 20px;"></span>
                <span>In Progress</span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-danger me-2" style="width: 20px; height: 20px;"></span>
                <span>Cancelled</span>
              </div>
              <div class="d-flex align-items-center">
                <span class="badge bg-info me-2" style="width: 20px; height: 20px;"></span>
                <span>Completed</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Calendar Content -->
      <div class="col app-calendar-content position-relative">
        <div class="calendar-loading d-none" id="calendarLoading">
          <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <div class="card shadow-none border-0">
          <div class="card-body pb-0">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%); color: white;">
        <h5 class="modal-title" id="bookingDetailsModalLabel">
          <i class="ri-calendar-check-line me-2"></i>Booking Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div id="bookingDetailsContent">
          <!-- Content will be loaded dynamically -->
        </div>
        <div class="quick-actions" id="quickActions">
          <!-- Quick action buttons will be added here -->
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" id="viewBookingLink" class="btn btn-primary" target="_blank">
          <i class="ri-eye-line me-1"></i>View Full Details
        </a>
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  let calendar;
  let currentFilters = {
    status: [],
    search: '',
    dateFrom: '',
    dateTo: '',
    paymentStatus: ''
  };

  const calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    height: 'auto',
    navLinks: true,
    dayMaxEvents: 3,
    moreLinkClick: 'popover',
    locale: 'en',
    firstDay: 1,
    weekNumbers: true,
    weekNumberCalculation: 'ISO',
    eventDisplay: 'block',
    events: function(fetchInfo, successCallback, failureCallback) {
      showLoading();
      const params = new URLSearchParams({
        start: fetchInfo.startStr,
        end: fetchInfo.endStr,
        ...currentFilters
      });
      
      if (currentFilters.status.length > 0) {
        params.set('status', currentFilters.status.join(','));
      }
      
      fetch(`{{ route("admin.bookings.calendar.data") }}?${params}`)
        .then(response => response.json())
        .then(data => {
          hideLoading();
          successCallback(data);
        })
        .catch(error => {
          hideLoading();
          console.error('Error fetching bookings:', error);
          failureCallback(error);
        });
    },
    eventClick: function(info) {
      const booking = info.event.extendedProps;
      
      // Build modal content
      let content = `
        <div class="row g-3">
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Booking Reference</div>
            <div class="booking-detail-value">
              <strong class="text-primary">${booking.booking_reference || 'N/A'}</strong>
            </div>
          </div>
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Status</div>
            <div class="booking-detail-value">
              <span class="status-badge bg-${getStatusColor(booking.status)} text-white">
                ${formatStatus(booking.status)}
              </span>
            </div>
          </div>
          <div class="col-md-12 booking-detail-row">
            <div class="booking-detail-label">Tour Name</div>
            <div class="booking-detail-value">
              <i class="ri-map-pin-line me-1"></i><strong>${info.event.title || 'N/A'}</strong>
            </div>
          </div>
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Departure Date</div>
            <div class="booking-detail-value">
              <i class="ri-calendar-line me-1"></i>${formatDate(info.event.start)}
            </div>
          </div>
          ${info.event.end ? `
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">End Date</div>
            <div class="booking-detail-value">
              <i class="ri-calendar-check-line me-1"></i>${formatDate(info.event.end)}
            </div>
          </div>
          ` : ''}
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Customer Name</div>
            <div class="booking-detail-value">
              <i class="ri-user-line me-1"></i>${booking.customer_name || 'N/A'}
            </div>
          </div>
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Number of Travelers</div>
            <div class="booking-detail-value">
              <i class="ri-group-line me-1"></i><strong>${booking.travelers || 0}</strong> ${(booking.travelers || 0) == 1 ? 'Traveler' : 'Travelers'}
              ${booking.number_of_adults ? `(${booking.number_of_adults} Adults` : ''}
              ${booking.number_of_children ? `, ${booking.number_of_children} Children)` : booking.number_of_adults ? ')' : ''}
            </div>
          </div>
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Customer Email</div>
            <div class="booking-detail-value">
              <i class="ri-mail-line me-1"></i>
              ${booking.customer_email ? `<a href="mailto:${booking.customer_email}">${booking.customer_email}</a>` : 'N/A'}
            </div>
          </div>
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Customer Phone</div>
            <div class="booking-detail-value">
              <i class="ri-phone-line me-1"></i>
              ${booking.customer_phone ? `<a href="tel:${booking.customer_phone}">${booking.customer_phone}</a>` : 'N/A'}
            </div>
          </div>
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Total Price</div>
            <div class="booking-detail-value">
              <strong class="text-success" style="font-size: 1.1rem;">
                ${booking.currency || 'USD'} ${parseFloat(booking.total_price || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
              </strong>
            </div>
          </div>
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Payment Status</div>
            <div class="booking-detail-value">
              <span class="status-badge bg-${getPaymentStatusColor(booking.payment_status)} text-white">
                ${formatPaymentStatus(booking.payment_status)}
              </span>
            </div>
          </div>
          ${booking.pickup_location ? `
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Pickup Location</div>
            <div class="booking-detail-value">
              <i class="ri-map-pin-line me-1"></i>${booking.pickup_location}
            </div>
          </div>
          ` : ''}
          ${booking.dropoff_location ? `
          <div class="col-md-6 booking-detail-row">
            <div class="booking-detail-label">Drop-off Location</div>
            <div class="booking-detail-value">
              <i class="ri-map-pin-line me-1"></i>${booking.dropoff_location}
            </div>
          </div>
          ` : ''}
          ${booking.special_requirements ? `
          <div class="col-12 booking-detail-row">
            <div class="booking-detail-label">Special Requirements</div>
            <div class="booking-detail-value">${booking.special_requirements}</div>
          </div>
          ` : ''}
          ${booking.notes ? `
          <div class="col-12 booking-detail-row">
            <div class="booking-detail-label">Notes</div>
            <div class="booking-detail-value">${booking.notes}</div>
          </div>
          ` : ''}
        </div>
      `;
      
      // Build quick actions
      let quickActions = `
        <a href="/admin/bookings/${info.event.id}" class="btn btn-primary quick-action-btn" target="_blank">
          <i class="ri-eye-line me-1"></i>View Details
        </a>
        <a href="/admin/bookings/${info.event.id}?edit=1" class="btn btn-success quick-action-btn" target="_blank">
          <i class="ri-edit-line me-1"></i>Edit
        </a>
        <a href="/admin/documents/booking/${info.event.id}/confirmation-voucher" class="btn btn-info quick-action-btn" target="_blank">
          <i class="ri-file-download-line me-1"></i>Download PDF
        </a>
        ${booking.customer_email ? `
        <button class="btn btn-warning quick-action-btn" onclick="sendEmail(${info.event.id}, 'confirmation')">
          <i class="ri-mail-line me-1"></i>Send Email
        </button>
        ` : ''}
      `;
      
      // Set modal content
      document.getElementById('bookingDetailsContent').innerHTML = content;
      document.getElementById('quickActions').innerHTML = quickActions;
      document.getElementById('viewBookingLink').href = `/admin/bookings/${info.event.id}`;
      
      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
      modal.show();
      
      info.jsEvent.preventDefault();
    },
    eventDidMount: function(info) {
      // Add status class for styling
      const status = info.event.extendedProps.status;
      if (status) {
        info.el.classList.add(`fc-event-status-${status}`);
      }
      
      // Add tooltip
      const booking = info.event.extendedProps;
      const tooltip = `${info.event.title} - ${booking.booking_reference || ''}\n${booking.customer_name || ''} - ${booking.travelers || 0} travelers`;
      info.el.setAttribute('title', tooltip);
    },
    eventContent: function(arg) {
      const props = arg.event.extendedProps || {};
      return {
        html: `
          <div class="fc-event-title">${arg.event.title}</div>
          <div class="fc-event-time">${props.customer_name || ''} - ${props.travelers || 0} pax</div>
        `
      };
    }
  });

  calendar.render();

  // Filter functionality
  const filters = document.querySelectorAll('.input-filter');
  const selectAll = document.getElementById('selectAll');

  selectAll.addEventListener('change', function() {
    filters.forEach(filter => {
      filter.checked = this.checked;
    });
    applyFilters();
  });

  filters.forEach(filter => {
    filter.addEventListener('change', function() {
      selectAll.checked = Array.from(filters).every(f => f.checked);
      applyFilters();
    });
  });

  function applyFilters() {
    const checkedFilters = Array.from(filters)
      .filter(f => f.checked)
      .map(f => f.dataset.value);
    
    currentFilters.status = checkedFilters;
    calendar.refetchEvents();
  }

  function applyAdvancedFilters() {
    currentFilters.search = document.getElementById('searchBookings').value;
    currentFilters.dateFrom = document.getElementById('filterDateFrom').value;
    currentFilters.dateTo = document.getElementById('filterDateTo').value;
    currentFilters.paymentStatus = document.getElementById('filterPaymentStatus').value;
    calendar.refetchEvents();
  }

  function resetFilters() {
    document.getElementById('searchBookings').value = '';
    document.getElementById('filterDateFrom').value = '';
    document.getElementById('filterDateTo').value = '';
    document.getElementById('filterPaymentStatus').value = '';
    currentFilters = {
      status: Array.from(filters).filter(f => f.checked).map(f => f.dataset.value),
      search: '',
      dateFrom: '',
      dateTo: '',
      paymentStatus: ''
    };
    calendar.refetchEvents();
  }

  function showLoading() {
    document.getElementById('calendarLoading').classList.remove('d-none');
  }

  function hideLoading() {
    document.getElementById('calendarLoading').classList.add('d-none');
  }

  function exportCalendar() {
    const start = calendar.view.activeStart.toISOString().split('T')[0];
    const end = calendar.view.activeEnd.toISOString().split('T')[0];
    const params = new URLSearchParams({
      start: start,
      end: end,
      export: 'pdf'
    });
    window.open(`{{ route("admin.bookings.calendar.data") }}?${params}`, '_blank');
  }

  function sendEmail(bookingId, type) {
    // Implement email sending
    alert('Email sending feature will be implemented');
  }

  // Helper functions
  function formatDate(date) {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  }

  function formatStatus(status) {
    const statusMap = {
      'confirmed': 'Confirmed',
      'pending_payment': 'Pending Payment',
      'cancelled': 'Cancelled',
      'completed': 'Completed',
      'in_progress': 'In Progress'
    };
    return statusMap[status] || status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
  }

  function getStatusColor(status) {
    const colorMap = {
      'confirmed': 'success',
      'pending_payment': 'warning',
      'cancelled': 'danger',
      'completed': 'info',
      'in_progress': 'info'
    };
    return colorMap[status] || 'secondary';
  }

  function formatPaymentStatus(status) {
    const statusMap = {
      'paid': 'Paid',
      'partial': 'Partial',
      'unpaid': 'Unpaid'
    };
    return statusMap[status] || status || 'N/A';
  }

  function getPaymentStatusColor(status) {
    const colorMap = {
      'paid': 'success',
      'partial': 'warning',
      'unpaid': 'danger'
    };
    return colorMap[status] || 'secondary';
  }
});
</script>
@endpush
