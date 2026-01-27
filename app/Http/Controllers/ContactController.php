<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function index(): View
    {
        return view('contact.index');
    }

    /**
     * Handle the contact form submission.
     */
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Store in customer queries
        if (class_exists(\App\Models\CustomerQuery::class)) {
            \App\Models\CustomerQuery::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'] ?? 'Contact Form Inquiry',
                'category' => 'other',
                'message' => $validated['message'],
                'status' => 'new',
                'priority' => 'normal',
            ]);
        }

        return back()->with('success', 'Thank you for your message! We will get back to you shortly.');
    }
}
