<?php

namespace Database\Seeders;

use App\Models\NotificationProvider;
use Illuminate\Database\Seeder;

class NotificationProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates sample SMS providers with realistic data
     */
    public function run(): void
    {
        // Clear existing providers if running fresh seed
        if (NotificationProvider::where('type', 'sms')->count() > 0) {
            $this->command->info('Clearing existing SMS providers...');
            NotificationProvider::where('type', 'sms')->delete();
        }

        // Get values using the same fallback logic as NotificationService
        // Try SystemSetting first, then fallback to env, then hardcoded defaults
        $smsUsername = null;
        $smsPassword = null;
        $smsFrom = null;
        $smsUrl = null;

        try {
            // Try to get from SystemSetting (if table exists)
            if (class_exists(\App\Models\SystemSetting::class)) {
                $smsUsername = \App\Models\SystemSetting::getValue('sms_username');
                $smsPassword = \App\Models\SystemSetting::getValue('sms_password');
                $smsFrom = \App\Models\SystemSetting::getValue('sms_from');
                $smsUrl = \App\Models\SystemSetting::getValue('sms_url');
            }
        } catch (\Exception $e) {
            // SystemSetting table might not exist, continue to env fallback
        }

        // Fallback to env variables (matching NotificationService constructor)
        $smsUsername = $smsUsername ?: env('SMS_USERNAME', 'emcatechn');
        $smsPassword = $smsPassword ?: env('SMS_PASSWORD', 'Emca@#12');
        $smsFrom = $smsFrom ?: env('SMS_FROM', 'OfisiLink');
        $smsUrl = $smsUrl ?: env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single');

        // Primary SMS Provider (using NotificationService constructor logic)
        NotificationProvider::create([
            'name' => 'Messaging Service (Primary)',
            'type' => 'sms',
            'is_primary' => true,
            'is_active' => true,
            'sms_username' => $smsUsername,
            'sms_password' => $smsPassword,
            'sms_from' => $smsFrom,
            'sms_url' => $smsUrl,
            'sms_method' => 'post',
            'priority' => 0,
            'connection_status' => 'unknown',
            'notes' => 'Primary SMS provider - Configured using SystemSetting → env → default fallback (matching NotificationService constructor logic)',
        ]);

        // Secondary Provider - Twilio (Example)
        NotificationProvider::create([
            'name' => 'Twilio SMS Gateway',
            'type' => 'sms',
            'is_primary' => false,
            'is_active' => true,
            'sms_username' => env('TWILIO_ACCOUNT_SID', 'ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
            'sms_password' => env('TWILIO_AUTH_TOKEN', 'your_auth_token_here'),
            'sms_from' => env('TWILIO_FROM', '+1234567890'),
            'sms_url' => 'https://api.twilio.com/2010-04-01/Accounts/{AccountSid}/Messages.json',
            'sms_method' => 'post',
            'priority' => 1,
            'connection_status' => 'unknown',
            'notes' => 'Twilio SMS gateway - International SMS provider. Requires Twilio account credentials. Replace {AccountSid} with actual Account SID in URL.',
        ]);

        // Tertiary Provider - Africa's Talking (Example)
        NotificationProvider::create([
            'name' => 'Africa\'s Talking',
            'type' => 'sms',
            'is_primary' => false,
            'is_active' => true,
            'sms_username' => env('AFRICASTALKING_USERNAME', 'sandbox'),
            'sms_password' => env('AFRICASTALKING_API_KEY', 'your_api_key_here'),
            'sms_from' => env('AFRICASTALKING_FROM', 'LauParadise'),
            'sms_url' => 'https://api.africastalking.com/version1/messaging',
            'sms_method' => 'post',
            'priority' => 2,
            'connection_status' => 'unknown',
            'notes' => 'Africa\'s Talking SMS gateway - Popular in East Africa. Supports both sandbox and production environments.',
        ]);

        // Backup Provider - Generic HTTP API (Example)
        NotificationProvider::create([
            'name' => 'Generic HTTP API',
            'type' => 'sms',
            'is_primary' => false,
            'is_active' => false,
            'sms_username' => 'api_user',
            'sms_password' => 'api_password',
            'sms_from' => 'LauParadise',
            'sms_url' => 'https://api.example.com/sms/send',
            'sms_method' => 'get',
            'priority' => 3,
            'connection_status' => 'unknown',
            'notes' => 'Generic HTTP API provider - Example backup provider using GET method. Currently inactive. Configure with your provider credentials.',
        ]);

        // Local Provider - Custom Gateway (Example)
        NotificationProvider::create([
            'name' => 'Custom Local Gateway',
            'type' => 'sms',
            'is_primary' => false,
            'is_active' => true,
            'sms_username' => 'local_user',
            'sms_password' => 'local_pass',
            'sms_from' => 'LauParadise',
            'sms_url' => 'https://sms.local-provider.co.tz/api/send',
            'sms_method' => 'post',
            'priority' => 4,
            'connection_status' => 'unknown',
            'notes' => 'Custom local SMS gateway - Example of a local Tanzanian SMS provider. Update credentials with actual provider details.',
        ]);

        $this->command->info('SMS providers seeded successfully!');
        $this->command->info('Created 5 sample providers:');
        $this->command->info('  1. Messaging Service (Primary) - Active');
        $this->command->info('     Username: ' . $smsUsername);
        $this->command->info('     From: ' . $smsFrom);
        $this->command->info('     URL: ' . $smsUrl);
        $this->command->info('  2. Twilio SMS Gateway - Active');
        $this->command->info('  3. Africa\'s Talking - Active');
        $this->command->info('  4. Generic HTTP API - Inactive');
        $this->command->info('  5. Custom Local Gateway - Active');
        $this->command->info('');
        $this->command->info('Primary provider uses: SystemSetting → env → default fallback');
        $this->command->info('Note: Update credentials with your actual provider details before using.');
    }
}

