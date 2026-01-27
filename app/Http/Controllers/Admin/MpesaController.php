<?php

namespace App\Http\Controllers\Admin;

use App\Models\SystemSetting;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MpesaController extends BaseAdminController
{
    /**
     * Display MPESA Daraja settings with wizard interface
     */
    public function index()
    {
        // Get all MPESA settings from SystemSetting
        $settings = [];
        try {
            if (Schema::hasTable('system_settings')) {
                $mpesaSettings = SystemSetting::where('group', 'mpesa_daraja')->get()->keyBy('key');
                
                foreach ($mpesaSettings as $key => $setting) {
                    $settings[$key] = $setting->value;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to get MPESA settings: ' . $e->getMessage());
        }

        // Set defaults from env if not in database
        $defaults = [
            'business_name' => env('MPESA_BUSINESS_NAME', ''),
            'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
            'shortcode' => env('MPESA_SHORTCODE', ''),
            'shortcode_type' => env('MPESA_SHORTCODE_TYPE', 'paybill'),
            'consumer_key' => env('MPESA_CONSUMER_KEY', ''),
            'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),
            'passkey' => env('MPESA_PASSKEY', ''),
            'security_credential' => env('MPESA_SECURITY_CREDENTIAL', ''),
            'access_token_url' => env('MPESA_ACCESS_TOKEN_URL', 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'),
            'api_base_url' => env('MPESA_API_BASE_URL', 'https://sandbox.safaricom.co.ke'),
            'stk_callback_url' => env('MPESA_STK_CALLBACK_URL', url('/api/mpesa/stk/callback')),
            'stk_timeout_url' => env('MPESA_STK_TIMEOUT_URL', url('/api/mpesa/stk/timeout')),
            'c2b_validation_url' => env('MPESA_C2B_VALIDATION_URL', url('/api/mpesa/c2b/validate')),
            'c2b_confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL', url('/api/mpesa/c2b/confirm')),
            'c2b_shortcode' => env('MPESA_C2B_SHORTCODE', ''),
            'c2b_command_id' => env('MPESA_C2B_COMMAND_ID', 'CustomerPayBillOnline'),
            'b2c_initiator_username' => env('MPESA_B2C_INITIATOR_USERNAME', ''),
            'b2c_initiator_password' => env('MPESA_B2C_INITIATOR_PASSWORD', ''),
            'b2c_shortcode' => env('MPESA_B2C_SHORTCODE', ''),
            'b2c_result_url' => env('MPESA_B2C_RESULT_URL', url('/api/mpesa/b2c/result')),
            'b2c_timeout_url' => env('MPESA_B2C_TIMEOUT_URL', url('/api/mpesa/b2c/timeout')),
            'b2c_remarks' => env('MPESA_B2C_REMARKS', 'Payment'),
            'enabled' => env('MPESA_ENABLED', 'false'),
        ];

        // Merge defaults with saved settings
        foreach ($defaults as $key => $value) {
            if (!isset($settings[$key])) {
                $settings[$key] = $value;
            }
        }

        return view('admin.settings.mpesa', compact('settings'));
    }

    /**
     * Update MPESA Daraja settings
     */
    public function update(Request $request)
    {
        try {
            if (!Schema::hasTable('system_settings')) {
                return response()->json([
                    'success' => false,
                    'message' => 'System settings table does not exist. Please run migrations.',
                ], 500);
            }

            // Get all submitted data (not just validated)
            $data = $request->all();
            
            // Remove CSRF token and method spoofing
            unset($data['_token'], $data['_method']);
            
            // Validate only fields that are present
            $validated = $this->validateMpesaSettings($request);
            
            // Merge validated data with all submitted data (for partial updates)
            $updateData = array_merge($data, $validated);

            DB::beginTransaction();

            foreach ($updateData as $key => $value) {
                // Skip non-MPESA fields
                if (in_array($key, ['_token', '_method', 'submit', 'form_type'])) {
                    continue;
                }
                
                // Handle boolean values
                if ($key === 'enabled') {
                    $value = $request->has('enabled') && ($value === '1' || $value === 1 || $value === true || $value === 'true') ? '1' : '0';
                }
                
                // Convert empty strings to null for optional fields
                $optionalFields = ['security_credential', 'stk_timeout_url', 'c2b_shortcode', 'c2b_command_id', 
                                   'b2c_initiator_username', 'b2c_initiator_password', 'b2c_shortcode', 
                                   'b2c_result_url', 'b2c_timeout_url', 'b2c_remarks'];
                if (in_array($key, $optionalFields) && $value === '') {
                    $value = null;
                }
                
                // Update all fields (including null for optional ones)
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value ?? '',
                        'type' => $this->getFieldType($key, $value),
                        'group' => 'mpesa_daraja',
                        'description' => $this->getFieldDescription($key),
                    ]
                );
            }

            DB::commit();

            Log::info('MPESA settings updated', [
                'updated_by' => auth()->id(),
                'updated_fields' => array_keys($updateData),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'MPESA Daraja settings updated successfully!',
                ]);
            }

            return redirect()->route('admin.settings.mpesa')
                ->with('success', 'MPESA Daraja settings updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update MPESA settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update settings: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Test MPESA connection
     */
    public function test(Request $request)
    {
        try {
            // Get settings from database
            $settings = [];
            if (Schema::hasTable('system_settings')) {
                $mpesaSettings = SystemSetting::where('group', 'mpesa_daraja')->get()->keyBy('key');
                foreach ($mpesaSettings as $key => $setting) {
                    $settings[$key] = $setting->value;
                }
            }
            
            // Basic validation test
            $consumerKey = $request->input('consumer_key') ?? $settings['consumer_key'] ?? '';
            $consumerSecret = $request->input('consumer_secret') ?? $settings['consumer_secret'] ?? '';
            $environment = $request->input('environment') ?? $settings['environment'] ?? 'sandbox';

            if (empty($consumerKey) || empty($consumerSecret)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Consumer Key and Consumer Secret are required',
                ], 400);
            }

            // Test OAuth token generation
            $accessTokenUrl = $environment === 'production' 
                ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
                : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

            $ch = curl_init($accessTokenUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret)
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                if (isset($data['access_token'])) {
                    return response()->json([
                        'success' => true,
                        'message' => 'MPESA connection test successful! Access token generated.',
                        'access_token' => substr($data['access_token'], 0, 20) . '...', // Partial token for display
                        'expires_in' => $data['expires_in'] ?? 3600,
                    ]);
                }
            }

            $errorData = json_decode($response, true);
            $errorMessage = $errorData['errorMessage'] ?? $errorData['error_description'] ?? 'Failed to generate access token. Please check your credentials.';

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'http_code' => $httpCode,
            ], 400);

        } catch (\Exception $e) {
            Log::error('MPESA test failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simulate STK Push (M-Pesa Express)
     */
    public function simulateStkPush(Request $request)
    {
        try {
            // Validate required fields
            $validated = $request->validate([
                'phone_number' => 'required|string|regex:/^254\d{9}$/',
                'amount' => 'required|numeric|min:1|max:70000',
                'account_reference' => 'nullable|string|max:12',
                'transaction_desc' => 'nullable|string|max:13',
            ]);

            // Override settings if custom credentials provided
            if ($request->has('consumer_key') || $request->has('consumer_secret') || $request->has('passkey')) {
                // Temporarily update settings for this request
                $settings = [];
                if (Schema::hasTable('system_settings')) {
                    $mpesaSettings = SystemSetting::where('group', 'mpesa_daraja')->get()->keyBy('key');
                    foreach ($mpesaSettings as $key => $setting) {
                        $settings[$key] = $setting->value;
                    }
                }

                // Override with custom values if provided
                if ($request->has('consumer_key')) {
                    $settings['consumer_key'] = $request->input('consumer_key');
                }
                if ($request->has('consumer_secret')) {
                    $settings['consumer_secret'] = $request->input('consumer_secret');
                }
                if ($request->has('passkey')) {
                    $settings['passkey'] = $request->input('passkey');
                }
                if ($request->has('shortcode')) {
                    $settings['shortcode'] = $request->input('shortcode');
                }
                if ($request->has('environment')) {
                    $settings['environment'] = $request->input('environment');
                }

                // Save temporarily to SystemSetting for service to use
                foreach ($settings as $key => $value) {
                    SystemSetting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value, 'group' => 'mpesa_daraja']
                    );
                }
            }

            $mpesaService = new MpesaService();
            $callbackUrl = $request->input('callback_url') ?? null;
            
            $result = $mpesaService->stkPush(
                $validated['phone_number'],
                $validated['amount'],
                $validated['account_reference'] ?? null,
                $validated['transaction_desc'] ?? null,
                $callbackUrl
            );

            // Log transaction
            if ($result['success'] && isset($result['data'])) {
                try {
                    \App\Models\MpesaTransaction::create([
                        'transaction_type' => 'stk_push',
                        'merchant_request_id' => $result['data']['MerchantRequestID'] ?? null,
                        'checkout_request_id' => $result['data']['CheckoutRequestID'] ?? null,
                        'amount' => $validated['amount'],
                        'phone_number' => $validated['phone_number'],
                        'account_reference' => $validated['account_reference'] ?? null,
                        'status' => 'pending',
                        'result_code' => $result['data']['ResponseCode'] ?? null,
                        'result_description' => $result['data']['ResponseDescription'] ?? null,
                        'metadata' => $result['data'],
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to log STK Push transaction', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('STK Push simulation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Simulation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate MPESA settings
     */
    private function validateMpesaSettings(Request $request)
    {
        // Determine which form is being submitted based on form_type
        $formType = $request->input('form_type', 'configuration');
        $rules = [];
        
        // Configuration form - validate all basic fields if present
        if ($formType === 'configuration') {
            if ($request->has('business_name')) {
                $rules['business_name'] = 'required|string|max:255';
            }
            if ($request->has('environment')) {
                $rules['environment'] = 'required|in:sandbox,production';
            }
            if ($request->has('shortcode')) {
                $rules['shortcode'] = 'required|string|max:20';
            }
            if ($request->has('shortcode_type')) {
                $rules['shortcode_type'] = 'required|in:till_number,paybill,buy_goods';
            }
            if ($request->has('consumer_key')) {
                $rules['consumer_key'] = 'required|string|max:255';
            }
            if ($request->has('consumer_secret')) {
                $rules['consumer_secret'] = 'required|string|max:255';
            }
            if ($request->has('passkey')) {
                $rules['passkey'] = 'required|string|max:255';
            }
            if ($request->has('security_credential')) {
                $rules['security_credential'] = 'nullable|string|max:500';
            }
            if ($request->has('access_token_url')) {
                $rules['access_token_url'] = 'required|url|max:500';
            }
            if ($request->has('api_base_url')) {
                $rules['api_base_url'] = 'required|url|max:500';
            }
            if ($request->has('enabled')) {
                $rules['enabled'] = 'nullable|boolean';
            }
        }
        
        // STK Push form
        if ($formType === 'stk_push') {
            if ($request->has('stk_callback_url')) {
                $rules['stk_callback_url'] = 'required|url|max:500';
            }
            if ($request->has('stk_timeout_url')) {
                $rules['stk_timeout_url'] = 'nullable|url|max:500';
            }
        }
        
        // C2B form
        if ($formType === 'c2b') {
            if ($request->has('c2b_validation_url')) {
                $rules['c2b_validation_url'] = 'required|url|max:500';
            }
            if ($request->has('c2b_confirmation_url')) {
                $rules['c2b_confirmation_url'] = 'required|url|max:500';
            }
            if ($request->has('c2b_shortcode')) {
                $rules['c2b_shortcode'] = 'nullable|string|max:20';
            }
            if ($request->has('c2b_command_id')) {
                $rules['c2b_command_id'] = 'nullable|string|max:50';
            }
        }
        
        // B2C form
        if ($formType === 'b2c') {
            if ($request->has('b2c_initiator_username')) {
                $rules['b2c_initiator_username'] = 'nullable|string|max:255';
            }
            if ($request->has('b2c_initiator_password')) {
                $rules['b2c_initiator_password'] = 'nullable|string|max:255';
            }
            if ($request->has('b2c_shortcode')) {
                $rules['b2c_shortcode'] = 'nullable|string|max:20';
            }
            if ($request->has('b2c_result_url')) {
                $rules['b2c_result_url'] = 'nullable|url|max:500';
            }
            if ($request->has('b2c_timeout_url')) {
                $rules['b2c_timeout_url'] = 'nullable|url|max:500';
            }
            if ($request->has('b2c_remarks')) {
                $rules['b2c_remarks'] = 'nullable|string|max:255';
            }
        }
        
        // If no rules, return empty array (allow partial updates)
        if (empty($rules)) {
            return [];
        }
        
        return $request->validate($rules);
    }

    /**
     * Get field type
     */
    private function getFieldType(string $key, $value)
    {
        if (is_bool($value) || in_array($key, ['enabled'])) {
            return 'boolean';
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return 'url';
        }
        if (in_array($key, ['consumer_key', 'consumer_secret', 'passkey', 'security_credential', 'b2c_initiator_password'])) {
            return 'password';
        }
        return 'text';
    }

    /**
     * Get field description
     */
    private function getFieldDescription(string $key)
    {
        $descriptions = [
            'business_name' => 'Your business or company name',
            'environment' => 'MPESA environment: sandbox or production',
            'shortcode' => 'Your Paybill/Till number',
            'shortcode_type' => 'Type of shortcode: Till Number, Paybill, or Buy Goods',
            'consumer_key' => 'Generated from Safaricom Developer Portal',
            'consumer_secret' => 'Used with the key for OAuth token',
            'passkey' => 'Lipa na MPESA Online Passkey',
            'security_credential' => 'Encrypted password created via Safaricom certificate',
            'access_token_url' => 'OAuth token generation endpoint',
            'api_base_url' => 'MPESA API base URL (sandbox or production)',
            'stk_callback_url' => 'Callback URL for STK Push responses',
            'stk_timeout_url' => 'Timeout URL for STK Push',
            'c2b_validation_url' => 'C2B validation endpoint',
            'c2b_confirmation_url' => 'C2B confirmation endpoint',
            'c2b_shortcode' => 'C2B shortcode number',
            'c2b_command_id' => 'C2B command ID (usually CustomerPayBillOnline)',
            'b2c_initiator_username' => 'B2C initiator username',
            'b2c_initiator_password' => 'B2C initiator password (encrypted)',
            'b2c_shortcode' => 'B2C shortcode number',
            'b2c_result_url' => 'B2C result callback URL',
            'b2c_timeout_url' => 'B2C timeout callback URL',
            'b2c_remarks' => 'Default remarks for B2C transactions',
            'enabled' => 'Enable or disable MPESA integration',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Query STK Push transaction status
     */
    public function queryStkStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'checkout_request_id' => 'required|string',
            ]);

            $mpesaService = new MpesaService();
            $result = $mpesaService->queryStkStatus($validated['checkout_request_id']);

            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            Log::error('STK Status query failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Query failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register C2B URLs
     */
    public function registerC2b(Request $request)
    {
        try {
            $validated = $request->validate([
                'validation_url' => 'required|url',
                'confirmation_url' => 'required|url',
                'shortcode' => 'nullable|string',
            ]);

            $mpesaService = new MpesaService();
            $result = $mpesaService->registerC2bUrls(
                $validated['validation_url'],
                $validated['confirmation_url'],
                $validated['shortcode'] ?? null
            );

            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            Log::error('C2B registration failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Initiate B2C payment
     */
    public function initiateB2c(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone_number' => 'required|string|regex:/^254\d{9}$/',
                'amount' => 'required|numeric|min:1|max:500000',
                'remarks' => 'nullable|string|max:255',
                'occasion' => 'nullable|string|max:255',
            ]);

            $mpesaService = new MpesaService();
            $result = $mpesaService->b2cPayment(
                $validated['phone_number'],
                $validated['amount'],
                $validated['remarks'] ?? null,
                $validated['occasion'] ?? null
            );

            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            Log::error('B2C payment failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Query account balance
     */
    public function queryBalance(Request $request)
    {
        try {
            $mpesaService = new MpesaService();
            $result = $mpesaService->accountBalance();

            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            Log::error('Balance query failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Query failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reverse transaction
     */
    public function reverseTransaction(Request $request)
    {
        try {
            $validated = $request->validate([
                'transaction_id' => 'required|string',
                'amount' => 'required|numeric|min:1',
                'receiver_party' => 'nullable|string',
                'remarks' => 'nullable|string|max:255',
            ]);

            $mpesaService = new MpesaService();
            $result = $mpesaService->reverseTransaction(
                $validated['transaction_id'],
                $validated['amount'],
                $validated['receiver_party'] ?? null,
                $validated['remarks'] ?? null
            );

            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            Log::error('Transaction reversal failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Reversal failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transaction history/logs
     */
    public function transactionHistory(Request $request)
    {
        try {
            $query = \App\Models\MpesaTransaction::query();

            // Apply filters
            if ($request->filled('transaction_type')) {
                $query->where('transaction_type', $request->transaction_type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('phone_number')) {
                $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                      ->orWhere('mpesa_receipt_number', 'like', "%{$search}%")
                      ->orWhere('account_reference', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                });
            }

            // Get statistics
            $stats = [
                'total' => \App\Models\MpesaTransaction::count(),
                'completed' => \App\Models\MpesaTransaction::where('status', 'completed')->count(),
                'pending' => \App\Models\MpesaTransaction::where('status', 'pending')->count(),
                'failed' => \App\Models\MpesaTransaction::where('status', 'failed')->count(),
                'total_amount' => \App\Models\MpesaTransaction::where('status', 'completed')->sum('amount'),
            ];

            // Paginate results
            $perPage = $request->input('per_page', 20);
            $transactions = $query->orderBy('created_at', 'desc')->paginate($perPage);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $transactions->items(),
                    'pagination' => [
                        'current_page' => $transactions->currentPage(),
                        'last_page' => $transactions->lastPage(),
                        'per_page' => $transactions->perPage(),
                        'total' => $transactions->total(),
                    ],
                    'stats' => $stats,
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'stats' => $stats,
                'message' => 'Transaction history retrieved',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get transaction history', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve history: ' . $e->getMessage(),
            ], 500);
        }
    }
}
