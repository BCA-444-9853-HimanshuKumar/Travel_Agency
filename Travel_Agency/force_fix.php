<?php
// Force fix database structure
$con = mysqli_connect("localhost", "root", "", "travel_agency");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Force Database Fix</h2>";

// Drop and recreate bookings table
echo "<p>Dropping old bookings table...</p>";
mysqli_query($con, "DROP TABLE IF EXISTS bookings");

echo "<p>Creating new bookings table with correct structure...</p>";
$create_bookings = "
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    package_id INT DEFAULT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    `date` DATE NOT NULL,
    seats INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($con, $create_bookings)) {
    echo "<p style='color: green;'>✅ Bookings table created successfully!</p>";
} else {
    echo "<p style='color: red;'>❌ Error: " . mysqli_error($con) . "</p>";
}

// Drop and recreate payments table
echo "<p>Dropping old payments table...</p>";
mysqli_query($con, "DROP TABLE IF EXISTS payments");

echo "<p>Creating payments table...</p>";
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
    echo "<p style='color: red;'>❌ Error: " . mysqli_error($con) . "</p>";
}

// Test the exact query from booking.php
echo "<h3>Testing the exact booking query...</h3>";
$test_sql = "INSERT INTO bookings (user_id, source, destination, date, seats, amount, package_id) 
             VALUES (1, 'Test Source', 'Test Destination', '2024-12-01', 2, 1500.00, NULL)";

echo "<p>Query: " . $test_sql . "</p>";

if (mysqli_query($con, $test_sql)) {
    echo "<p style='color: green;'>✅ Test query successful!</p>";
    mysqli_query($con, "DELETE FROM bookings WHERE source = 'Test Source'");
} else {
    echo "<p style='color: red;'>❌ Test query failed: " . mysqli_error($con) . "</p>";
}

// Show final table structure
echo "<h3>Final bookings table structure:</h3>";
$result = mysqli_query($con, "DESCRIBE bookings");
echo "<table border='1'><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
}
echo "</table>";

echo "<hr>";
echo "<h3>✅ Database Fix Complete!</h3>";
echo "<p><strong>Now try your booking again - it should work!</strong></p>";
echo "<p><a href='booking.php'>Go to Booking Page</a></p>";

mysqli_close($con);
?>
