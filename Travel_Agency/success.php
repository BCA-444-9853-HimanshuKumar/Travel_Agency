<?php
session_start();
require_once 'config/auth.php';
requireLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful - Travel Agency</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .success-container {
            text-align: center;
            padding: 60px 20px;
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-title {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .success-message {
            color: #666;
            margin-bottom: 30px;
            font-size: 18px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            margin: 0 10px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="main-container">

    <!-- Navbar -->
    <header>
        <nav>
            <h2>Travel Agency</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="packages.php">Packages</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
            </ul>
        </nav>
    </header>

    <!-- Success Section -->
    <section class="success-container">
        <div class="success-icon">✅</div>
        <h1 class="success-title">Payment Successful!</h1>
        <p class="success-message">
            Thank you for your booking. Your payment has been processed successfully.<br>
            You will receive a confirmation email shortly with your booking details.
        </p>
        
        <div>
            <a href="index.php" class="btn">Back to Home</a>
            <a href="booking.php" class="btn btn-success">Book Another Trip</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date("Y"); ?> Travel Agency. All rights reserved.
    </footer>

</div>

</body>
</html>
