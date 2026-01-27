<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends BaseAdminController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display notification sending interface
     */
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();
        $bookings = Booking::where('status', 'confirmed')->latest()->limit(100)->get();
        $roles = DB::table('roles')->orderBy('name')->get();

        // Get recent notifications sent (if Notification model exists)
        $recentNotifications = collect([]);
        if (class_exists(\App\Models\Notification::class)) {
            $recentNotifications = \App\Models\Notification::with('user')
                ->latest()
                ->limit(20)
                ->get();
        }

        return view('admin.notifications.index', compact('users', 'bookings', 'roles', 'recentNotifications'));
    }

    /**
     * Send notification to users
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:users,roles,bookings,manual',
            'user_ids' => 'required_if:recipient_type,users|array',
            'user_ids.*' => 'exists:users,id',
            'role_names' => 'required_if:recipient_type,roles|array',
            'booking_ids' => 'required_if:recipient_type,bookings|array',
            'booking_ids.*' => 'exists:bookings,id',
            'manual_emails' => 'required_if:recipient_type,manual|string',
            'manual_phones' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'channels' => 'required|array',
            'channels.*' => 'in:email,sms,in_app',
            'link' => 'nullable|url',
            'send_immediately' => 'nullable|boolean',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $recipientIds = [];
        $recipientEmails = [];
        $recipientPhones = [];

        // Get recipient IDs based on type
        switch ($validated['recipient_type']) {
            case 'users':
                $recipientIds = $validated['user_ids'];
                break;
            
            case 'roles':
                $recipientIds = User::whereHas('roles', function($q) use ($validated) {
                    $q->whereIn('name', $validated['role_names']);
                })->pluck('id')->toArray();
                break;
            
            case 'bookings':
                $bookings = Booking::whereIn('id', $validated['booking_ids'])->get();
                foreach ($bookings as $booking) {
                    if ($booking->user_id) {
                        $recipientIds[] = $booking->user_id;
                    } else {
                        $recipientEmails[] = $booking->customer_email;
                        $recipientPhones[] = $booking->customer_phone;
                    }
                }
                break;
            
            case 'manual':
                $emails = array_filter(array_map('trim', explode(',', $validated['manual_emails'])));
                $phones = $request->filled('manual_phones') 
                    ? array_filter(array_map('trim', explode(',', $validated['manual_phones'])))
                    : [];
                
                // Try to find users by email
                foreach ($emails as $email) {
                    $user = User::where('email', $email)->first();
                    if ($user) {
                        $recipientIds[] = $user->id;
                    } else {
                        $recipientEmails[] = $email;
                    }
                }
                
                // Try to find users by phone
                foreach ($phones as $phone) {
                    $user = User::where('phone', $phone)->orWhere('mobile', $phone)->first();
                    if ($user && !in_array($user->id, $recipientIds)) {
                        $recipientIds[] = $user->id;
                    } else {
                        $recipientPhones[] = $phone;
                    }
                }
                break;
        }

        $sentCount = 0;
        $errors = [];

        // Send to user IDs
        if (!empty($recipientIds)) {
            $uniqueIds = array_unique($recipientIds);
            
            foreach ($uniqueIds as $userId) {
                try {
                    $user = User::find($userId);
                    if (!$user) continue;

                    $data = ['skip_sms' => !in_array('sms', $validated['channels'])];
                    
                    if (in_array('in_app', $validated['channels'])) {
                        $this->notificationService->notify(
                            $userId,
                            $validated['message'],
                            $validated['link'] ?? null,
                            $validated['subject'],
                            $data
                        );
                    } else {
                        // Send email only
                        if (in_array('email', $validated['channels']) && $user->email) {
                            $this->notificationService->notifyPhone(
                                $user->phone ?? $user->mobile,
                                $validated['message'],
                                $user->email,
                                $validated['subject'],
                                $data
                            );
                        }
                    }
                    
                    $sentCount++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to send to user ID {$userId}: " . $e->getMessage();
                }
            }
        }

        // Send to manual emails/phones
        foreach ($recipientEmails as $email) {
            try {
                if (in_array('email', $validated['channels'])) {
                    $this->notificationService->notifyPhone(
                        null,
                        $validated['message'],
                        $email,
                        $validated['subject'],
                        ['skip_sms' => true]
                    );
                    $sentCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to send to {$email}: " . $e->getMessage();
            }
        }

        foreach ($recipientPhones as $phone) {
            try {
                if (in_array('sms', $validated['channels'])) {
                    $this->notificationService->sendSMS($phone, $validated['message']);
                    $sentCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to send SMS to {$phone}: " . $e->getMessage();
            }
        }

        $message = "Notification sent to {$sentCount} recipient(s).";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " error(s) occurred.";
        }

        return $this->successResponse($message, route('admin.notifications.index'));
    }

    /**
     * Send bulk notification
     */
    public function bulkSend(Request $request)
    {
        $validated = $request->validate([
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'channels' => 'required|array',
            'channels.*' => 'in:email,sms,in_app',
            'link' => 'nullable|url',
        ]);

        $sentCount = 0;
        foreach ($validated['recipient_ids'] as $userId) {
            try {
                $data = ['skip_sms' => !in_array('sms', $validated['channels'])];
                
                $this->notificationService->notify(
                    $userId,
                    $validated['message'],
                    $validated['link'] ?? null,
                    $validated['subject'],
                    $data
                );
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error("Failed to send notification to user {$userId}: " . $e->getMessage());
            }
        }

        return $this->successResponse("Notifications sent to {$sentCount} user(s)!", route('admin.notifications.index'));
    }

    /**
     * Get notification statistics
     */
    public function stats()
    {
        $stats = [
            'total_sent' => 0,
            'today_sent' => 0,
            'unread' => 0,
            'by_channel' => [
                'email' => 0,
                'sms' => 0,
                'in_app' => 0,
            ],
        ];

        if (class_exists(\App\Models\Notification::class)) {
            $stats = [
                'total_sent' => \App\Models\Notification::count(),
                'today_sent' => \App\Models\Notification::whereDate('created_at', today())->count(),
                'unread' => \App\Models\Notification::where('is_read', false)->count(),
                'by_channel' => [
                    'email' => \App\Models\Notification::whereNotNull('email_sent_at')->count(),
                    'sms' => \App\Models\Notification::whereNotNull('sms_sent_at')->count(),
                    'in_app' => \App\Models\Notification::count(),
                ],
            ];
        }

        return response()->json($stats);
    }
}
