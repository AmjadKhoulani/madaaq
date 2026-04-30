<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Crypt;

echo "Testing Terminal formatResponse\n\n";

// Simulate MikroTik response
$response = [
    [
        '.id' => '*3',
        'address' => '11.0.0.1/8',
        'network' => '11.0.0.0',
        'interface' => 'bridge1',
        'invalid' => 'false',
        'dynamic' => 'false',
    ],
    [
        '.id' => '*4',
        'address' => '192.168.1.1/24',
        'network' => '192.168.1.0',
        'interface' => 'ether1',
        'invalid' => 'false',
        'dynamic' => 'true',
    ],
];

echo "Sample Response:\n";
print_r($response);

// Test formatResponse logic
function formatResponse($response)
{
    if (empty($response)) {
        return "✓ Command executed successfully (No output returned)";
    }

    // If it's a simple message array
    if (isset($response['after'])) {
        unset($response['after']);
    }

    $output = "";
    $count = count($response);
    
    foreach ($response as $index => $item) {
        $output .= "╔═══ Item " . ($index + 1) . " / {$count} ═══╗\n";
        
        if (is_array($item)) {
            foreach ($item as $key => $value) {
                // Format boolean values
                if ($value === 'true' || $value === true) $value = 'yes';
                if ($value === 'false' || $value === false) $value = 'no';
                
                // Format the output line
                $output .= sprintf("║ %-20s: %s\n", $key, $value);
            }
        } else {
            $output .= "║ " . $item . "\n";
        }
        
        $output .= "╚" . str_repeat("═", 50) . "╝\n\n";
    }

    return $output;
}

$formatted = formatResponse($response);
echo "\n\nFormatted Output:\n";
echo $formatted;

echo "\n\nOutput Length: " . strlen($formatted) . "\n";
echo "Empty? " . (empty($formatted) ? 'YES' : 'NO') . "\n";
