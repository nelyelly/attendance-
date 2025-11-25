<?php
// test_connection.php - Test database connection

echo "<h2>Testing Database Connection</h2>";

try {
    require_once 'db_connect.php';
    
    // Attempt to connect to database
    $conn = getDBConnection();
    
    if ($conn) {
        echo "<p style='color: green; font-weight: bold;'>✓ Connection successful!</p>";
        
        // Display database info
        echo "<h3>Database Information:</h3>";
        echo "<ul>";
        echo "<li>Database Host: " . DB_HOST . "</li>";
        echo "<li>Database Name: " . DB_NAME . "</li>";
        echo "<li>PDO Driver: " . $conn->getAttribute(PDO::ATTR_DRIVER_NAME) . "</li>";
        echo "<li>Server Version: " . $conn->getAttribute(PDO::ATTR_SERVER_VERSION) . "</li>";
        echo "</ul>";
    }
    
    // Close connection
    $conn = null;
    
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>✗ Connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Check your config.php settings and ensure MySQL is running.</p>";
}

echo "<hr>";
echo "<p><a href='javascript:history.back()'>Go Back</a></p>";
?>