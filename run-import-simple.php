<?php

// Simple wrapper to run import and show output
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting import script...\n";
echo "Current directory: " . __DIR__ . "\n";
echo "PHP version: " . PHP_VERSION . "\n\n";

// Run the import script
include __DIR__ . '/import-altezza-tours.php';

echo "\n\nScript execution completed.\n";






