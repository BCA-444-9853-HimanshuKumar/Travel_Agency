<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "travel_agency";

// Create connection
$con = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($con, "utf8");

// Create users table if it doesn't exist
$create_table_query = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('active', 'blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($con, $create_table_query)) {
    echo "Error creating users table: " . mysqli_error($con);
}

// Create bookings table if it doesn't exist
$create_bookings_table = "
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    package_id INT NOT NULL,
    booking_date DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (!mysqli_query($con, $create_bookings_table)) {
    echo "Error creating bookings table: " . mysqli_error($con);
}

// Create packages table if it doesn't exist (sample data)
$create_packages_table = "
CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration VARCHAR(50),
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($con, $create_packages_table)) {
    echo "Error creating packages table: " . mysqli_error($con);
}

// Insert sample packages if table is empty
$check_packages = "SELECT COUNT(*) as count FROM packages";
$result = mysqli_query($con, $check_packages);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $sample_packages = [
        ["Paris Getaway", "Experience the city of love with our 3-day Paris package including Eiffel Tower, Louvre Museum, and Seine River cruise.", 1299.99, "3 Days 2 Nights", "paris.jpg"],
        ["Bali Adventure", "Discover the tropical paradise of Bali with beaches, temples, and rice terraces. 5 days of pure bliss.", 899.99, "5 Days 4 Nights", "bali.jpg"],
        ["Dubai Luxury", "Live the high life in Dubai with Burj Khalifa, desert safari, and luxury shopping experiences.", 1599.99, "4 Days 3 Nights", "dubai.jpg"],
        ["Tokyo Explorer", "Immerse yourself in Japanese culture with temples, modern technology, and amazing cuisine.", 1199.99, "6 Days 5 Nights", "tokyo.jpg"],
        ["Maldives Paradise", "Relax in overwater bungalows and enjoy pristine beaches and crystal-clear waters.", 1999.99, "5 Days 4 Nights", "maldives.jpg"]
    ];
    
    foreach ($sample_packages as $package) {
        $insert_package = "INSERT INTO packages (name, description, price, duration, image) VALUES ('$package[0]', '$package[1]', $package[2], '$package[3]', '$package[4]')";
        mysqli_query($con, $insert_package);
    }
}
?>
