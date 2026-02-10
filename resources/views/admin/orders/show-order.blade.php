@extends('admin.layouts')
@section('content')
        @include('sweetalert::alert')

        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                            <h5 class="card-title mb-0 text-md-start text-center">All Orders</h5>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped border-top" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>Sr. No</th>
                                        <th>Order #</th>
                                        <th>Campus</th>
                                        <th>Parent / Student</th>
                                        <th>Class / Section</th>
                                        <th>Contact</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Placed At</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $psMap = [
                                            'unpaid' => 'secondary',
                                            'paid' => 'success',
                                            'refunded' => 'warning',
                                            'failed' => 'danger',
                                        ];
                                        $osMap = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp

                                    @forelse ($orders as $key => $order)
                                        <tr>
                                            <td data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Row #{{ $key + 1 }}">
                                                {{ $key + 1 }}
                                            </td>

                                            <td class="fw-semibold" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Order #: {{ $order->order_number }}">
                                                #{{ $order->order_number }}
                                            </td>

                                            <td data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $order->campus }}">
                                                {{ $order->campus }}
                                            </td>

                                            <td>
                                                <div data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Parent: {{ $order->parent_name }}">
                                                    <strong>{{ $order->parent_name }}</strong>
                                                </div>
                                                <div class="text-muted small" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Student: {{ $order->student_name }}">
                                                    Student: {{ $order->student_name }}
                                                </div>
                                            </td>

                                            <td data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Class / Section: {{ $order->class ?? '—' }} / {{ $order->section ?? '—' }}">
                                                {{ $order->class ?? '—' }} / {{ $order->section ?? '—' }}
                                            </td>

                                            <td>
                                                <div data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Phone: {{ $order->phone ?? '—' }}">
                                                    {{ $order->phone ?? '—' }}
                                                </div>
                                                <div class="text-muted small" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Email: {{ $order->email ?? '—' }}">
                                                    {{ $order->email ?? '—' }}
                                                </div>
                                            </td>

                                            @php $itemCount = (int) $order->items->sum('quantity'); @endphp
                                            <td>
                                                <button type="button"
                                                    class="btn btn-primary btn-sm align-items-center show-items"
                                                    data-order="#{{ $order->order_number }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Click to view items">
                                                    {{ $itemCount }} item{{ $itemCount === 1 ? '' : 's' }}
                                                </button>

                                                {{-- Hidden: items HTML (modal ke liye) --}}
                                                <div class="items-html d-none">
                                                    <div class="p-2 border rounded bg-light">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm mb-0">
                                                                <thead class="table-secondary">
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Product</th>
                                                                        <th>Size</th>
                                                                        <th class="text-end">Unit Price</th>
                                                                        <th class="text-end">Qty</th>
                                                                        <th class="text-end">Line Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($order->items as $k => $it)
                                                                        <tr>
                                                                            <td>{{ $k + 1 }}</td>
                                                                            <td data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                title="{{ $it->product_name ?? (optional($it->product)->name ?? 'Product') }}">
                                                                                {{ $it->product_name ?? (optional($it->product)->name ?? 'Product') }}
                                                                            </td>
                                                                            <td data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                title="{{ optional($it->sizeItem)->size ?? '—' }}">
                                                                                {{ optional($it->sizeItem)->size ?? '—' }}
                                                                            </td>
                                                                            <td class="text-end">Rs.
                                                                                {{ number_format((float) $it->unit_price, 2) }}
                                                                            </td>
                                                                            <td class="text-end">{{ (int) $it->quantity }}
                                                                            </td>
                                                                            <td class="text-end">Rs.
                                                                                {{ number_format((float) $it->line_total, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="5" class="text-end">Subtotal</th>
                                                                        <th class="text-end">Rs.
                                                                            {{ number_format((float) $order->subtotal, 2) }}
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="5" class="text-end">Total</th>
                                                                        <th class="text-end">Rs.
                                                                            {{ number_format((float) $order->total, 2) }}
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Total: Rs. {{ number_format((float) $order->total, 2) }}">
                                                Rs. {{ number_format((float) $order->total, 2) }}
                                            </td>

                                            @php
                                                $psMap = [
                                                    'unpaid' => 'secondary',
                                                    'paid' => 'success',
                                                    'refunded' => 'warning',
                                                    'failed' => 'danger',
                                                ];
                                                $osMap = [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                ];
                                                $paymentEnums = ['unpaid', 'paid', 'refunded', 'failed'];
                                                $orderEnums = ['pending', 'processing', 'completed', 'cancelled'];
                                            @endphp

                                            {{-- Payment status dropdown --}}
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="badge bg-{{ $psMap[$order->payment_status] ?? 'secondary' }} dropdown-toggle border-0"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        title="Payment status: {{ ucfirst($order->payment_status) }}"
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
                                                        class="badge bg-{{ $osMap[$order->status] ?? 'secondary' }} dropdown-toggle border-0"
                                                        data-role="order-status-toggle" data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        @if ($order->status === 'completed') disabled @endif
                                                        title="Order status: {{ ucfirst($order->status) }}">
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
                                            <td class="text-nowrap" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ optional($order->created_at)->toDayDateTimeString() }}">
                                                {{ optional($order->created_at)->format('d M Y, h:i A') }}
                                            </td>
                                            <td class="text-center">
                                                @can('order delete')
                                                    <a href="{{ route('orders.delete', $order->id) }}"
                                                        class="text-danger fs-5" title="Delete this order"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        onclick="return confirm('Are you sure you want to delete this Order?')">
                                                        <i class='bx bx-trash'></i>
                                                    </a>
                                                @endcan
                        </div>
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
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
        <script>
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.show-items');
                if (!btn) return;

                const tr = btn.closest('tr');
                const html = tr.querySelector('.items-html')?.innerHTML || '<div class="p-3">No items.</div>';

                const modalEl = document.getElementById('orderItemsModal');
                modalEl.querySelector('.modal-title').textContent = `Items for ${btn.dataset.order || ''}`;
                modalEl.querySelector('.modal-body').innerHTML = html;

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
                        unpaid: 'bg-secondary',
                        paid: 'bg-success',
                        refunded: 'bg-warning',
                        failed: 'bg-danger'
                    };
                    const clsMapOrder = {
                        pending: 'bg-warning',
                        processing: 'bg-info',
                        completed: 'bg-success',
                        cancelled: 'bg-danger'
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
