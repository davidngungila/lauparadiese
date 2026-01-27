<?php

namespace App\Http\Controllers\Admin;

use App\Models\NotificationProvider;
use App\Models\SystemSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SmsGatewayController extends BaseAdminController
{
    /**
     * Display SMS Gateway settings with multiple providers
     */
    public function index()
    {
        // Get all SMS providers (handle if table doesn't exist)
        $providers = collect([]);
        try {
            if (class_exists(NotificationProvider::class)) {
                $providers = NotificationProvider::where('type', 'sms')
                    ->orderBy('is_primary', 'desc')
                    ->orderBy('priority', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('NotificationProvider table not available: ' . $e->getMessage());
            $providers = collect([]);
        }

        // Get fallback settings from SystemSetting
        $fallbackSettings = [];
        try {
            if (class_exists(SystemSetting::class)) {
                $fallbackSettings = [
                    'sms_username' => SystemSetting::getValue('sms_username', env('SMS_USERNAME', '')),
                    'sms_password' => SystemSetting::getValue('sms_password', env('SMS_PASSWORD', '')),
                    'sms_from' => SystemSetting::getValue('sms_from', env('SMS_FROM', '')),
                    'sms_url' => SystemSetting::getValue('sms_url', env('SMS_URL', '')),
                ];
            } else {
                $fallbackSettings = [
                    'sms_username' => env('SMS_USERNAME', ''),
                    'sms_password' => env('SMS_PASSWORD', ''),
                    'sms_from' => env('SMS_FROM', ''),
                    'sms_url' => env('SMS_URL', ''),
                ];
            }
        } catch (\Exception $e) {
            // Table doesn't exist, use env
            $fallbackSettings = [
                'sms_username' => env('SMS_USERNAME', ''),
                'sms_password' => env('SMS_PASSWORD', ''),
                'sms_from' => env('SMS_FROM', ''),
                'sms_url' => env('SMS_URL', ''),
            ];
        }

        return view('admin.settings.sms-gateway', compact('providers', 'fallbackSettings'));
    }

    /**
     * Store a new SMS provider
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sms_username' => 'required|string|max:255',
            'sms_password' => 'required|string|max:255',
            'sms_from' => 'required|string|max:50',
            'sms_url' => 'required|url|max:500',
            'sms_method' => 'required|in:get,post',
            'is_primary' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Convert string '1'/'0' to boolean if needed
        if (isset($validated['is_primary']) && is_string($validated['is_primary'])) {
            $validated['is_primary'] = $validated['is_primary'] === '1' || $validated['is_primary'] === 'true';
        }
        if (isset($validated['is_active']) && is_string($validated['is_active'])) {
            $validated['is_active'] = $validated['is_active'] === '1' || $validated['is_active'] === 'true';
        }

        try {
            DB::beginTransaction();

            $provider = NotificationProvider::create([
                'name' => $validated['name'],
                'type' => 'sms',
                'sms_username' => $validated['sms_username'],
                'sms_password' => $validated['sms_password'],
                'sms_from' => $validated['sms_from'],
                'sms_url' => $validated['sms_url'],
                'sms_method' => $validated['sms_method'],
                'is_primary' => $validated['is_primary'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'priority' => $validated['priority'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'connection_status' => 'unknown',
            ]);

            // If set as primary, unset others
            if ($provider->is_primary) {
                $provider->setAsPrimary();
            }

            DB::commit();

            Log::info('SMS Provider created', [
                'provider_id' => $provider->id,
                'name' => $provider->name,
                'created_by' => auth()->id(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMS Provider created successfully!',
                    'provider' => $provider,
                ]);
            }

            return redirect()->route('admin.settings.sms-gateway')
                ->with('success', 'SMS Provider created successfully!');
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
            Log::error('Failed to create SMS Provider', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create SMS Provider: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to create SMS Provider: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing SMS provider
     */
    public function update(Request $request, $id)
    {
        $provider = NotificationProvider::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sms_username' => 'required|string|max:255',
            'sms_password' => 'required|string|max:255',
            'sms_from' => 'required|string|max:50',
            'sms_url' => 'required|url|max:500',
            'sms_method' => 'required|in:get,post',
            'is_primary' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Convert string '1'/'0' to boolean if needed
        if (isset($validated['is_primary']) && is_string($validated['is_primary'])) {
            $validated['is_primary'] = $validated['is_primary'] === '1' || $validated['is_primary'] === 'true';
        }
        if (isset($validated['is_active']) && is_string($validated['is_active'])) {
            $validated['is_active'] = $validated['is_active'] === '1' || $validated['is_active'] === 'true';
        }

        try {
            DB::beginTransaction();

            $wasPrimary = $provider->is_primary;
            $provider->update([
                'name' => $validated['name'],
                'sms_username' => $validated['sms_username'],
                'sms_password' => $validated['sms_password'],
                'sms_from' => $validated['sms_from'],
                'sms_url' => $validated['sms_url'],
                'sms_method' => $validated['sms_method'],
                'is_primary' => $validated['is_primary'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'priority' => $validated['priority'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            // If set as primary, unset others
            if ($provider->is_primary && !$wasPrimary) {
                $provider->setAsPrimary();
            }

            DB::commit();

            Log::info('SMS Provider updated', [
                'provider_id' => $provider->id,
                'name' => $provider->name,
                'updated_by' => auth()->id(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMS Provider updated successfully!',
                    'provider' => $provider,
                ]);
            }

            return redirect()->route('admin.settings.sms-gateway')
                ->with('success', 'SMS Provider updated successfully!');
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
            Log::error('Failed to update SMS Provider', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update SMS Provider: ' . $e->getMessage(),
                ], 500);
            }

            return $this->errorResponse('Failed to update SMS Provider: ' . $e->getMessage());
        }
    }

    /**
     * Delete an SMS provider
     */
    public function destroy($id)
    {
        try {
            $provider = NotificationProvider::findOrFail($id);

            // Don't allow deleting primary provider
            if ($provider->is_primary) {
                return $this->errorResponse('Cannot delete the primary SMS provider. Please set another provider as primary first.');
            }

            $providerName = $provider->name;
            $provider->delete();

            Log::info('SMS Provider deleted', [
                'provider_id' => $id,
                'name' => $providerName,
                'deleted_by' => auth()->id(),
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMS Provider deleted successfully!',
                ]);
            }

            return $this->successResponse('SMS Provider deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete SMS Provider', [
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to delete SMS Provider: ' . $e->getMessage());
        }
    }

    /**
     * Set provider as primary
     */
    public function setPrimary($id)
    {
        try {
            $provider = NotificationProvider::findOrFail($id);
            $provider->setAsPrimary();

            Log::info('SMS Provider set as primary', [
                'provider_id' => $provider->id,
                'name' => $provider->name,
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Provider set as primary successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to set primary provider', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary provider: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test SMS provider connection
     */
    public function testConnection($id)
    {
        try {
            $provider = NotificationProvider::findOrFail($id);
            $result = $provider->testConnection();

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'status' => $provider->connection_status,
                'last_tested' => $provider->last_tested_at?->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to test SMS provider connection', [
                'provider_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test SMS sending with a specific provider
     */
    public function test(Request $request)
    {
        $request->validate([
            'provider_id' => 'nullable|exists:notification_providers,id',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:160',
        ]);

        try {
            $provider = null;
            if ($request->provider_id) {
                $provider = NotificationProvider::findOrFail($request->provider_id);
            }

            $notificationService = app(NotificationService::class);
            $result = $notificationService->sendSMS($request->phone, $request->message, $provider);

            if ($result) {
                Log::info('SMS test sent successfully', [
                    'phone' => $request->phone,
                    'provider_id' => $provider?->id,
                    'sent_by' => auth()->id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Test SMS sent successfully to ' . $request->phone . '!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'SMS test failed. Please check the logs for details.',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('SMS test failed', [
                'phone' => $request->phone,
                'provider_id' => $request->provider_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'SMS test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle provider active status
     */
    public function toggleActive($id)
    {
        try {
            $provider = NotificationProvider::findOrFail($id);
            $provider->is_active = !$provider->is_active;
            $provider->save();

            return response()->json([
                'success' => true,
                'message' => 'Provider status updated successfully!',
                'is_active' => $provider->is_active,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update provider status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
