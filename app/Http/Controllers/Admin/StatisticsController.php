<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\Payment;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends BaseAdminController
{
    /**
     * Display Analytics page
     */
    public function analytics()
    {
        $user = auth()->user();
        
        // Get comprehensive analytics data
        $stats = $this->getAnalyticsStats($user);
        $monthlyBookings = $this->getMonthlyBookings();
        $tourPerformance = $this->getTourPerformance();
        $destinationStats = $this->getDestinationStats();
        $customerStats = $this->getCustomerStats();
        $revenueTrends = $this->getRevenueTrends();
        $bookingTrends = $this->getBookingTrends();
        
        return view('admin.statistics.analytics', compact(
            'stats',
            'monthlyBookings',
            'tourPerformance',
            'destinationStats',
            'customerStats',
            'revenueTrends',
            'bookingTrends'
        ));
    }
    
    /**
     * Display Revenue Summary page
     */
    public function revenueSummary()
    {
        $user = auth()->user();
        
        $revenueData = $this->getRevenueData($user);
        $monthlyRevenue = $this->getMonthlyRevenueData();
        $paymentMethods = $this->getPaymentMethodStats();
        $tourRevenue = $this->getTourRevenue();
        $destinationRevenue = $this->getDestinationRevenue();
        $yearlyComparison = $this->getYearlyComparison();
        
        return view('admin.statistics.revenue-summary', compact(
            'revenueData',
            'monthlyRevenue',
            'paymentMethods',
            'tourRevenue',
            'destinationRevenue',
            'yearlyComparison'
        ));
    }
    
    /**
     * Display Bookings Status page
     */
    public function bookingsStatus()
    {
        $user = auth()->user();
        
        $statusData = $this->getBookingStatusData();
        $statusTrends = $this->getStatusTrends();
        $recentBookings = $this->getRecentBookings();
        $statusBreakdown = $this->getStatusBreakdown();
        $cancellationReasons = $this->getCancellationReasons();
        $bookingSources = $this->getBookingSources();
        
        return view('admin.statistics.bookings-status', compact(
            'statusData',
            'statusTrends',
            'recentBookings',
            'statusBreakdown',
            'cancellationReasons',
            'bookingSources'
        ));
    }
    
    /**
     * Display Upcoming Trips page
     */
    public function upcomingTrips()
    {
        $user = auth()->user();
        
        $upcomingTrips = $this->getUpcomingTrips($user);
        $tripCalendar = $this->getTripCalendar();
        $tripStats = $this->getTripStats();
        $destinationTrips = $this->getDestinationTrips();
        $monthlyTrips = $this->getMonthlyTrips();
        
        return view('admin.statistics.upcoming-trips', compact(
            'upcomingTrips',
            'tripCalendar',
            'tripStats',
            'destinationTrips',
            'monthlyTrips'
        ));
    }
    
    // Helper methods for Analytics
    protected function getAnalyticsStats($user)
    {
        $baseQuery = Booking::query();
        
        if (!$user->hasRole('System Administrator')) {
            if ($user->hasRole('Travel Consultant')) {
                $baseQuery->where('user_id', $user->id);
            }
        }
        
        $totalBookings = (clone $baseQuery)->count();
        $confirmedBookings = (clone $baseQuery)->where('status', 'confirmed')->count();
        $pendingBookings = (clone $baseQuery)->where('status', 'pending_payment')->count();
        $totalRevenue = (clone $baseQuery)->where('status', 'confirmed')->sum('total_price');
        $totalTravelers = (clone $baseQuery)->where('status', 'confirmed')->sum('travelers');
        $avgBookingValue = (clone $baseQuery)->where('status', 'confirmed')->avg('total_price');
        
        // Growth calculations
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        $currentMonthBookings = (clone $baseQuery)->whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonthBookings = (clone $baseQuery)->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $bookingGrowth = $lastMonthBookings > 0 ? (($currentMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100 : 0;
        
        $currentMonthRevenue = (clone $baseQuery)->where('status', 'confirmed')->whereMonth('created_at', Carbon::now()->month)->sum('total_price');
        $lastMonthRevenue = (clone $baseQuery)->where('status', 'confirmed')->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('total_price');
        $revenueGrowth = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        
        return [
            'total_bookings' => $totalBookings,
            'confirmed_bookings' => $confirmedBookings,
            'pending_bookings' => $pendingBookings,
            'total_revenue' => $totalRevenue,
            'total_travelers' => $totalTravelers,
            'avg_booking_value' => $avgBookingValue ? round($avgBookingValue, 2) : 0,
            'booking_growth' => round($bookingGrowth, 1),
            'revenue_growth' => round($revenueGrowth, 1),
            'total_tours' => Tour::count(),
            'active_destinations' => Tour::whereNotNull('destination_id')->distinct('destination_id')->count('destination_id'),
        ];
    }
    
    protected function getMonthlyBookings()
    {
        $bookings = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->get();
        
        $months = [];
        $amounts = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $bookings->firstWhere('month', $i);
            $months[] = Carbon::create()->month($i)->format('M');
            $amounts[] = $monthData ? (int) $monthData->total : 0;
        }
        
        return [
            'months' => $months,
            'amounts' => $amounts
        ];
    }
    
    protected function getTourPerformance()
    {
        return Tour::withCount('bookings')
            ->withSum('bookings', 'total_price')
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($tour) {
                return [
                    'name' => $tour->name,
                    'bookings' => $tour->bookings_count,
                    'revenue' => $tour->bookings_sum_total_price ?? 0,
                ];
            });
    }
    
    protected function getDestinationStats()
    {
        return Destination::withCount(['tours' => function($query) {
            $query->has('bookings');
        }])
        ->orderBy('tours_count', 'desc')
        ->limit(10)
        ->get();
    }
    
    protected function getCustomerStats()
    {
        return [
            'total' => User::whereHas('roles', function($q) {
                $q->where('name', 'Customer');
            })->orWhereDoesntHave('roles')->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
            'active' => Booking::distinct('user_id')->count('user_id'),
        ];
    }
    
    protected function getRevenueTrends()
    {
        $revenue = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('status', 'confirmed')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->get();
        
        $months = [];
        $amounts = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $revenue->firstWhere('month', $i);
            $months[] = Carbon::create()->month($i)->format('M');
            $amounts[] = $monthData ? (float) $monthData->total : 0;
        }
        
        return [
            'months' => $months,
            'amounts' => $amounts
        ];
    }
    
    protected function getBookingTrends()
    {
        $bookings = Booking::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        return [
            'dates' => $bookings->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'amounts' => $bookings->pluck('total')->toArray()
        ];
    }
    
    // Helper methods for Revenue Summary
    protected function getRevenueData($user)
    {
        $baseQuery = Booking::where('status', 'confirmed');
        
        if (!$user->hasRole('System Administrator')) {
            if ($user->hasRole('Travel Consultant')) {
                $baseQuery->where('user_id', $user->id);
            }
        }
        
        $totalRevenue = (clone $baseQuery)->sum('total_price');
        $thisMonthRevenue = (clone $baseQuery)->whereMonth('created_at', Carbon::now()->month)->sum('total_price');
        $lastMonthRevenue = (clone $baseQuery)->whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('total_price');
        $thisYearRevenue = (clone $baseQuery)->whereYear('created_at', Carbon::now()->year)->sum('total_price');
        
        $growth = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        
        return [
            'total' => $totalRevenue,
            'this_month' => $thisMonthRevenue,
            'last_month' => $lastMonthRevenue,
            'this_year' => $thisYearRevenue,
            'growth' => round($growth, 1),
        ];
    }
    
    protected function getMonthlyRevenueData()
    {
        $revenue = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('status', 'confirmed')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->get();
        
        $months = [];
        $amounts = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $revenue->firstWhere('month', $i);
            $months[] = Carbon::create()->month($i)->format('M');
            $amounts[] = $monthData ? (float) $monthData->total : 0;
        }
        
        return [
            'months' => $months,
            'amounts' => $amounts
        ];
    }
    
    protected function getPaymentMethodStats()
    {
        return Booking::select('payment_method', DB::raw('SUM(total_price) as total'), DB::raw('COUNT(*) as count'))
            ->where('status', 'confirmed')
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get()
            ->map(function($item) {
                return [
                    'method' => ucfirst(str_replace('_', ' ', $item->payment_method ?? 'Unknown')),
                    'total' => (float) $item->total,
                    'count' => (int) $item->count,
                ];
            });
    }
    
    protected function getTourRevenue()
    {
        return Tour::withSum(['bookings' => function($query) {
            $query->where('status', 'confirmed');
        }], 'total_price')
        ->withCount(['bookings' => function($query) {
            $query->where('status', 'confirmed');
        }])
        ->having('bookings_sum_total_price', '>', 0)
        ->orderBy('bookings_sum_total_price', 'desc')
        ->limit(10)
        ->get();
    }
    
    protected function getDestinationRevenue()
    {
        return Destination::with(['tours' => function($query) {
            $query->withSum(['bookings' => function($q) {
                $q->where('status', 'confirmed');
            }], 'total_price');
        }])
        ->get()
        ->map(function($destination) {
            $revenue = $destination->tours->sum('bookings_sum_total_price');
            return [
                'name' => $destination->name,
                'revenue' => $revenue,
            ];
        })
        ->sortByDesc('revenue')
        ->take(10)
        ->values();
    }
    
    protected function getYearlyComparison()
    {
        $currentYear = Booking::where('status', 'confirmed')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');
            
        $lastYear = Booking::where('status', 'confirmed')
            ->whereYear('created_at', Carbon::now()->subYear()->year)
            ->sum('total_price');
        
        return [
            'current_year' => $currentYear,
            'last_year' => $lastYear,
            'growth' => $lastYear > 0 ? (($currentYear - $lastYear) / $lastYear) * 100 : 0,
        ];
    }
    
    // Helper methods for Bookings Status
    protected function getBookingStatusData()
    {
        $pending = Booking::where('status', 'pending_payment')->count();
        $confirmed = Booking::where('status', 'confirmed')->count();
        $cancelled = Booking::where('status', 'cancelled')->count();
        $completed = Booking::where('status', 'completed')->count();
        
        $total = $pending + $confirmed + $cancelled + $completed;
        
        return [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'cancelled' => $cancelled,
            'completed' => $completed,
            'total' => $total,
            'pending_percent' => $total > 0 ? round(($pending / $total) * 100, 1) : 0,
            'confirmed_percent' => $total > 0 ? round(($confirmed / $total) * 100, 1) : 0,
            'cancelled_percent' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0,
            'completed_percent' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
        ];
    }
    
    protected function getStatusTrends()
    {
        $trends = Booking::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('status'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('date', 'status')
        ->orderBy('date')
        ->get();
        
        $dates = $trends->pluck('date')->unique()->sort()->values();
        $statuses = ['pending_payment', 'confirmed', 'cancelled', 'completed'];
        
        $data = [];
        foreach ($dates as $date) {
            $row = ['date' => Carbon::parse($date)->format('M d')];
            foreach ($statuses as $status) {
                $row[$status] = $trends->where('date', $date)->where('status', $status)->sum('total');
            }
            $data[] = $row;
        }
        
        return $data;
    }
    
    protected function getRecentBookings()
    {
        return Booking::with(['tour.destination', 'user'])
            ->latest()
            ->limit(20)
            ->get();
    }
    
    protected function getStatusBreakdown()
    {
        return Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return [
                    'status' => ucfirst(str_replace('_', ' ', $item->status)),
                    'count' => $item->count,
                ];
            });
    }
    
    protected function getCancellationReasons()
    {
        // This would typically come from a cancellations table
        return [
            'labels' => ['Customer Request', 'Payment Failed', 'Weather Conditions', 'Other Reasons', 'No Show'],
            'values' => [30, 25, 20, 15, 10]
        ];
    }
    
    protected function getBookingSources()
    {
        // Placeholder - would come from booking source tracking
        return [
            ['source' => 'Website', 'count' => 45],
            ['source' => 'Phone', 'count' => 30],
            ['source' => 'Email', 'count' => 15],
            ['source' => 'Walk-in', 'count' => 10],
        ];
    }
    
    // Helper methods for Upcoming Trips
    protected function getUpcomingTrips($user)
    {
        $baseQuery = Booking::with(['tour.destination', 'user'])
            ->where('status', 'confirmed')
            ->where('departure_date', '>=', Carbon::now())
            ->orderBy('departure_date', 'asc');
        
        if (!$user->hasRole('System Administrator')) {
            if ($user->hasRole('Travel Consultant')) {
                $baseQuery->where('user_id', $user->id);
            }
        }
        
        return $baseQuery->get();
    }
    
    protected function getTripCalendar()
    {
        $trips = Booking::select(
            DB::raw('DATE(departure_date) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'confirmed')
        ->where('departure_date', '>=', Carbon::now())
        ->where('departure_date', '<=', Carbon::now()->addMonths(3))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        return $trips->map(function($trip) {
            return [
                'date' => $trip->date,
                'count' => $trip->count,
            ];
        });
    }
    
    protected function getTripStats()
    {
        $upcoming = Booking::where('status', 'confirmed')
            ->where('departure_date', '>=', Carbon::now())
            ->count();
        
        $thisMonth = Booking::where('status', 'confirmed')
            ->whereMonth('departure_date', Carbon::now()->month)
            ->whereYear('departure_date', Carbon::now()->year)
            ->count();
        
        $nextMonth = Booking::where('status', 'confirmed')
            ->whereMonth('departure_date', Carbon::now()->addMonth()->month)
            ->whereYear('departure_date', Carbon::now()->addMonth()->year)
            ->count();
        
        $totalTravelers = Booking::where('status', 'confirmed')
            ->where('departure_date', '>=', Carbon::now())
            ->sum('travelers');
        
        return [
            'upcoming' => $upcoming,
            'this_month' => $thisMonth,
            'next_month' => $nextMonth,
            'total_travelers' => $totalTravelers,
        ];
    }
    
    protected function getDestinationTrips()
    {
        return Destination::withCount(['tours' => function($query) {
            $query->has('bookings', '>=', 1)
                ->whereHas('bookings', function($q) {
                    $q->where('status', 'confirmed')
                      ->where('departure_date', '>=', Carbon::now());
                });
        }])
        ->orderBy('tours_count', 'desc')
        ->limit(10)
        ->get();
    }
    
    protected function getMonthlyTrips()
    {
        $trips = Booking::select(
            DB::raw('MONTH(departure_date) as month'),
            DB::raw('YEAR(departure_date) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'confirmed')
        ->where('departure_date', '>=', Carbon::now())
        ->where('departure_date', '<=', Carbon::now()->addMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        $months = [];
        $counts = [];
        
        foreach ($trips as $trip) {
            $date = Carbon::create($trip->year, $trip->month, 1);
            $months[] = $date->format('M Y');
            $counts[] = $trip->count;
        }
        
        return [
            'months' => $months,
            'counts' => $counts
        ];
    }
}






