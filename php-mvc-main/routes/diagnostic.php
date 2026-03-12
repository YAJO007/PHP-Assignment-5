<?php
/**
 * System Diagnostic Page
 */
echo "<h1>🔍 System Diagnostics</h1>";
echo "<div style='margin: 20px;'>";

// Check PHP version
echo "<h3>PHP Information</h3>";
echo "Version: <strong>" . phpversion() . "</strong><br>";
echo "MySQLi Extension: <strong>" . (extension_loaded('mysqli') ? '✓ Yes' : '✗ No') . "</strong><br>";

// Check MySQL connection
echo "<h3>MySQL Connection</h3>";
$conn = @new mysqli('localhost', 'root', '');
if ($conn->connect_error) {
    echo "<span style='color: red;'>❌ Cannot connect: " . $conn->connect_error . "</span><br>";
} else {
    echo "<span style='color: green;'>✓ Connected to MySQL</span><br>";
    
    // Check databases
    $result = $conn->query("SHOW DATABASES");
    $databases = [];
    while ($row = $result->fetch_row()) {
        $databases[] = $row[0];
    }
    
    if (in_array('enrollment', $databases)) {
        echo "<span style='color: green;'>✓ Database 'enrollment' exists</span><br>";
        
        $conn->select_db('enrollment');
        
        // Check tables
        $result = $conn->query("SHOW TABLES");
        $tables = [];
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
        
        echo "<strong>Tables:</strong>";
        echo in_array('users', $tables) ? " ✓ users" : " ❌ users";
        echo in_array('courses', $tables) ? " ✓ courses" : " ❌ courses";
        echo in_array('enrollments', $tables) ? " ✓ enrollments" : " ❌ enrollments";
        echo "<br>";
        
        // Count data
        $userCount = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
        $courseCount = $conn->query("SELECT COUNT(*) FROM courses")->fetch_row()[0];
        
        echo "<strong>Data:</strong> " . $userCount . " users, " . $courseCount . " courses<br>";
    } else {
        echo "<span style='color: orange;'>⚠ Database 'enrollment' not found</span><br>";
        echo "<span style='color: blue;'>Go to <a href='/setup'>/setup</a> to create it</span><br>";
    }
    
    $conn->close();
}

// Check file structure
echo "<h3>File Structure</h3>";
$files = [
    'includes/database.php' => __DIR__ . '/../includes/database.php',
    'includes/router.php' => __DIR__ . '/../includes/router.php',
    'includes/view.php' => __DIR__ . '/../includes/view.php',
    'routes/home.php' => __DIR__ . '/../routes/home.php',
    'routes/login.php' => __DIR__ . '/../routes/login.php',
    'routes/courses.php' => __DIR__ . '/../routes/courses.php',
    'templates/home.php' => __DIR__ . '/../templates/home.php',
    'templates/login.php' => __DIR__ . '/../templates/login.php',
    'templates/courses.php' => __DIR__ . '/../templates/courses.php',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path) ? '✓' : '❌';
    $color = file_exists($path) ? 'green' : 'red';
    echo "<span style='color: $color;'>$exists $name</span><br>";
}

echo "</div>";
echo "<p style='margin: 20px;'><a href='/setup'>Setup Database</a> | <a href='/'>Home</a></p>";

echo "<style>";
echo "body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }";
echo "h1, h3 { color: #333; }";
echo "strong { font-weight: bold; }";
echo "a { color: #0066cc; text-decoration: none; }";
echo "a:hover { text-decoration: underline; }";
echo "</style>";
?>
