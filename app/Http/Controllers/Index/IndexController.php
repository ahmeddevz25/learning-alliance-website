<?php
namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Mail\ContactSubmitted;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\InstructionGuid;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSizeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class IndexController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Fetch products related to "Boys" and "Girls" categories
            $products = Product::with(['images', 'sizes', 'categories'])
                ->whereHas('categories', function ($query) {
                    $query->whereIn('name', ['Boys', 'Girls']);
                })
                ->get();

            $sizes = ProductSizeItem::all();

            // Ensure persistent cart key (90 days). First visit pe cookie set ho jayegi.
            $cartKey = Cookie::get('cart_key');
            if (! $cartKey) {
                $cartKey = (string) Str::uuid();
                Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
            }

            // Header badge: total quantity
            $cartCount = Cart::where('session_id', $cartKey)->sum('quantity');

            // Fetch only "Boys" and "Girls" categories for the menu
            $uniformCats = Category::whereIn('name', ['Winter Uniform', 'Summer Uniform'])
                ->with('children')
                ->orderBy('name')
                ->get()
                ->unique('name'); // Ensure only one category for each "Winter Uniform" and "Summer Uniform"

            // Return view
            return view('index.index', compact('products', 'sizes', 'cartCount', 'uniformCats'));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Home page error: ' . $e->getMessage());

            // Return view with empty data, but show error toastr
            return view('index.index', [
                'products'    => collect(), // empty collection
                'sizes'       => collect(),
                'cartCount'   => 0,
                'uniformCats' => collect(),
            ])->with('error', 'Having trouble loading some data. Please try again later.');
        }
    }

    public function show(Request $request, int $id, string $slug)
    {
        // Find the category by ID (faster + unique)
        $category = Category::with('children')->findOrFail($id);

        // Agar slug match nahi karta to 404 return karo
        if (Str::slug($category->name) !== $slug) {
            abort(404);
        }

        // Get descendant IDs (child categories)
        $baseIds = $category->descendantIds();

        // Selected category from query string (optional)
        $selectedId = $request->integer('category');

        if ($selectedId) {
            $selectedCategory = Category::find($selectedId);

            if ($selectedCategory) {
                $filterIds   = $selectedCategory->descendantIds();
                $filterIds[] = $selectedId;
            } else {
                $selectedId = null;
                $filterIds  = $baseIds;
            }
        } else {
            $filterIds = $baseIds;
        }

        // ✅ Products with only ACTIVE sizes
        $products = Product::with([
            'images',
            'categories',
            'sizes' => function ($q) {
                $q->where('is_active', true)
                    ->with('sizeItem');
            },
        ])
            ->whereHas('categories', function ($q) use ($filterIds) {
                $q->whereIn('categories.id', $filterIds);
            })
            ->paginate(12)
            ->appends($request->query());

        // All categories for dropdown
        $categories = Category::orderBy('created_at')->get();

        // Persistent Cart
        $cartKey = Cookie::get('cart_key') ?? tap(
            (string) Str::uuid(),
            fn($k) => Cookie::queue(cookie('cart_key', $k, 60 * 24 * 90, null, null, false, true, false, 'Lax'))
        );
        $cartCount = Cart::where('session_id', $cartKey)->sum('quantity');

        return view('index.category.show-products', [
            'category'   => $category,
            'products'   => $products,
            'categories' => $categories,
            'selectedId' => $selectedId,
            'categoryId' => $selectedId,
            'cartCount'  => $cartCount,
        ]);
    }

    public function accessories(Request $request)
    {
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            $cartKey = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }

        // Header badge: total quantity
        $cartCount = Cart::where('session_id', $cartKey)->sum('quantity');

        // Get category ID from request
        $categoryId = $request->integer('category');

        // Get current category
        $category = Category::find($categoryId);

        // If category exists, get its parent category
        $parentCategory = $category ? $category->parent : null;

        // Query for products (with many-to-many categories relation)
        $query = Product::with([
            'images',
            'categories',
            'sizes' => function ($q) {
                $q->where('is_active', true); // 👈 sirf active sizes
            },
            'sizes.sizeItem',
        ]);

        if ($categoryId && $category) {
            // Collect IDs of selected category + its descendants
            $categoryIds   = $category->descendantIds();
            $categoryIds[] = $categoryId;

            // Filter products that have these categories
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Get products and preserve the filter with appends()
        $products = $query->paginate(10)->appends($request->query());

        // Get categories sorted by the order or creation date
        $categories = Category::orderBy('created_at')->get();

        return view('index.accessories', compact('products', 'categories', 'categoryId', 'cartCount', 'parentCategory', 'category'));
    }

    public function product_details(Request $request, $slug)
    {
        try {
            $product = Product::with(['sizes.sizeItem', 'categories.parent', 'categories.children'])
                ->get()
                ->first(function ($item) use ($slug) {
                    return Str::slug($item->name) === $slug;
                });
            // dd($product);
            if (! $product) {
                abort(404);
            }

            $categories = Category::all();

            // Pehle product ki categories collect karo
            $categoryIds = $product->categories->pluck('id')->toArray();

            $relatedProducts = $product->categories()
                ->with(['products' => function ($q) use ($product) {
                    $q->where('products.id', '!=', $product->id)
                        ->with(['images', 'sizes'])
                        ->inRandomOrder()
                        ->paginate(4);
                }])
                ->get()
                ->pluck('products')
                ->flatten();

            if (! empty($categoryIds)) {
                $relatedProducts = Product::whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds); // 👈 yahan prefix karo
                })
                    ->where('products.id', '!=', $product->id) // 👈 aur yahan bhi
                    ->with(['images', 'sizes'])
                    ->inRandomOrder()
                    ->paginate(4);
            }

            // Persistent Cart
            $cartKey = Cookie::get('cart_key');
            if (! $cartKey) {
                $cartKey = (string) Str::uuid();
                Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
            }
            $cartCount = Cart::where('session_id', $cartKey)->sum('quantity');

            return view('index.product-details', compact('product', 'relatedProducts', 'categories', 'cartCount'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function size_chart(Request $request)
    {
        $image = InstructionGuid::where('type', 'size_guide')->first();
        // dd($image);
        // Ensure persistent cart key (90 days). First visit pe cookie set ho jayegi.
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            $cartKey = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }
        // Header badge: total quantity
        $cartCount = Cart::where('session_id', $cartKey)->sum('quantity');
        return view('index.size-chart', compact('cartCount', 'image'));
    }

    public function contactus(Request $request)
    {
        // Ensure persistent cart key (90 days). First visit pe cookie set ho jayegi.
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            $cartKey = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }
        // Header badge: total quantity
        $cartCount = Cart::where('session_id', $cartKey)->sum('quantity');
        return view('index.contactus', compact('cartCount'));
    }

    public function washing_instructions(Request $request)
    {
        $image = InstructionGuid::where('type', 'washing_instructions')->first();
        // Ensure persistent cart key (90 days). First visit pe cookie set ho jayegi.
        $cartKey = Cookie::get('cart_key');
        if (! $cartKey) {
            $cartKey = (string) Str::uuid();
            Cookie::queue(cookie('cart_key', $cartKey, 60 * 24 * 90, null, null, false, true, false, 'Lax'));
        }
        // Header badge: total quantity
        $cartCount = Cart::where('session_id', $cartKey)->sum('quantity');
        return view('index.washing-instructions', compact('cartCount', 'image'));
    }

    public function thankyou(Order $order)
    {
        if (Cookie::get('cart_key') !== $order->cart_key) {
            abort(404);
        }

        // items + product image + size label
        $order->load(['items.product.images', 'items.sizeItem']);

        return view('index.thank-you', compact('order'));
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:190'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:5000'],
            // 'website' => ['prohibited'], // optional honeypot
        ]);

        $contact = ContactMessage::create($data);

        // Email admin
        Mail::to(config('mail.contact_admin'))->send(new ContactSubmitted($contact));

        return back()->with('status', 'Thank you! Your message has been sent.');
    }

    public function cacheclear()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Cache Cleared successfully!');
    }
}
