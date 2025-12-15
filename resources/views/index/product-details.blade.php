@extends('index.layout')
@section('content')
    <style>
        input[type="radio"]:disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
        }

        .toast-success {
            background-color: #51A351 !important;
        }

        .toast-error {
            background-color: #BD362F !important;
        }

        .toast-info {
            background-color: #2F96B4 !important;
        }

        .toast-warning {
            background-color: #F89406 !important;
        }
    </style>
    <div class="body-wrapper">
        @include('sweetalert::alert')
        <!-- breadcrumb start -->
        <div class="breadcrumb">
            <div class="container">
                <ul class="list-unstyled d-flex align-items-center m-0">
                    <li><a href="/">Home</a></li>

                    <!-- Parent Category (e.g., Boys) -->
                    @if ($product->category?->parent)
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
                        <li>
                            <a href="/category/{{ $product->category?->parent?->slug }}">
                                {{ $product->category?->parent?->name }}
                            </a>
                        </li>
                    @endif

                    @if ($product->category)
                        <li>
                            <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.4">
                                    <path
                                        d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"
                                        fill="#000" />
                                </g>
                            </svg>
                        </li>
                        <li>
                            <a href="/category/{{ $product->category?->slug }}">
                                {{ $product->category?->name }}
                            </a>
                        </li>
                    @endif


                    <!-- Current Category (e.g., Winter Uniform) -->
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
                    <!-- Product Name -->
                    <li>{{ $product->name }}</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->

        <main id="MainContent" class="content-for-layout">
            <div class="product-page mt-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="product-gallery product-gallery-vertical d-flex">
                                <div class="product-img-large">
                                    <div class="img-large-slider common-slider"
                                        data-slick='{
                                        "slidesToShow": 1,
                                        "slidesToScroll": 1,
                                        "dots": false,
                                        "arrows": false,
                                        "asNavFor": ".img-thumb-slider"
                                    }'>
                                        @foreach ($product->images as $image)
                                            <div class="img-large-wrapper">
                                                <a href="{{ asset('storage/' . $image->image_path) }}"
                                                    data-fancybox="gallery">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        alt="Product Image">
                                                </a>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="product-img-thumb">
                                    <div class="img-thumb-slider common-slider" data-vertical-slider="true"
                                        data-slick='{
                                        "slidesToShow": 5,
                                        "slidesToScroll": 1,
                                        "dots": false,
                                        "arrows": true,
                                        "infinite": false,
                                        "speed": 300,
                                        "cssEase": "ease",
                                        "focusOnSelect": true,
                                        "swipeToSlide": true,
                                        "asNavFor": ".img-large-slider"
                                    }'>
                                        @foreach ($product->images as $image)
                                            <div>
                                                <div class="img-thumb-wrapper">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        alt="Product Thumbnail">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div
                                        class="activate-arrows show-arrows-always arrows-white d-none d-lg-flex justify-content-between mt-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            // default selected size (first available)
                            $firstSize = $product->sizes->first();
                        @endphp

                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="product-details ps-lg-4">
                                <div class="mb-3"><span class="product-availability">In Stock</span></div>
                                <h2 class="product-title mb-3">{{ $product->name }}</h2>

                                <div class="mb-2">

                                    @foreach ($product->categories as $cat)
                                        <a href="{{ route('category.show', [$cat->id, Str::slug($cat->name)]) }}"
                                            class="badge bg-light text-dark border mb-1 px-3 py-2"
                                            style="font-size: 0.7rem; border-radius: 20px; transition: all 0.2s;">
                                            {{ $cat->name }}
                                        </a>
                                    @endforeach
                                </div>



                                <div class="product-price-wrapper mb-4">
                                    <span class="product-price regular-price" id="size-price">
                                        Rs. {{ $firstSize->price ?? 0 }}
                                    </span>
                                </div>

                                <div class="product-variant-wrapper">
                                    <div class="product-variant product-variant-other">
                                        <strong class="label mb-1 d-block mb-3">Size:</strong>

                                        {{-- <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                                            @foreach ($product->sizes as $size)
                                                <li class="variant-item">
                                                    <input type="radio" name="size_choice"
                                                        value="{{ $size->sizeItem->id }}"
                                                        data-price="{{ $size->price }}" data-stock="{{ $size->stock }}"
                                                        id="size-{{ $size->sizeItem->id }}"
                                                        @if ($size->stock == 0) disabled @endif
                                                        @if ($loop->first && $size->stock > 0) checked @endif />

                                                    @if ($size->stock == 0)
                                                        <span class="text-danger"
                                                            style="display:block;margin-top:-20px;font-size:.85rem">
                                                            Out of Stock
                                                        </span>
                                                    @endif
                                                    <label class="variant-label" for="size-{{ $size->sizeItem->id }}">
                                                        {{ $size->sizeItem->size }}
                                                    </label>

                                                </li>
                                            @endforeach
                                        </ul> --}}
                                        <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                                            @foreach ($product->sizes as $size)
                                                <li class="variant-item">
                                                    <input type="radio" name="size_choice"
                                                        value="{{ $size->sizeItem->id }}" data-price="{{ $size->price }}"
                                                        id="size-{{ $size->sizeItem->id }}"
                                                        @if ($loop->first) checked @endif />

                                                    <label class="variant-label" for="size-{{ $size->sizeItem->id }}">
                                                        {{ $size->sizeItem->size }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>

                                    </div>
                                </div>

                                {{-- <div class="product-stock-wrapper mb-4">
                                    <span class="product-stock" id="size-stock">
                                        @if (($firstSize->price ?? 0) > 0 && ($firstSize->stock ?? 0) > 0)
                                            {{ $firstSize->stock }} in stock
                                        @else
                                            Out of Stock
                                        @endif
                                    </span>
                                </div> --}}


                                {{-- ADD TO CART FORM --}}
                                <form class="product-form" method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    @if ($firstSize && $firstSize->sizeItem)
                                        <input type="hidden" id="size_id_input" name="size_id"
                                            value="{{ $firstSize->sizeItem->id }}">
                                    @else
                                        <input type="hidden" id="size_id_input" name="size_id" value="">
                                    @endif

                                    {{-- <input class="qty-input" type="number" name="quantity" value="1" min="1"> --}}


                                    {{-- <div class="quantity d-flex align-items-center justify-content-between">
                                        <button type="button" class="qty-btn dec-qty" aria-label="Decrease">
                                            <img src="{{ asset('index') }}/assets/img/icon/minus.svg" alt="minus">
                                        </button>

                                        <input id="qty-input" class="qty-input" type="number" name="quantity"
                                            value="1" min="1" step="1"
                                            max="{{ $firstSize ? max(1, (int) $firstSize->stock) : 1 }}">


                                        <button type="button" class="qty-btn inc-qty" aria-label="Increase">
                                            <img src="{{ asset('index') }}/assets/img/icon/plus.svg" alt="plus">
                                        </button>
                                    </div> --}}
                                    <div class="quantity d-flex align-items-center justify-content-between">
                                        <button type="button" class="qty-btn dec-qty" aria-label="Decrease">
                                            <img src="{{ asset('index') }}/assets/img/icon/minus.svg" alt="minus">
                                        </button>

                                        <input id="qty-input" class="qty-input" type="number" name="quantity"
                                            value="1" min="1" step="1">

                                        <button type="button" class="qty-btn inc-qty" aria-label="Increase">
                                            <img src="{{ asset('index') }}/assets/img/icon/plus.svg" alt="plus">
                                        </button>
                                    </div>


                                    <div
                                        class="product-form-buttons d-flex align-items-center justify-content-between mt-4">
                                        <button type="submit" class="position-relative btn-atc btn-add-to-cart loader">
                                            ADD TO CART
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- product tab start -->
            {{-- <div class="product-tab-section mt-100" data-aos="fade-up" data-aos-duration="700">
                <div class="container">
                    <div class="tab-list product-tab-list">
                        <nav class="nav product-tab-nav">
                            <a class="product-tab-link tab-link active" href="#pdescription"
                                data-bs-toggle="tab">Description</a>
                        </nav>
                    </div>
                    <div class="tab-content product-tab-content">
                        <div id="pdescription" class="tab-pane fade show active">
                            <div class="row">
                                <div class="col-lg-7 col-md-12 col-12">
                                    <div class="desc-content">
                                        <p class="text_16 mb-4">{!! Str::limit(strip_tags($product->desc), 50) !!}</< /p>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12 col-12">
                                    <div class="desc-img">
                                        @php
                                            $mainImage = $product->images->firstWhere('is_primary', true);
                                        @endphp
                                        @if ($mainImage && $mainImage->image_path)
                                            <img src="{{ asset('storage/' . $mainImage->image_path) }}" alt="Main Image">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            @php
                use Illuminate\Support\Str;
                $relCount = $relatedProducts->count();
            @endphp

            @if ($relCount > 0)
                <div class="featured-collection-section mt-100 home-section overflow-hidden mb-100">
                    <div class="container">
                        <div class="section-header">
                            <h2 class="section-heading">You may also like</h2>
                        </div>
                        <div class="product-container position-relative {{ $relCount === 1 ? 'related-one' : '' }}">
                            <div class="row justify-content-start">
                                @foreach ($relatedProducts as $product)
                                    @php
                                        $imgs = $product->images ?? collect();
                                        $main =
                                            optional($imgs->firstWhere('is_primary', true))->image_path ??
                                            (optional($imgs->first())->image_path ?? 'default.jpg');
                                        $hover = optional($imgs->skip(1)->first())->image_path ?? $main;
                                        $price = optional($product->sizes->first())->price ?? ($product->price ?? 0);
                                    @endphp

                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                        <div class="new-item" data-aos="fade-up" data-aos-duration="300">
                                            <div class="product-card">
                                                <div class="product-card-img">
                                                    <a class="hover-switch"
                                                        href="{{ route('product.details', Str::slug($product->name)) }}">
                                                        <img class="secondary-img" src="{{ asset('storage/' . $hover) }}"
                                                            alt="{{ $product->name }}">
                                                        <img class="primary-img" src="{{ asset('storage/' . $main) }}"
                                                            alt="{{ $product->name }}">
                                                    </a>
                                                </div>
                                                <div class="product-card-details text-center">
                                                    <h3 class="product-card-title">
                                                        <a
                                                            href="{{ route('product.details', Str::slug($product->name)) }}">{{ $product->name }}</a>
                                                    </h3>
                                                    <div class="product-card-price">
                                                        <span class="card-price-regular">Rs.
                                                            {{ number_format((float) $price) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="container text-center mb-3">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4">
                                        <div class="text-center mb-3">
                                            {{ $relatedProducts->links() }}
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            @endif
        </main>
        <button id="scrollup">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </button>

        {{-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.__PD_BOUND__) return;
                window.__PD_BOUND__ = true;

                const form = document.querySelector('.product-form');
                const qty = document.getElementById('qty-input');
                const radios = document.querySelectorAll('input[name="size_choice"]');
                const hidden = document.getElementById('size_id_input');
                const priceEl = document.getElementById('size-price');
                const stockEl = document.getElementById('size-stock');
                const submit = form?.querySelector('[type="submit"]');

                const clamp = (v, min, max) => Math.max(min, Math.min(max, isNaN(v = parseInt(v, 10)) ? min : v));
                const bounds = () => ({
                    min: parseInt(qty.min || '1', 10),
                    max: parseInt(qty.max || '9999', 10)
                });
                const updateBtns = () => {
                    const {
                        min,
                        max
                    } = bounds(), v = parseInt(qty.value || String(min), 10);
                    const inc = form?.querySelector('.inc-qty'),
                        dec = form?.querySelector('.dec-qty');
                    if (dec) dec.disabled = v <= min;
                    if (inc) inc.disabled = v >= max;
                };

                function applySize(r) {
                    if (!r) return;
                    const price = Number(r.dataset.price || 0);
                    const stock = parseInt(r.dataset.stock || '0', 10);

                    hidden && (hidden.value = r.value);
                    priceEl && (priceEl.textContent = 'Rs. ' + price.toLocaleString());
                    stockEl && (stockEl.textContent = stock > 0 ? `${stock} in stock` : 'Out of Stock');

                    qty.max = Math.max(1, stock);
                    qty.value = clamp(qty.value || '1', 1, parseInt(qty.max, 10));
                    submit && (submit.disabled = stock <= 0);
                    updateBtns();
                }

                // init
                if (qty) {
                    qty.step ||= '1';
                    qty.min ||= '1';
                    qty.value = clamp(qty.value || '1', parseInt(qty.min, 10), parseInt(qty.max || '9999', 10));
                }
                updateBtns();
                applySize([...radios].find(r => r.checked) || [...radios].find(r => !r.disabled));
                radios.forEach(r => r.addEventListener('change', e => applySize(e.target)));

                // qty +/- (capture to beat theme delegates)
                document.addEventListener('click', (e) => {
                    const inc = e.target.closest('.inc-qty'),
                        dec = e.target.closest('.dec-qty');
                    if (!inc && !dec) return;
                    if (form && !form.contains(e.target)) return;

                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    const {
                        min,
                        max
                    } = bounds();
                    let v = clamp(qty.value, min, max);
                    if (inc && v < max) v++;
                    if (dec && v > min) v--;
                    qty.value = v;
                    updateBtns();
                }, true);

                qty?.addEventListener('input', () => {
                    const {
                        min,
                        max
                    } = bounds();
                    qty.value = clamp(qty.value, min, max);
                    updateBtns();
                });
                qty?.addEventListener('wheel', (e) => {
                    e.preventDefault();
                    qty.blur();
                }, {
                    passive: false
                });
            });
        </script> --}}

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.__QTY_BOUND__) return;
                window.__QTY_BOUND__ = true;

                const qtyInput = document.getElementById('qty-input');
                const incBtn = document.querySelector('.inc-qty');
                const decBtn = document.querySelector('.dec-qty');

                if (!qtyInput || !incBtn || !decBtn) return;

                // Remove theme's existing click handlers (clone trick)
                function resetBtn(btn) {
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                    return newBtn;
                }

                const inc = resetBtn(incBtn);
                const dec = resetBtn(decBtn);

                // safe clamp
                function clamp(val, min, max) {
                    val = parseInt(val, 10);
                    if (isNaN(val)) val = min;
                    return Math.max(min, Math.min(max, val));
                }

                inc.addEventListener('click', (e) => {
                    e.preventDefault();
                    let val = clamp(qtyInput.value, 1, 9999);
                    qtyInput.value = val + 1;
                });

                dec.addEventListener('click', (e) => {
                    e.preventDefault();
                    let val = clamp(qtyInput.value, 1, 9999);
                    if (val > 1) qtyInput.value = val - 1;
                });

                qtyInput.addEventListener('input', () => {
                    qtyInput.value = clamp(qtyInput.value, 1, 9999);
                });

                // disable scroll wheel
                qtyInput.addEventListener('wheel', e => e.preventDefault(), {
                    passive: false
                });
            });
        </script>






    </div>
@endsection
