<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = App\Models\Product::with('images')->latest()->take(10)->get();
foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    $mainImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    if ($mainImage) {
        echo " - Asset URL: " . asset('storage/' . $mainImage->image_path) . "\n";
        echo " - Storage URL: " . Storage::disk('public')->url($mainImage->image_path) . "\n";
    }
}
