<?php
require_once 'config/database.php';

echo "<h2>Packages Database Test</h2>";

// Check if packages table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'packages'");
if (mysqli_num_rows($table_check) > 0) {
    echo "✅ Packages table exists<br>";
} else {
    echo "❌ Packages table doesn't exist<br>";
}

// Check packages count
$result = mysqli_query($con, "SELECT * FROM packages");
if ($result) {
    $package_count = mysqli_num_rows($result);
    echo "✅ Found $package_count packages in database<br>";
    
    if ($package_count > 0) {
        echo "<h3>Available Packages:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Duration</th><th>Status</th></tr>";
        while ($package = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $package['id'] . "</td>";
            echo "<td>" . htmlspecialchars($package['name']) . "</td>";
            echo "<td>$" . number_format($package['price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($package['duration']) . "</td>";
            echo "<td>" . $package['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ No packages found. Creating sample packages...<br>";
        
        // Insert sample packages
        $sample_packages = [
            ["Paris Getaway", "Experience the city of love with our 3-day Paris package including Eiffel Tower, Louvre Museum, and Seine River cruise.", 1299.99, "3 Days 2 Nights", "paris.jpg"],
            ["Bali Adventure", "Discover the tropical paradise of Bali with beaches, temples, and rice terraces. 5 days of pure bliss.", 899.99, "5 Days 4 Nights", "bali.jpg"],
            ["Dubai Luxury", "Live the high life in Dubai with Burj Khalifa, desert safari, and luxury shopping experiences.", 1599.99, "4 Days 3 Nights", "dubai.jpg"],
            ["Tokyo Explorer", "Immerse yourself in Japanese culture with temples, modern technology, and amazing cuisine.", 1199.99, "6 Days 5 Nights", "tokyo.jpg"],
            ["Maldives Paradise", "Relax in overwater bungalows and enjoy pristine beaches and crystal-clear waters.", 1999.99, "5 Days 4 Nights", "maldives.jpg"]
        ];
        
        foreach ($sample_packages as $package) {
            $insert_package = "INSERT INTO packages (name, description, price, duration, image, status) VALUES ('$package[0]', '$package[1]', $package[2], '$package[3]', '$package[4]', 'active')";
            if (mysqli_query($con, $insert_package)) {
                echo "✅ Created package: " . $package[0] . "<br>";
            }
        }
    }
} else {
    echo "❌ Error querying packages table: " . mysqli_error($con) . "<br>";
}

echo "<br><a href='packages.php'>Go to Packages Page</a>";
?>
