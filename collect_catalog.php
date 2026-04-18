<?php
$config = json_decode(file_get_contents($argv[1]), true);
$catKey = $argv[2];
$outputFile = $argv[3];

if (!isset($config[$catKey])) {
    die("Invalid catalog key: " . $catKey);
}

$output = "# Source Catalog: " . $catKey . "\n\n";

foreach ($config[$catKey] as $cat) {
    $path = "resources/views/" . $cat;
    if (is_dir($path)) {
        $ritit = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($ritit as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $pathName = $file->getPathname();
                $output .= "## " . $pathName . "\n\n";
                $output .= "```php\n" . file_get_contents($pathName) . "\n```\n\n";
            }
        }
    } elseif (is_file($path)) {
        $output .= "## " . $path . "\n\n";
        $output .= "```php\n" . file_get_contents($path) . "\n```\n\n";
    }
}

file_put_contents($outputFile, $output);
echo "Done: $outputFile\n";
