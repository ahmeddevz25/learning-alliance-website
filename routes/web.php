<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\InstructionGuideController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Index\CartController;
use App\Http\Controllers\Index\IndexController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('admin/login', [AdminController::class, 'LoginForm'])->name('login');
Route::post('admin/login', [AdminController::class, 'login'])->name('login.submit');

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::get('/uniform/{id}/{slug}', [IndexController::class, 'show'])
    ->whereNumber('id')
    ->name('category.show');
// web.php
Route::get('/uniform/{name}', [IndexController::class, 'category'])->name('category.products');

Route::get('size-chart', [IndexController::class, 'size_chart'])->name('SizeChart');
Route::get('contact-us', [IndexController::class, 'contactus'])->name('contactus');
Route::post('/contact', [IndexController::class, 'submit'])->name('contact.submit');
Route::get('washing-instructions', [IndexController::class, 'washing_instructions'])->name('washingInstructions');
Route::get('accessories', [IndexController::class, 'accessories'])->name('accessories');

Route::get('product/details/{slug}', [IndexController::class, 'product_details'])->name('product.details');

Route::get('/thank-you/{order}', [IndexController::class, 'thankyou'])->name('order.thankyou');

// Cart Routes
Route::get('cart', [CartController::class, 'index'])->name('cart.index');                // Cart page view
Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');               // Add to cart
Route::post('cart/update/{id}', [CartController::class, 'update'])->name('cart.update'); // Update quantity/size
Route::delete('/cart/remove/{id}', [CartController::class, 'ajaxRemove'])
    ->name('cart.ajaxRemove');

Route::post('/cart/bulk-add', [CartController::class, 'bulkAdd'])->name('cart.bulkAdd');
Route::get('cart/details', [CartController::class, 'cartdetails'])->name('cartdetails');
Route::patch('/cart/{cart}/quantity', [CartController::class, 'ajaxUpdateQuantity'])
    ->name('cart.quantity');
Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout/place-order', [CartController::class, 'place'])->name('order.place');

