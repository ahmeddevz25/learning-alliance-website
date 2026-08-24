<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:orders')->only('index', 'show');
        $this->middleware('permission:order delete')->only('destroy');
    }

    public function index()
    {
        $orders = Order::with(['items.sizeItem', 'items.product'])->latest()->get();

        return view('admin.orders.show-order', compact('orders'));
    }

    public function update(Request $request, $id)
    {
        // Update order status or other details
        $order = Order::findOrFail($id);
        $order->update($request->all());
        return redirect()->route('orders.show', $id)->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        // Delete an order
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders')->with('success', 'Order deleted successfully.');
    }

    // Admin/OrderController.php
    public function updatePayment(Request $r, \App\Models\Order $order)
    {
        $data = $r->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded,failed'
        ]);

        DB::transaction(function () use ($order, $data) {
            $order->payment_status = $data['payment_status'];

            // ✅ Auto-complete on paid (skip if cancelled — optional, remove condition if not needed)
            if ($data['payment_status'] === 'paid' && $order->status !== 'cancelled') {
                $order->status = 'completed';
            }

            $order->save();
        });

        return response()->json([
            'status'         => 'success',
            'payment_status' => $order->payment_status,
            'status_value'   => $order->status,           // front-end ke liye
            'lock_status'    => $order->status === 'completed', // disable flag
        ]);
    }

    public function updateStatus(Request $r, \App\Models\Order $order)
    {
        // Prevent changing status if already completed
        if ($order->status === 'completed') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order is already completed.',
            ], 422);
        }

        $data = $r->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status' => $data['status']]);

        return response()->json([
            'status'       => 'success',
            'status_value' => $order->status,
            'lock_status'  => $order->status === 'completed',
        ]);
    }
}
