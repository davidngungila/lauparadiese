<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_name', 'logo_url', 'address', 'city', 'state', 'country', 'postal_code',
        'phone', 'email', 'website', 'tax_id', 'registration_number',
        'bank_name', 'bank_country', 'iban', 'swift_code',
        'invoice_footer_note', 'invoice_terms', 'currency',
        'invoice_prefix', 'quotation_prefix', 'booking_prefix',
    ];

    /**
     * Get the organization settings (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();
        if (!$settings) {
            $settings = self::create([
                'organization_name' => 'Lau Paradise Adventures',
                'address' => 'Arusha, Tanzania',
                'currency' => 'USD',
            ]);
        }
        return $settings;
    }

    /**
     * Get full address as a string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->postal_code,
        ]);
        return implode(', ', $parts);
    }
}
