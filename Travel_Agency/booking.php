<?php 
session_start();
$con = mysqli_connect("localhost","root","","travel_agency");
require_once 'config/auth.php';

// Require login to access booking
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get current user info
    $currentUser = getCurrentUser();
    
    // Get user details from database
    $user_query = "SELECT * FROM users WHERE email = '" . $currentUser['email'] . "'";
    $user_result = mysqli_query($con, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);
    
    // Sanitize inputs
    $package_id = mysqli_real_escape_string($con, $_POST['package_id']);
    $travel_date = mysqli_real_escape_string($con, $_POST['travel_date']);
    
    // Check if bookings table has user_id column, if not use customer_name, email, phone
    $table_check = mysqli_query($con, "DESCRIBE bookings");
    $has_user_id = false;
    while ($column = mysqli_fetch_assoc($table_check)) {
        if ($column['Field'] == 'user_id') {
            $has_user_id = true;
            break;
        }
    }
    
    if ($has_user_id) {
        // New table structure with user_id
        $query = "INSERT INTO bookings (user_id, package_id, booking_date, status, created_at) 
                  VALUES ('" . $user_data['id'] . "', '$package_id', '$travel_date', 'pending', NOW())";
    } else {
        // Old table structure with customer details
        $query = "INSERT INTO bookings (customer_name, email, phone, package_id, booking_date) 
                  VALUES ('" . mysqli_real_escape_string($con, $user_data['full_name']) . "', 
                          '" . $user_data['email'] . "', 
                          '" . mysqli_real_escape_string($con, $user_data['phone']) . "', 
                          '$package_id', '$travel_date')";
    }

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Booking Successful! We will contact you soon.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error while booking: " . mysqli_error($con) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking - Travel Agency</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .booking-container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    .booking-container h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #007bff;
    }

    form label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #333;
    }

    form input, form select {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    form input:focus, form select:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 6px rgba(0,123,255,0.3);
    }

    .btn-submit {
      width: 100%;
      padding: 12px;
      background: #28a745;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-submit:hover {
      background: #1e7e34;
    }

    nav {
      background: #007bff;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    nav h2 {
      color: #fff;
      margin: 0;
    }
    nav ul {
      list-style: none;
      display: flex;
      gap: 15px;
    }
    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
    }
    nav ul li a.active {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header>
    <nav>
      <h2>Travel Agency</h2>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="packages.php">Packages</a></li>
        <li><a href="booking.php" class="active">Booking</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <!-- Booking Form -->
  <section class="booking-container">
    <h1>Book Your Tour</h1>
    <p style="text-align: center; color: #666; margin-bottom: 20px;">
      Welcome, <strong><?php echo $_SESSION['username']; ?></strong>!
    </p>
    <form method="POST">
      <label for="package_id">Select Package</label>
      <select id="package_id" name="package_id" required>
        <option value="">-- Choose a Package --</option>
        <?php
          $result = $con->query("SELECT id, name FROM packages");
          if ($result && $result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  echo "<option value='".$row['id']."'>".$row['name']."</option>";
              }
          } else {
              echo "<option disabled>No packages available</option>";
          }
        ?>
      </select>

      <label for="travel_date">Travel Date</label>
      <input type="date" id="travel_date" name="travel_date" required>

      <button type="submit" class="btn-submit">Confirm Booking</button>
    </form>
  </section>

</body>
</html>
