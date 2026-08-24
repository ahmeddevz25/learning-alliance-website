<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = App\Models\Product::with('images')->take(5)->get();
foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    foreach ($product->images as $image) {
        echo " - Image: {$image->image_path} (Primary: {$image->is_primary})\n";
    }
}
