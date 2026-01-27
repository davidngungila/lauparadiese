<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrganizationSetting;
use Illuminate\Http\Request;

class OrganizationSettingController extends BaseAdminController
{
    /**
     * Display organization settings
     */
    public function index()
    {
        $settings = OrganizationSetting::getSettings();
        return view('admin.settings.organization', compact('settings'));
    }

    /**
     * Update organization settings
     */
    public function update(Request $request)
    {
        $settings = OrganizationSetting::getSettings();
        
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'logo_url' => 'nullable|url|max:500',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:500',
            'tax_id' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_country' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:50',
            'swift_code' => 'nullable|string|max:20',
            'invoice_footer_note' => 'nullable|string',
            'invoice_terms' => 'nullable|string',
            'currency' => 'required|string|size:3',
            'invoice_prefix' => 'nullable|string|max:10',
            'quotation_prefix' => 'nullable|string|max:10',
            'booking_prefix' => 'nullable|string|max:10',
        ]);
        
        $settings->update($validated);
        
        return $this->successResponse('Organization settings updated successfully!', route('admin.settings.organization'));
    }
}
