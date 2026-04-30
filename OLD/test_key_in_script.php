<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

$controller = new \App\Http\Controllers\MikroTikServerController();
$server = \App\Models\MikroTikServer::find(2);

$response = $controller->getSetupScript($server);
$script = $response->getContent();

// Find the WireGuard peer line
$lines = explode("\n", $script);
foreach ($lines as $line) {
    if (strpos($line, 'public-key') !== false) {
        echo "Found: $line\n";
        
        // Extract the key
        preg_match('/public-key="([^"]*)"/', $line, $matches);
        if (isset($matches[1])) {
            echo "\nExtracted Public Key: {$matches[1]}\n";
            echo "Expected Key: " . env('WIREGUARD_PUBLIC_KEY') . "\n";
            
            if ($matches[1] === env('WIREGUARD_PUBLIC_KEY')) {
                echo "\n✅ MATCH! Script is correct!\n";
            } else {
                echo "\n❌ MISMATCH! Keys don't match!\n";
                echo "Length of extracted: " . strlen($matches[1]) . "\n";
                echo "Length of expected: " . strlen(env('WIREGUARD_PUBLIC_KEY')) . "\n";
            }
        } else {
            echo "\n❌ Could not extract key from line!\n";
        }
        break;
    }
}
