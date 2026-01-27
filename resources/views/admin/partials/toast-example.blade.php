{{-- Example: How to use Toast Notifications in Forms --}}

{{-- Method 1: Automatic toast from session (already implemented in layout) --}}
{{-- Just use: return back()->with('success', 'Message'); --}}

{{-- Method 2: Add data attributes to form for automatic toast --}}
<form method="POST" action="{{ route('admin.bookings.store') }}" data-show-toast="true" data-toast-type="success" data-toast-message="Booking created successfully!" data-toast-title="Success">
    @csrf
    {{-- Form fields --}}
</form>

{{-- Method 3: Use JavaScript functions directly --}}
<button onclick="showSuccessToast('Operation completed successfully!', 'Success')">Success Toast</button>
<button onclick="showErrorToast('Something went wrong!', 'Error')">Error Toast</button>
<button onclick="showWarningToast('Please check your input!', 'Warning')">Warning Toast</button>
<button onclick="showInfoToast('New information available!', 'Information')">Info Toast</button>

{{-- Method 4: AJAX operations --}}
<script>
    // After AJAX success
    fetch('/admin/bookings', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message, 'Success');
        } else {
            showErrorToast(data.message, 'Error');
        }
    });
</script>




