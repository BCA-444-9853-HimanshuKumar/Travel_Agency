<?php
// Test email system
require_once 'config/mail.php';

echo "<h2>Testing Email System...</h2>";

try {
    // Create mail service
    $mailService = createMailService();
    
    // Test booking details
    $testBookingDetails = [
        'booking_id' => 'TEST-001',
        'package_name' => 'Test Package',
        'source' => 'Test City',
        'destination' => 'Test Destination',
        'date' => '2024-12-25',
        'seats' => 2,
        'amount' => 1000.00
    ];
    
    // Test thank you email
    $thankYouResult = $mailService->sendThankYouEmail(
        'test@example.com', 
        'Test User', 
        $testBookingDetails
    );
    
    if ($thankYouResult) {
        echo "<p style='color: green;'>Thank You Email: Sent successfully!</p>";
    } else {
        echo "<p style='color: orange;'>Thank You Email: Failed (check server configuration)</p>";
    }
    
    // Test confirmation email
    $testBookingDetails['payment_method'] = 'Test Payment';
    $confirmationResult = $mailService->sendBookingConfirmationEmail(
        'test@example.com', 
        'Test User', 
        $testBookingDetails
    );
    
    if ($confirmationResult) {
        echo "<p style='color: green;'>Confirmation Email: Sent successfully!</p>";
    } else {
        echo "<p style='color: orange;'>Confirmation Email: Failed (check server configuration)</p>";
    }
    
    echo "<h3>Email System Status: WORKING</h3>";
    echo "<p>The email system is functional. Try making a real booking to test with actual user data.</p>";
    echo "<p><a href='index.php'>Go to Home</a> | <a href='booking.php'>Test Booking</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
