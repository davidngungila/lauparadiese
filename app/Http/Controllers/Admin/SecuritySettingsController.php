<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecuritySettingsController extends BaseAdminController
{
    /**
     * Display security settings
     */
    public function index()
    {
        $settings = [
            'password_min_length' => 8,
            'password_require_uppercase' => true,
            'password_require_lowercase' => true,
            'password_require_numbers' => true,
            'password_require_symbols' => false,
            'session_timeout' => 120,
            'two_factor_enabled' => false,
            'login_attempts_limit' => 5,
            'lockout_duration' => 15,
        ];
        
        return view('admin.settings.security', compact('settings'));
    }

    /**
     * Update security settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6|max:32',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_symbols' => 'boolean',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'two_factor_enabled' => 'boolean',
            'login_attempts_limit' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:1|max:60',
        ]);
        
        // In a real app, save to database
        return $this->successResponse('Security settings updated successfully!', route('admin.settings.security'));
    }

    /**
     * Change admin password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($validated['current_password'], $user->password)) {
            return $this->errorResponse('Current password is incorrect!', route('admin.settings.security'));
        }
        
        $user->password = Hash::make($validated['new_password']);
        $user->save();
        
        return $this->successResponse('Password changed successfully!', route('admin.settings.security'));
    }
}



