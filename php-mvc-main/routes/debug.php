<?php
declare(strict_types=1);

echo "<h1>Debug Information</h1>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'Not set') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'Not set') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'Not set') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "\n";
echo "\nDIRECTORY STRUCTURE:\n";
echo "Current File: " . __FILE__ . "\n";
echo "INCLUDES_DIR: " . INCLUDES_DIR . "\n";
echo "ROUTE_DIR: " . ROUTE_DIR . "\n";
echo "TEMPLATES_DIR: " . TEMPLATES_DIR . "\n";
echo "\nFILES EXIST:\n";
echo "database.php: " . (file_exists(INCLUDES_DIR . '/database.php') ? 'YES' : 'NO') . "\n";
echo "router.php: " . (file_exists(INCLUDES_DIR . '/router.php') ? 'YES' : 'NO') . "\n";
echo "view.php: " . (file_exists(INCLUDES_DIR . '/view.php') ? 'YES' : 'NO') . "\n";
echo "home.php route: " . (file_exists(ROUTE_DIR . '/home.php') ? 'YES' : 'NO') . "\n";
echo "</pre>";
?>
