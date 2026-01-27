@extends('layouts.app')

@section('title', 'Gift Cards - Adventure Tours')
@section('description', 'Give the gift of adventure. Purchase a gift card from Adventure Tours, the perfect present for any explorer, valid for any tour.')

@section('body_class', 'support-page')

@section('content')

    <!-- Page Header -->
    <section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1520175488493-4a9987d625b5?auto=format&fit=crop&w=1920&q=80&blend=0f3429&sat=-100&bri=-20&bm=multiply');">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Give the Gift of Adventure</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">The perfect present for the explorer in your life.</p>
        </div>
    </section>

    <!-- Gift Card Section -->
    <section class="content-section" x-data="{ amount: 250 }">
        <div class="container">
            <div class="gift-card-wrapper">
                <div class="gift-card-visual" data-aos="fade-right">
                    <div class="card-design">
                        <span class="card-logo">ADVENTURE<span class="highlight">TOURS</span></span>
                        <span class="card-label">Gift Card</span>
                        <span class="card-amount" x-text="'$' + amount"></span>
                    </div>
                </div>
                <div class="gift-card-form" data-aos="fade-left">
                    <h2 class="form-title">Purchase a Gift Card</h2>
                    <p>Gift cards are delivered by email and contain instructions to redeem them at checkout. Our gift cards have no additional processing fees.</p>
                    <div class="amount-selection">
                        <button @click="amount = 100" :class="{'active': amount === 100}">$100</button>
                        <button @click="amount = 250" :class="{'active': amount === 250}">$250</button>
                        <button @click="amount = 500" :class="{'active': amount === 500}">$500</button>
                        <button @click="amount = 1000" :class="{'active': amount === 1000}">$1000</button>
                    </div>
                    <form action="#">
                        <div class="form-group">
                            <label>Selected Amount</label>
                            <input type="text" :value="'$' + amount" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Page Header Styles */
    .page-header { padding: 100px 0; /* ... */ }

    .gift-card-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    .card-design {
        background: var(--dark-green);
        color: var(--white);
        padding: 40px;
        border-radius: 15px;
        aspect-ratio: 16 / 10;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 15px 40px var(--shadow);
    }
    .card-logo { font-family: var(--font-primary); font-size: 1.5rem; letter-spacing: 2px; }
    .card-logo .highlight { color: var(--accent-green); }
    .card-label { font-size: 1rem; opacity: 0.7; }
    .card-amount { font-family: var(--font-primary); font-size: 3rem; text-align: right; }
    
    .form-title { font-family: var(--font-primary); font-size: 2rem; margin-bottom: 15px; }
    .gift-card-form p { color: var(--gray); margin-bottom: 30px; }
    .amount-selection { display: flex; gap: 10px; margin-bottom: 20px; }
    .amount-selection button {
        flex-grow: 1; padding: 15px; background: var(--light-green); border: 2px solid var(--light-green);
        border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;
    }
    .amount-selection button.active { border-color: var(--accent-green); background: var(--white); }
    html.dark .amount-selection button { background: var(--card-bg); border-color: var(--shadow); }
    html.dark .amount-selection button.active { border-color: var(--accent-green); }
    .btn-block { width: 100%; text-align: center; padding-top: 15px; padding-bottom: 15px; font-size: 1.1rem; }

    @media (max-width: 992px) { .gift-card-wrapper { grid-template-columns: 1fr; } }
</style>
@endpush
