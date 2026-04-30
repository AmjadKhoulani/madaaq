<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Cache Driver: " . config('cache.default') . "\n";

$key = 'test_key_' . time();
$value = 'test_value_' . time();

echo "Setting Key: $key = $value\n";
Cache::put($key, $value, 60);

$retrieved = Cache::get($key);
echo "Retrieved: " . var_export($retrieved, true) . "\n";

if ($retrieved === $value) {
    echo "SUCCESS: Cache works.\n";
} else {
    echo "FAILURE: Cache did not return expected value.\n";
}
