@extends('admin.layouts')
@section('content')
        @include('sweetalert::alert')

        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="card mb-4 shadow border-0 rounded-3">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom pb-3 pt-4">
                            <h5 class="card-title mb-0 fw-bold text-primary"><i class="bx bx-cart me-2"></i> All Orders</h5>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <th>Sr. No</th>
                                        <th>Order #</th>
                                        <th>Parent / Student</th>
                                        <th>Contact</th>
                                        <th>Details</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $psMap = [
                                            'unpaid' => 'label-secondary',
                                            'paid' => 'label-success',
                                            'refunded' => 'label-warning',
                                            'failed' => 'label-danger',
                                        ];
                                        $osMap = [
                                            'pending' => 'label-warning',
                                            'processing' => 'label-info',
                                            'completed' => 'label-success',
                                            'cancelled' => 'label-danger',
                                        ];
                                    @endphp

                                    @forelse ($orders as $key => $order)
                                        <tr>
                                            <td class="text-muted">
                                                {{ $key + 1 }}
                                            </td>

                                            <td class="fw-semibold">
                                                #{{ $order->order_number }}
                                            </td>



                                            <td>
                                                <div>
                                                    <strong>{{ $order->parent_name }}</strong>
                                                </div>
                                                <div class="text-muted small">
                                                    Student: {{ $order->student_name }}
                                                </div>
                                            </td>



                                            <td>
                                                <div>
                                                    {{ $order->phone ?? '—' }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $order->email ?? '—' }}
                                                </div>
                                            </td>

                                            @php $itemCount = (int) $order->items->sum('quantity'); @endphp
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary show-items fw-bold text-nowrap"
                                                    data-order="#{{ $order->order_number }}">
                                                    <i class="bx bx-show me-1"></i> View Details
                                                </button>

                                                {{-- Hidden: items HTML (modal ke liye) --}}
                                                <div class="items-html d-none">
                                                    <div class="d-flex flex-wrap bg-label-secondary p-3 rounded mb-4 gap-3">
                                                        <div class="flex-grow-1">
                                                            <span class="text-muted d-block" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Campus</span>
                                                            <strong class="text-dark">{{ $order->campus }}</strong>
                                                        </div>
                                                        <div class="flex-grow-1 border-start ps-3">
                                                            <span class="text-muted d-block" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Class / Section</span>
                                                            <strong class="text-dark">{{ $order->class ?? '—' }} <span class="text-muted mx-1">/</span> {{ $order->section ?? '—' }}</strong>
                                                        </div>
                                                        <div class="flex-grow-1 border-start ps-3">
                                                            <span class="text-muted d-block" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Placed At</span>
                                                            <strong class="text-dark">{{ optional($order->created_at)->format('d M Y, h:i A') }}</strong>
                                                        </div>
                                                    </div>
                                                    <h6 class="text-primary mb-3 fw-bold"><i class="bx bx-package me-1"></i> Order Items ({{ $itemCount }})</h6>
                                                    <div class="border rounded shadow-sm overflow-hidden">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover text-dark mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Product</th>
                                                                        <th>Size</th>
                                                                        <th class="text-end">Unit Price</th>
                                                                        <th class="text-end">Qty</th>
                                                                        <th class="text-end">Line Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-dark">
                                                                    @foreach ($order->items as $k => $it)
                                                                        <tr>
                                                                            <td>{{ $k + 1 }}</td>
                                                                            <td>
                                                                                {{ $it->product_name ?? (optional($it->product)->name ?? 'Product') }}
                                                                            </td>
                                                                            <td>
                                                                                {{ optional($it->sizeItem)->size ?? '—' }}
                                                                            </td>
                                                                            <td class="text-end">Rs.
                                                                                {{ number_format((float) $it->unit_price, 0) }}
                                                                            </td>
                                                                            <td class="text-end">{{ (int) $it->quantity }}
                                                                            </td>
                                                                            <td class="text-end">Rs.
                                                                                {{ number_format((float) $it->line_total, 0) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot class="text-dark">
                                                                    <tr>
                                                                        <th colspan="5" class="text-end">Subtotal</th>
                                                                        <th class="text-end">Rs.
                                                                            {{ number_format((float) $order->subtotal, 0) }}
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="5" class="text-end">Total</th>
                                                                        <th class="text-end text-primary fs-6">Rs.
                                                                            {{ number_format((float) $order->total, 0) }}
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                Rs. {{ number_format((float) $order->total, 0) }}
                                            </td>

                                            @php
                                                $psMap = [
                                                    'unpaid' => 'label-secondary',
                                                    'paid' => 'label-success',
                                                    'refunded' => 'label-warning',
                                                    'failed' => 'label-danger',
                                                ];
                                                $osMap = [
                                                    'pending' => 'label-warning',
                                                    'processing' => 'label-info',
                                                    'completed' => 'label-success',
                                                    'cancelled' => 'label-danger',
                                                ];
                                                $paymentEnums = ['unpaid', 'paid', 'refunded', 'failed'];
                                                $orderEnums = ['pending', 'processing', 'completed', 'cancelled'];
                                            @endphp

                                            {{-- Payment status dropdown --}}
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-sm dropdown-toggle border-0 badge bg-{{ $psMap[$order->payment_status] ?? 'label-secondary' }} fw-bold"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        @if ($order->payment_status === 'paid') disabled @endif>
                                                        {{ ucfirst($order->payment_status) }}
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @foreach ($paymentEnums as $val)
                                                            @continue($val === $order->payment_status)
                                                            <li>
                                                                <a href="#" class="dropdown-item js-set-status"
                                                                    data-kind="payment"
                                                                    data-url="{{ route('admin.orders.payment', $order) }}"
                                                                    data-value="{{ $val }}">
                                                                    {{ ucfirst($val) }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>

                                            {{-- Order status dropdown (disable if completed) --}}
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-sm dropdown-toggle border-0 badge bg-{{ $osMap[$order->status] ?? 'label-secondary' }} fw-bold"
                                                        data-role="order-status-toggle" data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        @if ($order->status === 'completed') disabled @endif>
                                                        {{ ucfirst($order->status) }}
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @foreach ($orderEnums as $val)
                                                            @continue($val === $order->status)
                                                            <li>
                                                                <a href="#" class="dropdown-item js-set-status"
                                                                    data-kind="order"
                                                                    data-url="{{ route('admin.orders.status', $order) }}"
                                                                    data-value="{{ $val }}">
                                                                    {{ ucfirst($val) }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                @can('order delete')
                                                    <a href="{{ route('orders.delete', $order->id) }}"
                                                        class="text-danger fs-5"
                                                        onclick="return confirm('Are you sure you want to delete this Order?')">
                                                        <i class='bx bx-trash'></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">No orders found</td>
                        </tr>
                        @endforelse
                        </tbody>

                        </table>
                    </div>
                    <div class="modal fade" id="orderItemsModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Order Items</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0"></div>
                            </div>
                        </div>
                    </div>

                    </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.show-items');
                if (!btn) return;

                const tr = btn.closest('tr');
                const html = tr.querySelector('.items-html')?.innerHTML || '<div class="p-3">No items.</div>';

                const modalEl = document.getElementById('orderItemsModal');
                modalEl.querySelector('.modal-title').innerHTML = `<i class="bx bx-receipt me-1"></i> Order Details <span class="text-primary">${btn.dataset.order || ''}</span>`;
                modalEl.querySelector('.modal-body').innerHTML = `<div class="p-4">${html}</div>`;

                const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                bsModal.show();
            });
        </script>
        <script>
            document.addEventListener('click', async (e) => {
                const a = e.target.closest('.js-set-status');
                if (!a) return;

                e.preventDefault();

                const url = a.dataset.url;
                const kind = a.dataset.kind; // 'payment' | 'order'
                const value = a.dataset.value;
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

                // current row
                const row = a.closest('tr');

                try {
                    const res = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(kind === 'payment' ? {
                            payment_status: value
                        } : {
                            status: value
                        })
                    });

                    const data = await res.json();

                    if (!res.ok || data.status !== 'success') {
                        const msg = data.message || 'Update failed';
                        if (window.toastr) toastr.error(msg);
                        else alert(msg);
                        return;
                    }

                    // ---- Update the clicked dropdown's badge (payment OR order) ----
                    const dd = a.closest('.dropdown');
                    const btn = dd.querySelector('.dropdown-toggle');

                    // maps
                    const clsMapPayment = {
                        unpaid: 'bg-label-secondary',
                        paid: 'bg-label-success',
                        refunded: 'bg-label-warning',
                        failed: 'bg-label-danger'
                    };
                    const clsMapOrder = {
                        pending: 'bg-label-warning',
                        processing: 'bg-label-info',
                        completed: 'bg-label-success',
                        cancelled: 'bg-label-danger'
                    };

                    const clsMap = (kind === 'payment') ? clsMapPayment : clsMapOrder;

                    // remove old bg-* class then add new
                    btn.className = btn.className.replace(/\bbg-\w+\b/g, '').trim();
                    btn.classList.add(clsMap[value]);
                    btn.textContent = value.charAt(0).toUpperCase() + value.slice(1);

                    // close dropdown
                    bootstrap.Dropdown.getOrCreateInstance(btn).hide();

                    // ---- If payment became 'paid', reflect auto-complete on order status ----
                    if (kind === 'payment' && data.status_value) {
                        const statusBtn = row.querySelector('[data-role="order-status-toggle"]');
                        if (statusBtn) {
                            const newStatus = data.status_value; // backend sent actual status (likely 'completed')
                            statusBtn.className = statusBtn.className.replace(/\bbg-\w+\b/g, '').trim();
                            statusBtn.classList.add(clsMapOrder[newStatus] || 'bg-secondary');
                            statusBtn.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                            // lock if completed
                            if (data.lock_status || newStatus === 'completed') {
                                statusBtn.setAttribute('disabled', 'disabled');
                            }
                        }
                    }

                    // ---- If order status was set to completed manually, lock it ----
                    if (kind === 'order' && (data.lock_status || value === 'completed')) {
                        const statusBtn = row.querySelector('[data-role="order-status-toggle"]');
                        statusBtn?.setAttribute('disabled', 'disabled');
                    }

                    // ---- If payment was set to 'paid', lock it ----
                    if (kind === 'payment' && value === 'paid') {
                        const payBtn = row.querySelector('[title^="Payment status"]');
                        payBtn?.setAttribute('disabled', 'disabled');
                    }


                    // toast
                    if (window.toastr) {
                        toastr.success('Updated Status Successfully');
                    } else if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Updated',
                            showConfirmButton: false,
                            timer: 1600
                        });
                    }
                } catch (err) {
                    console.error(err);
                    if (window.toastr) toastr.error('Network error');
                    else alert('Network error');
                }
            });
        </script>
@endsection
