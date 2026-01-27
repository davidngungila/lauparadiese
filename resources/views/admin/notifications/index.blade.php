@extends('admin.layouts.app')

@section('title', 'Send Notifications - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-notification-line me-2"></i>Send Notifications
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-send-plane-line me-2"></i>Compose Notification</h5>
                </div>
                <div class="card-body">
                    <form id="sendNotificationForm" method="POST" action="{{ route('admin.notifications.send') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Recipient Type <span class="text-danger">*</span></label>
                            <select name="recipient_type" id="recipientType" class="form-select" required>
                                <option value="users">Specific Users</option>
                                <option value="roles">By Role</option>
                                <option value="bookings">By Booking</option>
                                <option value="manual">Manual Entry (Email/Phone)</option>
                            </select>
                        </div>

                        <!-- Users Selection -->
                        <div class="mb-3" id="usersSelection">
                            <label class="form-label">Select Users <span class="text-danger">*</span></label>
                            <select name="user_ids[]" id="userIds" class="form-select" multiple size="8">
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple users</small>
                        </div>

                        <!-- Roles Selection -->
                        <div class="mb-3" id="rolesSelection" style="display: none;">
                            <label class="form-label">Select Roles <span class="text-danger">*</span></label>
                            <select name="role_names[]" id="roleNames" class="form-select" multiple size="6">
                                @foreach($roles ?? [] as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple roles</small>
                        </div>

                        <!-- Bookings Selection -->
                        <div class="mb-3" id="bookingsSelection" style="display: none;">
                            <label class="form-label">Select Bookings <span class="text-danger">*</span></label>
                            <select name="booking_ids[]" id="bookingIds" class="form-select" multiple size="6">
                                @foreach($bookings ?? [] as $booking)
                                    <option value="{{ $booking->id }}">
                                        {{ $booking->booking_reference }} - {{ $booking->customer_name }} ({{ $booking->customer_email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple bookings</small>
                        </div>

                        <!-- Manual Entry -->
                        <div class="mb-3" id="manualSelection" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Email Addresses <span class="text-danger">*</span></label>
                                <textarea name="manual_emails" id="manualEmails" class="form-control" rows="3" placeholder="Enter email addresses separated by commas"></textarea>
                                <small class="text-muted">Separate multiple emails with commas</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Numbers</label>
                                <textarea name="manual_phones" id="manualPhones" class="form-control" rows="2" placeholder="Enter phone numbers separated by commas"></textarea>
                                <small class="text-muted">Separate multiple phones with commas</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="8" required placeholder="Enter your notification message..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notification Channels <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="channels[]" value="email" id="channelEmail" checked>
                                <label class="form-check-label" for="channelEmail">
                                    <i class="ri-mail-line me-1"></i>Email
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="channels[]" value="sms" id="channelSMS">
                                <label class="form-check-label" for="channelSMS">
                                    <i class="ri-message-3-line me-1"></i>SMS
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="channels[]" value="in_app" id="channelInApp" checked>
                                <label class="form-check-label" for="channelInApp">
                                    <i class="ri-notification-line me-1"></i>In-App Notification
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link (Optional)</label>
                            <input type="url" name="link" class="form-control" placeholder="https://example.com/page">
                            <small class="text-muted">Link to include in notification</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ri-send-plane-line me-1"></i>Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Users</span>
                        <strong>{{ count($users ?? []) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Active Bookings</span>
                        <strong>{{ count($bookings ?? []) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Available Roles</span>
                        <strong>{{ count($roles ?? []) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Notification Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="ri-information-line me-1"></i>Notification Channels</h6>
                        <ul class="mb-0 small">
                            <li><strong>Email:</strong> Sends email notification</li>
                            <li><strong>SMS:</strong> Sends SMS text message</li>
                            <li><strong>In-App:</strong> Creates in-app notification</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="ri-alert-line me-1"></i>Recipient Types</h6>
                        <ul class="mb-0 small">
                            <li><strong>Users:</strong> Select specific users</li>
                            <li><strong>Roles:</strong> Send to all users with specific roles</li>
                            <li><strong>Bookings:</strong> Send to booking customers</li>
                            <li><strong>Manual:</strong> Enter email/phone directly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#recipientType').on('change', function() {
        const type = $(this).val();
        
        // Hide all selections
        $('#usersSelection, #rolesSelection, #bookingsSelection, #manualSelection').hide();
        $('#userIds, #roleNames, #bookingIds, #manualEmails').removeAttr('required');
        
        // Show relevant selection
        switch(type) {
            case 'users':
                $('#usersSelection').show();
                $('#userIds').attr('required', true);
                break;
            case 'roles':
                $('#rolesSelection').show();
                $('#roleNames').attr('required', true);
                break;
            case 'bookings':
                $('#bookingsSelection').show();
                $('#bookingIds').attr('required', true);
                break;
            case 'manual':
                $('#manualSelection').show();
                $('#manualEmails').attr('required', true);
                break;
        }
    });

    // Form validation
    $('#sendNotificationForm').on('submit', function(e) {
        const recipientType = $('#recipientType').val();
        let hasSelection = false;

        switch(recipientType) {
            case 'users':
                hasSelection = $('#userIds').val() && $('#userIds').val().length > 0;
                break;
            case 'roles':
                hasSelection = $('#roleNames').val() && $('#roleNames').val().length > 0;
                break;
            case 'bookings':
                hasSelection = $('#bookingIds').val() && $('#bookingIds').val().length > 0;
                break;
            case 'manual':
                hasSelection = $('#manualEmails').val().trim().length > 0;
                break;
        }

        if (!hasSelection) {
            e.preventDefault();
            alert('Please select at least one recipient');
            return false;
        }

        const channels = $('input[name="channels[]"]:checked').length;
        if (channels === 0) {
            e.preventDefault();
            alert('Please select at least one notification channel');
            return false;
        }
    });
});
</script>
@endpush
@endsection

