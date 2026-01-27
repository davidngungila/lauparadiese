<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class AuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'model_name',
        'description',
        'old_values',
        'new_values',
        'changed_fields',
        'ip_address',
        'user_agent',
        'route',
        'method',
        'request_data',
        'status',
        'error_message',
        'module',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'request_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model instance
     */
    public function model()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Scope: Filter by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by module
     */
    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Static method to log an action
     */
    public static function log(array $data): ?self
    {
        try {
            // Check if table exists before attempting to log
            if (!\Schema::hasTable('audit_trails')) {
                return null;
            }
            
            return self::create([
                'user_id' => $data['user_id'] ?? auth()->id(),
                'action' => $data['action'],
                'model_type' => $data['model_type'] ?? null,
                'model_id' => $data['model_id'] ?? null,
                'model_name' => $data['model_name'] ?? null,
                'description' => $data['description'] ?? null,
                'old_values' => $data['old_values'] ?? null,
                'new_values' => $data['new_values'] ?? null,
                'changed_fields' => $data['changed_fields'] ?? null,
                'ip_address' => $data['ip_address'] ?? request()->ip(),
                'user_agent' => $data['user_agent'] ?? request()->userAgent(),
                'route' => $data['route'] ?? request()->route()?->getName(),
                'method' => $data['method'] ?? request()->method(),
                'request_data' => $data['request_data'] ?? null,
                'status' => $data['status'] ?? 'success',
                'error_message' => $data['error_message'] ?? null,
                'module' => $data['module'] ?? null,
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the application if logging fails
            \Log::error('Audit trail logging failed: ' . $e->getMessage());
            return null;
        }
    }
}

