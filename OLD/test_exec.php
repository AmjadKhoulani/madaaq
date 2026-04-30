<?php
echo "User: " . get_current_user() . "\n";
echo "Whoami: " . exec('whoami') . "\n";
if (function_exists('shell_exec')) {
    echo "shell_exec: EXISTS\n";
    echo "Output: " . shell_exec('echo hello');
} else {
    echo "shell_exec: DISABLED\n";
}
if (function_exists('exec')) {
    echo "exec: EXISTS\n";
} else {
    echo "exec: DISABLED\n";
}
