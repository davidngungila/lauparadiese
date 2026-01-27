<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalController extends Controller
{
    /**
     * Display the privacy policy page.
     */
    public function privacy(): View
    {
        return view('legal.privacy');
    }

    /**
     * Display the terms of service page.
     */
    public function terms(): View
    {
        return view('legal.terms');
    }

    /**
     * Display the cookie policy page.
     */
    public function cookies(): View
    {
        return view('legal.cookies');
    }

    /**
     * Display the accessibility statement page.
     */
    public function accessibility(): View
    {
        return view('legal.accessibility');
    }
}
