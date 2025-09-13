@extends('index.layout')
@section('content')
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
                </ul>
            </div>
        </div>
        <main id="MainContent" class="content-for-layout mb-5">
            <div class="cart-page mt-100">
                <div class="container">
                    <div class="cart-page-wrapper">
                        <div class="row">
                            <div class="col-lg-7 col-md-12 col-12">
                                <table class="cart-table w-100">
                                    <thead>
                                        <tr>
                                            <th class="cart-caption heading_18">Product</th>
                                            <th class="cart-caption heading_18"></th>
                                            <th class="cart-caption text-center heading_18 d-none d-md-table-cell">Quantity
                                            </th>
                                            <th class="cart-caption text-end heading_18">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

                                            <tr class="cart-item cart-line" data-line-id="{{ $item->id }}"
                                                data-update-url="{{ route('cart.quantity', $item->id) }}">
                                                <td class="cart-item-media">
                                                    <div class="mini-img-wrapper">
                                                        <img class="mini-img" src="{{ asset('storage/' . $img) }}"
                                                            alt="{{ $p?->name }}">
                                                    </div>
                                                </td>

                                                <td class="cart-item-details">
                                                    <h2 class="product-title">
                                                        <a
                                                            href="{{ route('product.details', \Illuminate\Support\Str::slug($p?->name ?? 'product')) }}">
                                                            {{ $p?->name ?? 'Product' }}
                                                        </a>
                                                    </h2>
                                                    <p class="product-vendor">{{ $size?->size ?? '—' }}</p>
                                                    <small class="text-muted d-md-none">
                                                        Rs. <span data-unit-price>{{ number_format($price, 2) }}</span> ×
                                                        <span data-qty-text>{{ $qty }}</span>
                                                    </small>
                                                    <button type="button" class="cart-remove-btn text-danger"
                                                        data-id="{{ $item->id }}"
                                                        data-url="{{ route('cart.ajaxRemove', $item->id) }}">
                                                        🗑 Remove
                                                    </button>



                                                </td>

                                                <td class="cart-item-quantity d-none d-md-table-cell">
                                                    <div class="quantity d-flex align-items-center justify-content-between">
                                                        <button type="button" class="qty-btn dec-qty">
                                                            <img src="{{ asset('index/assets/img/icon/minus.svg') }}"
                                                                alt="minus">
                                                        </button>
                                                        <input class="qty-input" type="number" name="quantity"
                                                            value="{{ $qty }}" min="1" step="1">

                                                        <button type="button" class="qty-btn inc-qty">
                                                            <img src="{{ asset('index/assets/img/icon/plus.svg') }}"
                                                                alt="plus">
                                                        </button>
                                                    </div>
                                                </td>

                                                <td class="cart-item-price text-end">
                                                    <div class="product-price">Rs. <span
                                                            data-line-total>{{ number_format($line, 2) }}</span></div>
                                                    <small class="text-muted d-none d-md-block">
                                                        Rs. <span data-unit-price>{{ number_format($price, 2) }}</span> ×
                                                        <span data-qty-text>{{ $qty }}</span>
                                                    </small>
                                                </td>

                                            </tr>
                                        @endforeach

                                        @if ($cartItems->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center py-5">Your cart is empty.</td>
                                            </tr>
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            <div class="col-lg-5 col-md-12 col-12">
                                <div class="cart-total-area">
                                    <h4 class="cart-total-title d-none d-lg-block mb-0">Cart Totals</h4>

                                    <div class="cart-total-box mt-4">
                                        <div class="subtotal-item subtotal-box">
                                            <h4 class="subtotal-title">Subtotal:</h4>
                                            <p class="subtotal-value">
                                                Rs. <span id="cart-subtotal">{{ number_format($subtotal ?? 0, 2) }}</span>
                                            </p>
                                        </div>

                                        <hr />

                                        <div class="subtotal-item discount-box">
                                            <h4 class="subtotal-title">Total:</h4>
                                            <p class="subtotal-value">
                                                Rs. <span
                                                    id="cart-grand">{{ number_format($grandTotal ?? ($subtotal ?? 0), 2) }}</span>
                                            </p>
                                        </div>

                                        <div class="d-flex justify-content-center mt-4">
                                            <a href="{{ route('checkout') }}"
                                                class="position-relative btn-primary text-uppercase {{ ($cartItems ?? collect())->isEmpty() ? 'disabled' : '' }}"
                                                @if (($cartItems ?? collect())->isEmpty()) aria-disabled="true" tabindex="-1" @endif>
                                                Proceed to checkout
                                            </a>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        (() => {
            if (window.__CART_AJAX_MIN__) return;
            window.__CART_AJAX_MIN__ = 1;

            const $ = (s, r = document) => r.querySelector(s);
            const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
            const CSRF = $('meta[name="csrf-token"]')?.content;
            const badge = $('.header-cart .cart-badge');
            const clamp = (v, a, b) => Math.max(a, Math.min(b, parseInt(v || a, 10) || a));

            async function updateLine(row, qty) {
                const url = row?.dataset.updateUrl,
                    input = $('.qty-input', row);
                if (!row || !url || !input) return;

                const min = +(input.min || 1),
                    max = +(input.max || 9999);
                qty = clamp(qty, min, max);
                input.value = qty;
                $$('[data-qty-text]', row).forEach(el => el.textContent = qty);

                try {
                    const res = await fetch(url, {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            quantity: qty
                        })
                    });
                    if (!res.ok) throw new Error(await res.text());
                    const d = await res.json();

                    $$('[data-unit-price]', row).forEach(el => el.textContent = d.line.unit_price);
                    $$('[data-qty-text]', row).forEach(el => el.textContent = d.line.quantity);

                    const totals = $$('[data-line-total]', row);
                    if (totals.length) totals.forEach(el => el.textContent = d.line.line_total);
                    else $$('.product-price', row).forEach(el => el.innerHTML = 'Rs. ' + d.line.line_total);

                    input.value = d.line.quantity;

                    $('#cart-subtotal') && ($('#cart-subtotal').textContent = d.totals.subtotal);
                    $('#cart-grand') && ($('#cart-grand').textContent = d.totals.grand);

                    if (badge) {
                        badge.textContent = d.totals.badge_text;
                        badge.classList.add('bump');
                        setTimeout(() => badge.classList.remove('bump'), 350);
                    }
                } catch (e) {
                    console.error(e);
                    alert('Could not update quantity. Please try again.');
                }
            }

            // inc/dec — capture to beat theme delegates
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.inc-qty, .dec-qty');
                if (!btn) return;
                const row = btn.closest('.cart-line'),
                    input = $('.qty-input', row);
                if (!row || !input) return;
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                const min = +(input.min || 1),
                    max = +(input.max || 9999);
                let v = clamp(input.value, min, max);
                if (btn.classList.contains('inc-qty') && v < max) v++;
                if (btn.classList.contains('dec-qty') && v > min) v--;
                updateLine(row, v);
            }, true);

            // typing: clamp live; update on blur / Enter
            document.addEventListener('input', (e) => {
                if (!e.target.matches('.cart-line .qty-input')) return;
                const i = e.target,
                    min = +(i.min || 1),
                    max = +(i.max || 9999);
                i.value = clamp(i.value, min, max);
            });
            document.addEventListener('blur', (e) => {
                if (!e.target.matches('.cart-line .qty-input')) return;
                updateLine(e.target.closest('.cart-line'), +e.target.value || 1);
            }, true);
            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Enter' || !e.target.matches('.cart-line .qty-input')) return;
                e.preventDefault();
                updateLine(e.target.closest('.cart-line'), +e.target.value || 1);
            });
        })();
    </script>


    <script>
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.cart-remove-btn');
            if (!btn) return;

            const row = btn.closest('tr');
            const url = btn.dataset.url || `/cart/remove/${btn.dataset.id}`;

            try {
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });

                const data = await res.json(); // read body before checks so we can inspect it

                if (res.ok && data.status === 'success') {
                    // Remove row
                    row.remove();
                    const tbody = document.querySelector('.cart-table tbody');
                    if (tbody && !tbody.querySelector('.cart-line, .cart-item')) {
                        tbody.innerHTML =
                            `<tr><td colspan="4" class="text-center py-5">Your cart is empty.</td></tr>`;
                    }

                    // --- TOAST ---
                    const msg = data.message || data.success || 'Removed from cart';
                    if (window.toastr) {
                        toastr.success(msg);
                    } else if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: msg,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        // last resort
                        console.log('Toast libs not found. Message:', msg);
                    }
                    // --------------

                } else {
                    const errMsg = (data && (data.message || data.error || data.success)) ||
                        'Failed to remove item.';
                    if (window.toastr) toastr.error(errMsg);
                    else alert(errMsg);
                }
            } catch (err) {
                console.error(err);
                if (window.toastr) toastr.error('Error removing item.');
                else alert('Error removing item.');
            }
        });
    </script>
    </div>
@endsection
