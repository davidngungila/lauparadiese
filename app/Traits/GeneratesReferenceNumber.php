<?php

namespace App\Traits;

use App\Models\OrganizationSetting;

trait GeneratesReferenceNumber
{
    /**
     * Generate a unique reference number in format {PREFIX}{YYYYMMDD}-{HHMM}-{NNN}
     * Example: BK20251112-0721-001, INV20251112-0721-001, QTN20251112-0721-001
     * Where:
     * - PREFIX: Configurable prefix from organization settings or default
     * - YYYYMMDD: Date (20251112)
     * - HHMM: Time in 24-hour format (0721)
     * - NNN: Unique sequential number for the day (001, 002, 003, ...)
     * 
     * @param string $defaultPrefix Default prefix if not found in settings
     * @param string $prefixKey Key in organization settings (e.g., 'invoice_prefix', 'quotation_prefix')
     * @param string $referenceField Field name in the model (e.g., 'invoice_number', 'quotation_number')
     * @return string
     */
    public static function generateReferenceNumber(string $defaultPrefix, string $prefixKey = null, string $referenceField = null): string
    {
        $now = now();
        $date = $now->format('Ymd'); // YYYYMMDD
        $time = $now->format('Hi');   // HHMM (24-hour format)
        
        // Get the prefix from organization settings or use default
        $prefix = $defaultPrefix;
        if ($prefixKey) {
            try {
                $orgSettings = OrganizationSetting::getSettings();
                if ($orgSettings && isset($orgSettings->$prefixKey) && $orgSettings->$prefixKey) {
                    $prefix = $orgSettings->$prefixKey;
                }
            } catch (\Exception $e) {
                // Use default prefix if settings not available
            }
        }
        
        // Determine the reference field name
        if (!$referenceField) {
            // Try to guess from common patterns
            $modelName = class_basename(static::class);
            if (str_contains($modelName, 'Invoice')) {
                $referenceField = 'invoice_number';
            } elseif (str_contains($modelName, 'Quotation')) {
                $referenceField = 'quotation_number';
            } elseif (str_contains($modelName, 'Booking')) {
                $referenceField = 'booking_reference';
            } else {
                $referenceField = 'reference_number';
            }
        }
        
        // Count records created today to get the next sequential number for the day
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        
        // Get all records created today with the same date prefix
        $recordsToday = static::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where($referenceField, 'like', $prefix . $date . '-%')
            ->orderBy('id', 'desc')
            ->get();
        
        // Extract the highest counter number from today's records
        $maxCounter = 0;
        $pattern = '/^' . preg_quote($prefix, '/') . $date . '-\d{4}-(\d+)$/';
        foreach ($recordsToday as $record) {
            $referenceValue = $record->$referenceField;
            if (preg_match($pattern, $referenceValue, $matches)) {
                $counter = (int) $matches[1];
                if ($counter > $maxCounter) {
                    $maxCounter = $counter;
                }
            }
        }
        
        // Start counter from 1, increment if records exist
        $counter = $maxCounter + 1;
        
        // Generate reference and ensure uniqueness
        $attempts = 0;
        $maxAttempts = 1000; // Prevent infinite loop
        do {
            $reference = $prefix . $date . '-' . $time . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $exists = static::where($referenceField, $reference)->exists();
            if ($exists) {
                $counter++;
                $attempts++;
                // If we've tried many times, update time and reset attempts
                if ($attempts > 100 && $attempts % 100 == 0) {
                    $now = now();
                    $time = $now->format('Hi');
                }
                // Safety check to prevent infinite loop
                if ($attempts > $maxAttempts) {
                    // Fallback: use microtime to ensure uniqueness
                    $time = $now->format('Hi') . substr((string)microtime(true), -3, 2);
                    $counter = 1;
                    break;
                }
            }
        } while ($exists);

        return $reference;
    }
}


