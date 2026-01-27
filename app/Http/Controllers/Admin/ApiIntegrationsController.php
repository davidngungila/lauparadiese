<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ApiIntegrationsController extends BaseAdminController
{
    /**
     * Display API integrations dashboard
     */
    public function index()
    {
        $integrations = [
            'mpesa' => [
                'enabled' => config('services.mpesa.enabled', false),
                'consumer_key' => config('services.mpesa.consumer_key'),
                'consumer_secret' => config('services.mpesa.consumer_secret'),
            ],
            'sms' => [
                'enabled' => config('services.sms.enabled', false),
                'provider' => config('services.sms.provider', 'twilio'),
            ],
            'email' => [
                'enabled' => config('mail.default') !== null,
                'driver' => config('mail.default'),
            ],
            'payment' => [
                'stripe_enabled' => config('services.stripe.enabled', false),
                'paypal_enabled' => config('services.paypal.enabled', false),
            ],
        ];
        
        return view('admin.settings.api-integrations', compact('integrations'));
    }
}



