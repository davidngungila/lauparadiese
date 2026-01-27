<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles the rendering of static and informational pages.
 */
class PageController extends Controller
{
    /**
     * Display the homepage.
     *
     * @param Request $request
     * @return View
     */
    public function home(Request $request): View
    {
        // Get hero slides from database
        $heroSlides = \App\Models\HeroSlide::with('image')
            ->where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get()
            ->map(function($slide) {
                // Get image URL - prefer gallery image, fallback to direct URL
                $imageUrl = null;
                if ($slide->image_id && $slide->image) {
                    $imageUrl = $slide->image->display_url;
                } elseif ($slide->getAttributes()['image_url']) {
                    $rawUrl = $slide->getAttributes()['image_url'];
                    if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) {
                        $imageUrl = $rawUrl;
                    } else {
                        $imageUrl = asset($rawUrl);
                    }
                }
                
                return [
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'badge_text' => $slide->badge_text,
                    'badge_icon' => $slide->badge_icon,
                    'image_url' => $imageUrl ?: asset('images/safari_home-1.jpg'),
                    'primary_button_text' => $slide->primary_button_text,
                    'primary_button_link' => $slide->primary_button_link,
                    'primary_button_icon' => $slide->primary_button_icon,
                    'secondary_button_text' => $slide->secondary_button_text,
                    'secondary_button_link' => $slide->secondary_button_link,
                    'secondary_button_icon' => $slide->secondary_button_icon,
                    'animation_type' => $slide->animation_type,
                    'overlay_type' => $slide->overlay_type,
                ];
            });
        
