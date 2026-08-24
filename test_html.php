<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/products', 'GET');
$response = $app->handle($request);
file_put_contents(__DIR__.'/admin_output.html', $response->getContent());
