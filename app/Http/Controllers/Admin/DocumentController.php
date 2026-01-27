<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\TourOperation;
use App\Models\OrganizationSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use App\Models\SystemSetting;
use App\Models\EmailAccount;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;

class DocumentController extends BaseAdminController
{
    // ==================== BOOKING & RESERVATION DOCUMENTS ====================
    
    /**
     * Generate Booking Confirmation Voucher
     */
    public function bookingConfirmationVoucher($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.booking-confirmation-voucher', compact('booking'));
        return $pdf->download('booking-confirmation-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Tour Voucher / Service Voucher
     */
    public function tourVoucher($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.tour-voucher', compact('booking'));
        return $pdf->download('tour-voucher-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Payment Receipt
     */
    public function paymentReceipt($id)
    {
        $payment = Payment::with(['booking', 'invoice'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.payment-receipt', compact('payment'));
        return $pdf->download('payment-receipt-' . $payment->id . '.pdf');
    }
    
    /**
     * Generate Proforma Invoice
     */
    public function proformaInvoice($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.proforma-invoice', compact('booking'));
        return $pdf->download('proforma-invoice-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Final Invoice
     */
    public function finalInvoice($id)
    {
        $invoice = Invoice::with(['booking', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.final-invoice', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
    
    /**
     * Generate E-ticket
     */
    public function eticket($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.eticket', compact('booking'));
        return $pdf->download('eticket-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Cancellation Notice
     */
    public function cancellationNotice($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.cancellation-notice', compact('booking'));
        return $pdf->download('cancellation-notice-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Refund Receipt
     */
    public function refundReceipt($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.refund-receipt', compact('booking'));
        return $pdf->download('refund-receipt-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Travel Checklist
     */
    public function travelChecklist($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.travel-checklist', compact('booking'));
        return $pdf->download('travel-checklist-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Booking Amendment Letter
     */
    public function bookingAmendment($id, Request $request)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $amendmentType = $request->get('type', 'general'); // date_change, pax_change, general
        $pdf = Pdf::loadView('pdf.documents.booking-amendment', compact('booking', 'amendmentType'));
        return $pdf->download('booking-amendment-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Completion Certificate / Congratulations Certificate
     */
    public function completionCertificate($id, Request $request = null)
    {
        $booking = Booking::with(['tour', 'tour.destination', 'user'])->findOrFail($id);
        $issueDate = $request ? ($request->get('issue_date') ? Carbon::parse($request->get('issue_date')) : now()) : now();
        $pdf = Pdf::loadView('pdf.documents.completion-certificate', compact('booking', 'issueDate'))
            ->setPaper('a4', 'portrait')
            ->setOption('enable-php', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        return $pdf->download('completion-certificate-' . $booking->booking_reference . '.pdf');
    }
    
    // ==================== INTERNAL BOOKING DOCUMENTS ====================
    
    /**
     * Generate Booking Sheet
     */
    public function bookingSheet($id)
    {
        $booking = Booking::with(['tour', 'user', 'assignedStaff'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.internal.booking-sheet', compact('booking'));
        return $pdf->download('booking-sheet-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Daily Departure Manifest
     */
    public function dailyDepartureManifest(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $bookings = Booking::with(['tour', 'user'])
            ->where('departure_date', $date)
            ->where('status', 'confirmed')
            ->get();
        $pdf = Pdf::loadView('pdf.documents.internal.daily-departure-manifest', compact('bookings', 'date'));
        return $pdf->download('departure-manifest-' . $date . '.pdf');
    }
    
    /**
     * Generate Passenger List
     */
    public function passengerList($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.internal.passenger-list', compact('booking'));
        return $pdf->download('passenger-list-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Rooming List
     */
    public function roomingList(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $bookings = Booking::with(['tour', 'user'])
            ->where('departure_date', $date)
            ->where('status', 'confirmed')
            ->get();
        $pdf = Pdf::loadView('pdf.documents.internal.rooming-list', compact('bookings', 'date'));
        return $pdf->download('rooming-list-' . $date . '.pdf');
    }
    
    /**
     * Generate Transport Allocation Sheet
     */
    public function transportAllocationSheet(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $bookings = Booking::with(['tour', 'user', 'tourOperations.vehicle'])->where('departure_date', $date)->get();
        $pdf = Pdf::loadView('pdf.documents.internal.transport-allocation', compact('bookings', 'date'));
        return $pdf->download('transport-allocation-' . $date . '.pdf');
    }
    
    /**
     * Generate Guide Assignment Form
     */
    public function guideAssignmentForm(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $bookings = Booking::with(['tour', 'assignedStaff'])->where('departure_date', $date)->get();
        $pdf = Pdf::loadView('pdf.documents.internal.guide-assignment', compact('bookings', 'date'));
        return $pdf->download('guide-assignment-' . $date . '.pdf');
    }
    
    // ==================== TOUR PACKAGE DOCUMENTS ====================
    
    /**
     * Generate Tour Overview Document
     */
    public function tourOverview($id)
    {
        $tour = Tour::with(['destination', 'itineraries'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.tour-overview', compact('tour'));
        return $pdf->download('tour-overview-' . $tour->slug . '.pdf');
    }
    
    /**
     * Generate Detailed Itinerary
     */
    public function detailedItinerary($id)
    {
        $tour = Tour::with(['destination', 'itineraries'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.detailed-itinerary', compact('tour'));
        return $pdf->download('detailed-itinerary-' . $tour->slug . '.pdf');
    }
    
    /**
     * Generate Tour Pricing Sheet
     */
    public function tourPricingSheet($id)
    {
        $tour = Tour::with(['pricings', 'destination'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.tour-pricing-sheet', compact('tour'));
        return $pdf->download('tour-pricing-' . $tour->slug . '.pdf');
    }
    
    /**
     * Generate Tour Availability Calendar
     */
    public function tourAvailabilityCalendar($id)
    {
        $tour = Tour::with(['availabilities'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.tour-availability-calendar', compact('tour'));
        return $pdf->download('tour-availability-' . $tour->slug . '.pdf');
    }
    
    /**
     * Generate Inclusion/Exclusion List
     */
    public function inclusionExclusionList($id)
    {
        $tour = Tour::findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.inclusion-exclusion-list', compact('tour'));
        return $pdf->download('inclusion-exclusion-' . $tour->slug . '.pdf');
    }
    
    /**
     * Generate Terms & Conditions
     */
    public function termsConditions($id)
    {
        $tour = Tour::findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.terms-conditions', compact('tour'));
        return $pdf->download('terms-conditions-' . $tour->slug . '.pdf');
    }
    
    // ==================== OPERATIONS DOCUMENTS ====================
    
    /**
     * Generate Daily Operation Plan
     */
    public function dailyOperationPlan(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $operations = TourOperation::with(['booking.tour', 'vehicle', 'guide'])
            ->whereDate('operation_date', $date)
            ->get();
        $pdf = Pdf::loadView('pdf.documents.operations.daily-operation-plan', compact('operations', 'date'));
        return $pdf->download('daily-operation-plan-' . $date . '.pdf');
    }
    
    /**
     * Generate Guide Briefing Notes
     */
    public function guideBriefingNotes($id)
    {
        $booking = Booking::with(['tour', 'tourOperations'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.operations.guide-briefing-notes', compact('booking'));
        return $pdf->download('guide-briefing-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Driver Movement Sheet
     */
    public function driverMovementSheet(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $operations = TourOperation::with(['booking.tour', 'vehicle'])->whereDate('operation_date', $date)->get();
        $pdf = Pdf::loadView('pdf.documents.operations.driver-movement-sheet', compact('operations', 'date'));
        return $pdf->download('driver-movement-' . $date . '.pdf');
    }
    
    /**
     * Generate Meal Plan Report
     */
    public function mealPlanReport(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $bookings = Booking::with(['tour'])->where('departure_date', $date)->where('status', 'confirmed')->get();
        $pdf = Pdf::loadView('pdf.documents.operations.meal-plan-report', compact('bookings', 'date'));
        return $pdf->download('meal-plan-report-' . $date . '.pdf');
    }
    
    /**
     * Generate Park Fees Summary
     */
    public function parkFeesSummary(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $bookings = Booking::with(['tour'])->where('departure_date', $date)->where('status', 'confirmed')->get();
        $pdf = Pdf::loadView('pdf.documents.operations.park-fees-summary', compact('bookings', 'date'));
        return $pdf->download('park-fees-summary-' . $date . '.pdf');
    }
    
    // ==================== FINANCE DOCUMENTS ====================
    
    /**
     * Generate Credit Note
     */
    public function creditNote($id)
    {
        $invoice = Invoice::with(['booking'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.finance.credit-note', compact('invoice'));
        return $pdf->download('credit-note-' . $invoice->invoice_number . '.pdf');
    }
    
    /**
     * Generate Supplier Payment Voucher
     */
    public function supplierPaymentVoucher($id)
    {
        $expense = Expense::findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.finance.supplier-payment-voucher', compact('expense'));
        return $pdf->download('supplier-payment-voucher-' . $expense->id . '.pdf');
    }
    
    /**
     * Generate Commission Statement
     */
    public function commissionStatement(Request $request)
    {
        $agentId = $request->get('agent_id');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());
        
        $bookings = Booking::with(['tour', 'assignedStaff'])
            ->where('assigned_staff_id', $agentId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'confirmed')
            ->get();
        
        $pdf = Pdf::loadView('pdf.documents.finance.commission-statement', compact('bookings', 'agentId', 'dateFrom', 'dateTo'));
        return $pdf->download('commission-statement-' . $dateFrom . '-to-' . $dateTo . '.pdf');
    }
    
    /**
     * Generate Revenue Report
     */
    public function revenueReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());
        
        $payments = Payment::with(['booking'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->get();
        
        $pdf = Pdf::loadView('pdf.documents.finance.revenue-report', compact('payments', 'dateFrom', 'dateTo'));
        return $pdf->download('revenue-report-' . $dateFrom . '-to-' . $dateTo . '.pdf');
    }
    
    /**
     * Generate Daily Cash Collection Report
     */
    public function dailyCashCollectionReport(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $payments = Payment::with(['booking'])
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();
        
        $pdf = Pdf::loadView('pdf.documents.finance.daily-cash-collection', compact('payments', 'date'));
        return $pdf->download('daily-cash-collection-' . $date . '.pdf');
    }
    
    /**
     * Generate Profit & Loss per Tour
     */
    public function profitLossPerTour($id)
    {
        $tour = Tour::with(['bookings.payments'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.finance.profit-loss-tour', compact('tour'));
        return $pdf->download('profit-loss-tour-' . $tour->slug . '.pdf');
    }
    
    /**
     * Generate Profit & Loss per Month
     */
    public function profitLossPerMonth(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $pdf = Pdf::loadView('pdf.documents.finance.profit-loss-month', compact('month'));
        return $pdf->download('profit-loss-month-' . $month . '.pdf');
    }
    
    /**
     * Generate Expense Breakdown
     */
    public function expenseBreakdown(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());
        
        $expenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])->get();
        $pdf = Pdf::loadView('pdf.documents.finance.expense-breakdown', compact('expenses', 'dateFrom', 'dateTo'));
        return $pdf->download('expense-breakdown-' . $dateFrom . '-to-' . $dateTo . '.pdf');
    }
    
    /**
     * Generate Outstanding Payments List
     */
    public function outstandingPaymentsList(Request $request)
    {
        $invoices = Invoice::with(['booking'])
            ->where('status', '!=', 'paid')
            ->get();
        
        $pdf = Pdf::loadView('pdf.documents.finance.outstanding-payments', compact('invoices'));
        return $pdf->download('outstanding-payments-' . now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Generate Aging Report
     */
    public function agingReport(Request $request)
    {
        $type = $request->get('type', 'receivables'); // receivables or payables
        $pdf = Pdf::loadView('pdf.documents.finance.aging-report', compact('type'));
        return $pdf->download('aging-report-' . $type . '-' . now()->format('Y-m-d') . '.pdf');
    }
    
    // ==================== FLEET & TRANSPORT DOCUMENTS ====================
    
    /**
     * Generate Transport Booking Sheet
     */
    public function transportBookingSheet($id)
    {
        $booking = Booking::with(['tour', 'tourOperations.vehicle'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.fleet.transport-booking-sheet', compact('booking'));
        return $pdf->download('transport-booking-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Driver Assignment Document
     */
    public function driverAssignmentDocument(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $operations = TourOperation::with(['booking.tour', 'vehicle'])->whereDate('operation_date', $date)->get();
        $pdf = Pdf::loadView('pdf.documents.fleet.driver-assignment', compact('operations', 'date'));
        return $pdf->download('driver-assignment-' . $date . '.pdf');
    }
    
    /**
     * Generate Vehicle Logbook
     */
    public function vehicleLogbook($id, Request $request)
    {
        $vehicle = Vehicle::findOrFail($id);
        $date = $request->get('date', now()->toDateString());
        $operations = TourOperation::with(['booking.tour'])->where('vehicle_id', $id)->whereDate('operation_date', $date)->get();
        $pdf = Pdf::loadView('pdf.documents.fleet.vehicle-logbook', compact('vehicle', 'operations', 'date'));
        return $pdf->download('vehicle-logbook-' . $vehicle->registration_number . '-' . $date . '.pdf');
    }
    
    /**
     * Generate Fuel Request Voucher
     */
    public function fuelRequestVoucher(Request $request)
    {
        $vehicleId = $request->get('vehicle_id');
        $vehicle = Vehicle::findOrFail($vehicleId);
        $pdf = Pdf::loadView('pdf.documents.fleet.fuel-request-voucher', compact('vehicle'));
        return $pdf->download('fuel-request-' . $vehicle->registration_number . '.pdf');
    }
    
    /**
     * Generate Maintenance Report
     */
    public function maintenanceReport($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.fleet.maintenance-report', compact('vehicle'));
        return $pdf->download('maintenance-report-' . $vehicle->registration_number . '.pdf');
    }
    
    /**
     * Generate Vehicle Condition Checklist
     */
    public function vehicleConditionChecklist($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.fleet.vehicle-condition-checklist', compact('vehicle'));
        return $pdf->download('vehicle-checklist-' . $vehicle->registration_number . '.pdf');
    }
    
    /**
     * Generate Trip Manifest for Drivers
     */
    public function tripManifest($id)
    {
        $booking = Booking::with(['tour', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.documents.fleet.trip-manifest', compact('booking'));
        return $pdf->download('trip-manifest-' . $booking->booking_reference . '.pdf');
    }
    
    /**
     * Generate Transport Cost Report
     */
    public function transportCostReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());
        
        $operations = TourOperation::with(['booking.tour', 'vehicle'])
            ->whereBetween('operation_date', [$dateFrom, $dateTo])
            ->get();
        
        $pdf = Pdf::loadView('pdf.documents.fleet.transport-cost-report', compact('operations', 'dateFrom', 'dateTo'));
        return $pdf->download('transport-cost-report-' . $dateFrom . '-to-' . $dateTo . '.pdf');
    }
    
    // ==================== EMAIL SENDING METHODS ====================
    
    /**
     * Configure mail transport to disable SSL verification
     * Works with Symfony Mailer (Laravel 12 default)
     */
    private function configureSwiftMailerTransport()
    {
        // Set stream context to disable SSL verification
        // This works with Symfony Mailer (Laravel 12 default)
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);
    }
    
    /**
     * Configure mail settings from system settings or email account
     */
    private function configureMailSettings()
    {
        try {
            // Disable SSL certificate verification for development/testing
            // WARNING: Only use this in development, not in production
            $streamContext = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            // Set default stream context for all SSL connections
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            // Try to get email account first
            $emailAccount = EmailAccount::where('is_active', true)->first();
            
            if ($emailAccount) {
                $config = $emailAccount->getSmtpConfig();
                $port = $config['port'] ?? 587;
                // Use SSL on port 465 to avoid STARTTLS SSL verification issues
                $encryption = ($port == 465) ? 'ssl' : ($config['encryption'] ?? $emailAccount->smtp_encryption ?? 'tls');
                
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', $config['host'] ?? 'smtp.gmail.com');
                Config::set('mail.mailers.smtp.port', $port);
                Config::set('mail.mailers.smtp.username', $config['username'] ?? $emailAccount->username ?? '');
                Config::set('mail.mailers.smtp.password', $config['password'] ?? $emailAccount->password ?? '');
                Config::set('mail.mailers.smtp.encryption', $encryption);
                Config::set('mail.mailers.smtp.stream', [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ],
                ]);
                Config::set('mail.from.address', $emailAccount->email ?? env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
                Config::set('mail.from.name', $emailAccount->name ?? env('MAIL_FROM_NAME', 'Lau Paradise Adventures'));
                return;
            }
            
            // Fallback to environment variables
            // Try port 465 with SSL instead of 587 with TLS to avoid STARTTLS SSL issues
            $port = env('MAIL_PORT', 465); // Use 465 for SSL instead of 587 for TLS
            $encryption = $port == 465 ? 'ssl' : (env('MAIL_ENCRYPTION', 'tls'));
            
            Config::set('mail.default', env('MAIL_MAILER', 'smtp'));
            Config::set('mail.mailers.smtp.host', env('MAIL_HOST', 'smtp.gmail.com'));
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.username', env('MAIL_USERNAME', ''));
            Config::set('mail.mailers.smtp.password', env('MAIL_PASSWORD', ''));
            Config::set('mail.mailers.smtp.encryption', $encryption);
            
            // Set SSL stream options for Symfony Mailer
            Config::set('mail.mailers.smtp.stream', [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            // Also set stream context globally
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            Config::set('mail.from.address', env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
            Config::set('mail.from.name', env('MAIL_FROM_NAME', 'Lau Paradise Adventures'));
        } catch (\Exception $e) {
            Log::warning('Failed to configure mail settings: ' . $e->getMessage());
            // Use default env settings
        }
    }
    
    /**
     * Send Booking Confirmation Voucher via Email
     */
    public function sendBookingConfirmationVoucher($id, Request $request)
    {
        try {
            $booking = Booking::with(['tour', 'user'])->findOrFail($id);
            
            if (!$booking->customer_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email is required'
                ], 422);
            }
            
            // Configure mail settings
            $this->configureMailSettings();
            
            $pdf = Pdf::loadView('pdf.documents.booking-confirmation-voucher', compact('booking'));
            $pdfContent = $pdf->output();
            
            $subject = 'Booking Confirmation - ' . $booking->booking_reference;
            
            // Prepare email content
            $org = OrganizationSetting::getSettings();
            $emailContent = '<p>We are delighted to confirm your booking for:</p>';
            $emailContent .= '<table class="details-table">';
            $emailContent .= '<tr><td>Safari:</td><td><span class="highlight-blue">' . ($booking->tour ? $booking->tour->name : 'Tour Package') . '</span></td></tr>';
            $emailContent .= '<tr><td>Dates:</td><td><span class="highlight-blue">' . ($booking->departure_date ? $booking->departure_date->format('d-M-Y') : 'N/A');
            if ($booking->travel_end_date) {
                $emailContent .= ' to ' . $booking->travel_end_date->format('d-M-Y');
            }
            $emailContent .= '</span></td></tr>';
            $emailContent .= '<tr><td>Travelers:</td><td><span class="highlight-blue">' . $booking->travelers . ' ' . Str::plural('Person', $booking->travelers) . '</span></td></tr>';
            $emailContent .= '<tr><td>Booking Reference:</td><td><span class="highlight-red">' . $booking->booking_reference . '</span></td></tr>';
            $emailContent .= '</table>';
            
            $emailContent .= '<p>Your detailed itinerary and vouchers are attached.</p>';
            
            if ($booking->balance_amount && $booking->balance_amount > 0) {
                $emailContent .= '<div class="warning-box">';
                $emailContent .= '<p><strong>Next Step:</strong> Please review all documents and make the final balance payment of ';
                $emailContent .= '<span class="highlight-orange">' . ($booking->currency ?? 'USD') . ' ' . number_format($booking->balance_amount, 2) . '</span>';
                $emailContent .= ' by <span class="highlight-orange">' . ($booking->departure_date ? $booking->departure_date->copy()->subDays(14)->format('d-M-Y') : 'the due date') . '</span>.</p>';
                $emailContent .= '</div>';
            }
            
            // Prepare buttons
            $buttons = [
                [
                    'text' => '📄 View Booking Details',
                    'url' => route('admin.bookings.show', $booking->id),
                    'class' => 'cta-button'
                ]
            ];
            
            if ($booking->balance_amount && $booking->balance_amount > 0) {
                $buttons[] = [
                    'text' => '💳 Make Payment',
                    'url' => route('admin.bookings.show', $booking->id) . '#payment',
                    'class' => 'cta-button-success'
                ];
            }
            
            $buttons[] = [
                'text' => '📋 Download Confirmation',
                'url' => route('admin.documents.booking.confirmation-voucher', $booking->id),
                'class' => 'cta-button-secondary'
            ];
            
            // Configure SwiftMailer transport with SSL options
            $this->configureSwiftMailerTransport();
            
            Mail::send('emails.document-email', [
                'recipientName' => $booking->customer_name,
                'emailContent' => $emailContent,
                'attachmentName' => 'Booking Confirmation - ' . $booking->booking_reference . '.pdf',
                'documentType' => 'Booking Confirmation',
                'buttons' => $buttons,
                'quote' => 'Your adventure begins here!',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($mail) use ($booking, $subject, $pdfContent) {
                $mail->to($booking->customer_email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'booking-confirmation-' . $booking->booking_reference . '.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Booking confirmation sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation: ' . $e->getMessage(), [
                'booking_id' => $id,
                'email' => $booking->customer_email ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send Tour Voucher via Email
     */
    public function sendTourVoucher($id, Request $request)
    {
        try {
            $booking = Booking::with(['tour', 'user'])->findOrFail($id);
            
            if (!$booking->customer_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email is required'
                ], 422);
            }
            
            // Configure mail settings
            $this->configureMailSettings();
            
            $pdf = Pdf::loadView('pdf.documents.tour-voucher', compact('booking'));
            $pdfContent = $pdf->output();
            
            $subject = 'Tour Voucher - ' . $booking->booking_reference;
            
            // Prepare email content
            $org = OrganizationSetting::getSettings();
            $emailContent = '<p>Please find attached your <span class="highlight-blue">tour voucher</span> for booking <span class="highlight-red">' . $booking->booking_reference . '</span>.</p>';
            
            $emailContent .= '<div class="info-box">';
            $emailContent .= '<p><strong>Important:</strong> Please present this voucher upon arrival at the service location.</p>';
            $emailContent .= '</div>';
            
            $emailContent .= '<table class="details-table">';
            if ($booking->tour) {
                $emailContent .= '<tr><td>Tour:</td><td><span class="highlight-blue">' . $booking->tour->name . '</span></td></tr>';
            }
            if ($booking->departure_date) {
                $emailContent .= '<tr><td>Departure Date:</td><td><span class="highlight-blue">' . $booking->departure_date->format('F d, Y') . '</span></td></tr>';
            }
            $emailContent .= '<tr><td>Travelers:</td><td><span class="highlight-blue">' . $booking->travelers . ' ' . Str::plural('Person', $booking->travelers) . '</span></td></tr>';
            $emailContent .= '</table>';
            
            // Prepare buttons
            $buttons = [
                [
                    'text' => '📄 View Booking',
                    'url' => route('admin.bookings.show', $booking->id),
                    'class' => 'cta-button'
                ],
                [
                    'text' => '📋 View Itinerary',
                    'url' => $booking->tour ? route('admin.tours.show', $booking->tour->id) : '#',
                    'class' => 'cta-button-secondary'
                ]
            ];
            
            // Configure SwiftMailer transport with SSL options
            $this->configureSwiftMailerTransport();
            
            Mail::send('emails.document-email', [
                'recipientName' => $booking->customer_name,
                'emailContent' => $emailContent,
                'attachmentName' => 'Tour Voucher - ' . $booking->booking_reference . '.pdf',
                'documentType' => 'Tour Voucher',
                'buttons' => $buttons,
                'quote' => 'Adventure awaits! Present this voucher and let the journey begin.',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($mail) use ($booking, $subject, $pdfContent) {
                $mail->to($booking->customer_email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'tour-voucher-' . $booking->booking_reference . '.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Tour voucher sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send tour voucher: ' . $e->getMessage(), [
                'booking_id' => $id,
                'email' => $booking->customer_email ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send Payment Receipt via Email
     */
    public function sendPaymentReceipt($id, Request $request)
    {
        try {
            $payment = Payment::with(['booking', 'invoice', 'user'])->findOrFail($id);
            
            // Get email from request first, then fall back to payment/booking/user email
            $email = $request->input('email') 
                ?? $payment->booking->customer_email ?? null
                ?? $payment->customer_email ?? null
                ?? $payment->user->email ?? null;
            
            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email is required. Please provide an email address.'
                ], 422);
            }
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email address format'
                ], 422);
            }
            
            // Configure mail settings and SSL BEFORE any mail operations
            $this->configureMailSettings();
            $this->configureSwiftMailerTransport();
            
            $pdf = Pdf::loadView('pdf.documents.payment-receipt', compact('payment'));
            $pdfContent = $pdf->output();
            
            $subject = 'Payment Receipt - ' . ($payment->booking ? $payment->booking->booking_reference : 'Payment #' . $payment->id);
            
            // Prepare email content
            $org = OrganizationSetting::getSettings();
            $customerName = $payment->booking ? $payment->booking->customer_name : 'Valued Customer';
            
            $emailContent = '<p>Thank you for your payment! Please find attached your <span class="highlight-green">payment receipt</span>.</p>';
            
            $emailContent .= '<div class="info-box">';
            $emailContent .= '<table class="details-table">';
            $emailContent .= '<tr><td>Receipt Number:</td><td><span class="highlight-red">' . ($payment->payment_reference ?? 'REC-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT)) . '</span></td></tr>';
            $emailContent .= '<tr><td>Amount Paid:</td><td><span class="highlight-green" style="font-size: 18px;">' . ($payment->currency ?? 'USD') . ' ' . number_format($payment->amount, 2) . '</span></td></tr>';
            if ($payment->payment_method) {
                $emailContent .= '<tr><td>Payment Method:</td><td><span class="highlight-blue">' . ucfirst(str_replace('_', ' ', $payment->payment_method)) . '</span></td></tr>';
            }
            if ($payment->paid_at) {
                $emailContent .= '<tr><td>Payment Date:</td><td><span class="highlight-blue">' . $payment->paid_at->format('F d, Y H:i') . '</span></td></tr>';
            }
            $emailContent .= '</table>';
            $emailContent .= '</div>';
            
            // Prepare buttons
            $buttons = [];
            if ($payment->booking) {
                $buttons[] = [
                    'text' => '📄 View Booking',
                    'url' => route('admin.bookings.show', $payment->booking->id),
                    'class' => 'cta-button'
                ];
            }
            $buttons[] = [
                'text' => '📋 Download Receipt',
                'url' => route('admin.documents.payment.receipt', $payment->id),
                'class' => 'cta-button-success'
            ];
            
            // Set SSL context multiple times to ensure it's applied
            // Symfony Mailer may create new connections, so we need to set this right before sending
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            // Use Mail::send but ensure SSL context is set
            Mail::send('emails.document-email', [
                'recipientName' => $customerName,
                'emailContent' => $emailContent,
                'attachmentName' => 'Payment Receipt - ' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) . '.pdf',
                'documentType' => 'Payment Receipt',
                'buttons' => $buttons,
                'quote' => 'Your payment has been received and processed successfully.',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($message) use ($email, $subject, $pdfContent, $payment) {
                $message->to($email)
                       ->subject($subject)
                       ->attachData($pdfContent, 'payment-receipt-' . $payment->id . '.pdf', [
                           'mime' => 'application/pdf',
                       ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Payment receipt sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment receipt: ' . $e->getMessage(), [
                'payment_id' => $id,
                'email' => $email ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send Proforma Invoice via Email
     */
    public function sendProformaInvoice($id, Request $request)
    {
        try {
            $booking = Booking::with(['tour', 'user'])->findOrFail($id);
            
            if (!$booking->customer_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email is required'
                ], 422);
            }
            
            // Configure mail settings
            $this->configureMailSettings();
            
            $pdf = Pdf::loadView('pdf.documents.proforma-invoice', compact('booking'));
            $pdfContent = $pdf->output();
            
            $subject = 'Proforma Invoice - ' . $booking->booking_reference;
            
            // Prepare email content
            $org = OrganizationSetting::getSettings();
            $dueDate = $booking->departure_date ? min(now()->addDays(30), $booking->departure_date->copy()->subDays(1)) : now()->addDays(30);
            $balanceDue = ($booking->total_price - ($booking->discount_amount ?? 0)) - ($booking->deposit_amount ?? 0);
            
            $emailContent = '<p>Please find attached your <span class="highlight-blue">proforma invoice</span> for booking <span class="highlight-red">' . $booking->booking_reference . '</span>.</p>';
            
            $emailContent .= '<div class="warning-box">';
            $emailContent .= '<p><strong>Payment Due:</strong> <span class="highlight-orange">' . $dueDate->format('F d, Y') . '</span></p>';
            $emailContent .= '<p><strong>Amount Due:</strong> <span class="highlight-orange" style="font-size: 18px;">' . ($booking->currency ?? 'USD') . ' ' . number_format($balanceDue, 2) . '</span></p>';
            $emailContent .= '</div>';
            
            $emailContent .= '<table class="details-table">';
            $emailContent .= '<tr><td>Total Amount:</td><td><span class="highlight-blue">' . ($booking->currency ?? 'USD') . ' ' . number_format($booking->total_price, 2) . '</span></td></tr>';
            if ($booking->deposit_amount && $booking->deposit_amount > 0) {
                $emailContent .= '<tr><td>Deposit Paid:</td><td><span class="highlight-green">' . ($booking->currency ?? 'USD') . ' ' . number_format($booking->deposit_amount, 2) . '</span></td></tr>';
            }
            $emailContent .= '<tr><td>Balance Due:</td><td><span class="highlight-red" style="font-size: 18px;">' . ($booking->currency ?? 'USD') . ' ' . number_format($balanceDue, 2) . '</span></td></tr>';
            $emailContent .= '</table>';
            
            // Prepare buttons
            $buttons = [
                [
                    'text' => '💳 Make Payment',
                    'url' => route('admin.bookings.show', $booking->id) . '#payment',
                    'class' => 'cta-button-success'
                ],
                [
                    'text' => '📄 View Booking',
                    'url' => route('admin.bookings.show', $booking->id),
                    'class' => 'cta-button'
                ],
                [
                    'text' => '📋 Download Invoice',
                    'url' => route('admin.documents.booking.proforma-invoice', $booking->id),
                    'class' => 'cta-button-secondary'
                ]
            ];
            
            // Configure SwiftMailer transport with SSL options
            $this->configureSwiftMailerTransport();
            
            Mail::send('emails.document-email', [
                'recipientName' => $booking->customer_name,
                'emailContent' => $emailContent,
                'attachmentName' => 'Proforma Invoice - ' . $booking->booking_reference . '.pdf',
                'documentType' => 'Proforma Invoice',
                'buttons' => $buttons,
                'quote' => 'Please review the invoice and make payment by the due date to confirm your booking.',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($mail) use ($booking, $subject, $pdfContent) {
                $mail->to($booking->customer_email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'proforma-invoice-' . $booking->booking_reference . '.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Proforma invoice sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send proforma invoice: ' . $e->getMessage(), [
                'booking_id' => $id,
                'email' => $booking->customer_email ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send Final Invoice via Email
     */
    public function sendFinalInvoice($id, Request $request)
    {
        try {
            $invoice = Invoice::with(['booking', 'user'])->findOrFail($id);
            $email = $invoice->customer_email ?? $invoice->booking->customer_email ?? null;
            
            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email is required'
                ], 422);
            }
            
            // Configure mail settings
            $this->configureMailSettings();
            
            $pdf = Pdf::loadView('pdf.documents.final-invoice', compact('invoice'));
            $pdfContent = $pdf->output();
            
            $subject = 'Final Invoice - ' . $invoice->invoice_number;
            
            // Prepare email content
            $org = OrganizationSetting::getSettings();
            $paidAmount = $invoice->payments()->where('status', 'completed')->sum('amount') ?? 0;
            $balanceDue = ($invoice->total_amount ?? 0) - $paidAmount;
            
            $emailContent = '<p>Please find attached your <span class="highlight-blue">final invoice</span>.</p>';
            
            $emailContent .= '<table class="details-table">';
            $emailContent .= '<tr><td>Invoice Number:</td><td><span class="highlight-red">' . $invoice->invoice_number . '</span></td></tr>';
            $emailContent .= '<tr><td>Total Amount:</td><td><span class="highlight-blue">' . ($invoice->currency ?? 'USD') . ' ' . number_format($invoice->total_amount ?? 0, 2) . '</span></td></tr>';
            if ($paidAmount > 0) {
                $emailContent .= '<tr><td>Amount Paid:</td><td><span class="highlight-green">' . ($invoice->currency ?? 'USD') . ' ' . number_format($paidAmount, 2) . '</span></td></tr>';
            }
            $emailContent .= '<tr><td>Balance Due:</td><td><span class="highlight-' . ($balanceDue > 0 ? 'orange' : 'green') . '" style="font-size: 18px;">' . ($invoice->currency ?? 'USD') . ' ' . number_format($balanceDue, 2) . '</span></td></tr>';
            if ($invoice->due_date) {
                $emailContent .= '<tr><td>Due Date:</td><td><span class="highlight-orange">' . $invoice->due_date->format('F d, Y') . '</span></td></tr>';
            }
            $emailContent .= '</table>';
            
            if ($balanceDue == 0) {
                $emailContent .= '<div class="info-box">';
                $emailContent .= '<p><strong>✓ Invoice Fully Paid</strong> - Thank you for your payment!</p>';
                $emailContent .= '</div>';
            } else {
                $emailContent .= '<div class="warning-box">';
                $emailContent .= '<p><strong>Payment Required:</strong> Please make payment of <span class="highlight-orange">' . ($invoice->currency ?? 'USD') . ' ' . number_format($balanceDue, 2) . '</span> by the due date.</p>';
                $emailContent .= '</div>';
            }
            
            // Prepare buttons
            $buttons = [];
            if ($invoice->booking) {
                $buttons[] = [
                    'text' => '📄 View Booking',
                    'url' => route('admin.bookings.show', $invoice->booking->id),
                    'class' => 'cta-button'
                ];
            }
            if ($balanceDue > 0) {
                $buttons[] = [
                    'text' => '💳 Make Payment',
                    'url' => ($invoice->booking ? route('admin.bookings.show', $invoice->booking->id) : '#') . '#payment',
                    'class' => 'cta-button-success'
                ];
            }
            $buttons[] = [
                'text' => '📋 Download Invoice',
                'url' => route('admin.documents.invoice.final', $invoice->id),
                'class' => 'cta-button-secondary'
            ];
            
            // Configure SwiftMailer transport with SSL options
            $this->configureSwiftMailerTransport();
            
            Mail::send('emails.document-email', [
                'recipientName' => $invoice->customer_name,
                'emailContent' => $emailContent,
                'attachmentName' => 'Invoice - ' . $invoice->invoice_number . '.pdf',
                'documentType' => 'Final Invoice',
                'buttons' => $buttons,
                'quote' => $balanceDue == 0 ? 'Your invoice has been fully paid. Thank you!' : 'Please review the invoice and make payment by the due date.',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($mail) use ($email, $subject, $pdfContent, $invoice) {
                $mail->to($email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'invoice-' . $invoice->invoice_number . '.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Final invoice sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send final invoice: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send Travel Checklist via Email
     */
    public function sendTravelChecklist($id, Request $request)
    {
        try {
            $booking = Booking::with(['tour', 'user'])->findOrFail($id);
            
            if (!$booking->customer_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email is required'
                ], 422);
            }
            
            // Configure mail settings
            $this->configureMailSettings();
            
            $pdf = Pdf::loadView('pdf.documents.travel-checklist', compact('booking'));
            $pdfContent = $pdf->output();
            
            $subject = 'Travel Checklist - ' . $booking->booking_reference;
            
            // Prepare email content
            $org = OrganizationSetting::getSettings();
            $daysUntilDeparture = $booking->departure_date ? now()->diffInDays($booking->departure_date, false) : 0;
            
            $emailContent = '<p>Please find attached your <span class="highlight-blue">pre-departure travel checklist</span> for booking <span class="highlight-red">' . $booking->booking_reference . '</span>.</p>';
            
            $emailContent .= '<div class="info-box">';
            $emailContent .= '<p>This checklist will help you prepare for your upcoming trip. Please check off each item as you pack to ensure nothing is forgotten.</p>';
            if ($daysUntilDeparture > 0) {
                $emailContent .= '<p><strong>Days until departure:</strong> <span class="highlight-orange">' . $daysUntilDeparture . ' ' . Str::plural('day', $daysUntilDeparture) . '</span></p>';
            }
            $emailContent .= '</div>';
            
            $emailContent .= '<p>The checklist includes:</p>';
            $emailContent .= '<ul style="line-height: 2;">';
            $emailContent .= '<li>Essential documents (passport, visa, insurance)</li>';
            $emailContent .= '<li>Packing essentials (clothing, equipment, supplies)</li>';
            $emailContent .= '<li>Health & safety reminders</li>';
            $emailContent .= '<li>Important contact information</li>';
            $emailContent .= '</ul>';
            
            // Prepare buttons
            $buttons = [
                [
                    'text' => '📄 View Booking',
                    'url' => route('admin.bookings.show', $booking->id),
                    'class' => 'cta-button'
                ],
                [
                    'text' => '📋 Download Checklist',
                    'url' => route('admin.documents.booking.travel-checklist', $booking->id),
                    'class' => 'cta-button-secondary'
                ],
                [
                    'text' => '📞 Contact Us',
                    'url' => 'mailto:' . ($org->email ?? 'info@lauparadise.com'),
                    'class' => 'cta-button-success'
                ]
            ];
            
            // Configure SwiftMailer transport with SSL options
            $this->configureSwiftMailerTransport();
            
            Mail::send('emails.document-email', [
                'recipientName' => $booking->customer_name,
                'emailContent' => $emailContent,
                'attachmentName' => 'Travel Checklist - ' . $booking->booking_reference . '.pdf',
                'documentType' => 'Travel Checklist',
                'buttons' => $buttons,
                'quote' => 'Proper preparation is the key to a successful adventure!',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($mail) use ($booking, $subject, $pdfContent) {
                $mail->to($booking->customer_email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'travel-checklist-' . $booking->booking_reference . '.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Travel checklist sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send travel checklist: ' . $e->getMessage(), [
                'booking_id' => $id,
                'email' => $booking->customer_email ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send Completion Certificate via Email
     */
    public function sendCompletionCertificate($id, Request $request)
    {
        try {
            $booking = Booking::with(['tour', 'tour.destination', 'user'])->findOrFail($id);
            
            if (!$booking->customer_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email is required'
                ], 422);
            }
            
            // Configure mail settings
            $this->configureMailSettings();
            
            $issueDate = $request->get('issue_date') ? Carbon::parse($request->get('issue_date')) : now();
            $pdf = Pdf::loadView('pdf.documents.completion-certificate', compact('booking', 'issueDate'));
            $pdfContent = $pdf->output();
            
            $subject = '🎉 Congratulations! Your Tour Completion Certificate - ' . $booking->booking_reference;
            
            // Prepare email content
            $org = OrganizationSetting::getSettings();
            $emailContent = '<div style="text-align: center; margin-bottom: 30px;">';
            $emailContent .= '<h1 style="color: #3ea572; font-size: 32px; margin-bottom: 10px;">🎉 Congratulations! 🎉</h1>';
            $emailContent .= '</div>';
            
            $emailContent .= '<p style="font-size: 16px; line-height: 1.8;">';
            $emailContent .= 'Dear <strong>' . $booking->customer_name . '</strong>,';
            $emailContent .= '</p>';
            
            $emailContent .= '<p style="font-size: 16px; line-height: 1.8;">';
            $emailContent .= 'We are thrilled to present you with your <strong style="color: #3ea572;">Certificate of Congratulations</strong> for successfully completing your tour with us!';
            $emailContent .= '</p>';
            
            $emailContent .= '<div class="info-box" style="background: linear-gradient(135deg, #e6f4ed 0%, #c8e6c9 100%); padding: 20px; border-radius: 10px; border-left: 5px solid #3ea572; margin: 20px 0;">';
            $emailContent .= '<p style="margin: 0; font-size: 16px; font-weight: bold; color: #2d7a5f;">Your Adventure:</p>';
            if ($booking->tour) {
                $emailContent .= '<p style="margin: 10px 0 0; font-size: 18px; color: #3ea572;">' . $booking->tour->name . '</p>';
                if ($booking->tour->destination) {
                    $emailContent .= '<p style="margin: 5px 0 0; font-size: 14px; color: #666;">' . $booking->tour->destination->name . '</p>';
                }
            }
            if ($booking->departure_date && $booking->travel_end_date) {
                $emailContent .= '<p style="margin: 10px 0 0; font-size: 14px; color: #666;">';
                $emailContent .= 'From ' . $booking->departure_date->format('F d, Y') . ' to ' . $booking->travel_end_date->format('F d, Y');
                $emailContent .= '</p>';
            }
            $emailContent .= '</div>';
            
            $emailContent .= '<p style="font-size: 16px; line-height: 1.8;">';
            $emailContent .= 'Your enthusiasm, positive spirit, and participation made this journey truly memorable. We are honored to have been part of your adventure!';
            $emailContent .= '</p>';
            
            $emailContent .= '<p style="font-size: 16px; line-height: 1.8; margin-top: 20px;">';
            $emailContent .= 'Please find attached your <strong style="color: #3ea572;">Completion Certificate</strong> as a keepsake of this wonderful experience.';
            $emailContent .= '</p>';
            
            $emailContent .= '<div style="text-align: center; margin: 30px 0; padding: 20px; background: #fff9e6; border-left: 5px solid #d4af37; border-radius: 5px;">';
            $emailContent .= '<p style="font-style: italic; font-size: 18px; color: #856404; margin: 0;">';
            $emailContent .= '"Travel is the only thing you buy that makes you richer."';
            $emailContent .= '</p>';
            $emailContent .= '</div>';
            
            // Prepare buttons
            $buttons = [
                [
                    'text' => '📄 Download Certificate',
                    'url' => route('admin.documents.booking.completion-certificate', $booking->id),
                    'class' => 'cta-button-success'
                ],
                [
                    'text' => '📋 View Booking',
                    'url' => route('admin.bookings.show', $booking->id),
                    'class' => 'cta-button'
                ],
                [
                    'text' => '⭐ Share Your Experience',
                    'url' => route('reviews'),
                    'class' => 'cta-button-secondary'
                ]
            ];
            
            // Configure SwiftMailer transport with SSL options
            $this->configureSwiftMailerTransport();
            
            Mail::send('emails.document-email', [
                'recipientName' => $booking->customer_name,
                'emailContent' => $emailContent,
                'attachmentName' => 'Completion Certificate - ' . $booking->booking_reference . '.pdf',
                'documentType' => 'Completion Certificate',
                'buttons' => $buttons,
                'quote' => 'Thank you for choosing us for your adventure!',
                'companyName' => $org->organization_name ?? 'Lau Paradise Adventures',
                'companyAddress' => ($org->address ?? '') . ', ' . ($org->city ?? '') . ', ' . ($org->country ?? 'Tanzania'),
                'companyPhone' => $org->phone ?? null,
                'companyEmail' => $org->email ?? null,
                'companyWebsite' => $org->website ?? null,
            ], function($mail) use ($booking, $subject, $pdfContent) {
                $mail->to($booking->customer_email)
                     ->subject($subject)
                     ->attachData($pdfContent, 'completion-certificate-' . $booking->booking_reference . '.pdf', [
                         'mime' => 'application/pdf',
                     ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Completion certificate sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send completion certificate: ' . $e->getMessage(), [
                'booking_id' => $id,
                'email' => $booking->customer_email ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
}

