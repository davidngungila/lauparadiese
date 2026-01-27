<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PaymentGatewayController extends BaseAdminController
{
    /**
     * Display payment gateway settings with multiple providers
     */
    public function index()
    {
        // Get all payment gateways
        $gateways = collect([]);
        try {
            if (Schema::hasTable('payment_gateways')) {
                $query = PaymentGateway::query();
                
                // Check if columns exist before ordering
                if (Schema::hasColumn('payment_gateways', 'is_primary')) {
                    $query->orderBy('is_primary', 'desc');
                }
                if (Schema::hasColumn('payment_gateways', 'priority')) {
                    $query->orderBy('priority', 'asc');
                }
                
                $gateways = $query->orderBy('created_at', 'desc')->get();
            }
        } catch (\Exception $e) {
            Log::warning('PaymentGateway table not available: ' . $e->getMessage());
        }

        // Get primary gateway ID
        $primaryGatewayId = null;
        try {
            $primaryGateway = PaymentGateway::where('is_primary', true)->first();
            $primaryGatewayId = $primaryGateway ? $primaryGateway->id : null;
        } catch (\Exception $e) {
            // Table doesn't exist
        }

        return view('admin.settings.payment-gateways', compact('gateways', 'primaryGatewayId'));
    }

    /**
     * Store a new payment gateway
     */
    public function store(Request $request)
    {
        $validated = $this->validateGateway($request);

        try {
            if (!Schema::hasTable('payment_gateways')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateways table does not exist. Please run migrations.',
                ], 500);
            }

            DB::beginTransaction();

            $gateway = PaymentGateway::create([
                'name' => $validated['name'],
                'display_name' => $validated['display_name'] ?? $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'is_test_mode' => $validated['is_test_mode'] ?? true,
                'is_primary' => $validated['is_primary'] ?? false,
                'priority' => $validated['priority'] ?? 0,
                'credentials' => $this->buildCredentials($validated),
                'supported_currencies' => $validated['supported_currencies'] ?? [$validated['currency'] ?? 'USD'],
                'supported_payment_methods' => $validated['supported_payment_methods'] ?? [],
                'transaction_fee_percentage' => $validated['transaction_fee_percentage'] ?? 0,
                'transaction_fee_fixed' => $validated['transaction_fee_fixed'] ?? 0,
                'settings' => $this->buildSettings($validated),
                'webhook_url' => $validated['webhook_url'] ?? null,
                'webhook_secret' => $validated['webhook_secret'] ?? null,
                'status' => 'active',
            ]);

            // If set as primary, unset others
            if ($gateway->is_primary) {
                $this->setAsPrimary($gateway->id);
            }

            DB::commit();

            Log::info('Payment Gateway created', [
                'gateway_id' => $gateway->id,
                'name' => $gateway->name,
                'created_by' => auth()->id(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment Gateway created successfully!',
                    'gateway' => $gateway,
                ]);
            }

            return redirect()->route('admin.settings.payment-gateways')
                ->with('success', 'Payment Gateway created successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
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
            Log::error('Failed to create Payment Gateway', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create Payment Gateway: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to create Payment Gateway: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing payment gateway
     */
    public function update(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        $validated = $this->validateGateway($request, $gateway->name);

        try {
            DB::beginTransaction();

            $wasPrimary = $gateway->is_primary;
            
            $gateway->update([
                'display_name' => $validated['display_name'] ?? $gateway->display_name,
                'description' => $validated['description'] ?? $gateway->description,
                'is_active' => $validated['is_active'] ?? $gateway->is_active,
                'is_test_mode' => $validated['is_test_mode'] ?? $gateway->is_test_mode,
                'is_primary' => $validated['is_primary'] ?? $gateway->is_primary,
                'priority' => $validated['priority'] ?? $gateway->priority,
                'credentials' => $this->buildCredentials($validated, $gateway->credentials),
                'supported_currencies' => $validated['supported_currencies'] ?? $gateway->supported_currencies,
                'supported_payment_methods' => $validated['supported_payment_methods'] ?? $gateway->supported_payment_methods,
                'transaction_fee_percentage' => $validated['transaction_fee_percentage'] ?? $gateway->transaction_fee_percentage,
                'transaction_fee_fixed' => $validated['transaction_fee_fixed'] ?? $gateway->transaction_fee_fixed,
                'settings' => $this->buildSettings($validated, $gateway->settings),
                'webhook_url' => $validated['webhook_url'] ?? $gateway->webhook_url,
                'webhook_secret' => $validated['webhook_secret'] ?? $gateway->webhook_secret,
            ]);

            // If set as primary, unset others
            if ($gateway->is_primary && !$wasPrimary) {
                $this->setAsPrimary($gateway->id);
            }

            DB::commit();

            Log::info('Payment Gateway updated', [
                'gateway_id' => $gateway->id,
                'name' => $gateway->name,
                'updated_by' => auth()->id(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment Gateway updated successfully!',
                    'gateway' => $gateway,
                ]);
            }

            return redirect()->route('admin.settings.payment-gateways')
                ->with('success', 'Payment Gateway updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
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
            Log::error('Failed to update Payment Gateway', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Payment Gateway: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to update Payment Gateway: ' . $e->getMessage());
        }
    }

    /**
     * Delete a payment gateway
     */
    public function destroy($id)
    {
        try {
            $gateway = PaymentGateway::findOrFail($id);
            $gatewayName = $gateway->name;

            if ($gateway->is_primary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete primary payment gateway. Set another as primary first.',
                ], 400);
            }

            $gateway->delete();

            Log::info('Payment Gateway deleted', [
                'gateway_id' => $id,
                'name' => $gatewayName,
                'deleted_by' => auth()->id(),
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment Gateway deleted successfully!',
                ]);
            }

            return $this->successResponse('Payment Gateway deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete Payment Gateway', [
                'error' => $e->getMessage(),
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete Payment Gateway: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to delete Payment Gateway: ' . $e->getMessage());
        }
    }

    /**
     * Set a gateway as primary
     */
    public function setPrimary($id)
    {
        try {
            $this->setAsPrimary($id);

            return response()->json([
                'success' => true,
                'message' => 'Primary payment gateway updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary gateway: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        try {
            $gateway = PaymentGateway::findOrFail($id);
            $gateway->is_active = !$gateway->is_active;
            $gateway->save();

            return response()->json([
                'success' => true,
                'message' => 'Gateway status updated successfully!',
                'is_active' => $gateway->is_active,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update gateway status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test gateway connection
     */
    public function testConnection($id)
    {
        try {
            $gateway = PaymentGateway::findOrFail($id);
            
            // Basic validation test
        $credentials = $gateway->credentials ?? [];
            $isValid = false;
            $message = '';

            if ($gateway->name === 'stripe') {
                $isValid = !empty($credentials['publishable_key']) && !empty($credentials['secret_key']);
                $message = $isValid ? 'Stripe credentials are configured' : 'Stripe credentials are missing';
            } elseif ($gateway->name === 'paypal') {
                $isValid = !empty($credentials['client_id']) && !empty($credentials['client_secret']);
                $message = $isValid ? 'PayPal credentials are configured' : 'PayPal credentials are missing';
            }

            // Update connection status
            $gateway->update([
                'status' => $isValid ? 'active' : 'inactive',
            ]);

            return response()->json([
                'success' => $isValid,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate gateway data
     */
    private function validateGateway(Request $request, $gatewayName = null)
    {
        $name = $request->input('name', $gatewayName);
        $rules = [
            'name' => 'required|string|in:stripe,paypal',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
            'is_test_mode' => 'nullable|boolean',
            'is_primary' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100',
            'currency' => 'required|string|size:3',
            'success_url' => 'nullable|url|max:500',
            'cancel_url' => 'nullable|url|max:500',
            'webhook_url' => 'nullable|url|max:500',
            'webhook_secret' => 'nullable|string|max:500',
            'transaction_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'transaction_fee_fixed' => 'nullable|numeric|min:0',
            'supported_currencies' => 'nullable|array',
            'supported_payment_methods' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ];

        // Stripe-specific rules
        if ($name === 'stripe') {
            $rules['publishable_key'] = 'required|string|max:255';
            $rules['secret_key'] = 'required|string|max:255';
            $rules['api_version'] = 'nullable|string|max:50';
            $rules['webhook_tolerance'] = 'nullable|integer|min:0|max:300';
            $rules['payout_mode'] = 'nullable|string|in:automatic,manual';
            $rules['description_prefix'] = 'nullable|string|max:100';
        }

        // PayPal-specific rules
        if ($name === 'paypal') {
            $rules['client_id'] = 'required|string|max:255';
            $rules['client_secret'] = 'required|string|max:255';
            $rules['mode'] = 'required|string|in:sandbox,live';
            $rules['webhook_id'] = 'nullable|string|max:255';
            $rules['api_base_url'] = 'nullable|url|max:500';
            $rules['payment_intent_mode'] = 'nullable|string|in:authorize,capture';
            $rules['webhook_verification_enabled'] = 'nullable|boolean';
        }

        return $request->validate($rules);
    }

    /**
     * Build credentials array
     */
    private function buildCredentials(array $validated, array $existing = [])
    {
        $name = $validated['name'] ?? 'stripe';
        $credentials = $existing;

        if ($name === 'stripe') {
            $credentials['publishable_key'] = $validated['publishable_key'] ?? $credentials['publishable_key'] ?? '';
            $credentials['secret_key'] = $validated['secret_key'] ?? $credentials['secret_key'] ?? '';
            $credentials['api_version'] = $validated['api_version'] ?? $credentials['api_version'] ?? '2024-06-01';
            $credentials['webhook_tolerance'] = $validated['webhook_tolerance'] ?? $credentials['webhook_tolerance'] ?? 300;
            $credentials['payout_mode'] = $validated['payout_mode'] ?? $credentials['payout_mode'] ?? 'automatic';
            $credentials['description_prefix'] = $validated['description_prefix'] ?? $credentials['description_prefix'] ?? 'OfisiLink Payment';
        } elseif ($name === 'paypal') {
            $credentials['client_id'] = $validated['client_id'] ?? $credentials['client_id'] ?? '';
            $credentials['client_secret'] = $validated['client_secret'] ?? $credentials['client_secret'] ?? '';
            $credentials['mode'] = $validated['mode'] ?? $credentials['mode'] ?? 'sandbox';
            $credentials['webhook_id'] = $validated['webhook_id'] ?? $credentials['webhook_id'] ?? '';
            $credentials['api_base_url'] = $validated['api_base_url'] ?? $credentials['api_base_url'] ?? '';
            $credentials['payment_intent_mode'] = $validated['payment_intent_mode'] ?? $credentials['payment_intent_mode'] ?? 'capture';
            $credentials['webhook_verification_enabled'] = $validated['webhook_verification_enabled'] ?? $credentials['webhook_verification_enabled'] ?? true;
        } elseif ($name === 'pesapal') {
            // Handle JSON credentials if provided as string
            if (isset($validated['credentials']) && is_string($validated['credentials'])) {
                $decoded = json_decode($validated['credentials'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $credentials = array_merge($credentials, $decoded);
                }
            }
            // Handle individual fields
            $credentials['test_consumer_key'] = $validated['test_consumer_key'] ?? $credentials['test_consumer_key'] ?? '';
            $credentials['test_consumer_secret'] = $validated['test_consumer_secret'] ?? $credentials['test_consumer_secret'] ?? '';
            $credentials['live_consumer_key'] = $validated['live_consumer_key'] ?? $credentials['live_consumer_key'] ?? '';
            $credentials['live_consumer_secret'] = $validated['live_consumer_secret'] ?? $credentials['live_consumer_secret'] ?? '';
        }

        return $credentials;
    }

    /**
     * Build settings array
     */
    private function buildSettings(array $validated, array $existing = [])
    {
        return array_merge($existing, [
            'currency' => $validated['currency'] ?? $existing['currency'] ?? 'USD',
            'success_url' => $validated['success_url'] ?? $existing['success_url'] ?? null,
            'cancel_url' => $validated['cancel_url'] ?? $existing['cancel_url'] ?? null,
            'notes' => $validated['notes'] ?? $existing['notes'] ?? null,
        ]);
    }

    /**
     * Set gateway as primary
     */
    private function setAsPrimary($gatewayId)
    {
        // Unset all primary flags
        PaymentGateway::where('is_primary', true)->update(['is_primary' => false]);
        
        // Set this one as primary
        PaymentGateway::where('id', $gatewayId)->update(['is_primary' => true]);
    }
}
