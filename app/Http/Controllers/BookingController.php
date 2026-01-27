<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Mail\BookingConfirmationMail; // Would be created in a real app
use App\Services\PaymentGateway; // A hypothetical payment service
use App\Services\PaymentGatewayFactory;
use App\Models\PaymentGateway as PaymentGatewayModel;

class BookingController extends Controller
{
    /**
     * Display the advanced booking wizard page.
     *
     * @return \Illuminate\View\View
     */
    public function wizard(Request $request): View
    {
        // Fetch available tours from database with relationships
        $tours = \App\Models\Tour::with(['destination', 'bookings' => function($query) {
                $query->whereIn('status', ['confirmed', 'pending_payment'])
                      ->where('departure_date', '>=', now());
            }])
            ->orderBy('is_featured', 'desc')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/hero-slider/safari-adventure.jpg'),
                    'description' => $tour->excerpt ?: substr($tour->description, 0, 150) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                    'rating' => $tour->rating ?? 4.5,
                    'fitness_level' => $tour->fitness_level ?? 'moderate',
                    'max_capacity' => $tour->max_capacity ?? 12,
                ];
            });

        // If a tour is specified in the URL, pre-select it
        $selectedTourId = $request->get('tour');
        if ($selectedTourId) {
            $tour = \App\Models\Tour::where('slug', $selectedTourId)
                ->orWhere('id', $selectedTourId)
                ->first();
            if ($tour) {
                $selectedTourId = $tour->id;
            }
        }

        return view('booking.wizard', [
            'tours' => $tours,
            'selectedTourId' => $selectedTourId,
        ]);
    }

    /**
     * Display the booking page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // Redirect to simplified booking if simple parameter is set
        if ($request->has('simple') || $request->get('simple') === '1') {
            return $this->simple($request);
        }
        
        return $this->wizard($request);
    }

    /**
     * Display the simplified booking page.
     *
     * @return \Illuminate\View\View
     */
    public function simple(Request $request): View
    {
        // Fetch available tours from database
        $tours = \App\Models\Tour::with(['destination'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/hero-slider/safari-adventure.jpg'),
                    'description' => $tour->excerpt ?: substr($tour->description, 0, 150) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                ];
            });

        // If a tour is specified in the URL, pre-select it
        $selectedTourId = null;
        $selectedTour = null;
        $tourSlug = $request->get('tour');
        
        if ($tourSlug) {
            $tour = \App\Models\Tour::where('slug', $tourSlug)
                ->orWhere('id', $tourSlug)
                ->first();
            if ($tour) {
                $selectedTourId = $tour->id;
                $selectedTour = [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) ($tour->starting_price ?? $tour->price),
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                    'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 150) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                ];
            }
        }

        // Get payment link URL from session if available
        $paymentLinkUrl = session('payment_link_url');
        $showPayment = session('show_payment', false);
        $bookingId = session('booking_id');

        return view('booking.simple', [
            'tours' => $tours,
            'selectedTourId' => $selectedTourId,
            'selectedTour' => $selectedTour,
            'paymentLinkUrl' => $paymentLinkUrl,
            'showPayment' => $showPayment,
            'bookingId' => $bookingId,
        ]);
    }

    /**
     * Calculate tour availability for the next 90 days
     */
    private function calculateTourAvailability($tour): array
    {
        $availability = [];
        $maxCapacity = $tour->max_capacity ?? 12;
        
        for ($i = 7; $i <= 90; $i += 7) {
            $date = now()->addDays($i)->format('Y-m-d');
            $bookedTravelers = Booking::forTourAndDate($tour->id, $date)->sum('travelers');
            $available = max(0, $maxCapacity - $bookedTravelers);
            
            $availability[] = [
                'date' => $date,
                'available' => $available,
                'booked' => $bookedTravelers,
                'status' => $available > 0 ? 'available' : 'full'
            ];
        }
        
        return $availability;
    }

    /**
     * Handle the final submission of the booking form.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request): RedirectResponse
    {
        // 1. VALIDATION
        // ============================================
        $validator = Validator::make($request->all(), [
            'tourId' => 'required|integer|min:1',
            'date' => 'required|date|after_or_equal:today',
            'travelers' => 'required|integer|min:1|max:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|string|max:20',
            'addons' => 'nullable|array',
            'addons.*' => 'string|in:insurance,gear',
            'payment_method' => 'required|string|in:card,pesapal,mpesa,tigopesa,bank_transfer,later',
            // Credit card fields are only required if the payment method is 'card' (not for Pesapal)
            'card-name' => 'required_if:payment_method,card|nullable|string|max:255',
            'card-number' => 'required_if:payment_method,card|nullable|string|regex:/^[0-9\s]{13,19}$/',
            'expiry' => 'required_if:payment_method,card|nullable|string|regex:/^(0[1-9]|1[0-2])\s?\/\s?([0-9]{2})$/',
            'cvc' => 'required_if:payment_method,card|nullable|string|regex:/^[0-9]{3,4}$/',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Please correct the errors and try again.');
        }

        $validated = $validator->validated();

        try {
            // 2. CHECK TOUR AVAILABILITY IN DATABASE
            // ============================================
            $tour = \App\Models\Tour::find($validated['tourId']);
            if (!$tour) {
                return back()->with('error', 'The selected tour is not available.');
            }

            // Check availability for the selected date
            $bookedTravelers = Booking::getTotalTravelersForDate($tour->id, $validated['date']);
            $maxCapacity = $tour->max_capacity ?? 12;
            $availableSpots = $maxCapacity - $bookedTravelers;

            if ($availableSpots < $validated['travelers']) {
                return back()->with('error', "Only {$availableSpots} spot(s) available for this date. Please select a different date or reduce the number of travelers.");
            }

            // 3. SERVER-SIDE PRICE CALCULATION (SECURITY)
            // ============================================
            $tourDetails = $this->getTourDetails($validated['tourId']);
            $addons_cost = $this->calculateAddonsCost($validated['addons'] ?? []);
            $basePrice = (float) $tour->price;
            $total_cost = ($basePrice * $validated['travelers']) + $addons_cost;
            
            // Calculate deposit (30% of total)
            $deposit_amount = $total_cost * 0.30;
            $balance_amount = $total_cost - $deposit_amount;


            // 4. SAVE BOOKING FIRST (BEFORE PAYMENT)
            // ============================================
            $booking = \DB::transaction(function () use ($validated, $request, $tour, $total_cost, $deposit_amount, $balance_amount) {
                $booking = Booking::create([
                    'tour_id' => $validated['tourId'],
                    'user_id' => Auth::id(),
                    'customer_name' => $validated['name'],
                    'customer_email' => $validated['email'],
                    'customer_phone' => $validated['phone'],
                    'customer_country' => $request->input('country', 'Tanzania'),
                    'travelers' => $validated['travelers'],
                    'departure_date' => $validated['date'],
                    'total_price' => $total_cost,
                    'deposit_amount' => $deposit_amount,
                    'balance_amount' => $balance_amount,
                    'currency' => 'USD',
                    'addons' => $validated['addons'] ?? [],
                    'status' => 'pending_payment', // Will be updated after payment
                    'payment_method' => 'stripe',
                    'notes' => $request->input('notes', ''),
                    'special_requirements' => $request->input('special_requirements', ''),
                    'emergency_contact_name' => $request->input('emergency_contact_name', ''),
                    'emergency_contact_phone' => $request->input('emergency_contact_phone', ''),
                ]);

                return $booking;
            });

            Log::info('Booking #' . $booking->id . ' created successfully.');

            // 5. CREATE STRIPE PAYMENT LINK AND REDIRECT
            // ============================================
            $stripeGateway = PaymentGatewayModel::byName('stripe');
            
            if (!$stripeGateway || !$stripeGateway->is_active) {
                return back()->with('error', 'Payment gateway not configured. Please contact support.');
            }
            
            try {
                $stripeService = PaymentGatewayFactory::create($stripeGateway);
                
                $paymentData = [
                    'amount' => $total_cost,
                    'currency' => 'usd',
                    'description' => "Booking #{$booking->id} - {$tour->name} - {$validated['travelers']} traveler(s)",
                    'booking_id' => $booking->id,
                    'email' => $validated['email'],
                    'name' => $validated['name'],
                    'success_url' => route('booking.confirmation', $booking->id),
                ];
                
                $paymentLinkResult = $stripeService->createPaymentLink($paymentData);
                
                if ($paymentLinkResult['success'] && isset($paymentLinkResult['payment_link_url'])) {
                    // Store payment link ID in booking for tracking
                    $booking->update([
                        'payment_gateway_id' => $paymentLinkResult['payment_link_id'] ?? 'pending',
                    ]);
                    
                    Log::info('Stripe payment link created for booking', [
                        'booking_id' => $booking->id,
                        'payment_link_url' => $paymentLinkResult['payment_link_url'],
                    ]);
                    
                    // Return to payment step with payment link URL
                    return redirect()->route('booking')->with([
                        'payment_link_url' => $paymentLinkResult['payment_link_url'],
                        'booking_id' => $booking->id,
                        'show_payment' => true,
                    ])->withInput();
                } else {
                    return back()->with('error', $paymentLinkResult['error'] ?? 'Failed to create payment link. Please try again.');
                }
            } catch (\Exception $e) {
                Log::error('Stripe payment link creation error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return back()->with('error', 'Payment system error. Please try again or contact support.');
            }


            // 6. SEND PENDING PAYMENT NOTIFICATION
            // ============================================
            // Note: Full confirmation notifications will be sent after payment is confirmed via webhook
            try {
                $notificationService = app(NotificationService::class);
                $bookingRef = $booking->booking_reference;
                $tourName = $tour->name;
                $departureDate = $booking->departure_date->format('F j, Y');
                $totalPrice = number_format($total_cost, 2);

                // Customer notification
                $customerSMS = "Hello {$validated['name']}, your booking #{$bookingRef} for {$tourName} on {$departureDate} has been received. Total: \${$totalPrice}. Please complete payment to confirm your booking.";
                
                $notificationService->notifyPhone(
                    $validated['phone'],
                    $customerSMS,
                    $validated['email'],
                    "Booking Received - {$tourName}",
                    ['booking' => $booking, 'tour' => $tour]
                );

                // Send email
                Mail::to($booking->customer_email)->send(new BookingConfirmationMail($booking));
                Log::info('Booking pending payment email sent', [
                    'booking_id' => $booking->id,
                    'email' => $booking->customer_email,
                ]);
            } catch (\Exception $e) {
                Log::warning('Notification sending failed: ' . $e->getMessage());
            }


        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Booking submission failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->with('error', 'An unexpected error occurred. Our team has been notified. Please try again or contact us directly.')->withInput();
        }

        // Note: Redirect to Payment Link happens in step 5 above
        // This code should not be reached, but kept as fallback
    }

    /**
     * Helper function to get tour details from database.
     */
    private function getTourDetails(int $tourId): ?array
    {
        $tour = \App\Models\Tour::with('destination')->find($tourId);
        
        if (!$tour) {
            return null;
        }

        return [
            'id' => $tour->id,
            'name' => $tour->name,
            'slug' => $tour->slug,
            'price' => (float) $tour->price,
            'duration_days' => $tour->duration_days,
            'description' => $tour->description,
            'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
        ];
    }

    /**
     * Check tour availability via AJAX
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|integer|exists:tours,id',
            'date' => 'required|date|after_or_equal:today',
            'travelers' => 'required|integer|min:1|max:20',
        ]);

        $tour = \App\Models\Tour::find($request->tour_id);
        if (!$tour) {
            return response()->json(['available' => false, 'message' => 'Tour not found'], 404);
        }

        $bookedTravelers = Booking::getTotalTravelersForDate($tour->id, $request->date);
        $maxCapacity = $tour->max_capacity ?? 12;
        $availableSpots = $maxCapacity - $bookedTravelers;

        return response()->json([
            'available' => $availableSpots >= $request->travelers,
            'available_spots' => $availableSpots,
            'booked' => $bookedTravelers,
            'max_capacity' => $maxCapacity,
            'requested' => $request->travelers,
            'message' => $availableSpots >= $request->travelers 
                ? "Available! {$availableSpots} spot(s) remaining." 
                : "Only {$availableSpots} spot(s) available. Please select fewer travelers or choose another date."
        ]);
    }

    /**
     * Display booking confirmation page.
     *
     * @param int $booking
     * @return \Illuminate\View\View
     */
    public function confirmation(int $booking): View
    {
        $booking = Booking::with(['tour.destination', 'user'])
            ->findOrFail($booking);
        
        // Get related bookings for recommendations
        $relatedBookings = Booking::where('tour_id', $booking->tour_id)
            ->where('id', '!=', $booking->id)
            ->where('departure_date', '>=', now())
            ->with('tour')
            ->orderBy('departure_date', 'asc')
            ->take(3)
            ->get();
        
        return view('booking.confirmation', [
            'booking' => $booking,
            'relatedBookings' => $relatedBookings,
        ]);
    }

    /**
     * Helper function to calculate addon costs.
     */
    private function calculateAddonsCost(array $selectedAddons): int
    {
        $addons = ['insurance' => 150, 'gear' => 80];
        return collect($selectedAddons)->reduce(fn($total, $key) => $total + ($addons[$key] ?? 0), 0);
    }

    /**
     * Download invoice for a booking.
     *
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice(string $reference)
    {
        // Find booking by reference
        $booking = Booking::where('booking_reference', $reference)
            ->orWhere('id', $reference)
            ->with(['tour.destination', 'user'])
            ->firstOrFail();

        // Generate invoice data
        $invoiceData = $this->prepareInvoiceData($booking);

        // Generate PDF using DomPDF or return HTML view
        return $this->generateInvoicePDF($booking, $invoiceData);
    }

    /**
     * View invoice in browser.
     *
     * @param string $reference
     * @return \Illuminate\View\View
     */
    public function viewInvoice(string $reference)
    {
        $booking = Booking::where('booking_reference', $reference)
            ->orWhere('id', $reference)
            ->with(['tour.destination', 'user'])
            ->firstOrFail();

        $invoiceData = $this->prepareInvoiceData($booking);

        return view('booking.invoice', [
            'booking' => $booking,
            'invoice' => $invoiceData,
        ]);
    }

    /**
     * Prepare invoice data from booking.
     */
    private function prepareInvoiceData(Booking $booking): array
    {
        $tour = $booking->tour;
        $addons = $booking->addons ?? [];
        
        // Calculate line items
        $lineItems = [];
        
        // Base tour price
        $adultsPrice = ($tour->price ?? 0) * ($booking->number_of_adults ?? $booking->travelers);
        $childrenPrice = 0;
        if ($booking->number_of_children > 0) {
            $childrenPrice = (($tour->price ?? 0) * 0.5) * $booking->number_of_children;
        }
        
        $lineItems[] = [
            'description' => $tour->name ?? 'Tour Package',
            'quantity' => ($booking->number_of_adults ?? $booking->travelers) . ' Adult(s)',
            'unit_price' => $tour->price ?? 0,
            'total' => $adultsPrice,
        ];
        
        if ($booking->number_of_children > 0) {
            $lineItems[] = [
                'description' => 'Children (50% discount)',
                'quantity' => $booking->number_of_children . ' Child(ren)',
                'unit_price' => ($tour->price ?? 0) * 0.5,
                'total' => $childrenPrice,
            ];
        }
        
        // Add-ons
        $addonPrices = [
            'airport_pickup' => 150,
            'extra_day' => 300,
            'private_guide' => 200,
            'photography' => 250,
            'camping_gear' => 100,
            'travel_insurance' => 150,
        ];
        
        foreach ($addons as $addon) {
            if (isset($addonPrices[$addon])) {
                $lineItems[] = [
                    'description' => ucfirst(str_replace('_', ' ', $addon)),
                    'quantity' => 1,
                    'unit_price' => $addonPrices[$addon],
                    'total' => $addonPrices[$addon],
                ];
            }
        }
        
        // Accommodation upgrade
        if ($booking->accommodation_level === 'luxury') {
            $luxuryUpgrade = 500 * ($booking->number_of_adults ?? $booking->travelers);
            $lineItems[] = [
                'description' => 'Luxury Accommodation Upgrade',
                'quantity' => ($booking->number_of_adults ?? $booking->travelers) . ' Person(s)',
                'unit_price' => 500,
                'total' => $luxuryUpgrade,
            ];
        }
        
        // Calculate totals
        $subtotal = collect($lineItems)->sum('total');
        $discount = $booking->discount_amount ?? 0;
        $total = $subtotal - $discount;
        
        return [
            'invoice_number' => $booking->invoice ? $booking->invoice->invoice_number : Invoice::generateInvoiceNumber(),
            'invoice_date' => now()->format('F j, Y'),
            'due_date' => $booking->departure_date->format('F j, Y'),
            'booking_reference' => $booking->booking_reference,
            'customer' => [
                'name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
                'address' => ($booking->city ?? '') . ', ' . ($booking->customer_country ?? 'Tanzania'),
            ],
            'tour' => [
                'name' => $tour->name ?? 'Tour Package',
                'destination' => $tour->destination->name ?? 'Tanzania',
                'start_date' => $booking->departure_date->format('F j, Y'),
                'end_date' => $booking->travel_end_date ? $booking->travel_end_date->format('F j, Y') : 'N/A',
                'duration' => $tour->duration_days ?? 0,
            ],
            'line_items' => $lineItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'deposit' => $booking->deposit_amount ?? ($total * 0.3),
            'balance' => $booking->balance_amount ?? ($total * 0.7),
            'payment_status' => $booking->payment_status ?? 'pending',
            'status' => $booking->status,
        ];
    }

    /**
     * Generate invoice PDF.
     */
    private function generateInvoicePDF(Booking $booking, array $invoiceData)
    {
        // Check if DomPDF is available, otherwise return HTML view
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('booking.invoice', [
                'booking' => $booking,
                'invoice' => $invoiceData,
            ]);
            
            return $pdf->download('Invoice-' . $booking->booking_reference . '.pdf');
        }
        
        // Fallback: Return HTML view that can be printed
        return view('booking.invoice', [
            'booking' => $booking,
            'invoice' => $invoiceData,
        ]);
    }
}

