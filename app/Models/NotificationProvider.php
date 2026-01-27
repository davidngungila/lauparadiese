<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_primary',
        'is_active',
        'sms_username',
        'sms_password',
        'sms_from',
        'sms_url',
        'sms_method',
        'mailer_type',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'connection_status',
        'last_tested_at',
        'last_test_result',
        'notes',
        'priority',
        'metadata',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'mail_port' => 'integer',
        'priority' => 'integer',
        'metadata' => 'array',
        'last_tested_at' => 'datetime',
    ];

    /**
     * Get the primary provider for a given type
     *
     * @param string $type 'sms' or 'email'
     * @return NotificationProvider|null
     */
    public static function getPrimary(string $type): ?self
    {
        return self::where('type', $type)
            ->where('is_primary', true)
            ->where('is_active', true)
            ->orderBy('priority', 'asc')
            ->first();
    }

    /**
     * Set this provider as primary (and unset others)
     *
     * @return bool
     */
    public function setAsPrimary(): bool
    {
        // Unset other primary providers of the same type
        self::where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set this as primary
        $this->is_primary = true;
        return $this->save();
    }

    /**
     * Get all active providers for a type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive(string $type)
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->orderBy('is_primary', 'desc')
            ->orderBy('priority', 'asc')
            ->get();
    }

    /**
     * Test connection status
     *
     * @return array
     */
    public function testConnection(): array
    {
        if ($this->type === 'sms') {
            return $this->testSmsConnection();
        } elseif ($this->type === 'email') {
            return $this->testEmailConnection();
        }

        return [
            'success' => false,
            'message' => 'Unknown provider type',
        ];
    }

    /**
     * Test SMS connection
     *
     * @return array
     */
    protected function testSmsConnection(): array
    {
        try {
            // Basic validation
            if (empty($this->sms_url) || empty($this->sms_username) || empty($this->sms_password)) {
                return [
                    'success' => false,
                    'message' => 'Missing required SMS credentials',
                ];
            }

            // Test URL accessibility
            $ch = curl_init($this->sms_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                return [
                    'success' => false,
                    'message' => 'Connection error: ' . $error,
                ];
            }

            // Update status
            $this->connection_status = ($httpCode >= 200 && $httpCode < 400) ? 'connected' : 'disconnected';
            $this->last_tested_at = now();
            $this->last_test_result = json_encode([
                'http_code' => $httpCode,
                'success' => $httpCode >= 200 && $httpCode < 400,
            ]);
            $this->save();

            return [
                'success' => $httpCode >= 200 && $httpCode < 400,
                'message' => $httpCode >= 200 && $httpCode < 400 
                    ? 'Connection successful (HTTP ' . $httpCode . ')' 
                    : 'Connection failed (HTTP ' . $httpCode . ')',
                'http_code' => $httpCode,
            ];
        } catch (\Exception $e) {
            $this->connection_status = 'disconnected';
            $this->last_tested_at = now();
            $this->last_test_result = json_encode(['error' => $e->getMessage()]);
            $this->save();

            return [
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test Email connection
     *
     * @return array
     */
    protected function testEmailConnection(): array
    {
        // Email connection testing would go here
        return [
            'success' => false,
            'message' => 'Email connection testing not implemented yet',
        ];
    }

    /**
     * Get connection status badge class
     *
     * @return string
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->connection_status) {
            'connected' => 'bg-label-success',
            'disconnected' => 'bg-label-danger',
            default => 'bg-label-secondary',
        };
    }

    /**
     * Get connection status icon
     *
     * @return string
     */
    public function getStatusIcon(): string
    {
        return match($this->connection_status) {
            'connected' => 'ri-checkbox-circle-line',
            'disconnected' => 'ri-close-circle-line',
            default => 'ri-question-line',
        };
    }
}






