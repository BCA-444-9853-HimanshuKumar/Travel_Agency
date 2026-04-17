<?php
// Test database connection
echo "<h2>Testing Database Connection...</h2>";

// Include database configuration
require_once 'config/database.php';

if ($con) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    echo "<p>Connected to database: " . $database . "</p>";
    echo "<p>MySQL Server Info: " . mysqli_get_server_info($con) . "</p>";
    
    // Test query
    $result = mysqli_query($con, "SHOW TABLES");
    $tables = [];
    while ($row = mysqli_fetch_array($result)) {
        $tables[] = $row[0];
    }
    
    echo "<h3>Tables in database:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . $table . "</li>";
    }
    echo "</ul>";
    
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
    
    echo "<h3>Troubleshooting Steps:</h3>";
    echo "<ol>";
    echo "<li>Make sure XAMPP MySQL service is running</li>";
    echo "<li>Check if MySQL root user has a password set</li>";
    echo "<li>Try resetting MySQL root password in XAMPP</li>";
    echo "<li>Verify database name exists</li>";
    echo "</ol>";
}
?>
