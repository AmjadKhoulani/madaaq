<?php
// Quick test script to verify fsockopen works from web context
$ip = '201.10.0.2';
$port = 8728;
$timeout = 10;

echo "<h2>Testing connection to $ip:$port</h2>";
echo "<pre>";

$startTime = microtime(true);
$fp = @fsockopen($ip, $port, $errno, $errstr, $timeout);
$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);

if ($fp) {
    echo "✅ SUCCESS: Connected to $ip:$port\n";
    echo "Time: {$duration}ms\n";
    fclose($fp);
} else {
    echo "❌ FAILED: $errno - $errstr\n";
    echo "Time: {$duration}ms\n";
}

echo "</pre>";
