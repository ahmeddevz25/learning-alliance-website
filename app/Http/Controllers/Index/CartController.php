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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class CartController extends Controller
{
    /**
     * Get or generate a persistent 90-day cart key cookie.
     */
    private function cartKey(): string
    {
        $key = Cookie::get('cart_key');
        if (! $key) {
            $key = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $key, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }

        return $key;
    }

    /**
     * Add single item to cart.
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id'    => 'required|exists:product_size_items,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cartKey = $this->cartKey();

        try {
            DB::transaction(function () use ($data, $cartKey) {
                $variant = ProductSize::where('product_id', $data['product_id'])
                    ->where('size_id', $data['size_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $line = Cart::where('session_id', $cartKey)
                    ->where('product_id', $data['product_id'])
                    ->where('size_id', $data['size_id'])
                    ->lockForUpdate()
                    ->first();

                $price      = (float) $variant->price;
                $incoming   = (int) $data['quantity'];
                $currentQty = $line ? (int) $line->quantity : 0;
                $newQty     = $currentQty + $incoming;

                if ($line) {
                    $line->quantity        = $newQty;
                    $line->subtotal        = round($price * $newQty, 2);
                    $line->product_size_id = $variant->id;
                    $line->save();
                } else {
                    Cart::create([
                        'session_id'      => $cartKey,
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
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Add to Cart Error: ' . $e->getMessage());
            Alert::error('Error', 'Failed to add product to cart.');
            return back();
        }
    }

    /**
     * Standard update for cart line item (fixed table name & session).
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'id'       => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartKey = $this->cartKey();

        $line = Cart::where('id', $data['id'])
            ->where('session_id', $cartKey)
            ->firstOrFail();

        $variant = $line->variant()->firstOrFail();

        $line->quantity = (int) $data['quantity'];
        $line->subtotal = round((float) $variant->price * $line->quantity, 2);
        $line->save();

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Ajax remove cart line item and return updated totals.
     */
    public function ajaxRemove(Request $request, $id)
    {
        $cartKey = $this->cartKey();

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

        // Calculate updated totals for dynamic front-end updates
        $remaining = Cart::where('session_id', $cartKey)->get(['quantity', 'subtotal']);
        $subtotal  = (float) $remaining->sum('subtotal');
        $shipping  = 0.0;
        $grand     = $subtotal + $shipping;
        $badge     = (int) $remaining->sum('quantity');

        return response()->json([
            'status'  => 'success',
            'message' => 'Product removed from cart successfully!',
            'totals'  => [
                'subtotal'   => number_format($subtotal, 2),
                'shipping'   => number_format($shipping, 2),
                'grand'      => number_format($grand, 2),
                'badge'      => $badge,
                'badge_text' => $badge > 99 ? '99+' : (string) $badge,
            ],
        ], 200);
    }

    /**
     * Bulk add items (e.g. from category or accessories page).
     */
    public function bulkAdd(Request $request)
    {
        $items = $request->input('items', []);
        if (empty($items)) {
            return back()->with('error', 'Please select at least one product.');
        }

        $cartKey = $this->cartKey();

        DB::beginTransaction();
        try {
            foreach ($items as $productId => $row) {
                $sizeId = (int) ($row['size_id'] ?? 0);
                $qty    = max(1, (int) ($row['qty'] ?? 1));

                $variant = ProductSize::where('product_id', $productId)
                    ->where('size_id', $sizeId)
                    ->first();

                if (! $variant) {
                    continue;
                }

                $line = Cart::where('session_id', $cartKey)
                    ->where('product_id', $productId)
                    ->where('size_id', $sizeId)
                    ->first();

                $price = (float) $variant->price;
                if ($line) {
                    $line->quantity += $qty;
                    $line->subtotal = round($line->quantity * $price, 2);
                    $line->save();
                } else {
                    Cart::create([
                        'session_id'      => $cartKey,
                        'product_id'      => $productId,
                        'size_id'         => $sizeId,
                        'product_size_id' => $variant->id,
                        'quantity'        => $qty,
                        'subtotal'        => round($price * $qty, 2),
                    ]);
                }
            }
            DB::commit();
            return back()->with('success', 'Selected products added to cart.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Bulk Add Error: ' . $e->getMessage());
            return back()->with('error', 'Could not add items. Try again.');
        }
    }

    /**
     * Show Cart Details page.
     */
    public function cartdetails(Request $request)
    {
        $cartKey = $this->cartKey();

        $cartItems = Cart::with(['product.images', 'sizeItem', 'variant'])
            ->where('session_id', $cartKey)
            ->get();

        $cartCount  = (int) $cartItems->sum('quantity');
        $subtotal   = (float) $cartItems->sum('subtotal');
        $shipping   = 0.0;
        $grandTotal = $subtotal + $shipping;

        return view('index.cart', compact('cartItems', 'cartCount', 'subtotal', 'shipping', 'grandTotal'));
    }

    /**
     * Ajax update quantity for a line item.
     */
    public function ajaxUpdateQuantity(Request $request, Cart $cart)
    {
        $cartKey = $this->cartKey();
        abort_unless($cart->session_id === $cartKey, 403);

        $qty   = max(1, (int) $request->input('quantity', 1));
        $price = (float) optional($cart->variant)->price ?? 0;

        $cart->update([
            'quantity' => $qty,
            'subtotal' => round($price * $qty, 2),
        ]);

        $items    = Cart::where('session_id', $cartKey)->get(['quantity', 'subtotal']);
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

    /**
     * Show Checkout page.
     */
    public function checkout(Request $request)
    {
        $cartKey = $this->cartKey();

        $cartItems = Cart::with(['product.images', 'sizeItem', 'variant'])
            ->where('session_id', $cartKey)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cartdetails')->with('error', 'Your cart is empty.');
        }

        $cartCount  = (int) $cartItems->sum('quantity');
        $subtotal   = (float) $cartItems->sum('subtotal');
        $shipping   = 0.0;
        $grandTotal = $subtotal + $shipping;

        return view('index.checkout', compact('cartItems', 'cartCount', 'subtotal', 'shipping', 'grandTotal'));
    }

    /**
     * Place order with Google reCAPTCHA verification and unique 4-digit order number.
     */
    public function place(Request $request)
    {
        $cartKey = $this->cartKey();

        $fields = $request->validate([
            'campus'               => 'required|string|in:Learning Alliance DHA,Learning Alliance International DHA,Learning Alliance Aziz Avenue,Learning Alliance Gulberg,Learning Alliance Faisalabad',
            'parent_name'          => 'required|string|max:120|not_regex:/[\"\=\<\>]/',
            'student_name'         => 'required|string|max:120|not_regex:/[\"\=\<\>]/',
            'class'                => 'nullable|string|max:50',
            'section'              => 'nullable|string|max:50',
            'phone'                => 'nullable|string|max:30',
            'email'                => 'nullable|email|max:150',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Please check the "I\'m not a robot" checkbox.',
        ]);

        $recaptchaSecret = config('services.recaptcha.secret_key') ?: env('RECAPTCHA_SECRET_KEY');
        if ($recaptchaSecret) {
            $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $recaptchaSecret,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (! $recaptchaResponse->json('success')) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed, please try again.'])->withInput();
            }
        }

        try {
            $order = DB::transaction(function () use ($fields, $cartKey) {
                $cartItems = Cart::with(['product', 'sizeItem', 'variant'])
                    ->where('session_id', $cartKey)
                    ->lockForUpdate()
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \RuntimeException('Cart is empty.');
                }

                $subtotal = (float) $cartItems->sum('subtotal');
                $total    = $subtotal;

                // Allocate a unique 4-digit code (table column length is 4 characters)
                $order = null;
                for ($i = 0; $i < 30; $i++) {
                    $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                    try {
                        $order = Order::create([
                            'order_number'   => $code,
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
                        if ($e->getCode() !== '23000') {
                            throw $e;
                        }
                    }
                }

                if (! $order) {
                    throw new \RuntimeException('Could not allocate unique order number, please retry.');
                }

                // Create Order Items
                foreach ($cartItems as $line) {
                    $p    = $line->product;
                    $v    = $line->variant;
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

                // Clear cart for this key
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
