<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPage;
use App\Models\AboutPageTeamMember;
use App\Models\AboutPageValue;
use App\Models\AboutPageRecognition;
use App\Models\AboutPageTimelineItem;
use App\Models\AboutPageStatistic;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sections
        $sections = [
            [
                'section_key' => 'hero',
                'section_name' => 'Hero Section',
                'content' => null,
                'data' => [
                    'badge_text' => 'Our Story',
                    'title' => 'About Lau Paradise Adventures',
                    'subtitle' => "Tanzania's premier tour operator offering authentic safaris, Kilimanjaro climbs, and beach holidays since 2025.",
                ],
                'image_url' => 'images/safari_home-1.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'section_key' => 'story',
                'section_name' => 'Our Story',
                'content' => "Lau Paradise Adventures was founded with a simple mission: to share the incredible beauty and wonder of Tanzania with the world. Born from a deep love for our homeland, we started as a small local company in Arusha, Tanzania, with a passion for showcasing the best of what Tanzania has to offer—from the iconic Serengeti and Kilimanjaro to the pristine beaches of Zanzibar.\n\nAs a Tanzania-based company, we understand our country like no one else. Our team consists of local Tanzanians who have grown up exploring these lands, from the vast savannas to the highest peaks. We've grown from organizing small local tours to becoming a trusted name in Tanzania tourism, yet our core philosophy remains unchanged: every journey should be authentic, sustainable, and deeply personal, showcasing the real Tanzania.",
                'data' => [
                    'badge' => 'Our Story',
                    'title' => 'Born in Tanzania, Dedicated to Tanzania',
                    'founded_year' => '2025',
                ],
                'image_url' => 'images/safari_home-1.jpg',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'section_key' => 'mission',
                'section_name' => 'Mission',
                'content' => "To provide authentic, sustainable, and transformative Tanzania travel experiences that connect travelers with the natural beauty, rich culture, and incredible wildlife of our homeland while supporting local communities and preserving Tanzania's natural heritage for future generations.",
                'data' => [
                    'title' => 'Our Mission',
                    'icon' => 'fas fa-bullseye',
                ],
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'section_key' => 'vision',
                'section_name' => 'Vision',
                'content' => "To be Tanzania's most trusted and respected tour operator, recognized globally for our commitment to excellence, sustainability, and authentic cultural experiences. We envision a future where responsible tourism helps preserve Tanzania's natural wonders while empowering local communities.",
                'data' => [
                    'title' => 'Our Vision',
                    'icon' => 'fas fa-eye',
                ],
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'section_key' => 'values',
                'section_name' => 'Values',
                'content' => null,
                'data' => [
                    'badge' => 'What We Stand For',
                    'title' => 'Our Core Values',
                    'subtitle' => 'These principles guide everything we do and shape every experience we create.',
                ],
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'section_key' => 'team',
                'section_name' => 'Team',
                'content' => null,
                'data' => [
                    'badge' => 'Meet Our Team',
                    'title' => 'The People Behind Your Adventure',
                    'subtitle' => 'Our passionate team of Tanzania experts is dedicated to making your journey unforgettable.',
                ],
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'section_key' => 'recognition',
                'section_name' => 'Recognition',
                'content' => null,
                'data' => [
                    'badge' => 'Recognition',
                    'title' => 'Recognition',
                    'subtitle' => "We're proud to be recognized for our commitment to excellence and responsible tourism.",
                ],
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'section_key' => 'timeline',
                'section_name' => 'Timeline',
                'content' => null,
                'data' => [
                    'badge' => 'Our Journey',
                    'title' => 'Our Story Through the Years',
                    'subtitle' => "From humble beginnings to becoming Tanzania's trusted tour operator",
                ],
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'section_key' => 'achievements',
                'section_name' => 'Achievements',
                'content' => null,
                'data' => [
                    'badge' => 'Our Achievements',
                    'title' => 'Milestones & Recognition',
                    'subtitle' => 'Proud moments and recognition for our commitment to excellence',
                ],
                'display_order' => 9,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $section) {
            AboutPage::updateOrCreate(
                ['section_key' => $section['section_key']],
                $section
            );
        }

        // Create values
        $values = [
            [
                'title' => 'Sustainability',
                'description' => "We're committed to eco-friendly practices, minimizing our environmental impact, and supporting conservation efforts across Tanzania.",
                'icon' => 'fas fa-leaf',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Community',
                'description' => 'We support local communities by hiring local guides, using local suppliers, and contributing to community development projects.',
                'icon' => 'fas fa-users',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Safety',
                'description' => 'Your safety is our top priority. We maintain the highest safety standards and provide comprehensive support throughout your journey.',
                'icon' => 'fas fa-shield-alt',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Excellence',
                'description' => 'We strive for excellence in every detail, from expert guides to premium accommodations and seamless service.',
                'icon' => 'fas fa-star',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Authenticity',
                'description' => 'We provide genuine Tanzanian experiences that connect you with the real culture, wildlife, and landscapes of our country.',
                'icon' => 'fas fa-heart',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Integrity',
                'description' => 'We operate with honesty, transparency, and ethical business practices in all our interactions and operations.',
                'icon' => 'fas fa-handshake',
                'display_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($values as $value) {
            AboutPageValue::updateOrCreate(
                ['title' => $value['title']],
                $value
            );
        }

        // Create recognitions (removed "Best Tour Operator 2023")
        $recognitions = [
            [
                'title' => 'TATO Member',
                'description' => 'Tanzania Association of Tour Operators - Certified Member',
                'icon' => 'fas fa-certificate',
                'year' => null,
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Fully Insured',
                'description' => 'Comprehensive insurance coverage for all travelers',
                'icon' => 'fas fa-shield-alt',
                'year' => null,
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'TALA Certified',
                'description' => 'Tanzania Association of Landlords & Agents Certification',
                'icon' => 'fas fa-check-circle',
                'year' => null,
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Eco-Friendly',
                'description' => 'Committed to sustainable and responsible tourism practices',
                'icon' => 'fas fa-leaf',
                'year' => null,
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => '5-Star Rating',
                'description' => 'Consistently rated 5 stars by travelers on TripAdvisor',
                'icon' => 'fas fa-star',
                'year' => null,
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'TATO Certified',
                'description' => 'Tanzania Association of Tour Operators - Full Member',
                'icon' => 'fas fa-certificate',
                'year' => '2025',
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Eco-Tourism Leader',
                'description' => 'Recognized for sustainable tourism practices',
                'icon' => 'fas fa-leaf',
                'year' => '2025',
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'TripAdvisor Excellence',
                'description' => 'Certificate of Excellence',
                'icon' => 'fas fa-star',
                'year' => '2025',
                'display_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($recognitions as $recognition) {
            AboutPageRecognition::create($recognition);
        }

        // Create timeline items (updated to 2025)
        $timelineItems = [
            [
                'year' => '2025',
                'title' => 'Foundation',
                'description' => 'Lau Paradise Adventures was founded in Arusha, Tanzania, with a vision to share the beauty of Tanzania with the world.',
                'display_order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($timelineItems as $item) {
            AboutPageTimelineItem::updateOrCreate(
                ['year' => $item['year'], 'title' => $item['title']],
                $item
            );
        }

        // Create statistics
        $statistics = [
            [
                'label' => 'Happy Travelers',
                'value' => '0',
                'description' => 'Travelers who have experienced unforgettable adventures with us',
                'icon' => 'fas fa-users',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'Tours Available',
                'value' => '0',
                'description' => 'Carefully crafted tours across Tanzania',
                'icon' => 'fas fa-calendar-check',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'Average Rating',
                'value' => '0',
                'description' => 'Consistently rated by our travelers',
                'icon' => 'fas fa-star',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'label' => 'Years Experience',
                'value' => '0',
                'description' => 'Serving travelers since 2025',
                'icon' => 'fas fa-award',
                'display_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($statistics as $stat) {
            AboutPageStatistic::updateOrCreate(
                ['label' => $stat['label']],
                $stat
            );
        }

        // Create team members
        $teamMembers = [
            [
                'name' => 'Laurence',
                'role' => 'Founder',
                'bio' => "With extensive experience in Tanzania tourism, Laurence founded Lau Paradise Adventures to share his passion for Tanzania's natural wonders.",
                'image_url' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'expertise' => ['Safari Expert', 'Wildlife Guide'],
                'social_links' => ['linkedin' => '#', 'twitter' => '#'],
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'David Ngungila',
                'role' => 'Software Developer',
                'bio' => 'David brings technical expertise to Lau Paradise Adventures, developing innovative solutions to enhance the travel experience for our clients.',
                'image_url' => 'https://randomuser.me/api/portraits/men/46.jpg',
                'expertise' => ['Web Development', 'Digital Solutions'],
                'social_links' => ['linkedin' => '#', 'twitter' => '#'],
                'display_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($teamMembers as $member) {
            AboutPageTeamMember::updateOrCreate(
                ['name' => $member['name'], 'role' => $member['role']],
                $member
            );
        }
    }
}
