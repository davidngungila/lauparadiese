<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use App\Models\Payment;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Vehicle;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends BaseAdminController
{
    
    public function index()
    {
        $user = auth()->user();
        
        // Get comprehensive dashboard statistics
        $stats = $this->getDashboardStats($user);
        
        // Get monthly bookings data for charts
        $monthlyBookings = $this->getMonthlyBookings($user);
        
        // Get revenue trends
        $revenueTrends = $this->getRevenueTrends($user);
        
        // Get booking trends (last 30 days)
        $bookingTrends = $this->getBookingTrends($user);
        
        // Get top performing tours
        $tourPerformance = $this->getTopTours($user);
        
        // Get destination statistics
        $destinationStats = $this->getPopularDestinations($user);
        
        // Get customer statistics
        $customerStats = $this->getCustomerStats($user);
        
        // Get recent bookings
        $recentBookings = $this->getRecentBookings($user);
        
        // Get upcoming bookings
        $upcomingBookings = $this->getUpcomingBookings($user);
        
        // Get activity metrics
        $activityMetrics = $this->getActivityMetrics($user);
        
        // Get booking status breakdown
        $bookingStatusData = $this->getBookingStatusData($user);
        
        // Get payment statistics
        $paymentStats = $this->getPaymentStats($user);
        
        // Get system health metrics
        $systemHealth = $this->getSystemHealth();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($user);
        
        return view('admin.dashboard.index', compact(
            'stats', 
            'monthlyBookings',
            'revenueTrends',
            'bookingTrends',
            'tourPerformance',
            'destinationStats',
            'customerStats',
            'recentBookings', 
            'upcomingBookings',
            'activityMetrics',
            'bookingStatusData',
            'paymentStats',
            'systemHealth',
            'recentActivities',
            'user'
        ));
    }
    
    /**
     * Get monthly bookings data
     */
    protected function getMonthlyBookings($user)
    {
        $baseQuery = Booking::query();
        
        if ($user->hasRole('Travel Consultant')) {
            $baseQuery->where('user_id', $user->id);
        }
        
        $bookings = $baseQuery->select(
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
    
    /**
     * Get revenue trends
     */
    protected function getRevenueTrends($user)
    {
        $baseQuery = Booking::where('status', 'confirmed');
        
        if ($user->hasRole('Travel Consultant')) {
            $baseQuery->where('user_id', $user->id);
        }
        
        $revenue = $baseQuery->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_price) as total')
        )
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
    
    /**
     * Get booking trends (last 30 days)
     */
    protected function getBookingTrends($user)
    {
        $baseQuery = Booking::query();
        
        if ($user->hasRole('Travel Consultant')) {
            $baseQuery->where('user_id', $user->id);
        }
        
        $bookings = $baseQuery->select(
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
    
    /**
     * Get customer statistics
     */
    protected function getCustomerStats($user)
    {
        return [
            'total' => User::whereHas('roles', function($q) {
                $q->where('name', 'Customer');
            })->orWhereDoesntHave('roles')->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
            'active' => Booking::distinct('user_id')->count('user_id'),
            'verified' => User::whereNotNull('email_verified_at')->count(),
        ];
    }
    
    /**
     * Get payment statistics
     */
    protected function getPaymentStats($user)
    {
        $baseQuery = Payment::where('status', 'completed');
        
        if ($user->hasRole('Travel Consultant')) {
            $baseQuery->whereHas('booking', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        return [
            'total_payments' => $baseQuery->count(),
            'total_amount' => $baseQuery->sum('amount'),
            'today' => (clone $baseQuery)->whereDate('paid_at', today())->sum('amount'),
            'this_month' => (clone $baseQuery)->whereMonth('paid_at', Carbon::now()->month)->sum('amount'),
            'by_method' => (clone $baseQuery)->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get(),
        ];
    }
    
    /**
     * Get system health metrics
     */
    protected function getSystemHealth()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'total_tours' => Tour::count(),
            'active_tours' => Tour::where('is_featured', true)->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending_payment')->count(),
            'system_uptime' => '99.9%', // Placeholder
            'storage_used' => '2.5 GB', // Placeholder
        ];
    }
    
    protected function getDashboardStats($user)
    {
        $stats = [
            'total_bookings' => 0,
            'pending_bookings' => 0,
            'confirmed_bookings' => 0,
            'total_revenue' => 0,
            'total_tours' => 0,
            'total_clients' => 0,
            'booking_growth' => 0,
            'pending_growth' => 0,
            'tours_growth' => 0,
            'revenue_growth' => 0,
            'performance_growth' => 0,
            'in_progress_bookings' => 0,
            'in_progress_growth' => 0,
            'tours_available_growth' => 0,
            'completed_growth' => 0,
            'satisfaction_growth' => 0,
            'travelers_growth' => 0,
            'avg_booking_growth' => 0,
            'total_travelers' => 0,
            'avg_booking_value' => 0,
            'avg_rating' => 4.5,
            'active_destinations' => 0,
        ];
        
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        // System Administrator sees everything
        if ($user->hasRole('System Administrator')) {
            $stats['total_bookings'] = Booking::count();
            $stats['pending_bookings'] = Booking::where('status', 'pending_payment')->count();
            $stats['confirmed_bookings'] = Booking::where('status', 'confirmed')->count();
            $stats['total_revenue'] = Booking::where('status', 'confirmed')->sum('total_price');
            $stats['total_tours'] = Tour::count();
            $stats['total_clients'] = User::whereHas('roles', function($q) {
                    $q->where('name', 'Customer');
            })->orWhereDoesntHave('roles')->count();
            
            // Calculate growth percentages
            $currentMonthBookings = Booking::whereMonth('created_at', Carbon::now()->month)->count();
            $lastMonthBookings = Booking::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
            $stats['booking_growth'] = $lastMonthBookings > 0 ? (($currentMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100 : 0;
            
            $currentMonthPending = Booking::where('status', 'pending_payment')->whereMonth('created_at', Carbon::now()->month)->count();
            $lastMonthPending = Booking::where('status', 'pending_payment')->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
            $stats['pending_growth'] = $lastMonthPending > 0 ? (($currentMonthPending - $lastMonthPending) / $lastMonthPending) * 100 : 0;
            
            $currentMonthRevenue = Booking::where('status', 'confirmed')->whereMonth('created_at', Carbon::now()->month)->sum('total_price');
            $lastMonthRevenue = Booking::where('status', 'confirmed')->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('total_price');
            $stats['revenue_growth'] = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        }
        // Travel Consultant sees their bookings
        elseif ($user->hasRole('Travel Consultant')) {
            $stats['total_bookings'] = Booking::where('user_id', $user->id)->count();
            $stats['pending_bookings'] = Booking::where('user_id', $user->id)->where('status', 'pending_payment')->count();
            $stats['confirmed_bookings'] = Booking::where('user_id', $user->id)->where('status', 'confirmed')->count();
            $stats['total_revenue'] = Booking::where('user_id', $user->id)->where('status', 'confirmed')->sum('total_price');
            $stats['total_tours'] = Tour::count();
        }
        // Reservations Officer sees all bookings
        elseif ($user->hasRole('Reservations Officer')) {
            $stats['total_bookings'] = Booking::count();
            $stats['pending_bookings'] = Booking::where('status', 'pending_payment')->count();
            $stats['confirmed_bookings'] = Booking::where('status', 'confirmed')->count();
            $stats['total_revenue'] = Booking::where('status', 'confirmed')->sum('total_price');
        }
        // Finance Officer sees financial data
        elseif ($user->hasRole('Finance Officer')) {
            $stats['total_bookings'] = Booking::count();
            $stats['total_revenue'] = Booking::where('status', 'confirmed')->sum('total_price');
            $stats['pending_bookings'] = Booking::where('status', 'pending_payment')->count();
        }
        
        // Additional stats for all roles
        $stats['in_progress_bookings'] = Booking::whereIn('status', ['confirmed', 'pending_payment'])
            ->where('departure_date', '>=', Carbon::now())
            ->count();
        
        $stats['total_travelers'] = Booking::where('status', 'confirmed')->sum('travelers');
        
        $avgBooking = Booking::where('status', 'confirmed')->avg('total_price');
        $stats['avg_booking_value'] = $avgBooking ? round($avgBooking, 2) : 0;
        
        $stats['active_destinations'] = Tour::whereNotNull('destination_id')->distinct('destination_id')->count('destination_id');
        
        // Calculate performance growth (placeholder)
        $stats['performance_growth'] = 12.5;
        $stats['in_progress_growth'] = 25.8;
        $stats['tours_available_growth'] = 4.3;
        $stats['completed_growth'] = -12.5;
        $stats['satisfaction_growth'] = 5.7;
        $stats['travelers_growth'] = 18.2;
        $stats['avg_booking_growth'] = 8.5;
        $stats['tours_growth'] = 6.2;
        
        return $stats;
    }

    protected function getRecentBookings($user)
    {
        $query = Booking::with(['tour.destination', 'user'])->latest();
        
        if ($user->hasRole('Travel Consultant')) {
            $query->where('user_id', $user->id);
        }
        
        return $query->limit(10)->get();
    }
    
    protected function getConfirmedBookings($user)
    {
        $query = Booking::with(['tour.destination', 'user'])
            ->where('status', 'confirmed')
            ->latest();
        
        if ($user->hasRole('Travel Consultant')) {
            $query->where('user_id', $user->id);
        }
        
        return $query->limit(10)->get();
    }
    
    protected function getUpcomingBookings($user)
    {
        $query = Booking::with(['tour.destination', 'user'])
            ->where('status', 'confirmed')
            ->where('departure_date', '>=', Carbon::now())
            ->orderBy('departure_date', 'asc');
        
        if ($user->hasRole('Travel Consultant')) {
            $query->where('user_id', $user->id);
        }
        
        return $query->limit(10)->get();
    }
    
    protected function getMonthlyRevenue($user = null)
    {
        $query = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', Carbon::now()->year);
        
        if ($user && $user->hasRole('Travel Consultant')) {
            $query->where('user_id', $user->id);
        }
        
        $bookings = $query->groupBy('year', 'month')
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
    
    protected function getBookingStatusData($user = null)
    {
        $query = Booking::query();
        
        if ($user && $user->hasRole('Travel Consultant')) {
            $query->where('user_id', $user->id);
        }
        
        $pending = (clone $query)->where('status', 'pending_payment')->count();
        $confirmed = (clone $query)->where('status', 'confirmed')->count();
        $cancelled = (clone $query)->where('status', 'cancelled')->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        
        $total = $pending + $confirmed + $cancelled + $completed;
        
        return [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'cancelled' => $cancelled,
            'completed' => $completed,
            'pending_percent' => $total > 0 ? round(($pending / $total) * 100, 1) : 0,
            'confirmed_percent' => $total > 0 ? round(($confirmed / $total) * 100, 1) : 0,
            'cancelled_percent' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0,
            'completed_percent' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
        ];
    }
    
    protected function getTopTours($user)
    {
        $query = Tour::withCount(['bookings' => function($q) use ($user) {
            if ($user && $user->hasRole('Travel Consultant')) {
                $q->where('user_id', $user->id);
            }
        }])
        ->withSum(['bookings' => function($q) use ($user) {
            if ($user && $user->hasRole('Travel Consultant')) {
                $q->where('user_id', $user->id);
            }
        }], 'total_price')
        ->orderBy('bookings_count', 'desc')
        ->limit(5);
        
        return $query->get();
    }
    
    protected function getPopularDestinations($user)
    {
        $query = Destination::withCount(['tours' => function($q) use ($user) {
            $q->has('bookings');
            if ($user && $user->hasRole('Travel Consultant')) {
                $q->whereHas('bookings', function($bq) use ($user) {
                    $bq->where('user_id', $user->id);
                });
            }
        }])
        ->orderBy('tours_count', 'desc')
        ->limit(5);
        
        return $query->get();
    }
    
    protected function getTopCustomers($user)
    {
        // Get top customers by booking count and revenue
        $query = Booking::select(
            'customer_name',
            'customer_email',
            DB::raw('COUNT(*) as bookings_count'),
            DB::raw('SUM(total_price) as total_revenue')
        )
        ->whereNotNull('customer_name');
        
        if ($user && $user->hasRole('Travel Consultant')) {
            $query->where('user_id', $user->id);
        }
        
        $customers = $query->groupBy('customer_name', 'customer_email')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object) [
                    'name' => $item->customer_name,
                    'email' => $item->customer_email,
                    'bookings_count' => $item->bookings_count,
                    'total_revenue' => $item->total_revenue,
                ];
            });
        
        return $customers;
    }
    
    protected function getActivityMetrics($user)
    {
        $baseQuery = Booking::query();
        
        if ($user->hasRole('Travel Consultant')) {
            $baseQuery->where('user_id', $user->id);
        }
        
        // Calculate hours spent (based on bookings processed this week)
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekBookings = (clone $baseQuery)
            ->where('created_at', '>=', $thisWeekStart)
            ->count();
        $hoursSpent = $thisWeekBookings * 2; // Estimate 2 hours per booking
        
        // Calculate completion rate (confirmed bookings / total bookings)
        $totalBookings = (clone $baseQuery)->count();
        $confirmedBookings = (clone $baseQuery)->where('status', 'confirmed')->count();
        $completionRate = $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100, 1) : 0;
        
        // Calculate trips completed this month
        $thisMonthCompleted = (clone $baseQuery)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Calculate total time spent (all time)
        $allTimeBookings = (clone $baseQuery)->count();
        $totalHours = $allTimeBookings * 2;
        $totalMinutes = ($totalHours % 1) * 60;
        
        return [
            'hours_spent' => $hoursSpent,
            'completion_rate' => $completionRate,
            'trips_completed' => $thisMonthCompleted,
            'total_hours' => floor($totalHours),
            'total_minutes' => round($totalMinutes),
            'growth' => 18.4, // Placeholder for growth calculation
        ];
    }
    
    protected function getWeeklyActivity($user)
    {
        $query = Booking::select(
            DB::raw('DAYOFWEEK(created_at) as day'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->startOfWeek());
        
        if ($user->hasRole('Travel Consultant')) {
            $query->where('user_id', $user->id);
        }
        
        $activities = $query->groupBy('day')
            ->orderBy('day')
            ->get();
        
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $amounts = [];
        
        for ($i = 0; $i < 7; $i++) {
            $dayData = $activities->firstWhere('day', $i + 1);
            $amounts[] = $dayData ? (int) $dayData->total : 0;
        }
        
        return [
            'days' => $days,
            'amounts' => $amounts
        ];
    }
    
    protected function getDestinationInterest($user)
    {
        $query = Destination::withCount(['tours' => function($q) use ($user) {
            $q->has('bookings');
            if ($user && $user->hasRole('Travel Consultant')) {
                $q->whereHas('bookings', function($bq) use ($user) {
                    $bq->where('user_id', $user->id);
                });
            }
        }])
        ->orderBy('tours_count', 'desc')
        ->limit(6)
        ->get();
        
        $labels = [];
        $values = [];
        $total = $query->sum('tours_count');
        
        foreach ($query as $destination) {
            $labels[] = $destination->name;
            $percentage = $total > 0 ? round(($destination->tours_count / $total) * 100, 1) : 0;
            $values[] = $percentage;
        }
        
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }
    
    protected function getRecentActivities($user)
    {
        $activities = [];
        
        // Recent bookings
        $recentBookings = Booking::with(['tour', 'user'])
            ->latest()
            ->limit(5)
            ->get();
        
        foreach ($recentBookings as $booking) {
            $activities[] = [
                'type' => 'booking',
                'icon' => 'ri-calendar-check-line',
                'color' => 'primary',
                'title' => 'New Booking',
                'description' => $booking->customer_name . ' booked ' . ($booking->tour->name ?? 'a tour'),
                'time' => $booking->created_at->diffForHumans(),
                'timestamp' => $booking->created_at,
            ];
        }
        
        // Sort by timestamp and return
        usort($activities, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });
        
        return array_slice($activities, 0, 10);
    }
    
    protected function getQuickStats($user)
    {
        $stats = [];
        
        if ($user->hasRole('System Administrator') || $user->hasRole('Finance Officer')) {
            $stats['pending_payments'] = Booking::where('status', 'pending_payment')->count();
            $stats['total_revenue'] = Booking::where('status', 'confirmed')->sum('total_price');
        }
        
        if ($user->hasRole('System Administrator') || $user->hasRole('Reservations Officer')) {
            $stats['pending_approvals'] = Booking::where('status', 'pending_payment')->count();
            $stats['upcoming_trips'] = Booking::where('status', 'confirmed')
                ->where('departure_date', '>=', Carbon::now())
                ->where('departure_date', '<=', Carbon::now()->addDays(7))
                ->count();
        }
        
        if ($user->hasRole('Travel Consultant')) {
            $stats['my_bookings'] = Booking::where('user_id', $user->id)->count();
            $stats['my_revenue'] = Booking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->sum('total_price');
        }
        
        return $stats;
    }
    
    protected function getCancellationReasons()
    {
        // This would typically come from a cancellations table with reasons
        // For now, return sample data
        return [
            'labels' => ['Customer Request', 'Payment Failed', 'Weather Conditions', 'Other Reasons', 'No Show'],
            'values' => [30, 25, 20, 15, 10]
        ];
    }
    
    public function profile()
    {
        $user = auth()->user();
        
        // Load user with relationships
        $user->load(['roles', 'permissions', 'bookings' => function($query) {
            $query->latest()->limit(10);
        }]);
        
        // Calculate statistics
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'confirmed_bookings' => $user->bookings()->where('status', 'confirmed')->count(),
            'pending_bookings' => $user->bookings()->where('status', 'pending_payment')->count(),
            'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
            'total_spent' => $user->bookings()->where('status', 'confirmed')->sum('total_price'),
            'roles_count' => $user->roles()->count(),
            'permissions_count' => $user->getAllPermissions()->count(),
            'recent_bookings' => $user->bookings()->with('tour')->latest()->limit(5)->get(),
        ];
        
        // Get all permissions (direct + via roles)
        $allPermissions = $user->getAllPermissions();
        
        return view('admin.profile.index', [
            'user' => $user,
            'stats' => $stats,
            'allPermissions' => $allPermissions,
        ]);
    }
    
    /**
     * Account Settings - Main Account Tab
     */
    public function accountSettings()
    {
        $user = auth()->user();
        return view('admin.account-settings.account', compact('user'));
    }
    
    /**
     * Account Settings - Security Tab
     */
    public function accountSecurity()
    {
        $user = auth()->user();
        return view('admin.account-settings.security', compact('user'));
    }
    
    /**
     * Account Settings - Billing Tab
     */
    public function accountBilling()
    {
        $user = auth()->user();
        return view('admin.account-settings.billing', compact('user'));
    }
    
    /**
     * Account Settings - Notifications Tab
     */
    public function accountNotifications()
    {
        $user = auth()->user();
        return view('admin.account-settings.notifications', compact('user'));
    }
    
    /**
     * Account Settings - Connections Tab
     */
    public function accountConnections()
    {
        $user = auth()->user();
        return view('admin.account-settings.connections', compact('user'));
    }
    
    /**
     * Update Account Settings
     */
    public function updateAccountSettings(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string',
            'timezone' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:10',
        ]);
        
        $user->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Account settings updated successfully.'
            ]);
        }
        
        return redirect()->route('admin.account-settings')->with('success', 'Account settings updated successfully.');
    }
    
    /**
     * Update Security Settings
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);
        }
        
        return redirect()->route('admin.account-settings.security')->with('success', 'Password updated successfully.');
    }
    
    /**
     * Update Billing Settings
     */
    public function updateBilling(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'billing_address' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:255',
            'billing_postal_code' => 'nullable|string|max:20',
            'payment_method' => 'nullable|string|max:50',
        ]);
        
        $user->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Billing information updated successfully.'
            ]);
        }
        
        return redirect()->route('admin.account-settings.billing')->with('success', 'Billing information updated successfully.');
    }
    
    /**
     * Update Notification Settings
     */
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'email_notifications' => 'nullable|in:0,1',
            'sms_notifications' => 'nullable|in:0,1',
            'push_notifications' => 'nullable|in:0,1',
            'booking_notifications' => 'nullable|in:0,1',
            'payment_notifications' => 'nullable|in:0,1',
            'marketing_notifications' => 'nullable|in:0,1',
        ]);
        
        // Convert string values to boolean
        foreach ($validated as $key => $value) {
            $validated[$key] = $value == '1' ? true : false;
        }
        
        $user->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated successfully.'
            ]);
        }
        
        return redirect()->route('admin.account-settings.notifications')->with('success', 'Notification preferences updated successfully.');
    }
    
    /**
     * Update Social Connections
     */
    public function updateConnections(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
        ]);
        
        $socialLinks = $user->social_links ?? [];
        $socialLinks = array_merge($socialLinks, $validated);
        $user->update(['social_links' => $socialLinks]);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Social connections updated successfully.'
            ]);
        }
        
        return redirect()->route('admin.account-settings.connections')->with('success', 'Social connections updated successfully.');
    }
    
    /**
     * Disconnect Social Provider
     */
    public function disconnectProvider($provider)
    {
        $user = auth()->user();
        $socialLinks = $user->social_links ?? [];
        unset($socialLinks[$provider]);
        $user->update(['social_links' => $socialLinks]);
        
        return redirect()->route('admin.account-settings.connections')->with('success', 'Social connection disconnected successfully.');
    }
    
    /**
     * Deactivate Account
     */
    public function deactivateAccount(Request $request)
    {
        $request->validate([
            'confirm_deactivation' => 'required|accepted',
        ]);
        
        $user = auth()->user();
        // Soft delete or mark as inactive
        $user->update(['email' => $user->email . '_deactivated_' . time()]);
        
        auth()->logout();
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your account has been deactivated.',
                'redirect' => route('login')
            ]);
        }
        
        return redirect()->route('login')->with('info', 'Your account has been deactivated.');
    }
    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string',
        ]);
        
        $user->update($validated);
        
        // Handle AJAX requests
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'user' => $user->fresh()
            ]);
        }
        
        // Send notification
        $this->notifySuccess('Your profile has been updated successfully.', 'Profile Updated', route('admin.profile'));
        
        return $this->successResponse('Profile updated successfully.');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        
        // Handle AJAX requests
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);
        }
        
        // Send notification
        $this->notifyWarning('Your password has been changed successfully. If you did not make this change, please contact support immediately.', 'Password Changed', route('admin.profile'));
        
        return $this->successResponse('Password updated successfully.');
    }
    
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        $user = auth()->user();
        
        if ($user->avatar) {
            \Storage::disk('public')->delete($user->avatar);
        }
        
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        
        // Handle AJAX requests
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully.',
                'avatar_url' => asset('storage/' . $path)
            ]);
        }
        
        // Send notification
        $this->notifySuccess('Your profile picture has been updated successfully.', 'Avatar Updated', route('admin.profile'));
        
        return $this->successResponse('Avatar updated successfully.');
    }
    
    // Placeholder methods for routes - will be moved to dedicated controllers
    public function settings() { return view('admin.settings.index'); }
    public function systemSettings() { return view('admin.settings.system'); }
    public function users() { return redirect()->route('admin.users.index'); }
    public function roles() { return redirect()->route('admin.roles.index'); }
    public function permissions() { return redirect()->route('admin.permissions.index'); }
    public function bookings() { return view('admin.bookings.index'); }
    public function createBooking() { return view('admin.bookings.create'); }
    public function pendingBookings() { return view('admin.bookings.pending'); }
    public function confirmedBookings() { return view('admin.bookings.confirmed'); }
    public function cancelledBookings() { return view('admin.bookings.cancelled'); }
    public function groupBookings() { return view('admin.bookings.group'); }
    public function agentBookings() { return view('admin.bookings.agent'); }
    public function tours(Request $request) 
    { 
        $query = Tour::with(['destination']);
        
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }
        
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured == '1');
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $tours = $query->withCount('bookings')->latest()->paginate(20);
        $destinations = Destination::orderBy('name')->get();
        
        // Calculate stats from database
        $stats = [
            'total' => Tour::count(),
            'featured' => Tour::where('is_featured', true)->count(),
            'total_bookings' => Tour::withCount('bookings')->get()->sum('bookings_count'),
            'avg_price' => Tour::avg('price'),
        ];
        
        return view('admin.tours.index', compact('tours', 'destinations', 'stats')); 
    }
    public function createTour() { return view('admin.tours.create'); }
    public function destinations() 
    { 
        return redirect()->route('admin.homepage.destinations');
    }
    public function categories() 
    { 
        return redirect()->route('admin.categories.index');
    }
    public function clients() { return view('admin.clients.index'); }
    public function createClient() { return view('admin.clients.create'); }
    public function payments() { return redirect()->route('admin.finance.payments'); }
    public function invoices() { return redirect()->route('admin.finance.invoices'); }
    public function expenses() { return redirect()->route('admin.finance.expenses'); }
    public function promotions() { return view('admin.promotions.index'); }
    public function campaigns() { return view('admin.campaigns.index'); }
    public function hotels(Request $request) 
    { 
        $query = Hotel::with('partner');
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('partner')) {
            if ($request->partner == '1') {
                $query->whereNotNull('partner_id');
            } else {
                $query->whereNull('partner_id');
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        $hotels = $query->latest()->paginate(20);
        
        // Calculate stats from database
        $stats = [
            'total' => Hotel::count(),
            'active' => Hotel::where('is_active', true)->count(),
            'partner' => Hotel::whereNotNull('partner_id')->count(),
            'total_rooms' => Hotel::sum('total_rooms'),
        ];
        
        return view('admin.hotels.index', compact('hotels', 'stats')); 
    }
    public function vehicles(Request $request) 
    { 
        $query = Vehicle::with('driver');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('vehicle_type', $request->type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }
        
        $vehicles = $query->latest()->paginate(20);
        
        // Calculate stats from database
        $stats = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('status', 'available')->count(),
            'in_use' => Vehicle::where('status', 'in_use')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
        ];
        
        return view('admin.vehicles.index', compact('vehicles', 'stats')); 
    }
    public function bookingReports(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? Carbon::now()->endOfMonth()->toDateString();
        
        $query = Booking::with(['tour', 'user', 'tour.destination']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }
        
        if ($request->filled('destination_id')) {
            $query->whereHas('tour', function($q) use ($request) {
                $q->where('destination_id', $request->destination_id);
            });
        }
        
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $dateFrom);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }
        
        if ($request->filled('departure_from')) {
            $query->where('departure_date', '>=', $request->departure_from);
        }
        
        if ($request->filled('departure_to')) {
            $query->where('departure_date', '<=', $request->departure_to);
        }
        
        $bookings = $query->latest()->get();
        
        // Calculate comprehensive statistics
        $stats = [
            'total' => $bookings->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'pending' => $bookings->where('status', 'pending_payment')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'total_revenue' => $bookings->where('status', 'confirmed')->sum('total_price'),
            'pending_revenue' => $bookings->where('status', 'pending_payment')->sum('total_price'),
            'cancelled_revenue' => $bookings->where('status', 'cancelled')->sum('total_price'),
            'total_travelers' => $bookings->sum('travelers'),
            'avg_travelers' => $bookings->count() > 0 ? round($bookings->sum('travelers') / $bookings->count(), 1) : 0,
            'avg_booking_value' => $bookings->where('status', 'confirmed')->count() > 0 
                ? round($bookings->where('status', 'confirmed')->sum('total_price') / $bookings->where('status', 'confirmed')->count(), 2) 
                : 0,
            'conversion_rate' => $bookings->count() > 0 
                ? round(($bookings->where('status', 'confirmed')->count() / $bookings->count()) * 100, 1) 
                : 0,
        ];
        
        // Booking trends by date (daily)
        $bookingTrends = Booking::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(CASE WHEN status = "confirmed" THEN total_price ELSE 0 END) as revenue'),
            DB::raw('SUM(travelers) as travelers')
        )
        ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        // Booking trends by month (for longer periods)
        $monthlyTrends = Booking::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(CASE WHEN status = "confirmed" THEN total_price ELSE 0 END) as revenue')
        )
        ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        // Status breakdown
        $statusBreakdown = Booking::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as revenue'))
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('status')
            ->get();
        
        // Top tours by bookings
        $topTours = Booking::select('tour_id', 
                DB::raw('COUNT(*) as booking_count'),
                DB::raw('SUM(CASE WHEN status = "confirmed" THEN total_price ELSE 0 END) as revenue'),
                DB::raw('SUM(travelers) as total_travelers')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('tour_id')
            ->groupBy('tour_id')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->with('tour')
            ->get();
        
        // Top destinations
        $topDestinations = DB::table('bookings')
            ->join('tours', 'bookings.tour_id', '=', 'tours.id')
            ->join('destinations', 'tours.destination_id', '=', 'destinations.id')
            ->select(
                'destinations.id as destination_id',
                'destinations.name as destination_name',
                DB::raw('COUNT(bookings.id) as booking_count'),
                DB::raw('SUM(CASE WHEN bookings.status = "confirmed" THEN bookings.total_price ELSE 0 END) as revenue')
            )
            ->whereBetween('bookings.created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('tours.destination_id')
            ->groupBy('destinations.id', 'destinations.name')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get();
        
        // Payment method breakdown
        $paymentMethods = Booking::select('payment_method', 
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "confirmed" THEN total_price ELSE 0 END) as revenue')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get();
        
        // Customer analysis
        $topCustomers = Booking::select('customer_email', 'customer_name',
                DB::raw('COUNT(*) as booking_count'),
                DB::raw('SUM(CASE WHEN status = "confirmed" THEN total_price ELSE 0 END) as total_spent'),
                DB::raw('SUM(travelers) as total_travelers')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('customer_email', 'customer_name')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get();
        
        $tours = Tour::with('destination')->orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        
        return view('admin.reports.bookings', compact(
            'bookings', 
            'stats', 
            'bookingTrends',
            'monthlyTrends',
            'statusBreakdown', 
            'topTours',
            'topDestinations',
            'paymentMethods',
            'topCustomers',
            'tours',
            'destinations',
            'dateFrom', 
            'dateTo'
        ));
    }
    public function revenueReports(Request $request)
    {
        // Redirect to Finance Controller's revenue reports for comprehensive finance reporting
        // Build query string from request parameters
        $queryParams = http_build_query($request->only(['date_from', 'date_to']));
        $url = route('admin.finance.revenue-reports');
        if ($queryParams) {
            $url .= '?' . $queryParams;
        }
        return redirect($url);
    }
    
    // Quotation methods
    public function quotations() { return view('admin.quotations.index'); }
    public function createQuotation() { return view('admin.quotations.create'); }
    public function pendingQuotations() { return view('admin.quotations.pending'); }
    public function sentQuotations() { return view('admin.quotations.sent'); }
    public function acceptedQuotations() { return view('admin.quotations.accepted'); }
}
