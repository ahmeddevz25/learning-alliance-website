<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Models\Product::where('name', 'Shirt Pinstriped For Girls (Half Sleeve)')->first();
if ($product) {
    echo "Product: {$product->name}\n";
    foreach ($product->images as $image) {
        echo " - Image: {$image->image_path} (Primary: {$image->is_primary})\n";
    }
} else {
    echo "Product not found.\n";
}
