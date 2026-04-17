<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/auth.php';
require_once 'config/database.php';
require_once 'config/mail.php';

// ✅ Protect page (user must be logged in)
requireLogin();

// ✅ Check booking session
if (!isset($_SESSION['booking_id']) || !isset($_SESSION['amount'])) {
    header("Location: index.php");
    exit();
}

$booking_id = $_SESSION['booking_id'];
$amount = $_SESSION['amount'];

// ✅ Database connection
$con = new mysqli("localhost", "root", "", "travel_agency");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// ✅ Handle payment
if (isset($_POST['pay'])) {

    $payment_method = $_POST['payment_method'];

    // Validate input
    if (empty($payment_method)) {
        $error = "Please select payment method";
    } else {

        // ✅ Prepared statement (secure)
        $stmt = $con->prepare("INSERT INTO payments (booking_id, payment_method, amount, status) VALUES (?, ?, ?, 'Paid')");

        if (!$stmt) {
            die("Query Error: " . $con->error);
        }

        $stmt->bind_param("isi", $booking_id, $payment_method, $amount);

        if ($stmt->execute()) {

            // Get booking details for email
            $booking_query = "SELECT b.*, u.username, u.email, p.name as package_name 
                            FROM bookings b 
                            LEFT JOIN users u ON b.user_id = u.id 
                            LEFT JOIN packages p ON b.package_id = p.id 
                            WHERE b.id = $booking_id";
            $booking_result = mysqli_query($con, $booking_query);
            
            if ($booking_result && mysqli_num_rows($booking_result) > 0) {
                $booking_data = mysqli_fetch_assoc($booking_result);
                
                // Prepare booking details for email
                $bookingDetails = [
                    'booking_id' => $booking_id,
                    'package_name' => $booking_data['package_name'] ?: 'Custom Package',
                    'source' => $booking_data['source'],
                    'destination' => $booking_data['destination'],
                    'date' => $booking_data['date'],
                    'seats' => $booking_data['seats'],
                    'amount' => $amount,
                    'payment_method' => $payment_method
                ];
                
                // Send booking confirmation email
                try {
                    $mailService = createMailService();
                    $emailSent = $mailService->sendBookingConfirmationEmail(
                        $booking_data['email'], 
                        $booking_data['username'], 
                        $bookingDetails
                    );
                    
                    if (!$emailSent) {
                        error_log("Payment confirmation email not sent for booking ID: $booking_id");
                    }
                } catch (Exception $e) {
                    error_log("Email error after payment: " . $e->getMessage());
                }
            }

            // Optional: clear session after payment
            unset($_SESSION['booking_id']);
            unset($_SESSION['amount']);

            echo "<script>alert('Payment Successful! A confirmation email has been sent to your registered email address.'); window.location='success.php';</script>";
            exit();

        } else {
            $error = "Payment failed: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        body{
            font-family: Arial;
            background: linear-gradient(135deg,#667eea,#764ba2);
        }
        .box{
            width:350px;
            margin:100px auto;
            background:#fff;
            padding:25px;
            border-radius:15px;
            text-align:center;
        }
        select{
            width:100%;
            padding:10px;
            margin:10px 0;
        }
        button{
            width:100%;
            padding:10px;
            background:#667eea;
            color:#fff;
            border:none;
            border-radius:8px;
        }
        .error{
            color:red;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>💳 Payment</h2>
    <h3>Amount: ₹<?php echo $amount; ?></h3>

    <!-- Error Message -->
    <?php if(isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <select name="payment_method" required>
            <option value="">Select Payment Mode</option>
            <option value="Card">Debit/Credit Card</option>
            <option value="UPI">UPI</option>
            <option value="Cash">Cash</option>
        </select>

        <button type="submit" name="pay">Pay Now</button>
    </form>
</div>

</body>
</html>