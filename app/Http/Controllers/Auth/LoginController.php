<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Check if user is trying to access admin
        if (request()->has('admin') || request()->is('admin*')) {
            return view('admin.auth.login');
        }
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            
            // Check if request is AJAX
            if ($request->expectsJson() || $request->ajax()) {
                // Determine redirect URL
                $redirectUrl = '/';
                if ($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer', 'Finance Officer', 'Content Manager', 'Marketing Officer', 'ICT Officer', 'Driver/Guide', 'Hotel Partner'])) {
                    $redirectUrl = route('admin.dashboard');
                } elseif ($user->hasRole('Customer')) {
                    $redirectUrl = route('customer.dashboard');
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back, ' . $user->name . '!',
                    'redirect' => $redirectUrl
                ]);
            }
            
            // Check if user has any admin role
            if ($user->hasAnyRole(['System Administrator', 'Travel Consultant', 'Reservations Officer', 'Finance Officer', 'Content Manager', 'Marketing Officer', 'ICT Officer', 'Driver/Guide', 'Hotel Partner'])) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
            }
            
            // Redirect customers to their dashboard
            if ($user->hasRole('Customer')) {
                return redirect()->intended(route('customer.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
            }
            
            return redirect()->intended('/')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Handle AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
                'errors' => [
                    'email' => ['The provided credentials do not match our records.']
                ]
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
