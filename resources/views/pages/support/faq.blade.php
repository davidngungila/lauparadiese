@extends('layouts.app')

@section('title', 'Frequently Asked Questions - Adventure Tours')
@section('description', 'Find answers to common questions about our tours, booking process, safety, and preparation. Get all the information you need for your next adventure.')

@section('body_class', 'support-page')

@section('content')

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Frequently Asked Questions</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">Have a question? We've got answers. If you can't find what you're looking for, please don't hesitate to contact us.</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="content-section faq-section">
        <div class="container">
            <div class="faq-accordion" x-data="{ active: 1 }">
                @php
                    $faqs = [
                        1 => ['q' => 'What level of fitness is required for your tours?', 'a' => 'Each of our tours has a specific fitness rating, from easy (1/5) for cultural tours to strenuous (5/5) for high-altitude expeditions like Kilimanjaro. You can find this rating on each tour details page. We pride ourselves on offering adventures suitable for a wide range of fitness levels.'],
                        2 => ['q' => 'Do you offer custom or private tours?', 'a' => 'Absolutely! We specialize in creating bespoke, private itineraries for individuals, families, and corporate groups. Our travel experts can tailor a trip to your specific interests, budget, and schedule. Please contact us to start planning your custom adventure.'],
                        3 => ['q' => 'What is your cancellation policy?', 'a' => 'We offer a flexible cancellation policy. You can cancel up to 60 days before your trip for a full refund, minus a small processing fee. Cancellations made between 30 and 60 days are eligible for a 50% refund. Please refer to our Terms of Service for complete details.'],
                        4 => ['q' => 'Is travel insurance required?', 'a' => 'Comprehensive travel insurance is mandatory for all our international tours, especially for high-altitude treks and remote expeditions. It ensures you are covered for medical emergencies, trip cancellations, and other unforeseen events. We can recommend trusted insurance partners.'],
                        5 => ['q' => 'What kind of accommodation should I expect?', 'a' => 'Accommodations vary by tour and are chosen to enhance your experience. They range from comfortable, locally-owned hotels and eco-lodges on our cultural tours to high-quality tents and mountain huts on our trekking expeditions. You can find specific details on each tour itinerary page.'],
                    ];
                @endphp
                @foreach($faqs as $id => $faq)
                <div class="faq-item" data-aos="fade-up" data-aos-delay="{{ $id * 100 }}">
                    <button class="faq-question" @click="active = active === {{ $id }} ? null : {{ $id }}">
                        <span>{{ $faq['q'] }}</span>
                        <i class="fas fa-chevron-down" :class="{'rotate-180': active === {{ $id }} }"></i>
                    </button>
                    <div class="faq-answer" x-show="active === {{ $id }}" x-collapse.duration.500ms>
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Page Header Styles */
    .page-header { padding: 100px 0; /* ... */ }

    .faq-section { padding: 100px 0; background: var(--light-green); }
    html.dark .faq-section { background: var(--background); }
    .faq-accordion { max-width: 800px; margin: 0 auto; }
    .faq-item { background: var(--card-bg); border-radius: 8px; margin-bottom: 10px; border: 1px solid var(--shadow); overflow: hidden; }
    .faq-question { width: 100%; padding: 20px; display: flex; justify-content: space-between; align-items: center; background: none; border: none; cursor: pointer; text-align: left; }
    .faq-question span { font-family: var(--font-secondary); font-weight: 600; font-size: 1.1rem; color: var(--text-color); }
    .faq-question i { font-size: 1.2rem; color: var(--accent-green); transition: transform 0.3s; }
    .faq-answer { padding: 0 20px 20px 20px; color: var(--gray); line-height: 1.7; }
    .rotate-180 { transform: rotate(180deg); }
</style>
@endpush
