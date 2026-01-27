<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Quotation;

class DashboardController extends Controller
{
    /**
     * Show customer dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get customer bookings
        $bookings = Booking::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $upcomingBookings = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date', 'asc')
            ->limit(3)
            ->get();
        
        // Get quotations (use customer_email)
        $quotations = Quotation::where('customer_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get invoices
        $invoices = Invoice::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate statistics
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $upcomingBookingsCount = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where('departure_date', '>=', now())
            ->count();
        
        $pendingQuotations = Quotation::where('customer_email', $user->email)
            ->where('status', 'pending')
            ->count();
        
        $totalQuotations = Quotation::where('customer_email', $user->email)->count();
        
        $totalInvoices = Invoice::where('user_id', $user->id)->count();
        $unpaidInvoices = Invoice::where('user_id', $user->id)
            ->where('status', 'unpaid')
            ->count();
        
        // Calculate total spent
        $totalSpent = Invoice::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('total_amount');
        
        // Get recent activity (combine bookings, quotations, invoices)
        $recentActivity = collect();
        
        // Add bookings
        foreach ($bookings->take(3) as $booking) {
            $recentActivity->push([
                'type' => 'booking',
                'title' => 'Booking Created',
                'description' => 'Booking #' . ($booking->id ?? 'N/A') . ' - ' . ($booking->tour_name ?? 'Tour Package'),
                'date' => $booking->created_at,
                'status' => $booking->status ?? 'pending',
                'icon' => 'ri-calendar-check-line',
                'color' => 'primary',
            ]);
        }
        
        // Add quotations
        foreach ($quotations->take(2) as $quotation) {
            $recentActivity->push([
                'type' => 'quotation',
                'title' => 'Quotation Received',
                'description' => $quotation->quotation_number . ' - ' . ($quotation->tour_name ?? 'Tour Package'),
                'date' => $quotation->created_at,
                'status' => $quotation->status ?? 'pending',
                'icon' => 'ri-file-list-3-line',
                'color' => 'info',
            ]);
        }
        
        // Sort by date and take latest 5
        $recentActivity = $recentActivity->sortByDesc('date')->take(5)->values();
        
        $stats = [
            'total_bookings' => $totalBookings,
            'upcoming_bookings' => $upcomingBookingsCount,
            'pending_quotations' => $pendingQuotations,
            'total_quotations' => $totalQuotations,
            'total_invoices' => $totalInvoices,
            'unpaid_invoices' => $unpaidInvoices,
            'total_spent' => $totalSpent ?? 0,
        ];
        
        return view('customer.dashboard', compact(
            'user', 
            'bookings', 
            'upcomingBookings',
            'quotations',
            'invoices',
            'recentActivity',
            'stats'
        ));
    }
}

