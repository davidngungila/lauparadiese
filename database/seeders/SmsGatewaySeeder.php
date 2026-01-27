<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates default SMS Gateway settings matching the format
     * used in NotificationService class.
     * 
     * Default values (matching NotificationService):
     * - sms_username: 'emcatechn' (from env SMS_USERNAME)
     * - sms_password: 'Emca@#12' (from env SMS_PASSWORD)
     * - sms_from: 'OfisiLink' (from env SMS_FROM)
     * - sms_url: 'https://messaging-service.co.tz/link/sms/v1/text/single' (from env SMS_URL)
     * 
     * These values are used as fallback when NotificationProvider is not available.
     * The NotificationService will check in this order:
     * 1. NotificationProvider (primary)
     * 2. SystemSetting (this seeder)
     * 3. Environment variables
     * 
     * To run this seeder:
     * php artisan db:seed --class=SmsGatewaySeeder
     * 
     * Or include it in DatabaseSeeder to run with all seeders.
     */
    public function run(): void
    {
        // Default SMS Gateway settings based on NotificationService defaults
        $smsSettings = [
            [
                'key' => 'sms_username',
                'value' => env('SMS_USERNAME', 'emcatechn'),
                'type' => 'text',
                'description' => 'SMS Gateway Username/API Key',
                'group' => 'sms_gateway',
            ],
            [
                'key' => 'sms_password',
                'value' => env('SMS_PASSWORD', 'Emca@#12'),
                'type' => 'password',
                'description' => 'SMS Gateway Password/API Secret',
                'group' => 'sms_gateway',
            ],
            [
                'key' => 'sms_from',
                'value' => env('SMS_FROM', 'OfisiLink'),
                'type' => 'text',
                'description' => 'SMS Sender ID/From Name',
                'group' => 'sms_gateway',
            ],
            [
                'key' => 'sms_url',
                'value' => env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single'),
                'type' => 'url',
                'description' => 'SMS Gateway API Endpoint URL',
                'group' => 'sms_gateway',
            ],
            [
                'key' => 'sms_enabled',
                'value' => env('SMS_ENABLED', 'true'),
                'type' => 'boolean',
                'description' => 'Enable/Disable SMS Gateway',
                'group' => 'sms_gateway',
            ],
            [
                'key' => 'sms_provider',
                'value' => env('SMS_PROVIDER', 'messaging_service'),
                'type' => 'text',
                'description' => 'SMS Provider Name (messaging_service, twilio, nexmo, etc.)',
                'group' => 'sms_gateway',
            ],
            [
                'key' => 'sms_method',
                'value' => env('SMS_METHOD', 'post'),
                'type' => 'text',
                'description' => 'HTTP Method for SMS API (get or post)',
                'group' => 'sms_gateway',
            ],
        ];

        // Insert or update SMS settings
        foreach ($smsSettings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('SMS Gateway settings seeded successfully!');
        $this->command->info('Default values:');
        $this->command->info('  - Username: ' . env('SMS_USERNAME', 'emcatechn'));
        $this->command->info('  - From: ' . env('SMS_FROM', 'OfisiLink'));
        $this->command->info('  - URL: ' . env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single'));
    }
}

