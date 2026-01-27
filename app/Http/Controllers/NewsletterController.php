<?php

namespace App\Http\Controllers;

use App\Jobs\SendSubscriberVerificationEmail;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    /**
     * Handle a new newsletter subscription request.
     * Implements a double opt-in mechanism.
     */
    public function subscribe(Request $request): RedirectResponse|JsonResponse
    {
        // 1. Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns|max:255',
        ]);

        if ($validator->fails()) {
            return $this->handleError($request, 'Please provide a valid email address.');
        }

        $validated = $validator->validated();
        $email = $validated['email'];

        // 2. Check for existing, verified subscriber
        $existingSubscriber = Subscriber::where('email', $email)->whereNotNull('verified_at')->first();
        if ($existingSubscriber) {
            return $this->handleError($request, 'This email address is already subscribed!');
        }

        try {
            // 3. Create or update the subscriber with a new verification token
            $subscriber = Subscriber::updateOrCreate(
                ['email' => $email],
                ['verification_token' => Str::random(60), 'verified_at' => null]
            );

            // 4. Dispatch a job to send the verification email in the background
            // This ensures the user gets a fast response without waiting for the email to send.
            SendSubscriberVerificationEmail::dispatch($subscriber);

            Log::info('Verification email dispatched for: ' . $email);

            $successMessage = 'Thanks for subscribing! Please check your email to confirm your subscription.';

            // Handle both AJAX and standard form submissions
            if ($request->wantsJson()) {
                return response()->json(['message' => $successMessage]);
            }
            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed: ' . $e->getMessage());
            return $this->handleError($request, 'Could not subscribe at this time. Please try again later.');
        }
    }

    /**
     * Verify a subscriber's email address using the token.
     */
    public function verify(string $token): View
    {
        $subscriber = Subscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            return view('pages.support.message', [
                'title' => 'Verification Failed',
                'message' => 'This verification link is invalid or has expired.'
            ]);
        }

        $subscriber->verified_at = now();
        $subscriber->verification_token = null; // Token is single-use
        $subscriber->save();

        Log::info('Subscriber verified: ' . $subscriber->email);

        // Here, we can also dispatch a job to send the notification email to the admin
        // NewSubscriberNotificationJob::dispatch($subscriber);

        return view('pages.support.message', [
            'title' => 'Subscription Confirmed!',
            'message' => 'Thank you for verifying your email. You are now subscribed to our newsletter.'
        ]);
    }

    /**
     * Helper to handle error responses for both web and JSON requests.
     */
    private function handleError(Request $request, string $message): RedirectResponse|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => $message], 422);
        }
        return back()->with('error', $message);
    }
}
