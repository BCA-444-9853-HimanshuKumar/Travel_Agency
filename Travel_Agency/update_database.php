<?php
// Database update script
$con = mysqli_connect("localhost", "root", "", "travel_agency");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Updating Database Structure...</h2>";

// Drop existing bookings table if it has wrong structure
echo "<p>Checking bookings table structure...</p>";
$check_bookings = "DESCRIBE bookings";
$result = mysqli_query($con, $check_bookings);
$columns = [];
while ($row = mysqli_fetch_assoc($result)) {
    $columns[] = $row['Field'];
}

// If required columns don't exist, recreate the table
$required_columns = ['user_id', 'package_id', 'source', 'destination', 'date', 'seats', 'amount'];
$missing_columns = [];
foreach ($required_columns as $col) {
    if (!in_array($col, $columns)) {
        $missing_columns[] = $col;
    }
}

if (!empty($missing_columns)) {
    echo "<p style='color: orange;'>Missing columns: " . implode(', ', $missing_columns) . "</p>";
    echo "<p>Dropping and recreating bookings table...</p>";
    
    mysqli_query($con, "DROP TABLE IF EXISTS bookings");
    
    $create_bookings_table = "
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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
    )";
    
    if (mysqli_query($con, $create_bookings_table)) {
        echo "<p style='color: green;'>✅ Bookings table recreated successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating bookings table: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Bookings table structure is correct!</p>";
}

// Create payments table if it doesn't exist
echo "<p>Checking payments table...</p>";
$check_payments = "SHOW TABLES LIKE 'payments'";
$result = mysqli_query($con, $check_payments);

if (mysqli_num_rows($result) == 0) {
    echo "<p>Creating payments table...</p>";
    
    $create_payments_table = "
    CREATE TABLE payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id INT NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        status ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending',
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
    )";
    
    if (mysqli_query($con, $create_payments_table)) {
        echo "<p style='color: green;'>✅ Payments table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating payments table: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Payments table already exists!</p>";
}

echo "<h3>Database Update Complete!</h3>";
echo "<p><a href='booking.php'>Go to Booking Page</a></p>";
echo "<p><a href='index.php'>Go to Home</a></p>";

mysqli_close($con);
?>
