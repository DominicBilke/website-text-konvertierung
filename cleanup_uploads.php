<?php
/**
 * Upload Directory Cleanup Script
 * Removes files older than 24 hours to keep the uploads directory clean
 */

$uploadsDir = './uploads/';
$maxAge = 24 * 60 * 60; // 24 hours in seconds
$currentTime = time();

echo "Starting upload directory cleanup...\n";

if (!is_dir($uploadsDir)) {
    echo "Uploads directory not found.\n";
    exit;
}

$files = scandir($uploadsDir);
$removedCount = 0;
$keptCount = 0;

foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }
    
    $filePath = $uploadsDir . $file;
    
    if (is_file($filePath)) {
        $fileAge = $currentTime - filemtime($filePath);
        
        if ($fileAge > $maxAge) {
            if (unlink($filePath)) {
                echo "Removed old file: $file\n";
                $removedCount++;
            } else {
                echo "Failed to remove: $file\n";
            }
        } else {
            $keptCount++;
        }
    }
}

echo "\nCleanup complete!\n";
echo "Files removed: $removedCount\n";
echo "Files kept: $keptCount\n";
echo "Total files processed: " . ($removedCount + $keptCount) . "\n";
?>
