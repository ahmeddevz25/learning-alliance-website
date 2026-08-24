@extends('index.layout')
@section('content')

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <div class="body-wrapper">
        <div class="breadcrumb">
            <div class="container">
                <ul class="list-unstyled d-flex align-items-center m-0">
                    <li><a href="/">Home</a></li>
                    <li>
                        <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.4">
                                <path
                                    d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"
                                    fill="#000"></path>
                            </g>
                        </svg>
                    </li>
                    <li>Cart</li>
                    <li>
                        <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.4">
                                <path
                                    d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"
                                    fill="#000" />
                            </g>
                        </svg>
                    </li>
                    <li>Checkout</li>
                </ul>
            </div>
        </div>
        <main id="MainContent" class="content-for-layout">
            <div class="checkout-page mt-100">
                <div class="container">
                    <div class="checkout-page-wrapper">
                        <div class="row">
                            <div class="col-xl-9 col-lg-8 col-md-12 col-12 mb-5">
                                <div class="section-header mb-3">
                                    <h2 class="section-heading">Check out</h2>
                                </div>

                                <div class="shipping-address-area">
                                    <h2 class="shipping-address-heading pb-1">Shipping address</h2>
                                    <div class="shipping-address-form-wrapper">
                                        <form action="{{ route('order.place') }}" method="POST"
                                            class="shipping-address-form common-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <fieldset>
                                                        <label class="label">Campus</label>
                                                        <select class="form-select" name="campus" required>
                                                            <option value="Learning Alliance DHA"
                                                                {{ old('campus') === 'Learning Alliance DHA' ? 'selected' : '' }}>
                                                                Learning Alliance DHA</option>
                                                            <option value="Learning Alliance International DHA"
                                                                {{ old('campus') === 'Learning Alliance International DHA' ? 'selected' : '' }}>
                                                                Learning Alliance International DHA</option>
                                                            <option value="Learning Alliance Aziz Avenue"
                                                                {{ old('campus') === 'Learning Alliance Aziz Avenue' ? 'selected' : '' }}>
                                                                Learning Alliance Aziz Avenue</option>
                                                            <option value="Learning Alliance Gulberg"
                                                                {{ old('campus') === 'Learning Alliance Gulberg' ? 'selected' : '' }}>
                                                                Learning Alliance Gulberg</option>
                                                            <option value="Learning Alliance Faisalabad"
                                                                {{ old('campus') === 'Learning Alliance Faisalabad' ? 'selected' : '' }}>
                                                                Learning Alliance Faisalabad</option>
                                                        </select>
                                                        @error('campus')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </fieldset>
                                                </div>

                                                <div class="col-lg-6 col-md-12 col-12">
                                                    <fieldset>
                                                        <label class="label">Parent name</label>
                                                        <input type="text" name="parent_name"
                                                            value="{{ old('parent_name') }}" required />
                                                        @error('parent_name')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </fieldset>
                                                </div>

                                                <div class="col-lg-6 col-md-12 col-12">
                                                    <fieldset>
                                                        <label class="label">Student name</label>
                                                        <input type="text" name="student_name"
                                                            value="{{ old('student_name') }}" required />
                                                        @error('student_name')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </fieldset>
                                                </div>

                                                <div class="col-lg-6 col-md-12 col-12">
                                                    <fieldset>
                                                        <label class="label">Class</label>
                                                        <input type="text" name="class" value="{{ old('class') }}" />
                                                        @error('class')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </fieldset>
                                                </div>

                                                <div class="col-lg-6 col-md-12 col-12">
                                                    <fieldset>
                                                        <label class="label">Section</label>
                                                        <input type="text" name="section"
                                                            value="{{ old('section') }}" />
                                                        @error('section')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </fieldset>
                                                </div>

                                                <div class="col-lg-6 col-md-12 col-12">
                                                    <fieldset>
                                                        <label class="label">Phone</label>
                                                        <input type="text" name="phone" value="{{ old('phone') }}" />
                                                        @error('phone')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </fieldset>
                                                </div>

                                                <div class="col-lg-6 col-md-12 col-12">
                                                    <fieldset>
                                                        <label class="label">Email</label>
                                                        <input type="email" name="email" value="{{ old('email') }}" />
                                                        @error('email')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </fieldset>
                                                </div>
                                            </div>

                                            <div class="shipping-address-area billing-area">
                                                <div class="form-checkbox d-flex align-items-center mt-4">
                                                    <label class="form-check-label ms-2">
                                                        Your personal data will be used to process your order, support your
                                                        experience throughout this website, and for other purposes described
                                                        in our privacy policy.
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="shipping-address-area billing-area">
                                                <div class="g-recaptcha mb-3" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                                @error('g-recaptcha-response')
                                                    <small class="text-danger d-block mb-3">{{ $message }}</small>
                                                @enderror
                                                <div
                                                    class="minicart-btn-area d-flex align-items-center justify-content-between flex-wrap">
                                                    <a href="{{ route('cartdetails') }}"
                                                        class="checkout-page-btn minicart-btn btn-secondary">BACK TO
                                                        CART</a>
                                                    <button type="submit"
                                                        class="checkout-page-btn minicart-btn btn-primary">PLACE
                                                        ORDER</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                
                            </div>
                            @php use Illuminate\Support\Str; @endphp

                            <div class="col-xl-3 col-lg-4 col-md-12 col-12">
                                <div class="cart-total-area checkout-summary-area">
                                    <h4 class="d-none d-lg-block mb-0 text-center heading_24 mb-4">Order summary</h4>

                                    {{-- Items list --}}
                                    @foreach ($cartItems as $item)
                                        @php
                                            $p = $item->product;
                                            $size = $item->sizeItem;
                                            $variant = $item->variant;
                                            $price = (float) ($variant->price ?? 0);
                                            $qty = (int) $item->quantity;
                                            $line = $price * $qty;

                                            $img =
                                                optional($p?->images?->where('is_primary', true)->first())
                                                    ->image_path ??
                                                (optional($p?->images?->first())->image_path ?? 'default.jpg');
                                        @endphp

                                        <div class="minicart-item d-flex align-items-center mb-3">
                                            <div class="mini-img-wrapper me-3">
                                                <img class="mini-img" src="{{ asset('storage/' . $img) }}"
                                                    alt="{{ $p?->name }}">
                                            </div>
                                            <div class="product-info flex-grow-1">
                                                <h2 class="product-title mb-1">
                                                    <a
                                                        href="{{ route('product.details', Str::slug($p?->name ?? 'product')) }}">
                                                        {{ $p?->name ?? 'Product' }}
                                                    </a>
                                                </h2>
                                                <p class="product-vendor mb-0">
                                                    {{ $size?->size ?? '—' }}
                                                    <span class="text-muted">• Rs. {{ number_format($price, 2) }} ×
                                                        {{ $qty }}</span>
                                                </p>
                                            </div>
                                            <div class="ms-2 text-end">
                                                <strong>Rs. {{ number_format($line, 2) }}</strong>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Totals --}}
                                    <div class="cart-total-box mt-4 bg-transparent p-0">
                                        <div class="subtotal-item subtotal-box">
                                            <h4 class="subtotal-title">Subtotal:</h4>
                                            <p class="subtotal-value">Rs. {{ number_format($subtotal, 2) }}</p>
                                        </div>

                                        <hr />

                                        <div class="subtotal-item discount-box">
                                            <h4 class="subtotal-title">Total:</h4>
                                            <p class="subtotal-value">Rs. {{ number_format($grandTotal, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
