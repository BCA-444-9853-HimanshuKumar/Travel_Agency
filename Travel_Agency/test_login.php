<?php
require_once 'config/database.php';

echo "<h2>Database Connection Test</h2>";
if ($con) {
    echo "✅ Database connected successfully<br>";
} else {
    echo "❌ Database connection failed<br>";
}

echo "<h2>Users Table Test</h2>";
$result = mysqli_query($con, "SELECT * FROM users");
if ($result) {
    $user_count = mysqli_num_rows($result);
    echo "✅ Found $user_count users in database<br>";
    
    if ($user_count > 0) {
        echo "<h3>Existing Users:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Full Name</th><th>Status</th></tr>";
        while ($user = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['full_name']) . "</td>";
            echo "<td>" . $user['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "❌ Error querying users table: " . mysqli_error($con) . "<br>";
}

echo "<h2>Create Test User (if needed)</h2>";
$test_password = password_hash('test123', PASSWORD_DEFAULT);
$insert_query = "INSERT IGNORE INTO users (full_name, username, email, phone, password, role, status) VALUES ('Test User', 'testuser', 'test@example.com', '1234567890', '$test_password', 'user', 'active')";
if (mysqli_query($con, $insert_query)) {
    echo "✅ Test user created or already exists<br>";
    echo "Username: testuser<br>";
    echo "Email: test@example.com<br>";
    echo "Password: test123<br>";
} else {
    echo "❌ Error creating test user: " . mysqli_error($con) . "<br>";
}

echo "<br><a href='login.php'>Go to Login Page</a>";
?>