        // Get featured tours for home page
        $featuredTours = \App\Models\Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'starting_price' => (float) ($tour->starting_price ?? $tour->price),
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg'),
                    'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 150) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                    'rating' => $tour->rating ?? 4.5,
                ];
            });

        // Get homepage gallery images
        $homepageGallery = \App\Models\Gallery::where('is_active', true)
            ->where(function($query) {
                $query->where('category', 'Homepage Gallery')
                      ->orWhere('category', 'Tanzania in Pictures')
                      ->orWhere('is_featured', true);
            })
            ->orderBy('display_order', 'asc')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(12) // Limit to 12 images for homepage
            ->get()
            ->map(function($gallery) {
                return [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'alt_text' => $gallery->alt_text ?? $gallery->title,
                    'caption' => $gallery->caption ?? $gallery->description,
                    'image_url' => $gallery->display_url ?? asset('images/safari_home-1.jpg'),
                    'thumbnail_url' => $gallery->getThumbnailUrl('600') ?? $gallery->display_url,
                ];
            });

        return view('pages.home', compact('featuredTours', 'heroSlides', 'homepageGallery'));
    }

    /**
     * Display the 'About Us' page.
     *
     * @param Request $request
     * @return View
     */
    public function about(Request $request): View
    {
        // Get all sections
        $sections = \App\Models\AboutPage::where('is_active', true)
            ->orderBy('display_order')
            ->get()
            ->keyBy('section_key');
        
        // Get team members
        $teamMembers = \App\Models\AboutPageTeamMember::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        // Get values
        $values = \App\Models\AboutPageValue::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        // Get recognitions (changed from certifications & awards)
        $recognitions = \App\Models\AboutPageRecognition::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        // Get timeline items
        $timelineItems = \App\Models\AboutPageTimelineItem::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        // Get statistics
        $statistics = \App\Models\AboutPageStatistic::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        return view('pages.about', compact(
            'sections',
            'teamMembers',
            'values',
            'recognitions',
            'timelineItems',
            'statistics'
        ));
    }

    /**
     * Display the 'Our Team' page.
     *
     * @param Request $request
     * @return View
     */
    public function team(Request $request): View
    {
        return view('pages.team');
    }

    /**
     * Display the 'Sustainability' page.
     *
     * @param Request $request
     * @return View
     */
    public function sustainability(Request $request): View
    {
        return view('pages.sustainability');
    }

    /**
     * Display the 'Partners' page.
     *
     * @param Request $request
     * @return View
     */
    public function partners(Request $request): View
    {
        return view('pages.partners');
    }

    /**
     * Display the 'Careers' page.
     *
     * @param Request $request
     * @return View
     */
    public function careers(Request $request): View
    {
        return view('pages.careers');
    }

    /**
     * Display the 'Press & Media' page.
     *
     * @param Request $request
     * @return View
     */
    public function press(Request $request): View
    {
        return view('pages.press');
    }

    /**
     * Display the 'Booking Help' page.
     *
     * @param Request $request
     * @return View
     */
    public function bookingHelp(Request $request): View
    {
        return view('pages.support.booking-help');
    }

    /**
     * Display the 'FAQ' page.
     *
     * @param Request $request
     * @return View
     */
    public function faq(Request $request): View
    {
        return view('pages.support.faq');
    }

    /**
     * Display the 'Customer Reviews' page.
     *
     * @param Request $request
     * @return View
     */
    public function reviews(Request $request): View
    {
        return view('pages.support.reviews');
    }

    /**
     * Display the 'Travel Insurance' page.
     *
     * @param Request $request
     * @return View
     */
    public function travelInsurance(Request $request): View
    {
        return view('pages.support.travel-insurance');
    }

    /**
     * Display the 'Travel Tips' page.
     *
     * @param Request $request
     * @return View
     */
    public function travelTips(Request $request): View
    {
        return view('pages.support.travel-tips');
    }

    /**
     * Display the 'Gift Cards' page.
     *
     * @param Request $request
     * @return View
     */
    public function giftCards(Request $request): View
    {
        return view('pages.support.gift-cards');
    }

    /**
     * Display the 'Safaris' page.
     *
     * @param Request $request
     * @return View
     */
    public function safaris(Request $request): View
    {
        // Get safari tours from database
        $safariTours = \App\Models\Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->where(function($query) {
                $query->where('name', 'like', '%Safari%')
                      ->orWhere('description', 'like', '%Safari%')
                      ->orWhere('short_description', 'like', '%Safari%');
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'starting_price' => (float) ($tour->starting_price ?? $tour->price),
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/hero-slider/safari-adventure.jpg'),
                    'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 200) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                    'rating' => $tour->rating ?? 4.5,
                    'is_featured' => $tour->is_featured ?? false,
                ];
            });

        return view('pages.safaris', compact('safariTours'));
    }

    /**
     * Display the 'Custom Tours' page.
     *
     * @param Request $request
     * @return View
     */
    public function customTours(Request $request): View
    {
        // Get destinations for the form (Destination model doesn't have is_active column)
        $destinations = \App\Models\Destination::orderBy('name')->get();
        
        // Get tour categories
        $categories = \App\Models\TourCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.custom-tours', compact('destinations', 'categories'));
    }

    /**
     * Display the 'Travel Proposal' page.
     *
     * @param Request $request
     * @return View
     */
    public function travelProposal(Request $request): View
    {
        return view('pages.travel-proposal');
    }

    /**
     * Display the 'Family Experiences' page.
     *
     * @param Request $request
     * @return View
     */
    public function familyExperiences(Request $request): View
    {
        // Get family-friendly tours
        $familyTours = \App\Models\Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->where(function($query) {
                $query->where('name', 'like', '%Family%')
                      ->orWhere('description', 'like', '%Family%')
                      ->orWhere('short_description', 'like', '%Family%')
                      ->orWhere('name', 'like', '%Kid%')
                      ->orWhere('description', 'like', '%Kid%');
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'starting_price' => (float) ($tour->starting_price ?? $tour->price),
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/hero-slider/animal-movement.jpg'),
                    'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 200) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                    'rating' => $tour->rating ?? 4.5,
                    'is_featured' => $tour->is_featured ?? false,
                ];
            });

        return view('pages.experiences.family', compact('familyTours'));
    }

    /**
     * Display the 'Wildlife Birds' page.
     *
     * @param Request $request
     * @return View
     */
    public function wildlifeBirds(Request $request): View
    {
        // Get bird watching tours
        $birdingTours = \App\Models\Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->where(function($query) {
                $query->where('name', 'like', '%bird%')
                      ->orWhere('description', 'like', '%bird%')
                      ->orWhere('short_description', 'like', '%bird%')
                      ->orWhere('name', 'like', '%birding%')
                      ->orWhere('description', 'like', '%birding%');
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->get()
            ->map(function($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'slug' => $tour->slug,
                    'price' => (float) $tour->price,
                    'starting_price' => (float) ($tour->starting_price ?? $tour->price),
                    'duration_days' => $tour->duration_days,
                    'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/hero-slider/group-of-animals.jpg'),
                    'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 200) . '...',
                    'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
                    'rating' => $tour->rating ?? 4.5,
                    'is_featured' => $tour->is_featured ?? false,
                ];
            });

        return view('pages.wildlife.birds', compact('birdingTours'));
    }
}

