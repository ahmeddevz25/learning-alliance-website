<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Visitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{

    // public function index()
    // {
    //     // Total counts
    //     $totalProducts = Product::count();
    //     $totalOrders   = Order::count();

    //     // Order status breakdown
    //     $ordersByStatus = [
    //         'pending'    => Order::where('status', 'pending')->count(),
    //         'confirmed'  => Order::where('status', 'confirmed')->count(),
    //         'processing' => Order::where('status', 'processing')->count(),
    //         'completed'  => Order::where('status', 'completed')->count(),
    //         'cancelled'  => Order::where('status', 'cancelled')->count(),
    //     ];

    //     return view('admin.index', compact(
    //         'totalProducts',
    //         'totalOrders',
    //         'ordersByStatus'
    //     ));
    // }

    public function index()
    {
        // Total counts
        $totalProducts = Product::count();
        $totalOrders   = Order::count();

        // Order status breakdown
        $ordersByStatus = [
            'pending'    => Order::where('status', 'pending')->count(),
            'confirmed'  => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        // Visitors
        $todayVisitors     = Visitor::whereDate('visit_date', today())->count();
        $yesterdayVisitors = Visitor::whereDate('visit_date', today()->subDay())->count();
        $allVisitors       = Visitor::count();
        $newVisitors       = Visitor::select('ip_address')->distinct()->count();

        // Growth calculations
        $todayGrowth = $yesterdayVisitors > 0
            ? round((($todayVisitors - $yesterdayVisitors) / $yesterdayVisitors) * 100, 2)
            : ($todayVisitors > 0 ? 100 : 0);

        $previousWeekVisitors = Visitor::whereBetween('visit_date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();

        $thisWeekVisitors = Visitor::whereBetween('visit_date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        $weekGrowth = $previousWeekVisitors > 0
            ? round((($thisWeekVisitors - $previousWeekVisitors) / $previousWeekVisitors) * 100, 2)
            : ($thisWeekVisitors > 0 ? 100 : 0);

        return view('admin.index', compact(
            'totalProducts',
            'totalOrders',
            'ordersByStatus',
            'todayVisitors',
            'yesterdayVisitors',
            'allVisitors',
            'newVisitors',
            'todayGrowth',
            'previousWeekVisitors',
            'thisWeekVisitors',
            'weekGrowth'
        ));
    }

    public function LoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Welcome to Dashboard!');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (! $query) {
            return response()->json([]);
        }

        $results = [];

        // 🔎 Products
        $products = Product::where('name', 'like', "%$query%")
            ->limit(5)
            ->get(['id', 'name']);
        foreach ($products as $product) {
            $results[] = [
                'type'  => 'Product',
                'label' => $product->name,
                'url'   => route('products'), // ya detail page agar banaya ho
            ];
        }

        // 🔎 Orders
        $orders = Order::where('order_number', 'like', "%$query%")
            ->limit(5)
            ->get(['id', 'order_number']);
        foreach ($orders as $order) {
            $results[] = [
                'type'  => 'Order',
                'label' => 'Order #' . $order->order_number,
                'url'   => route('orders'),
            ];
        }

        // 🔎 Users (example for admin search)
        $users = User::where('name', 'like', "%$query%")
            ->limit(5)
            ->get(['id', 'name']);
        foreach ($users as $user) {
            $results[] = [
                'type'  => 'User',
                'label' => $user->name,
                'url'   => route('users'),
            ];
        }

        if (strtolower($query) === 'products') {
            return response()->json([
                ['type' => 'Redirect', 'label' => 'Go to Products', 'url' => route('products')],
            ]);
        }
        if (strtolower($query) === 'orders') {
            return response()->json([
                ['type' => 'Redirect', 'label' => 'Go to Orders', 'url' => route('orders')],
            ]);
        }

        return response()->json($results);
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