Route::middleware(['auth'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/search', [AdminController::class, 'search'])->name('global.search');

    // Show All Categories
    Route::get('all-categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('store-category', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('edit-category/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('update-category/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('delete-category/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

    // Products Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::delete('/product-image/{id}', [ProductController::class, 'deleteImage'])->name('product.image.delete');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.delete');

    //Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/delete/{id}', [OrderController::class, 'destroy'])->name('orders.delete');

    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::patch('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('admin.orders.payment');

    //Instructions Routes
    Route::get('/instruction-guides', [InstructionGuideController::class, 'index'])->name('instructionguides');
    Route::post('/instruction-guides/store', [InstructionGuideController::class, 'store'])->name('instructionguides.store');
    Route::get('/instruction-guides/edit/{id}', [InstructionGuideController::class, 'edit'])->name('instructionguides.edit');
    Route::post('/instruction-guides/update/{id}', [InstructionGuideController::class, 'update'])->name('instructionguides.update');
    Route::get('/instruction-guides/delete/{id}', [InstructionGuideController::class, 'destroy'])->name('instructionguides.delete');

    //Contact Messages
    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contactmessages');
    Route::get('/contact-messages/delete/{id}', [ContactMessageController::class, 'destroy'])->name('contactmessages.delete');

    // Route::post('/contact', [ContactMessageController::class, 'submit'])->name('contact.submit');

    // Index - All Users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

    //Roles Management
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.delete');

    //Permissions Management
    // routes/web.php
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/permissions/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/permissions/delete/{id}', [PermissionController::class, 'destroy'])->name('permissions.delete');

    Route::get('clear-cache', [AdminController::class, 'cacheclear'])->name('cacheclear');
});

Route::get('clear-cache', [IndexController::class, 'cacheclear'])->name('cacheclear');
Route::get('/setup-project', function () {
    // Migrate fresh
    Artisan::call('migrate:fresh', [
        '--force' => true,
    ]);

    // Seed database
    Artisan::call('db:seed', [
        '--force' => true,
    ]);

    // Storage link
    Artisan::call('storage:link');

    return "✅ Project setup completed successfully!";
});

// Maintainance Mode
Route::get('/down', function () {
    Artisan::call('down');
    return "✅ Application is now in maintenance mode.";
});

Route::get('/up', function () {
    Artisan::call('up');
    return "✅ Application is now live.";
});

Route::get('/insert-data', function () {
    DB::table('product_size_items')->insert([
        ['id' => 1, 'size' => 'Small', 'status' => 'A', 'position' => 18, 'date_time' => '2023-10-07 15:24:14'],
        ['id' => 2, 'size' => 'Large', 'status' => 'A', 'position' => 20, 'date_time' => '2023-10-07 15:24:18'],
        ['id' => 3, 'size' => 'Medium', 'status' => 'A', 'position' => 19, 'date_time' => '2023-10-07 15:24:23'],
        ['id' => 4, 'size' => '22', 'status' => 'A', 'position' => 3, 'date_time' => '2023-10-19 15:01:59'],
        ['id' => 5, 'size' => '24', 'status' => 'A', 'position' => 4, 'date_time' => '2023-10-19 15:02:04'],
        ['id' => 6, 'size' => '26', 'status' => 'A', 'position' => 5, 'date_time' => '2023-10-19 15:02:09'],
        ['id' => 7, 'size' => '28', 'status' => 'A', 'position' => 6, 'date_time' => '2023-10-19 15:02:14'],
        ['id' => 8, 'size' => '30', 'status' => 'A', 'position' => 7, 'date_time' => '2023-10-19 15:02:18'],
        ['id' => 9, 'size' => '32', 'status' => 'A', 'position' => 8, 'date_time' => '2023-10-19 15:02:24'],
        ['id' => 10, 'size' => '34', 'status' => 'A', 'position' => 9, 'date_time' => '2023-10-19 15:02:30'],
        ['id' => 11, 'size' => '36', 'status' => 'A', 'position' => 10, 'date_time' => '2023-10-19 15:02:35'],
        ['id' => 12, 'size' => '38', 'status' => 'A', 'position' => 11, 'date_time' => '2023-10-19 15:02:42'],
        ['id' => 13, 'size' => '40', 'status' => 'A', 'position' => 12, 'date_time' => '2023-10-19 15:02:47'],
        ['id' => 14, 'size' => 'X Small', 'status' => 'A', 'position' => 17, 'date_time' => '2023-11-21 14:06:56'],
        ['id' => 15, 'size' => 'X Large', 'status' => 'A', 'position' => 21, 'date_time' => '2023-11-21 14:07:22'],
        ['id' => 16, 'size' => 'XX Large', 'status' => 'A', 'position' => 22, 'date_time' => '2023-11-21 14:07:34'],
        ['id' => 17, 'size' => '16', 'status' => 'A', 'position' => 1, 'date_time' => '2023-11-21 14:07:43'],
        ['id' => 18, 'size' => '18', 'status' => 'A', 'position' => 2, 'date_time' => '2023-11-21 14:07:49'],
        ['id' => 19, 'size' => '20', 'status' => 'A', 'position' => 2, 'date_time' => '2023-11-21 14:07:59'],
        ['id' => 20, 'size' => '42', 'status' => 'A', 'position' => 13, 'date_time' => '2023-11-21 14:08:09'],
        ['id' => 21, 'size' => '44', 'status' => 'A', 'position' => 14, 'date_time' => '2023-11-21 14:08:17'],
        ['id' => 22, 'size' => '46', 'status' => 'A', 'position' => 15, 'date_time' => '2023-11-21 14:08:21'],
        ['id' => 23, 'size' => 'Special Size on Order', 'status' => 'A', 'position' => 23, 'date_time' => '2023-11-21 14:08:26'],
        ['id' => 24, 'size' => 'Standard', 'status' => 'A', 'position' => 25, 'date_time' => '2023-12-11 12:07:18'],
        ['id' => 25, 'size' => 'XX Small', 'status' => 'A', 'position' => 16, 'date_time' => '2024-01-11 11:05:45'],
        ['id' => 26, 'size' => 'Meter', 'status' => 'A', 'position' => 26, 'date_time' => '2024-01-11 14:19:08'],
        ['id' => 27, 'size' => 'Yard', 'status' => 'A', 'position' => 27, 'date_time' => '2024-01-11 14:19:19'],
        ['id' => 28, 'size' => '28 x 42', 'status' => 'A', 'position' => 28, 'date_time' => '2024-01-11 14:55:23'],
        ['id' => 29, 'size' => '30 x 42', 'status' => 'A', 'position' => 29, 'date_time' => '2024-01-11 14:55:31'],
        ['id' => 30, 'size' => '32 x 42', 'status' => 'A', 'position' => 30, 'date_time' => '2024-01-11 14:55:38'],
        ['id' => 31, 'size' => '34 x 42', 'status' => 'A', 'position' => 31, 'date_time' => '2024-01-11 14:55:47'],
        ['id' => 32, 'size' => '36 x 42', 'status' => 'A', 'position' => 32, 'date_time' => '2024-01-11 14:55:55'],
        ['id' => 33, 'size' => '38 x 42', 'status' => 'A', 'position' => 33, 'date_time' => '2024-01-11 14:56:04'],
        ['id' => 34, 'size' => '40 x 42', 'status' => 'A', 'position' => 34, 'date_time' => '2024-01-11 14:56:15'],
        ['id' => 35, 'size' => '42 x 42', 'status' => 'A', 'position' => 35, 'date_time' => '2024-01-11 14:56:40'],
    ]);

    DB::table('categories')->insert([
        ['id' => 1, 'name' => 'Boys', 'parent_id' => null, 'created_at' => '2025-08-01 05:02:25', 'updated_at' => '2025-08-01 05:02:25'],
        ['id' => 2, 'name' => 'Winter Uniform', 'parent_id' => 1, 'created_at' => '2025-08-01 05:02:45', 'updated_at' => '2025-08-01 05:02:45'],
        ['id' => 3, 'name' => 'Summer Uniform', 'parent_id' => 1, 'created_at' => '2025-08-01 05:22:05', 'updated_at' => '2025-08-01 05:22:05'],
        ['id' => 4, 'name' => 'Class I-IV (CIE)', 'parent_id' => 2, 'created_at' => '2025-08-01 05:23:37', 'updated_at' => '2025-08-01 05:23:37'],
        ['id' => 5, 'name' => 'Class V – O level', 'parent_id' => 2, 'created_at' => '2025-08-01 05:24:44', 'updated_at' => '2025-08-01 05:24:44'],
        ['id' => 6, 'name' => 'A Level', 'parent_id' => 2, 'created_at' => '2025-08-01 05:29:05', 'updated_at' => '2025-08-01 05:29:05'],
        ['id' => 7, 'name' => 'IB', 'parent_id' => 2, 'created_at' => '2025-08-01 05:29:36', 'updated_at' => '2025-08-01 05:29:36'],
        ['id' => 8, 'name' => 'PYP', 'parent_id' => 7, 'created_at' => '2025-08-01 05:30:04', 'updated_at' => '2025-08-01 05:30:04'],
        ['id' => 9, 'name' => 'MYP', 'parent_id' => 7, 'created_at' => '2025-08-01 05:30:23', 'updated_at' => '2025-08-01 05:30:23'],
        ['id' => 10, 'name' => 'DP', 'parent_id' => 7, 'created_at' => '2025-08-01 05:30:40', 'updated_at' => '2025-08-01 05:30:40'],
        ['id' => 11, 'name' => 'Class I-IV (CIE)', 'parent_id' => 3, 'created_at' => '2025-08-01 05:36:53', 'updated_at' => '2025-08-01 05:36:53'],
        ['id' => 12, 'name' => 'Class V – O level', 'parent_id' => 3, 'created_at' => '2025-08-01 05:37:08', 'updated_at' => '2025-08-01 05:37:08'],
        ['id' => 13, 'name' => 'A Level', 'parent_id' => 3, 'created_at' => '2025-08-01 05:37:24', 'updated_at' => '2025-08-01 05:37:24'],
        ['id' => 14, 'name' => 'IB', 'parent_id' => 3, 'created_at' => '2025-08-01 05:37:40', 'updated_at' => '2025-08-01 05:37:40'],
        ['id' => 15, 'name' => 'PYP', 'parent_id' => 14, 'created_at' => '2025-08-01 05:39:58', 'updated_at' => '2025-08-01 05:39:58'],
        ['id' => 16, 'name' => 'MYP', 'parent_id' => 14, 'created_at' => '2025-08-01 05:40:13', 'updated_at' => '2025-08-01 05:40:13'],
        ['id' => 17, 'name' => 'DP', 'parent_id' => 14, 'created_at' => '2025-08-01 05:40:35', 'updated_at' => '2025-08-01 05:40:35'],
        ['id' => 18, 'name' => 'Girls', 'parent_id' => null, 'created_at' => '2025-08-01 23:11:36', 'updated_at' => '2025-08-01 23:11:36'],
        ['id' => 19, 'name' => 'Winter Uniform', 'parent_id' => 18, 'created_at' => '2025-08-01 23:12:09', 'updated_at' => '2025-08-01 23:12:09'],
        ['id' => 20, 'name' => 'Summer Uniform', 'parent_id' => 18, 'created_at' => '2025-08-01 23:12:34', 'updated_at' => '2025-08-01 23:12:34'],
        ['id' => 21, 'name' => 'Class I-IV (CIE)', 'parent_id' => 19, 'created_at' => '2025-08-01 23:13:16', 'updated_at' => '2025-08-01 23:13:16'],
        ['id' => 22, 'name' => 'Class V – O level', 'parent_id' => 19, 'created_at' => '2025-08-01 23:13:34', 'updated_at' => '2025-08-01 23:13:34'],
        ['id' => 23, 'name' => 'A Level', 'parent_id' => 19, 'created_at' => '2025-08-01 23:13:53', 'updated_at' => '2025-08-01 23:13:53'],
        ['id' => 24, 'name' => 'IB', 'parent_id' => 19, 'created_at' => '2025-08-01 23:14:14', 'updated_at' => '2025-08-01 23:14:14'],
        ['id' => 25, 'name' => 'PYP', 'parent_id' => 24, 'created_at' => '2025-08-01 23:14:37', 'updated_at' => '2025-08-01 23:14:37'],
        ['id' => 26, 'name' => 'MYP', 'parent_id' => 24, 'created_at' => '2025-08-01 23:14:56', 'updated_at' => '2025-08-01 23:14:56'],
        ['id' => 27, 'name' => 'DP', 'parent_id' => 24, 'created_at' => '2025-08-01 23:15:13', 'updated_at' => '2025-08-01 23:15:13'],
        ['id' => 28, 'name' => 'Class I-IV (CIE)', 'parent_id' => 20, 'created_at' => '2025-08-01 23:15:53', 'updated_at' => '2025-08-01 23:15:53'],
        ['id' => 29, 'name' => 'Class V – O level', 'parent_id' => 20, 'created_at' => '2025-08-01 23:16:10', 'updated_at' => '2025-08-01 23:16:10'],
        ['id' => 30, 'name' => 'A Level', 'parent_id' => 20, 'created_at' => '2025-08-01 23:16:33', 'updated_at' => '2025-08-01 23:16:33'],
        ['id' => 31, 'name' => 'IB', 'parent_id' => 20, 'created_at' => '2025-08-01 23:16:49', 'updated_at' => '2025-08-01 23:16:49'],
        ['id' => 32, 'name' => 'PYP', 'parent_id' => 31, 'created_at' => '2025-08-01 23:17:51', 'updated_at' => '2025-08-01 23:17:51'],
        ['id' => 33, 'name' => 'MYP', 'parent_id' => 31, 'created_at' => '2025-08-01 23:18:13', 'updated_at' => '2025-08-01 23:18:13'],
        ['id' => 34, 'name' => 'DP', 'parent_id' => 31, 'created_at' => '2025-08-01 23:19:14', 'updated_at' => '2025-08-01 23:18:14'],
    ]);
    return "✅ Data inserted successfully!";
});

Route::get('/create-payment-mode-table', function () {
    if (! Schema::hasTable('payment_modes')) {
        Schema::create('payment_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        return "✅ payment_modes table created successfully!";
    }
    return "⚠️ payment_modes table already exists.";
});

// Route::get('/create-visitors-table', function () {
//     try {
//         Artisan::call('migrate', [
//             '--path' => 'database/migrations/2025_08_25_095321_create_visitors_table.php',
//             '--force' => true
//         ]);
//         return "✅ Visitors table migration completed successfully!";
//     } catch (\Exception $e) {
//         return "❌ Error: " . $e->getMessage();
//     }
// });

Route::get('/composer-dump-autoload', function () {
    try {
        // Run dump-autoload
        Artisan::call('dump-autoload');

        return "<pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

Route::get('/migrate', function () {

    Artisan::call('migrate');

    return "✅Migration successfully!";
});
