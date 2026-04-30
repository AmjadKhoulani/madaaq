<?php
echo "Web User: " . exec('whoami') . "\n";
if (function_exists('shell_exec')) {
    echo "shell_exec: Enabled\n";
    $output = shell_exec('sudo -n wg show 2>&1');
    echo "Output: " . $output . "\n";
} else {
    echo "shell_exec: Disabled\n";
}
