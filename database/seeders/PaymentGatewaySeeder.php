<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates sample payment gateways with recommended settings
     */
    public function run(): void
    {
        // Update or create Pesapal gateway (always ensure it exists with correct credentials)
        $pesapalGateway = PaymentGateway::updateOrCreate(
            ['name' => 'pesapal'],
            [
                'display_name' => 'Pesapal',
                'description' => 'Pesapal payment gateway - Secure online payment gateway for Tanzania. Accept cards, mobile money, and bank transfers.',
                'is_active' => true,
                'is_test_mode' => false, // Live credentials provided
                'is_primary' => true, // Set as primary payment gateway
                'priority' => 0,
                'credentials' => [
                    'test_consumer_key' => env('PESAPAL_TEST_CONSUMER_KEY', ''),
                    'test_consumer_secret' => env('PESAPAL_TEST_CONSUMER_SECRET', ''),
                    'live_consumer_key' => env('PESAPAL_LIVE_CONSUMER_KEY', 'qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD'),
                    'live_consumer_secret' => env('PESAPAL_LIVE_CONSUMER_SECRET', 'M89Yr4yZ/U6ImiNJNBbQyuNxRCU='),
                ],
                'supported_currencies' => ['KES', 'USD', 'TZS', 'UGX', 'RWF'],
                'supported_payment_methods' => ['card', 'mobile_money', 'bank_transfer', 'pesapal'],
                'transaction_fee_percentage' => 0.00,
                'transaction_fee_fixed' => 0.00,
                'settings' => [
                    'currency' => 'KES',
                    'success_url' => url('/pesapal/callback'),
                    'cancel_url' => url('/booking'),
                    'notes' => 'Pesapal integration for LAU PARADISE ADVENTURES - Live/Production mode',
                    'merchant_name' => 'LAU PARADISE ADVENTURES',
                ],
                'status' => 'active',
                'webhook_url' => url('/pesapal/ipn'),
                'webhook_secret' => null,
            ]
        );

        // If Pesapal is set as primary, unset others
        if ($pesapalGateway->is_primary) {
            PaymentGateway::where('name', '!=', 'pesapal')
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        // Check if other gateways already exist
        if (PaymentGateway::where('name', '!=', 'pesapal')->count() > 0) {
            $this->command->info('Pesapal gateway configured successfully!');
            $this->command->info('Other payment gateways already exist. Skipping creation.');
            return;
        }

        // Stripe Gateway (Primary)
        PaymentGateway::create([
            'name' => 'stripe',
            'display_name' => 'Stripe',
            'description' => 'Stripe payment gateway - Accept credit cards and other payment methods',
            'is_active' => true,
            'is_test_mode' => true,
            'is_primary' => true,
            'priority' => 0,
            'credentials' => [
                'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', 'pk_test_...'),
                'secret_key' => env('STRIPE_SECRET_KEY', 'sk_test_...'),
                'api_version' => '2024-06-01',
                'webhook_tolerance' => 300,
                'payout_mode' => 'automatic',
                'description_prefix' => 'OfisiLink Payment',
            ],
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'TZS'],
            'supported_payment_methods' => ['card', 'bank_transfer'],
            'transaction_fee_percentage' => 2.9,
            'transaction_fee_fixed' => 0.30,
            'settings' => [
                'currency' => 'USD',
                'success_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
                'notes' => 'Primary payment gateway - Stripe integration',
            ],
            'status' => 'active',
            'webhook_url' => url('/webhook/stripe'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
        ]);

        // PayPal Gateway
        PaymentGateway::create([
            'name' => 'paypal',
            'display_name' => 'PayPal',
            'description' => 'PayPal payment gateway - Accept PayPal payments',
            'is_active' => true,
            'is_test_mode' => true,
            'is_primary' => false,
            'priority' => 1,
            'credentials' => [
                'client_id' => env('PAYPAL_CLIENT_ID', ''),
                'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
                'mode' => 'sandbox',
                'webhook_id' => env('PAYPAL_WEBHOOK_ID', ''),
                'api_base_url' => '',
                'payment_intent_mode' => 'capture',
                'webhook_verification_enabled' => true,
            ],
            'supported_currencies' => ['USD', 'EUR', 'GBP'],
            'supported_payment_methods' => ['paypal'],
            'transaction_fee_percentage' => 2.9,
            'transaction_fee_fixed' => 0.30,
            'settings' => [
                'currency' => 'USD',
                'success_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
                'notes' => 'PayPal integration - Sandbox mode',
            ],
            'status' => 'active',
            'webhook_url' => url('/webhook/paypal'),
            'webhook_secret' => env('PAYPAL_WEBHOOK_SECRET', ''),
        ]);

        // Pesapal Gateway (Primary for LAU PARADISE ADVENTURES)
        PaymentGateway::create([
            'name' => 'pesapal',
            'display_name' => 'Pesapal',
            'description' => 'Pesapal payment gateway - Secure online payment gateway for Tanzania. Accept cards, mobile money, and bank transfers.',
            'is_active' => true,
            'is_test_mode' => false, // Live credentials provided
            'is_primary' => true, // Set as primary payment gateway
            'priority' => 0,
            'credentials' => [
                'test_consumer_key' => env('PESAPAL_TEST_CONSUMER_KEY', ''),
                'test_consumer_secret' => env('PESAPAL_TEST_CONSUMER_SECRET', ''),
                'live_consumer_key' => env('PESAPAL_LIVE_CONSUMER_KEY', 'qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD'),
                'live_consumer_secret' => env('PESAPAL_LIVE_CONSUMER_SECRET', 'M89Yr4yZ/U6ImiNJNBbQyuNxRCU='),
            ],
            'supported_currencies' => ['KES', 'USD', 'TZS', 'UGX', 'RWF'],
            'supported_payment_methods' => ['card', 'mobile_money', 'bank_transfer', 'pesapal'],
            'transaction_fee_percentage' => 0.00, // Pesapal fees are handled by Pesapal
            'transaction_fee_fixed' => 0.00,
            'settings' => [
                'currency' => 'KES',
                'success_url' => url('/pesapal/callback'),
                'cancel_url' => url('/booking'),
                'notes' => 'Pesapal integration for LAU PARADISE ADVENTURES - Live/Production mode',
                'merchant_name' => 'LAU PARADISE ADVENTURES',
            ],
            'status' => 'active',
            'webhook_url' => url('/pesapal/ipn'),
            'webhook_secret' => null, // Pesapal uses GET requests for IPN
        ]);

        $this->command->info('Payment gateways seeded successfully!');
        $this->command->info('Created 3 gateways:');
        $this->command->info('  1. Pesapal (Primary) - Active - Live Mode');
        $this->command->info('  2. Stripe - Active - Test Mode');
        $this->command->info('  3. PayPal - Active - Test Mode');
        $this->command->info('');
        $this->command->info('✅ Pesapal gateway configured with live credentials for LAU PARADISE ADVENTURES');
        $this->command->info('⚠️  Note: Update Stripe and PayPal credentials with your actual API keys before using.');
    }
}
