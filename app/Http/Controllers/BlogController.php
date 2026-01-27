<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display the main blog page with a list of all posts.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // In a real application, you would fetch these from your database.
        // e.g., $posts = Post::latest()->paginate(10);
        $posts = $this->getPlaceholderPosts();

        return view('blog.index', [
            'posts' => $posts
        ]);
    }

    /**
     * Display a single blog post.
     *
     * @param string $post_slug
     * @return \Illuminate\View\View
     */
    public function show(string $post_slug): View
    {
        // Find the specific post by its slug.
        $post = collect($this->getPlaceholderPosts())->firstWhere('slug', $post_slug);

        // If the post doesn't exist, abort with a 404 error.
        if (!$post) {
            abort(404);
        }

        return view('blog.show', [
            'post' => $post
        ]);
    }

    /**
     * Display posts belonging to a specific category.
     *
     * @param string $category_slug
     * @return \Illuminate\View\View
     */
    public function category(string $category_slug): View
    {
        // In a real app, you would find the category and its posts.
        // e.g., $category = Category::where('slug', $category_slug)->firstOrFail();
        //       $posts = $category->posts()->latest()->paginate(10);

        $category_name = ucwords(str_replace('-', ' ', $category_slug));
        $posts_in_category = collect($this->getPlaceholderPosts())->where('category_slug', $category_slug);

        return view('blog.category', [
            'category_name' => $category_name,
            'posts' => $posts_in_category
        ]);
    }

    /**
     * Helper function to provide placeholder blog data.
     *
     * @return array
     */
    private function getPlaceholderPosts(): array
    {
        return [
            [
                'title' => '10 Essential Tips for High-Altitude Trekking',
                'slug' => '10-essential-tips-for-high-altitude-trekking',
                'author' => 'Alex Riley',
                'published_at' => '2025-08-15',
                'category' => 'Travel Tips',
                'category_slug' => 'travel-tips',
                'excerpt' => 'Preparing for a high-altitude trek requires more than just physical fitness. Here are our top 10 tips to ensure you stay safe, healthy, and happy on your journey to the top.',
                'image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'A Guide to Ethical Wildlife Photography on Safari',
                'slug' => 'a-guide-to-ethical-wildlife-photography-on-safari',
                'author' => 'Maria Chen',
                'published_at' => '2025-07-22',
                'category' => 'Safari Guides',
                'category_slug' => 'safari-guides',
                'excerpt' => 'Capturing the perfect shot shouldn\'t come at the expense of the animals\' well-being. Learn the principles of ethical wildlife photography to respect nature while getting incredible photos.',
                'image' => 'https://images.unsplash.com/photo-1549462375-4c5b0814f13b?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Packing for Patagonia: The Ultimate Gear List',
                'slug' => 'packing-for-patagonia-the-ultimate-gear-list',
                'author' => 'Alex Riley',
                'published_at' => '2025-06-30',
                'category' => 'Travel Tips',
                'category_slug' => 'travel-tips',
                'excerpt' => 'Patagonia\'s weather is notoriously unpredictable. Our comprehensive gear list covers everything you\'ll need to stay comfortable, from the trail to your tent.',
                'image' => 'https://images.unsplash.com/photo-1542692244-138c45424912?auto=format&fit=crop&w=800&q=80',
            ],
        ];
    }
}

