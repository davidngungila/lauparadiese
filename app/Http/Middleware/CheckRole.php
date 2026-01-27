<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // If user doesn't have any required role, redirect based on user type
        $user = auth()->user();
        
        if ($user->hasRole('Customer')) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }
        
        // For admin users, redirect to admin dashboard
        if ($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer', 'Finance Officer', 'Content Manager', 'Marketing Officer', 'ICT Officer', 'Driver/Guide', 'Hotel Partner'])) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }
        
        // Default redirect to home
        return redirect('/')
            ->with('error', 'You do not have permission to access this page.');
    }
}
