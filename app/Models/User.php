<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'avatar',
        'phone',
        'mobile',
        'whatsapp_number',
        'address',
        'city',
        'country',
        'nationality',
        'date_of_birth',
        'gender',
        'passport_number',
        'passport_expiry',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'preferred_destination',
        'preferred_tour_type',
        'preferred_budget',
        'special_needs',
        'customer_status',
        'assigned_consultant_id',
        'internal_notes',
        'bio',
        'social_links',
        'timezone',
        'currency',
        'language',
        'billing_address',
        'billing_city',
        'billing_country',
        'billing_postal_code',
        'payment_method',
        'email_notifications',
        'sms_notifications',
        'push_notifications',
        'booking_notifications',
        'payment_notifications',
        'marketing_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
            'preferred_budget' => 'decimal:2',
            'social_links' => 'array',
        ];
    }

    /**
     * Get user initials for avatar fallback
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->name);
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Get avatar URL or return initials
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return '';
    }

    /**
     * Get all of the posts for the User.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all bookings for the User.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all payments for the User through bookings.
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Payment::class,      // Final model (Payment)
            Booking::class,       // Intermediate model (Booking)
            'user_id',           // Foreign key on bookings table (booking.user_id)
            'booking_id',        // Foreign key on payments table (payment.booking_id)
            'id',                // Local key on users table (user.id)
            'id'                 // Local key on bookings table (booking.id)
        );
    }

    /**
     * Get all invoices for the User.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all reviews for the User.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all vehicles assigned to the User (as driver).
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'driver_id');
    }

    /**
     * Get the driver profile for this user.
     */
    public function driverProfile(): HasOne
    {
        return $this->hasOne(DriverProfile::class);
    }

    /**
     * Get all transport bookings assigned to this driver.
     */
    public function transportBookings(): HasMany
    {
        return $this->hasMany(TransportBooking::class, 'driver_id');
    }

    /**
     * Check if user is admin (using Spatie roles)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('System Administrator');
    }

    /**
     * Get customer groups
     */
    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class, 'customer_group_user')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    /**
     * Get assigned consultant
     */
    public function assignedConsultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_consultant_id');
    }

    /**
     * Get customers assigned to this consultant
     */
    public function assignedCustomers(): HasMany
    {
        return $this->hasMany(User::class, 'assigned_consultant_id');
    }

    /**
     * Get customer feedback
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(CustomerFeedback::class, 'customer_id');
    }

    /**
     * Get customer messages
     */
    public function messages(): HasMany
    {
        return $this->hasMany(CustomerMessage::class, 'customer_id');
    }

    /**
     * Get assigned messages (as staff)
     */
    public function assignedMessages(): HasMany
    {
        return $this->hasMany(CustomerMessage::class, 'assigned_staff_id');
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim(($this->first_name ?? '') . ' ' . ($this->middle_name ?? '') . ' ' . ($this->last_name ?? ''));
        }
        return $this->name;
    }

    /**
     * Get total spend
     */
    public function getTotalSpendAttribute(): float
    {
        return $this->bookings()->where('status', '!=', 'cancelled')->sum('total_price') ?? 0;
    }
}
