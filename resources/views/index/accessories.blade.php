@extends('index.layout')
@section('content')
    <div class="body-wrapper">
        <!-- breadcrumb start -->
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
                                    fill="#000" />
                            </g>
                        </svg>
                    </li>
                    <li>Products</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->
        @php use Illuminate\Support\Str; @endphp

        <main id="MainContent" class="content-for-layout">
            <div class="collection mt-100">
                <div class="container">
                    <div class="row">
                        <section class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h2 class="heading_24 mb-0">All products</h2>
                                    <small class="text-muted">
                                        ({{ method_exists($products, 'total') ? $products->total() : $products->count() }}
                                        items)
                                    </small>
                                </div>

                                {{-- Filter + Reset --}}
                                <form method="GET" class="d-flex align-items-center gap-2" id="filterForm">
                                    <label for="categorySelect" class="text_14 mb-0">Filter:</label>
                                    <select name="category" id="categorySelect" class="form-select form-select-sm"
                                        style="min-width:220px">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                data-slug="{{ \Illuminate\Support\Str::slug($cat->name) }}"
                                                @selected($categoryId == $cat->id)>
                                                {{ $cat->name }}
                                                @if ($cat->parent)
                                                    ({{ $cat->parent->name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>



                                    {{-- Reset → default (all) --}}
                                    <a href="{{ $category ? route('category.show', [$category->id, Str::slug($category->name)]) : route('home') }}"
                                        class="btn btn-primary btn-sm">Reset</a>
                                </form>


                                {{-- Bulk actions --}}
                                <div class="d-flex gap-2">
                                    <button form="bulkForm" class="btn btn-primary btn-sm" id="btnAddSelected">
                                        Add selected to cart
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="btnAddAll">
                                        Add all to cart
                                    </button>
                                </div>
                            </div>

                            {{-- BULK FORM + PRODUCT LIST --}}
                            <form id="bulkForm" method="POST" action="{{ route('cart.bulkAdd') }}">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width:36px;"><input type="checkbox" id="checkAll"></th>
                                                <th style="width:110px;">Thumbnail</th>
                                                <th>Name</th>
                                                <th style="width:140px;">Price</th>
                                                <th style="width:220px;">Size</th>
                                                <th style="width:160px;">Qty</th>
                                                <th style="width:140px;" class="text-end">Buy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($products as $product)
                                                @php
                                                    $main =
                                                        $product->images->where('is_primary', true)->first() ??
                                                        $product->images->first();
                                                    $firstVariant =
                                                        $product->sizes->firstWhere('stock', '>', 0) ??
                                                        $product->sizes->first();
                                                    $priceForRow = $firstVariant?->price ?? ($product->price ?? 0);
                                                    $inStock = (int) ($firstVariant?->stock ?? 0);
                                                @endphp
                                                <tr data-row data-product="{{ $product->id }}">
                                                    <td><input type="checkbox" class="row-check"></td>
                                                    <td>
                                                        <img src="{{ asset('storage/' . ($main?->image_path ?? 'default.jpg')) }}"
                                                            alt="{{ $product->name }}"
                                                            style="width:96px;height:96px;object-fit:cover;border-radius:6px;">
                                                    </td>
                                                    <td>
                                                        <div class="product-title">
                                                            <a
                                                                href="{{ route('product.details', Str::slug($product->name)) }}">{{ $product->name }}</a>
                                                        </div>
                                                        <small
                                                            class="product-vendor">{{ $product->categories->pluck('name')->join(', ') }}
                                                        </small>
                                                    </td>
                                                    <td class="cart-item-price">
                                                        <div class="product-price">
                                                            Rs. <span
                                                                data-line-total>{{ number_format($priceForRow, 2) }}</span>
                                                        </div>

                                                        {{-- @php $inStock = (int) ($firstVariant?->stock ?? 0); @endphp
                                                        <small data-stock-note
                                                            class="{{ $inStock > 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $inStock > 0 ? $inStock . ' in stock' : 'Out of stock' }}
                                                        </small> --}}
                                                    </td>

                                                    <td>
                                                        <fieldset>
                                                            <select class="form-select form-select-sm row-size">
                                                                @foreach ($product->sizes as $s)
                                                                    <option value="{{ $s->sizeItem->id }}"
                                                                        data-price="{{ (float) $s->price }}"
                                                                        @selected($firstVariant && $s->id === $firstVariant->id)>
                                                                        {{ $s->sizeItem->size }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </fieldset>
                                                    </td>

                                                    <td class="cart-item-quantity d-none d-md-table-cell">
                                                        <div
                                                            class="quantity d-flex align-items-center justify-content-between">
                                                            <button type="button" class="qty-btn dec-qty">−</button>
                                                            <input type="number" class="qty-input" value="1"
                                                                min="1" style="width:64px;">

                                                            <button type="button" class="qty-btn inc-qty">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button"
                                                            class="add-to-cart-btn btn btn-primary btn-sm"
                                                            style="padding-left: 7px;">
                                                            Add to Cart
                                                        </button>

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5">No products found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </form>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $products->links() }}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const bulkForm = document.getElementById('bulkForm');
                const table = document.querySelector('.table.align-middle');
                const checkAll = document.getElementById('checkAll');
                const btnSel = document.getElementById('btnAddSelected');
                const btnAll = document.getElementById('btnAddAll');

                const clearHidden = () =>
                    bulkForm.querySelectorAll('input[type="hidden"][data-dynamic="1"]').forEach(n => n.remove());

                function injectHiddenForRow(row) {
                    const pid = row.dataset.product;
                    const sel = row.querySelector('.row-size');
                    const qtyI = row.querySelector('.qty-input');
                    const qty = parseInt(qtyI?.value || '1', 10);
                    const safeQty = isNaN(qty) || qty < 1 ? 1 : qty;

                    const wrap = document.createElement('div');
                    wrap.innerHTML = `
                <input type="hidden" data-dynamic="1" name="items[${pid}][product_id]" value="${pid}">
                <input type="hidden" data-dynamic="1" name="items[${pid}][size_id]" value="${sel.value}">
                <input type="hidden" data-dynamic="1" name="items[${pid}][qty]" value="${safeQty}">
            `;
                    bulkForm.appendChild(wrap);
                }

                // ✅ Add to Cart (single row)
                table.addEventListener('click', e => {
                    const btn = e.target.closest('.add-to-cart-btn');
                    if (!btn) return;
                    e.preventDefault();

                    clearHidden();
                    injectHiddenForRow(btn.closest('tr[data-row]'));

                    if (bulkForm.querySelector('input[data-dynamic="1"]')) {
                        bulkForm.submit();
                    }
                });

                // ✅ Add Selected
                btnSel?.addEventListener('click', e => {
                    e.preventDefault();
                    clearHidden();
                    const rows = [...table.querySelectorAll('tr[data-row]')].filter(r => r.querySelector(
                        '.row-check')?.checked);
                    if (!rows.length) return alert('Please select at least one product.');
                    rows.forEach(injectHiddenForRow);
                    bulkForm.submit();
                });

                // ✅ Add All
                btnAll?.addEventListener('click', e => {
                    e.preventDefault();
                    clearHidden();
                    table.querySelectorAll('tr[data-row]').forEach(injectHiddenForRow);
                    bulkForm.submit();
                });

                // ✅ Price auto-update on size change
                table.addEventListener('change', e => {
                    if (e.target.classList.contains('row-size')) {
                        const sel = e.target;
                        const price = sel.options[sel.selectedIndex].dataset.price;
                        const row = sel.closest('tr[data-row]');
                        const priceSpan = row.querySelector('[data-line-total]');

                        if (priceSpan && price) {
                            priceSpan.textContent = parseFloat(price).toFixed(2);
                        }
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const catSelect = document.getElementById('categorySelect');

                catSelect?.addEventListener('change', () => {
                    const selectedOption = catSelect.options[catSelect.selectedIndex];
                    const categoryId = selectedOption.value;
                    const slug = selectedOption.dataset.slug;

                    if (categoryId && slug) {
                        // redirect with ID + slug
                        window.location.href = `/uniform/${categoryId}/${slug}?category=${categoryId}`;
                    }
                });
            });
        </script>

    </div>
@endsection
