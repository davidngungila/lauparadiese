<?php

namespace App\Http\Controllers\Admin;

use App\Models\PromoCode;
use App\Models\EmailCampaign;
use App\Models\SmsCampaign;
use App\Models\SocialMediaPost;
use App\Models\LandingPage;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarketingController extends BaseAdminController
{
    /**
     * Display marketing dashboard
     */
    public function dashboard()
    {
        try {
            $stats = [
                'total_campaigns' => EmailCampaign::count() + SmsCampaign::count(),
                'active_campaigns' => EmailCampaign::whereIn('status', ['sending', 'scheduled'])->count() + SmsCampaign::whereIn('status', ['sending', 'scheduled'])->count(),
                'total_emails_sent' => EmailCampaign::sum('sent_count') ?? 0,
                'total_sms_sent' => SmsCampaign::sum('sent_count') ?? 0,
                'email_open_rate' => EmailCampaign::avg('open_rate') ?? 0,
                'sms_delivery_rate' => SmsCampaign::avg('delivery_rate') ?? 0,
                'total_promo_codes' => PromoCode::count(),
                'active_promo_codes' => PromoCode::where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                    })->count(),
                'total_banners' => Banner::count(),
                'active_banners' => Banner::where('is_active', true)->count(),
                'total_landing_pages' => LandingPage::count(),
                'published_landing_pages' => LandingPage::where('status', 'published')->count(),
            ];
        } catch (\Exception $e) {
            $stats = [
                'total_campaigns' => 0,
                'active_campaigns' => 0,
                'total_emails_sent' => 0,
                'total_sms_sent' => 0,
                'email_open_rate' => 0,
                'sms_delivery_rate' => 0,
                'total_promo_codes' => 0,
                'active_promo_codes' => 0,
                'total_banners' => 0,
                'active_banners' => 0,
                'total_landing_pages' => 0,
                'published_landing_pages' => 0,
            ];
        }
        
        return view('admin.marketing.dashboard', compact('stats'));
    }

    // ==================== PROMO CODES ====================
    
    public function promoCodes(Request $request)
    {
        $query = PromoCode::query();
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true)
                      ->where(function($q) {
                          $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                      })
                      ->where(function($q) {
                          $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                      });
            } else {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $promoCodes = $query->latest()->paginate(20);
        
        return view('admin.marketing.promo-codes', compact('promoCodes'));
    }

    public function createPromoCode()
    {
        return view('admin.marketing.promo-codes-create');
    }

    public function storePromoCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'applicable_to' => 'required|in:all,tours,hotels,specific',
            'applicable_ids' => 'nullable|array',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['code'] = strtoupper($validated['code']);
        
        PromoCode::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promo code created successfully!']);
        }
        
        return redirect()->route('admin.marketing.promo-codes')->with('success', 'Promo code created successfully!');
    }

    public function editPromoCode($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return view('admin.marketing.promo-codes-edit', compact('promoCode'));
    }

    public function updatePromoCode(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $promoCode->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'applicable_to' => 'required|in:all,tours,hotels,specific',
            'applicable_ids' => 'nullable|array',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['code'] = strtoupper($validated['code']);
        
        $promoCode->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promo code updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.promo-codes')->with('success', 'Promo code updated successfully!');
    }

    public function destroyPromoCode($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promo code deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.promo-codes')->with('success', 'Promo code deleted successfully!');
    }

    // ==================== EMAIL CAMPAIGNS ====================
    
    public function emailCampaigns(Request $request)
    {
        $query = EmailCampaign::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        $campaigns = $query->latest()->paginate(20);
        
        return view('admin.marketing.email-campaigns', compact('campaigns'));
    }

    public function createEmailCampaign()
    {
        return view('admin.marketing.email-campaigns-create');
    }

    public function storeEmailCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        EmailCampaign::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign created successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign created successfully!');
    }

    public function editEmailCampaign($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        return view('admin.marketing.email-campaigns-edit', compact('campaign'));
    }

    public function updateEmailCampaign(Request $request, $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        $campaign->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign updated successfully!');
    }

    public function sendEmailCampaign($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status == 'sent') {
            return response()->json(['success' => false, 'message' => 'Campaign has already been sent!']);
        }
        
        // TODO: Implement actual email sending logic
        $campaign->update([
            'status' => 'sending',
            'sent_at' => now(),
        ]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign sent successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign sent successfully!');
    }

    public function destroyEmailCampaign($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status == 'sending' || $campaign->status == 'sent') {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete a campaign that is sending or has been sent!']);
            }
            return redirect()->route('admin.marketing.email-campaigns')->with('error', 'Cannot delete a campaign that is sending or has been sent!');
        }
        
        $campaign->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign deleted successfully!');
    }

    // ==================== SMS CAMPAIGNS ====================
    
    public function smsCampaigns(Request $request)
    {
        try {
            $query = SmsCampaign::query();
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }
            
            $campaigns = $query->latest()->paginate(20);
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $campaigns = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        
        return view('admin.marketing.sms-campaigns', compact('campaigns'));
    }

    public function createSmsCampaign()
    {
        return view('admin.marketing.sms-campaigns-create');
    }

    public function storeSmsCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:160',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        SmsCampaign::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign created successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign created successfully!');
    }

    public function editSmsCampaign($id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        return view('admin.marketing.sms-campaigns-edit', compact('campaign'));
    }

    public function updateSmsCampaign(Request $request, $id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:160',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        $campaign->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign updated successfully!');
    }

    public function sendSmsCampaign($id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        
        if ($campaign->status == 'sent') {
            return response()->json(['success' => false, 'message' => 'Campaign has already been sent!']);
        }
        
        // TODO: Implement actual SMS sending logic
        $campaign->update([
            'status' => 'sending',
            'sent_at' => now(),
        ]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign sent successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign sent successfully!');
    }

    public function destroySmsCampaign($id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        
        if ($campaign->status == 'sending' || $campaign->status == 'sent') {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete a campaign that is sending or has been sent!']);
            }
            return redirect()->route('admin.marketing.sms-campaigns')->with('error', 'Cannot delete a campaign that is sending or has been sent!');
        }
        
        $campaign->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign deleted successfully!');
    }

    // ==================== SOCIAL MEDIA ====================
    
    public function socialMedia(Request $request)
    {
        try {
            $query = SocialMediaPost::query();
            
            if ($request->filled('platform')) {
                $query->where('platform', $request->platform);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('content', 'like', "%{$search}%");
                });
            }
            
            $posts = $query->latest()->paginate(20);
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $posts = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        
        return view('admin.marketing.social-media', compact('posts'));
    }

    public function createSocialMedia()
    {
        return view('admin.marketing.social-media-create');
    }

    public function storeSocialMedia(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|in:facebook,twitter,instagram,linkedin',
            'content' => 'required|string',
            'media_url' => 'nullable|url',
            'status' => 'required|in:draft,scheduled,published',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        SocialMediaPost::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post created successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post created successfully!');
    }

    public function editSocialMedia($id)
    {
        $post = SocialMediaPost::findOrFail($id);
        return view('admin.marketing.social-media-edit', compact('post'));
    }

    public function updateSocialMedia(Request $request, $id)
    {
        $post = SocialMediaPost::findOrFail($id);
        
        $validated = $request->validate([
            'platform' => 'required|in:facebook,twitter,instagram,linkedin',
            'content' => 'required|string',
            'media_url' => 'nullable|url',
            'status' => 'required|in:draft,scheduled,published',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        $post->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post updated successfully!');
    }

    public function publishSocialMedia($id)
    {
        $post = SocialMediaPost::findOrFail($id);
        
        if ($post->status == 'published') {
            return response()->json(['success' => false, 'message' => 'Post has already been published!']);
        }
        
        // TODO: Implement actual social media publishing logic
        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post published successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post published successfully!');
    }

    public function destroySocialMedia($id)
    {
        $post = SocialMediaPost::findOrFail($id);
        $post->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post deleted successfully!');
    }

    // ==================== LANDING PAGES ====================
    
    public function landingPages(Request $request)
    {
        try {
            $query = LandingPage::query();
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }
            
            $pages = $query->latest()->paginate(20);
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $pages = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        
        return view('admin.marketing.landing-pages', compact('pages'));
    }

    public function createLandingPage()
    {
        return view('admin.marketing.landing-pages-create');
    }

    public function storeLandingPage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);
        
        LandingPage::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Landing page created successfully!']);
        }
        
        return redirect()->route('admin.marketing.landing-pages')->with('success', 'Landing page created successfully!');
    }

    public function editLandingPage($id)
    {
        $page = LandingPage::findOrFail($id);
        return view('admin.marketing.landing-pages-edit', compact('page'));
    }

    public function updateLandingPage(Request $request, $id)
    {
        $page = LandingPage::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);
        
        $page->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Landing page updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.landing-pages')->with('success', 'Landing page updated successfully!');
    }

    public function destroyLandingPage($id)
    {
        $page = LandingPage::findOrFail($id);
        $page->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Landing page deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.landing-pages')->with('success', 'Landing page deleted successfully!');
    }

    // ==================== ANALYTICS ====================
    
    public function analytics(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->subDays(30)->toDateString();
        $dateTo = $request->date_to ?? Carbon::now()->toDateString();
        
        try {
            $analytics = [
                'email_open_rate' => EmailCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->avg('open_rate') ?? 0,
                'email_click_rate' => EmailCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->avg('click_rate') ?? 0,
                'sms_delivery_rate' => SmsCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->avg('delivery_rate') ?? 0,
                'social_engagement' => SocialMediaPost::whereBetween('created_at', [$dateFrom, $dateTo])->avg('engagement_rate') ?? 0,
                'total_emails_sent' => EmailCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->sum('sent_count') ?? 0,
                'total_sms_sent' => SmsCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->sum('sent_count') ?? 0,
                'total_social_posts' => SocialMediaPost::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'published')->count(),
            ];
        } catch (\Exception $e) {
        $analytics = [
            'email_open_rate' => 0,
            'email_click_rate' => 0,
            'sms_delivery_rate' => 0,
            'social_engagement' => 0,
                'total_emails_sent' => 0,
                'total_sms_sent' => 0,
                'total_social_posts' => 0,
        ];
        }
        
        return view('admin.marketing.analytics', compact('analytics', 'dateFrom', 'dateTo'));
    }

    // ==================== BANNERS ====================
    
    public function banners(Request $request)
    {
        $query = Banner::query();
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }
        
        $banners = $query->orderBy('display_order')->latest()->paginate(20);
        
        return view('admin.marketing.banners', compact('banners'));
    }

    public function createBanner()
    {
        return view('admin.marketing.banners-create');
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'required|url',
            'link_url' => 'nullable|url',
            'position' => 'required|in:header,sidebar,footer,popup',
            'type' => 'required|in:banner,popup',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'display_order' => 'nullable|integer|min:0',
            'target_audience' => 'required|in:all,logged_in,guests',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        if (!$validated['display_order']) {
            $validated['display_order'] = (Banner::max('display_order') ?? 0) + 1;
        }
        
        Banner::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Banner created successfully!']);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner created successfully!');
    }

    public function editBanner($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.marketing.banners-edit', compact('banner'));
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'required|url',
            'link_url' => 'nullable|url',
            'position' => 'required|in:header,sidebar,footer,popup',
            'type' => 'required|in:banner,popup',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'display_order' => 'nullable|integer|min:0',
            'target_audience' => 'required|in:all,logged_in,guests',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $banner->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Banner updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner updated successfully!');
    }

    public function toggleBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Banner status updated successfully!',
                'is_active' => $banner->is_active
            ]);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner status updated successfully!');
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Banner deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner deleted successfully!');
    }
}
