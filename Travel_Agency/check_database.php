<?php
// Check database structure and fix it
$con = mysqli_connect("localhost", "root", "", "travel_agency");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Database Structure Check</h2>";

// Check if bookings table exists
echo "<h3>Checking bookings table...</h3>";
$check_table = "SHOW TABLES LIKE 'bookings'";
$result = mysqli_query($con, $check_table);

if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: red;'>❌ Bookings table does not exist!</p>";
    
    // Create bookings table
    $create_bookings = "
    CREATE TABLE bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        package_id INT DEFAULT NULL,
        source VARCHAR(100) NOT NULL,
        destination VARCHAR(100) NOT NULL,
        date DATE NOT NULL,
        seats INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($con, $create_bookings)) {
        echo "<p style='color: green;'>✅ Bookings table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating bookings table: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Bookings table exists</p>";
    
    // Check columns
    $describe = "DESCRIBE bookings";
    $result = mysqli_query($con, $describe);
    echo "<table border='1'><tr><th>Column</th><th>Type</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
    }
    echo "</table>";
    
    // Check if user_id column exists
    $check_column = "SHOW COLUMNS FROM bookings LIKE 'user_id'";
    $result = mysqli_query($con, $check_column);
    if (mysqli_num_rows($result) == 0) {
        echo "<p style='color: red;'>❌ user_id column missing! Adding it...</p>";
        mysqli_query($con, "ALTER TABLE bookings ADD COLUMN user_id INT NOT NULL FIRST");
        echo "<p style='color: green;'>✅ user_id column added</p>";
    }
}

// Check payments table
echo "<h3>Checking payments table...</h3>";
$check_payments = "SHOW TABLES LIKE 'payments'";
$result = mysqli_query($con, $check_payments);

if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: red;'>❌ Payments table does not exist!</p>";
    
    // Create payments table
    $create_payments = "
    CREATE TABLE payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id INT NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        status ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending',
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($con, $create_payments)) {
        echo "<p style='color: green;'>✅ Payments table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating payments table: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Payments table exists</p>";
}

// Test booking insertion
echo "<h3>Testing booking insertion...</h3>";
$test_query = "INSERT INTO bookings (user_id, source, destination, date, seats, amount) VALUES (1, 'Test City', 'Test Destination', '2024-12-01', 2, 1000)";
if (mysqli_query($con, $test_query)) {
    echo "<p style='color: green;'>✅ Test booking successful!</p>";
    // Remove test record
    mysqli_query($con, "DELETE FROM bookings WHERE source = 'Test City'");
} else {
    echo "<p style='color: red;'>❌ Test booking failed: " . mysqli_error($con) . "</p>";
}

echo "<hr>";
echo "<p><a href='booking.php'>Test Booking Page</a></p>";

mysqli_close($con);
?>
