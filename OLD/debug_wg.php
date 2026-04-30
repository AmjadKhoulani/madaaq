<?php
echo "User: " . exec('whoami') . "\n";
echo "Groups: " . exec('groups') . "\n";
echo "Testing sudo wg show:\n";
exec('sudo -n wg show 2>&1', $output, $returnVar);
echo implode("\n", $output) . "\n";
echo "Return Code: " . $returnVar . "\n";
