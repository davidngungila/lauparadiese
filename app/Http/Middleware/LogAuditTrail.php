<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditTrail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogAuditTrail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log admin routes
        if (!$request->is('admin/*')) {
            return $response;
        }

        // Skip logging for certain routes
        $skipRoutes = [
            'admin.settings.system-logs',
            'admin.settings.audit-trails',
            'admin.settings.activity-logs',
        ];

        $routeName = $request->route()?->getName();
        if (in_array($routeName, $skipRoutes)) {
            return $response;
        }

        // Determine action from route name or method
        $action = $this->getActionFromRoute($routeName, $request->method());
        
        // Determine module from route
        $module = $this->getModuleFromRoute($routeName);

        // Get model information if available
        $modelInfo = $this->getModelInfo($request, $response);

        // Log the audit trail
        try {
            AuditTrail::log([
                'action' => $action,
                'model_type' => $modelInfo['model_type'] ?? null,
                'model_id' => $modelInfo['model_id'] ?? null,
                'model_name' => $modelInfo['model_name'] ?? null,
                'description' => $this->getDescription($request, $action, $modelInfo),
                'old_values' => $modelInfo['old_values'] ?? null,
                'new_values' => $modelInfo['new_values'] ?? null,
                'changed_fields' => $modelInfo['changed_fields'] ?? null,
                'request_data' => $this->sanitizeRequestData($request->all()),
                'status' => $response->getStatusCode() >= 200 && $response->getStatusCode() < 300 ? 'success' : 'failed',
                'module' => $module,
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the application if logging fails
            \Log::error('Audit trail logging failed: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Get action from route name or HTTP method
     */
    private function getActionFromRoute(?string $routeName, string $method): string
    {
        if ($routeName) {
            if (str_contains($routeName, '.store') || str_contains($routeName, '.create')) {
                return 'created';
            }
            if (str_contains($routeName, '.update') || str_contains($routeName, '.edit')) {
                return 'updated';
            }
            if (str_contains($routeName, '.destroy') || str_contains($routeName, '.delete')) {
                return 'deleted';
            }
            if (str_contains($routeName, '.show') || str_contains($routeName, '.index')) {
                return 'viewed';
            }
        }

        return match($method) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'viewed',
        };
    }

    /**
     * Get module from route name
     */
    private function getModuleFromRoute(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }

        $parts = explode('.', $routeName);
        if (count($parts) >= 2) {
            return $parts[1]; // e.g., 'admin.tours.index' -> 'tours'
        }

        return null;
    }

    /**
     * Get model information from request/response
     */
    private function getModelInfo(Request $request, Response $response): array
    {
        $info = [];

        // Try to get model from route parameters
        $routeParams = $request->route()?->parameters();
        if ($routeParams) {
            foreach ($routeParams as $key => $value) {
                if ($key === 'id' || $key === 'tourId' || $key === 'userId' || $key === 'bookingId') {
                    $info['model_id'] = $value;
                    break;
                }
            }
        }

        // Try to get model type from route name
        $routeName = $request->route()?->getName();
        if ($routeName) {
            $parts = explode('.', $routeName);
            if (count($parts) >= 2) {
                $module = $parts[1];
                $modelClass = 'App\\Models\\' . ucfirst(Str::singular($module));
                if (class_exists($modelClass)) {
                    $info['model_type'] = $modelClass;
                    if (isset($info['model_id'])) {
                        try {
                            $model = $modelClass::find($info['model_id']);
                            if ($model) {
                                $info['model_name'] = $model->name ?? $model->title ?? (method_exists($model, '__toString') ? (string)$model : null);
                            }
                        } catch (\Exception $e) {
                            // Ignore
                        }
                    }
                }
            }
        }

        return $info;
    }

    /**
     * Get description for audit trail
     */
    private function getDescription(Request $request, string $action, array $modelInfo): string
    {
        $routeName = $request->route()?->getName();
        $module = $this->getModuleFromRoute($routeName);
        
        $description = ucfirst($action);
        if ($module) {
            $description .= ' ' . Str::singular($module);
        }
        if ($modelInfo['model_name'] ?? null) {
            $description .= ': ' . $modelInfo['model_name'];
        }

        return $description;
    }

    /**
     * Sanitize request data (remove sensitive fields)
     */
    private function sanitizeRequestData(array $data): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'current_password', 'token', 'api_key', 'secret'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***HIDDEN***';
            }
        }

        return $data;
    }
}

