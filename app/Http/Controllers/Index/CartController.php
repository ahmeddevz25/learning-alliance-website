<?php
namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSize;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id'    => 'required|exists:product_size_items,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        // ✅ Stable cart key (90 days) — isi ko carts.session_id me store / query karenge
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            $cartKey = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }

        try {
            DB::transaction(function () use ($data, $cartKey) {
                // Variant lock (consistent stock/price)
                $variant = ProductSize::where('product_id', $data['product_id'])
                    ->where('size_id', $data['size_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Existing cart line (lock too)
                $line = Cart::where('session_id', $cartKey)
                    ->where('product_id', $data['product_id'])
                    ->where('size_id', $data['size_id'])
                    ->lockForUpdate()
                    ->first();

                $price      = (float) $variant->price;
                $incoming   = (int) $data['quantity'];
                $currentQty = $line ? (int) $line->quantity : 0;
                $newQty     = $currentQty + $incoming;

                // ✅ Stock check on combined qty
                // if ($newQty > (int) $variant->stock) {
                //     throw ValidationException::withMessages([
                //         'quantity' => "Selected size is short in stock. Available: {$variant->stock}",
                //     ]);
                // }

                if ($line) {
                    $line->quantity        = $newQty;
                    $line->subtotal        = round($price * $newQty, 2);
                    $line->product_size_id = $variant->id;
                    $line->save();
                } else {
                    Cart::create([
                        'session_id'      => $cartKey, // 👈 cookie key yahan save
                        'product_id'      => $data['product_id'],
                        'size_id'         => $data['size_id'],
                        'product_size_id' => $variant->id,
                        'quantity'        => $incoming,
                        'subtotal'        => round($price * $incoming, 2),
                    ]);
                }
            });

            Alert::success('Success', 'Product added to cart successfully!');
            return back();
        } catch (ValidationException $e) {
            // stock short — same page pe error dikhao
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Add to Cart Error: ' . $e->getMessage());
            Alert::error('Error', 'Failed to add product to cart.');
            return back();
        }
    }

    public function update(Request $r)
    {
        $data = $r->validate([
            'id'       => 'required|exists:cart,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $line = Cart::where('id', $data['id'])
            ->where('session_id', $r->session()->getId())
            ->firstOrFail();

        $variant = $line->variant()->firstOrFail(); // product_sizes row
                                                    // if ($variant->stock < $data['quantity']) {
                                                    //     return back()->with('error', 'Not enough stock for this size.');
                                                    // }

        $line->quantity = (int) $data['quantity'];
        $line->subtotal = $variant->price * $line->quantity; // recompute
        $line->save();

        return back()->with('success', 'Cart updated.');
    }

    public function ajaxRemove(Request $request, $id)
    {
        $cartKey = Cookie::get('cart_key') ?: $request->session()->getId();

        $item = Cart::where('id', $id)
            ->where('session_id', $cartKey)
            ->first();

        if (! $item) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item not found',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Product removed from cart successfully!',
        ], 200);
    }

    public function bulkAdd(Request $request)
    {
        $items = $request->input('items', []); // items[product_id] = ['size_id'=>x, 'qty'=>y]
        if (empty($items)) {
            return back()->with('error', 'Please select at least one product.');
        }

        $cartKey = Cookie::get('cart_key') ?? (function () {
            $k = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $k, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
            return $k;
        })();

        DB::beginTransaction();
        try {
            foreach ($items as $productId => $row) {
                $sizeId = (int) ($row['size_id'] ?? 0);
                $qty    = max(1, (int) ($row['qty'] ?? 1));

                // validate variant exists
                $variant = ProductSize::where('product_id', $productId)
                    ->where('size_id', $sizeId)->first();
                if (! $variant/*|| $variant->stock < $qty*/) {
                    continue;
                }

                $line = Cart::where('session_id', $cartKey)
                    ->where('product_id', $productId)
                    ->where('size_id', $sizeId)
                    ->first();

                $price = (float) $variant->price;
                if ($line) {
                    $line->quantity += $qty;
                    $line->subtotal = $line->quantity * $price;
                    $line->save();
                } else {
                    Cart::create([
                        'session_id'      => $cartKey,
                        'product_id'      => $productId,
                        'size_id'         => $sizeId,
                        'product_size_id' => $variant->id,
                        'quantity'        => $qty,
                        'subtotal'        => $price * $qty,
                    ]);
                }
            }
            DB::commit();
            return back()->with('success', 'Selected products added to cart.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Could not add items. Try again.');
        }
    }

    public function cartdetails(Request $request)
    {
        // persistent key
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            $cartKey = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }

        // items + relations (image/name/size dikhane ke liye)
        $cartItems = Cart::with(['product.images', 'sizeItem', 'variant'])
            ->where('session_id', $cartKey)
            ->get();

        // badge
        $cartCount = (int) $cartItems->sum('quantity');

        // ✅ totals: stored subtotal se
        $subtotal   = (float) $cartItems->sum('subtotal');
        $shipping   = 0.0; // yahan apni shipping logic lagao
        $grandTotal = $subtotal + $shipping;

        return view('index.cart', compact('cartItems', 'cartCount', 'subtotal', 'shipping', 'grandTotal'));
    }

    public function ajaxUpdateQuantity(Request $request, \App\Models\Cart $cart)
    {
        $cartKey = Cookie::get('cart_key');
        abort_unless($cart->session_id === $cartKey, 403);

        $qty   = max(1, (int) $request->input('quantity', 1));
        $price = (float) optional($cart->variant)->price ?? 0;

        $cart->update([
            'quantity' => $qty,
            'subtotal' => round($price * $qty, 2),
        ]);

        // ✅ totals from stored subtotals
        $items    = \App\Models\Cart::where('session_id', $cartKey)->get(['quantity', 'subtotal']);
        $subtotal = (float) $items->sum('subtotal');
        $shipping = 0.0;
        $grand    = $subtotal + $shipping;
        $badge    = (int) $items->sum('quantity');

        return response()->json([
            'line'   => [
                'id'         => $cart->id,
                'quantity'   => $cart->quantity,
                'unit_price' => number_format($price, 2),
                'line_total' => number_format($cart->subtotal, 2),
            ],
            'totals' => [
                'subtotal'   => number_format($subtotal, 2),
                'shipping'   => number_format($shipping, 2),
                'grand'      => number_format($grand, 2),
                'badge'      => $badge,
                'badge_text' => $badge > 99 ? '99+' : (string) $badge,
            ],
        ]);
    }

    public function checkout(Request $request)
    {
        // persistent cart key
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            $cartKey = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }

        // items to show in summary (image/name/size/qty/price)
        $cartItems = Cart::with(['product.images', 'sizeItem', 'variant'])
            ->where('session_id', $cartKey)
            ->get();

        // badge count
        $cartCount = (int) $cartItems->sum('quantity');

        // totals (stored per-line subtotal is most reliable)
        $subtotal   = (float) $cartItems->sum('subtotal');
        $shipping   = 0.0; // adjust if you have shipping rules
        $grandTotal = $subtotal + $shipping;

        // (optional) empty cart -> back to cart
        if ($cartItems->isEmpty()) {
            return redirect()->route('cartdetails')->with('error', 'Your cart is empty.');
        }

        return view('index.checkout', compact('cartItems', 'cartCount', 'subtotal', 'shipping', 'grandTotal'));
    }

    private function cartKey(): string
    {
        $k = Cookie::get('cart_key');
        if (! $k) {
            $k = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $k, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }
        return $k;
    }

    // 4-digit unique order code generator
    private function genOrderCode4(): string
    {
        return str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function place(Request $request)
    {
        // cookie MUST exist at checkout
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            return redirect()->route('cartdetails')
                ->with('error', 'Your cart session expired. Please add items again.');
        }

        $fields = $request->validate([
            'campus'       => 'required|string|max:100',
            'parent_name'  => 'required|string|max:120',
            'student_name' => 'required|string|max:120',
            'class'        => 'nullable|string|max:50',
            'section'      => 'nullable|string|max:50',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:150',
        ]);

        try {
            $order = DB::transaction(function () use ($fields, $cartKey) {
                // snapshot + lock
                $cartItems = Cart::with(['product', 'sizeItem', 'variant'])
                    ->where('session_id', $cartKey)
                    ->lockForUpdate()
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \RuntimeException('Cart is empty.');
                }

                $subtotal = (float) $cartItems->sum('subtotal');
                $total    = $subtotal; // no shipping/tax for now

                // generate unique 4-digit order number
                $order = null;
                for ($i = 0; $i < 25; $i++) {
                    $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                    try {
                        $order = Order::create([
                            'order_number'   => $code, // ← only 4 digits
                            'cart_key'       => $cartKey,
                            'campus'         => $fields['campus'],
                            'parent_name'    => $fields['parent_name'],
                            'student_name'   => $fields['student_name'],
                            'class'          => $fields['class'] ?? null,
                            'section'        => $fields['section'] ?? null,
                            'phone'          => $fields['phone'] ?? null,
                            'email'          => $fields['email'] ?? null,
                            'subtotal'       => round($subtotal, 2),
                            'total'          => round($total, 2),
                            'status'         => 'pending',
                            'payment_status' => 'unpaid',
                        ]);
                        break;
                    } catch (QueryException $e) {
                        // 23000 = unique violation → retry
                        if ($e->getCode() !== '23000') {
                            throw $e;
                        }

                    }
                }
                if (! $order) {
                    throw new \RuntimeException('Could not allocate order number, please retry.');
                }

                // items + optional stock decrement
                foreach ($cartItems as $line) {
                    $p = $line->product;
                    $v = $line->variant;

                    // if ($v && $v->stock !== null) {
                    //     if ((int) $line->quantity > (int) $v->stock) {
                    //         throw new \RuntimeException("Stock changed for {$p->name}, please update cart.");
                    //     }
                    //     $v->decrement('stock', (int) $line->quantity);
                    // }

                    $unit = (float) ($v->price ?? 0);

                    OrderItem::create([
                        'order_id'        => $order->id,
                        'product_id'      => $line->product_id,
                        'size_id'         => $line->size_id,
                        'product_size_id' => $line->product_size_id,
                        'product_name'    => $p->name ?? 'Product',
                        'unit_price'      => $unit,
                        'quantity'        => (int) $line->quantity,
                        'line_total'      => round($unit * (int) $line->quantity, 2),
                    ]);
                }

                // clear cart for this key
                Cart::where('session_id', $cartKey)->delete();

                return $order;
            });

            return redirect()
                ->route('order.thankyou', $order)
                ->with('success', 'Order placed successfully!');
        } catch (\Throwable $e) {
            report($e);
            return redirect()
                ->route('cartdetails')
                ->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }
}
