/**
 * Calendar Events Data
 */
const calendarEvents = {
  events: [],
  
  // Fetch events from server
  fetchEvents: function(start, end) {
    return fetch(`/admin/bookings/calendar/data?start=${start}&end=${end}`)
      .then(res => res.json())
      .then(data => {
        this.events = data;
        return data;
      })
      .catch(err => {
        console.error('Error fetching events:', err);
        return [];
      });
  },
  
  // Get filtered events based on status
  getFilteredEvents: function() {
    const filters = this.getActiveFilters();
    if (filters.length === 0) return [];
    
    return this.events.filter(event => {
      const status = event.extendedProps?.status || event.status;
      return filters.includes(status) || filters.includes('all');
    });
  },
  
  // Get active filter values
  getActiveFilters: function() {
    const filters = [];
    document.querySelectorAll('.input-filter:checked').forEach(checkbox => {
      filters.push(checkbox.getAttribute('data-value'));
    });
    return filters;
  },
  
  // Add event
  addEvent: function(eventData) {
    return fetch('/admin/bookings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(eventData)
    })
    .then(res => res.json())
    .catch(err => {
      console.error('Error adding event:', err);
      throw err;
    });
  },
  
  // Update event
  updateEvent: function(eventId, eventData) {
    return fetch(`/admin/bookings/${eventId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(eventData)
    })
    .then(res => res.json())
    .catch(err => {
      console.error('Error updating event:', err);
      throw err;
    });
  },
  
  // Delete event
  deleteEvent: function(eventId) {
    return fetch(`/admin/bookings/${eventId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })
    .then(res => res.json())
    .catch(err => {
      console.error('Error deleting event:', err);
      throw err;
    });
  },
  
  // Get event by ID
  getEventById: function(eventId) {
    return this.events.find(event => event.id == eventId);
  }
};






