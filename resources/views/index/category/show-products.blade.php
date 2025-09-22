@extends('index.layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Choices wrapper ko small banado */
        .choices__inner {
            min-height: auto !important;
            padding: 2px 6px !important;
            /* chhota padding */
            font-size: 13px !important;
            /* text small */
            line-height: 1.2 !important;
        }

        /* Single item text */
        .choices__list--single .choices__item {
            font-size: 13px !important;
            padding: 0 !important;
        }

        /* Dropdown list items */
        .choices__list--dropdown .choices__item {
            font-size: 13px !important;
            padding: 4px 8px !important;
        }

        /* 🔥 Hover aur selected dono green */
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #065d3c !important;
            color: #fff !important;
        }

        /* Default text black */
        .choices__list--single .choices__item {
            color: #000 !important;
        }


        .page-link {
            color: #065d3c;
            border: 1px solid #dee2e6;
        }

        .page-link:hover {
            background-color: #065d3c;
            color: #fff !important;
            border-color: #065d3c;
        }

        .page-item.active .page-link {
            background-color: #065d3c;
            border-color: #065d3c;
            color: #fff !important;
        }
    </style>
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <div class="container">
            <ul class="list-unstyled d-flex align-items-center m-0">
                <li><a href="{{ route('home') }}">Home</a></li>
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
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ul>
        </div>
    </div>

    <div class="body-wrapper">
        @php use Illuminate\Support\Str; @endphp

        <main id="MainContent" class="content-for-layout">
            <div class="collection mt-100">
                <div class="container">
                    <div class="row">
                        <section class="col-12">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                                <div>
                                    <h2 class="heading_24 mb-0">{{ $category->name }} — Products</h2>
                                    <small class="text-muted">
                                        ({{ method_exists($products, 'total') ? $products->total() : $products->count() }}
                                        items)
                                    </small>
                                </div>

                                {{-- Filter + Reset --}}
                                <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                                    <form method="GET" class="d-flex align-items-center gap-2" id="filterForm">
                                        <label for="categorySelect" class="text_14 mb-0">Filter:</label>

                                        <select name="category" id="categorySelect" class="form-select form-select-sm"
                                            style="min-width:220px" onchange="this.form.submit()">
                                            <option value="" @selected(!$selectedId)>All Categories</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    data-slug="{{ \Illuminate\Support\Str::slug($cat->name) }}"
                                                    @selected($selectedId == $cat->id)>
                                                    {{ $cat->name }}
                                                    @if ($cat->parent)
                                                        ({{ $cat->parent->name }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>


                                        {{-- Reset button --}}
                                        <a href="{{ $category ? route('category.show', [$category->id, Str::slug($category->name)]) : route('home') }}"
                                            class="btn btn-primary btn-sm">Reset</a>
                                    </form>


                                </div>




                                {{-- Bulk actions --}}
                                <div class="d-flex gap-2 mt-3 mt-md-0">
                                    <button form="bulkForm" class="btn btn-primary btn-sm" id="btnAddSelected">Add selected
                                        to cart</button>
                                    <button class="btn btn-primary btn-sm" id="btnAddAll">Add all to cart</button>
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
                                                <th style="width:160px;">Qty</th> {{-- Show Qty on all screens --}}
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
                                                            alt="{{ $product->name }}" class="img-fluid"
                                                            style="max-width: 96px; object-fit: cover;">
                                                    </td>
                                                    <td>
                                                        <div class="product-title">
                                                            <a
                                                                href="{{ route('product.details', Str::slug($product->name)) }}">{{ $product->name }}</a>
                                                        </div>
                                                        <small class="product-vendor">
                                                            {{ $product->categories->pluck('name')->join(', ') }}
                                                        </small>

                                                    </td>
                                                    <td class="cart-item-price">
                                                        <div class="product-price">Rs. <span
                                                                data-line-total>{{ number_format($priceForRow, 2) }}</span>
                                                        </div>
                                                        {{-- <small data-stock-note
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


                                                    <td class="cart-item-quantity">
                                                        <div
                                                            class="quantity d-flex align-items-center justify-content-between">
                                                            <button type="button" class="qty-btn dec-qty">−</button>
                                                            <input type="number" class="qty-input" value="1"
                                                                min="1" step="1" style="width:64px;">
                                                            <button type="button" class="qty-btn inc-qty">+</button>
                                                        </div>
                                                    </td>

                                                    <td class="text-end">
                                                        <button type="button"
                                                            class="add-to-cart-btn btn btn-primary btn-sm"
                                                            style="width: 116px;font-size: 14px;">
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
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bulkForm = document.getElementById('bulkForm');
            const table = document.querySelector('.table.align-middle');
            const checkAll = document.getElementById('checkAll');
            const btnSel = document.getElementById('btnAddSelected');
            const btnAll = document.getElementById('btnAddAll');

            if (!bulkForm || !table) return;

            const clamp = (v, mn, mx) => Math.max(mn, Math.min(mx, v));
            const clearHidden = () =>
                bulkForm.querySelectorAll('input[type="hidden"][data-dynamic="1"]').forEach(n => n.remove());

            function injectHiddenForRow(row) {
                const pid = row.dataset.product;
                const sel = row.querySelector('.row-size');
                const opt = sel?.selectedOptions?.[0];
                const stock = parseInt(opt?.dataset.stock || '0', 10);
                if (stock <= 0) return; // skip OOS

                const qtyI = row.querySelector('.qty-input');
                const qty = clamp(parseInt(qtyI?.value || '1', 10), 1, stock);

                const wrap = document.createElement('div');
                wrap.innerHTML = `
                    <input type="hidden" data-dynamic="1" name="items[${pid}][size_id]" value="${sel.value}">
                    <input type="hidden" data-dynamic="1" name="items[${pid}][qty]"     value="${qty}">
                    `;
                bulkForm.appendChild(wrap);
            }

            // Master check
            checkAll?.addEventListener('change', e => {
                table.querySelectorAll('.row-check').forEach(c => c.checked = e.target.checked);
            });

            // Size change => price + stock note + qty/max + button state
            table.addEventListener('change', e => {
                const sel = e.target.closest('.row-size');
                if (!sel) return;

                const row = sel.closest('tr[data-row]');
                const price = Number(sel.selectedOptions[0].dataset.price || 0);
                const stock = parseInt(sel.selectedOptions[0].dataset.stock || '0', 10);

                const priceSpan = row.querySelector('[data-line-total]');
                if (priceSpan) priceSpan.textContent = price.toFixed(2);

                const note = row.querySelector('[data-stock-note]');
                if (note) {
                    if (stock > 0) {
                        note.textContent = `${stock} in stock`;
                        note.classList.remove('text-danger');
                        note.classList.add('text-success');
                    } else {
                        note.textContent = 'Out of stock';
                        note.classList.remove('text-success');
                        note.classList.add('text-danger');
                    }
                }

                const qty = row.querySelector('.qty-input');
                const inc = row.querySelector('.inc-qty');
                const dec = row.querySelector('.dec-qty');
                const addBt = row.querySelector('.add-to-cart-btn');

                if (stock > 0) {
                    qty.disabled = false;
                    qty.min = 1;
                    qty.max = stock;
                    qty.value = clamp(parseInt(qty.value || '1', 10), 1, stock);
                    inc?.removeAttribute('disabled');
                    dec?.removeAttribute('disabled');
                    addBt?.removeAttribute('disabled');
                } else {
                    qty.disabled = true;
                    qty.min = 0;
                    qty.removeAttribute('max');
                    qty.value = 0;
                    inc?.setAttribute('disabled', '');
                    dec?.setAttribute('disabled', '');
                    addBt?.setAttribute('disabled', '');
                }
            });

            // SINGLE add-to-cart
            table.addEventListener('click', e => {
                const btn = e.target.closest('.add-to-cart-btn');
                if (!btn) return;
                if (btn.disabled) return;
                e.preventDefault();
                clearHidden();
                injectHiddenForRow(btn.closest('tr[data-row]'));
                if (!bulkForm.querySelector('input[data-dynamic="1"]')) return;
                bulkForm.submit();
            });

            // ADD SELECTED
            btnSel?.addEventListener('click', e => {
                e.preventDefault();
                clearHidden();
                const rows = [...table.querySelectorAll('tr[data-row]')].filter(r => r.querySelector(
                    '.row-check')?.checked);
                if (!rows.length) return alert('Please select at least one product.');
                rows.forEach(injectHiddenForRow);
                if (!bulkForm.querySelector('input[data-dynamic="1"]')) return alert(
                    'No in-stock items were selected.');
                bulkForm.submit();
            });

            // ADD ALL
            btnAll?.addEventListener('click', e => {
                e.preventDefault();
                clearHidden();
                table.querySelectorAll('tr[data-row]').forEach(injectHiddenForRow);
                if (!bulkForm.querySelector('input[data-dynamic="1"]')) return alert(
                    'No in-stock items to add.');
                bulkForm.submit();
            });

            // Qty input: step=1 + wheel disable
            document.querySelectorAll('.qty-input').forEach(i => {
                i.setAttribute('step', '1');
                i.addEventListener('wheel', e => {
                    e.preventDefault();
                }, {
                    passive: false
                });
            });

            // Qty +/- (capture-phase to beat theme double-step)
            document.addEventListener('click', function(e) {
                const incBtn = e.target.closest('.inc-qty');
                const decBtn = e.target.closest('.dec-qty');
                if (!incBtn && !decBtn) return;

                const row = (incBtn || decBtn).closest('tr[data-row]');
                const qty = row?.querySelector('.qty-input');
                if (!qty || qty.disabled) return;

                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                const min = parseInt(qty.min || '1', 10);
                const max = parseInt(qty.max || '9999', 10);
                let v = parseInt(qty.value || String(min), 10);
                if (isNaN(v)) v = min;

                if (incBtn && v < max) v += 1;
                if (decBtn && v > min) v -= 1;

                qty.value = v;
            }, true);

        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bulkForm = document.getElementById('bulkForm');
            const table = document.querySelector('.table.align-middle');
            const checkAll = document.getElementById('checkAll');
            const btnSel = document.getElementById('btnAddSelected');
            const btnAll = document.getElementById('btnAddAll');

            if (!bulkForm || !table) return;

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

            // ✅ Master check/uncheck all
            checkAll?.addEventListener('change', e => {
                table.querySelectorAll('.row-check').forEach(c => c.checked = e.target.checked);
            });

            // ✅ Size change => sirf price update
            table.addEventListener('change', e => {
                const sel = e.target.closest('.row-size');
                if (!sel) return;

                const row = sel.closest('tr[data-row]');
                const price = Number(sel.selectedOptions[0].dataset.price || 0);
                const priceSpan = row.querySelector('[data-line-total]');
                if (priceSpan) {
                    priceSpan.textContent = price.toLocaleString('en-PK', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            });

            // ✅ Add to cart (single row)
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

            // ✅ Add selected
            btnSel?.addEventListener('click', e => {
                e.preventDefault();
                clearHidden();
                const rows = [...table.querySelectorAll('tr[data-row]')].filter(r =>
                    r.querySelector('.row-check')?.checked
                );
                if (!rows.length) return alert('Please select at least one product.');
                rows.forEach(injectHiddenForRow);
                bulkForm.submit();
            });

            // ✅ Add all
            btnAll?.addEventListener('click', e => {
                e.preventDefault();
                clearHidden();
                table.querySelectorAll('tr[data-row]').forEach(injectHiddenForRow);
                bulkForm.submit();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ✅ Input setup
            document.querySelectorAll('.qty-input').forEach(i => {
                i.setAttribute('step', '1');
                i.addEventListener('wheel', e => e.preventDefault(), {
                    passive: false
                });
            });

            // ✅ Increment/decrement buttons
            document.addEventListener('click', function(e) {
                const incBtn = e.target.closest('.inc-qty');
                const decBtn = e.target.closest('.dec-qty');
                if (!incBtn && !decBtn) return;

                const row = (incBtn || decBtn).closest('tr[data-row]');
                const qty = row?.querySelector('.qty-input');
                if (!qty) return;

                e.preventDefault();
                e.stopPropagation(); // 🚀 stop bubbling
                e.stopImmediatePropagation(); // 🚀 stop theme’s handler too

                let v = parseInt(qty.value || '1', 10);
                if (isNaN(v) || v < 1) v = 1;

                if (incBtn) v = v + 1;
                if (decBtn && v > 1) v = v - 1;

                qty.value = v;
            }, true);

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const catSelect = document.getElementById('categorySelect');
            catSelect?.addEventListener('change', () => {
                catSelect.form.submit(); // bas form submit kar do
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.row-size').forEach(function(el) {
                new Choices(el, {
                    searchEnabled: false, // search hide
                    itemSelectText: '', // "Press to select" text hatado
                });
            });
        });
    </script>
@endsection
