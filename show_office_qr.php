<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = App\Models\OfficeQrCode::select('id','code','valid_from','valid_until','is_active')
    ->orderBy('created_at','desc')
    ->get()
    ->toArray();

print_r($rows);
