/**
 * FullCalendar Integration
 */
document.addEventListener('DOMContentLoaded', function() {
  let calendar;
  let selectedEvent = null;
  
  // Initialize calendar
  function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      editable: true,
      dayMaxEvents: 3,
      navLinks: true,
      eventResize: handleEventResize,
      eventDrop: handleEventDrop,
      selectable: true,
      selectMirror: true,
      select: handleDateSelect,
      eventClick: handleEventClick,
      events: function(fetchInfo, successCallback, failureCallback) {
        calendarEvents.fetchEvents(fetchInfo.startStr, fetchInfo.endStr)
          .then(events => {
            const filteredEvents = filterEvents(events);
            successCallback(filteredEvents);
          })
          .catch(err => {
            failureCallback(err);
          });
      },
      eventContent: function(arg) {
        const props = arg.event.extendedProps || {};
        const status = props.status || 'pending_payment';
        const statusColors = {
          'confirmed': '#28a745',
          'pending_payment': '#ffc107',
          'cancelled': '#dc3545',
          'completed': '#17a2b8'
        };
        
        return {
          html: `
            <div class="fc-event-title" style="font-weight: 600;">${arg.event.title}</div>
            <div class="fc-event-time">${props.customer_name || ''} - ${props.travelers || 0} travelers</div>
            <div class="fc-event-status" style="font-size: 10px; opacity: 0.8;">${status.replace('_', ' ').toUpperCase()}</div>
          `,
          backgroundColor: statusColors[status] || '#6c757d'
        };
      }
    });
    
    calendar.render();
    initSidebarCalendar();
    initFilters();
  }
  
  // Filter events based on active filters
  function filterEvents(events) {
    const filters = calendarEvents.getActiveFilters();
    if (filters.includes('all') || filters.length === 0) return events;
    
    return events.filter(event => {
      const status = event.extendedProps?.status || event.status;
      return filters.includes(status);
    });
  }
  
  // Initialize sidebar calendar (flatpickr)
  function initSidebarCalendar() {
    const inlineCalendar = document.querySelector('.inline-calendar');
    if (!inlineCalendar) return;
    
    flatpickr(inlineCalendar, {
      inline: true,
      onChange: function(selectedDates, dateStr, instance) {
        if (selectedDates.length > 0) {
          calendar.gotoDate(selectedDates[0]);
        }
      }
    });
  }
  
  // Initialize filters
  function initFilters() {
    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    const inputFilters = document.querySelectorAll('.input-filter');
    
    selectAll.addEventListener('change', function() {
      inputFilters.forEach(filter => {
        filter.checked = this.checked;
      });
      refreshCalendar();
    });
    
    // Individual filters
    inputFilters.forEach(filter => {
      filter.addEventListener('change', function() {
        if (!this.checked) {
          selectAll.checked = false;
        } else {
          // Check if all filters are checked
          const allChecked = Array.from(inputFilters).every(f => f.checked);
          selectAll.checked = allChecked;
        }
        refreshCalendar();
      });
    });
  }
  
  // Refresh calendar with filtered events
  function refreshCalendar() {
    calendar.refetchEvents();
  }
  
  // Handle date select
  function handleDateSelect(selectInfo) {
    // Redirect to create booking with pre-filled date
    const startDate = selectInfo.startStr.split('T')[0];
    window.location.href = `/admin/bookings/create?departure_date=${startDate}`;
  }
  
  // Handle event click
  function handleEventClick(clickInfo) {
    selectedEvent = clickInfo.event;
    const eventId = clickInfo.event.id;
    
    // Fetch full booking details
    fetch(`/admin/bookings/${eventId}`)
      .then(res => res.json())
      .then(data => {
        populateEventForm(data.booking || data);
        const offcanvas = new bootstrap.Offcanvas(document.getElementById('addEventSidebar'));
        offcanvas.show();
      })
      .catch(err => {
        console.error('Error fetching booking details:', err);
        // Use event data if API fails
        populateEventFormFromEvent(clickInfo.event);
        const offcanvas = new bootstrap.Offcanvas(document.getElementById('addEventSidebar'));
        offcanvas.show();
      });
  }
  
  // Populate form from booking data
  function populateEventForm(booking) {
    document.getElementById('eventId').value = booking.id;
    document.getElementById('eventTitle').value = booking.tour?.name || `Tour #${booking.tour_id}`;
    document.getElementById('eventReference').value = booking.booking_reference || '';
    document.getElementById('eventCustomer').value = booking.customer_name || '';
    document.getElementById('eventTravelers').value = booking.travelers || 0;
    document.getElementById('eventStartDate').value = booking.departure_date || '';
    document.getElementById('eventPrice').value = booking.total_price ? `$${parseFloat(booking.total_price).toFixed(2)}` : '';
    document.getElementById('eventEmail').value = booking.customer_email || '';
    document.getElementById('eventPhone').value = booking.customer_phone || '';
    document.getElementById('eventDescription').value = booking.notes || booking.special_requirements || '';
    
    // Set status
    const statusSelect = document.getElementById('eventLabel');
    statusSelect.value = booking.status || 'pending_payment';
    
    // Update view button
    const viewBtn = document.getElementById('viewBookingBtn');
    viewBtn.href = `/admin/bookings/${booking.id}`;
    viewBtn.classList.remove('d-none');
    
    // Initialize flatpickr for date
    if (window.eventStartDatePicker) {
      window.eventStartDatePicker.destroy();
    }
    window.eventStartDatePicker = flatpickr(document.getElementById('eventStartDate'), {
      dateFormat: 'Y-m-d',
      defaultDate: booking.departure_date || new Date()
    });
    
    // Initialize select2 for status
    if ($('#eventLabel').hasClass('select2-hidden-accessible')) {
      $('#eventLabel').select2('destroy');
    }
    $('#eventLabel').select2({
      dropdownParent: $('#addEventSidebar')
    });
  }
  
  // Populate form from event data
  function populateEventFormFromEvent(event) {
    const props = event.extendedProps || {};
    document.getElementById('eventId').value = event.id;
    document.getElementById('eventTitle').value = event.title;
    document.getElementById('eventReference').value = props.booking_reference || '';
    document.getElementById('eventCustomer').value = props.customer_name || '';
    document.getElementById('eventTravelers').value = props.travelers || 0;
    document.getElementById('eventStartDate').value = event.startStr.split('T')[0];
    document.getElementById('eventPrice').value = props.total_price ? `$${parseFloat(props.total_price).toFixed(2)}` : '';
    document.getElementById('eventEmail').value = props.customer_email || '';
    document.getElementById('eventPhone').value = props.customer_phone || '';
    document.getElementById('eventDescription').value = props.notes || '';
    
    const statusSelect = document.getElementById('eventLabel');
    statusSelect.value = props.status || 'pending_payment';
    
    const viewBtn = document.getElementById('viewBookingBtn');
    viewBtn.href = `/admin/bookings/${event.id}`;
    viewBtn.classList.remove('d-none');
    
    if (window.eventStartDatePicker) {
      window.eventStartDatePicker.destroy();
    }
    window.eventStartDatePicker = flatpickr(document.getElementById('eventStartDate'), {
      dateFormat: 'Y-m-d',
      defaultDate: event.startStr
    });
    
    if ($('#eventLabel').hasClass('select2-hidden-accessible')) {
      $('#eventLabel').select2('destroy');
    }
    $('#eventLabel').select2({
      dropdownParent: $('#addEventSidebar')
    });
  }
  
  // Handle event resize
  function handleEventResize(resizeInfo) {
    const eventId = resizeInfo.event.id;
    const newStart = resizeInfo.event.startStr;
    const newEnd = resizeInfo.event.endStr;
    
    updateEventDate(eventId, newStart, newEnd);
  }
  
  // Handle event drop
  function handleEventDrop(dropInfo) {
    const eventId = dropInfo.event.id;
    const newStart = dropInfo.event.startStr;
    const newEnd = dropInfo.event.endStr;
    
    updateEventDate(eventId, newStart, newEnd);
  }
  
  // Update event date
  function updateEventDate(eventId, startDate, endDate) {
    fetch(`/admin/bookings/${eventId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        departure_date: startDate.split('T')[0]
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        calendar.refetchEvents();
      } else {
        // Revert on error
        calendar.refetchEvents();
        alert('Error updating booking date');
      }
    })
    .catch(err => {
      console.error('Error updating date:', err);
      calendar.refetchEvents();
      alert('Error updating booking date');
    });
  }
  
  // Update event button handler
  document.getElementById('updateEventBtn')?.addEventListener('click', function() {
    const eventId = document.getElementById('eventId').value;
    const status = document.getElementById('eventLabel').value;
    const departureDate = document.getElementById('eventStartDate').value;
    
    if (!eventId) return;
    
    const updateData = {
      status: status,
      departure_date: departureDate
    };
    
    fetch(`/admin/bookings/${eventId}/status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(updateData)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        calendar.refetchEvents();
        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('addEventSidebar'));
        offcanvas.hide();
        // Show success message
        if (typeof toastr !== 'undefined') {
          toastr.success('Booking updated successfully');
        }
      } else {
        alert('Error updating booking: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(err => {
      console.error('Error updating booking:', err);
      alert('Error updating booking');
    });
  });
  
  // Close offcanvas handler
  document.getElementById('addEventSidebar')?.addEventListener('hidden.bs.offcanvas', function() {
    document.getElementById('eventForm').reset();
    document.getElementById('eventId').value = '';
    document.getElementById('viewBookingBtn').classList.add('d-none');
    if (window.eventStartDatePicker) {
      window.eventStartDatePicker.destroy();
      window.eventStartDatePicker = null;
    }
    selectedEvent = null;
  });
  
  // Initialize calendar
  initCalendar();
});


