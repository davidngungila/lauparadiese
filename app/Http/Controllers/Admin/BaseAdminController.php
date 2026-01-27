<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BaseAdminController extends Controller
{
    /**
     * Return success response with redirect
     */
    protected function successResponse(string $message, string $redirectRoute = null): RedirectResponse
    {
        if ($redirectRoute) {
            return redirect($redirectRoute)->with('success', $message);
        }
        return back()->with('success', $message);
    }

    /**
     * Return error response with redirect
     */
    protected function errorResponse(string $message, string $redirectRoute = null): RedirectResponse
    {
        if ($redirectRoute) {
            return redirect($redirectRoute)->with('error', $message);
        }
        return back()->with('error', $message);
    }

    /**
     * Return warning response with redirect
     */
    protected function warningResponse(string $message, string $redirectRoute = null): RedirectResponse
    {
        if ($redirectRoute) {
            return redirect($redirectRoute)->with('warning', $message);
        }
        return back()->with('warning', $message);
    }

    /**
     * Return info response with redirect
     */
    protected function infoResponse(string $message, string $redirectRoute = null): RedirectResponse
    {
        if ($redirectRoute) {
            return redirect($redirectRoute)->with('info', $message);
        }
        return back()->with('info', $message);
    }

    /**
     * Send success notification (toast + in-app notification)
     * 
     * @param string $message Notification message
     * @param string|null $title Notification title
     * @param string|null $link Optional link for notification
     * @param array|int|null $userIds User IDs to notify (default: current user)
     * @return void
     */
    protected function notifySuccess(string $message, ?string $title = null, ?string $link = null, $userIds = null): void
    {
        $userIds = $userIds ?? (Auth::check() ? [Auth::id()] : []);
        
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        try {
            $notificationService = app(NotificationService::class);
            $notificationService->notify(
                $userIds,
                $message,
                $link,
                $title ?? 'Success',
                ['skip_sms' => true] // Skip SMS for admin notifications
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('Failed to send success notification: ' . $e->getMessage());
        }
    }

    /**
     * Send error notification (toast + in-app notification)
     * 
     * @param string $message Notification message
     * @param string|null $title Notification title
     * @param string|null $link Optional link for notification
     * @param array|int|null $userIds User IDs to notify (default: current user)
     * @return void
     */
    protected function notifyError(string $message, ?string $title = null, ?string $link = null, $userIds = null): void
    {
        $userIds = $userIds ?? (Auth::check() ? [Auth::id()] : []);
        
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        try {
            $notificationService = app(NotificationService::class);
            $notificationService->notify(
                $userIds,
                $message,
                $link,
                $title ?? 'Error',
                ['skip_sms' => true] // Skip SMS for admin notifications
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('Failed to send error notification: ' . $e->getMessage());
        }
    }

    /**
     * Send warning notification (toast + in-app notification)
     * 
     * @param string $message Notification message
     * @param string|null $title Notification title
     * @param string|null $link Optional link for notification
     * @param array|int|null $userIds User IDs to notify (default: current user)
     * @return void
     */
    protected function notifyWarning(string $message, ?string $title = null, ?string $link = null, $userIds = null): void
    {
        $userIds = $userIds ?? (Auth::check() ? [Auth::id()] : []);
        
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        try {
            $notificationService = app(NotificationService::class);
            $notificationService->notify(
                $userIds,
                $message,
                $link,
                $title ?? 'Warning',
                ['skip_sms' => true] // Skip SMS for admin notifications
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('Failed to send warning notification: ' . $e->getMessage());
        }
    }

    /**
     * Send info notification (toast + in-app notification)
     * 
     * @param string $message Notification message
     * @param string|null $title Notification title
     * @param string|null $link Optional link for notification
     * @param array|int|null $userIds User IDs to notify (default: current user)
     * @return void
     */
    protected function notifyInfo(string $message, ?string $title = null, ?string $link = null, $userIds = null): void
    {
        $userIds = $userIds ?? (Auth::check() ? [Auth::id()] : []);
        
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        try {
            $notificationService = app(NotificationService::class);
            $notificationService->notify(
                $userIds,
                $message,
                $link,
                $title ?? 'Information',
                ['skip_sms' => true] // Skip SMS for admin notifications
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('Failed to send info notification: ' . $e->getMessage());
        }
    }
}
