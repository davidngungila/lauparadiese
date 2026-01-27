<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class WebsiteSettingsController extends BaseAdminController
{
    /**
     * Display website settings
     */
    public function index()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => '',
            'site_keywords' => '',
            'contact_email' => '',
            'contact_phone' => '',
            'contact_address' => '',
            'social_facebook' => '',
            'social_twitter' => '',
            'social_instagram' => '',
            'social_linkedin' => '',
        ];
        
        return view('admin.settings.website', compact('settings'));
    }

    /**
     * Update website settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:500',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
        ]);
        
        // In a real app, save to database
        return $this->successResponse('Website settings updated successfully!', route('admin.settings.website'));
    }
}



