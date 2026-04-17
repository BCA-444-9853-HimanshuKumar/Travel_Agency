<?php
// OTP System Test Suite
require_once 'config/database.php';
require_once 'config/otp_system.php';

echo "<h2>OTP System Test Suite</h2>";

// Initialize OTP system
$otpSystem = new OTPSystem();

// Test 1: OTP Generation
echo "<h3>Test 1: OTP Generation</h3>";
$testPhone = '9876543210';
$otpCode = $otpSystem->generateOTP($testPhone);

if ($otpCode) {
    echo "<p style='color: green;'>OTP Generated: $otpCode</p>";
    echo "<p>Phone Number: $testPhone</p>";
} else {
    echo "<p style='color: red;'>OTP Generation Failed</p>";
}

// Test 2: OTP Verification (Correct OTP)
echo "<h3>Test 2: OTP Verification (Correct OTP)</h3>";
$verification = $otpSystem->verifyOTP($testPhone, $otpCode);

if ($verification['success']) {
    echo "<p style='color: green;'>OTP Verification: {$verification['message']}</p>";
} else {
    echo "<p style='color: red;'>OTP Verification Failed: {$verification['message']}</p>";
}

// Test 3: OTP Generation for New Test
echo "<h3>Test 3: New OTP Generation</h3>";
$newOtp = $otpSystem->generateOTP('9876543211');
if ($newOtp) {
    echo "<p style='color: green;'>New OTP Generated: $newOtp</p>";
}

// Test 4: OTP Verification (Wrong OTP)
echo "<h3>Test 4: OTP Verification (Wrong OTP)</h3>";
$wrongVerification = $otpSystem->verifyOTP('9876543211', '000000');
if (!$wrongVerification['success']) {
    echo "<p style='color: green;'>Wrong OTP Rejected: {$wrongVerification['message']}</p>";
} else {
    echo "<p style='color: red;'>Wrong OTP Accepted (Security Issue!)</p>";
}

// Test 5: OTP Resend Cooldown
echo "<h3>Test 5: OTP Resend Cooldown</h3>";
$canResend = $otpSystem->canResendOTP('9876543211');
if ($canResend) {
    echo "<p style='color: green;'>Can Resend OTP</p>";
} else {
    echo "<p style='color: orange;'>Cannot Resend OTP (Cooldown Active)</p>";
}

// Test 6: Phone Number Cleaning
echo "<h3>Test 6: Phone Number Cleaning</h3>";
$reflection = new ReflectionClass($otpSystem);
$method = $reflection->getMethod('cleanPhoneNumber');
$method->setAccessible(true);

$testNumbers = [
    '9876543210' => '919876543210',
    '09876543210' => '919876543210',
    '+919876543210' => '919876543210',
    '1234567890' => '911234567890'
];

foreach ($testNumbers as $input => $expected) {
    $result = $method->invoke($otpSystem, $input);
    if ($result === $expected) {
        echo "<p style='color: green;'>Phone '$input' -> '$result' (Correct)</p>";
    } else {
        echo "<p style='color: red;'>Phone '$input' -> '$result' (Expected: '$expected')</p>";
    }
}

// Test 7: Remaining Time
echo "<h3>Test 7: Remaining Time Calculation</h3>";
$remainingTime = $otpSystem->getRemainingTime('9876543211');
echo "<p>Remaining Time: $remainingTime seconds</p>";

// Test 8: Database Cleanup
echo "<h3>Test 8: Database Cleanup</h3>";
$cleanupResult = $otpSystem->cleanupExpiredOTPs();
echo "<p>Database cleanup completed</p>";

// Test 9: SMS Sending (Development Mode)
echo "<h3>Test 9: SMS Sending (Development Mode)</h3>";
define('DEV_MODE', true);
$smsResult = $otpSystem->sendOTP('9876543212', '123456');
if ($smsResult) {
    echo "<p style='color: green;'>SMS (Development) Sent Successfully</p>";
    echo "<p>Check error logs for OTP code</p>";
} else {
    echo "<p style='color: red;'>SMS Sending Failed</p>";
}

// Test 10: Multiple Attempts
echo "<h3>Test 10: Multiple Attempts Test</h3>";
$testOtp2 = $otpSystem->generateOTP('9876543213');
echo "<p>Test OTP: $testOtp2</p>";

// Try wrong OTPs
for ($i = 1; $i <= 4; $i++) {
    $result = $otpSystem->verifyOTP('9876543213', '999999');
    echo "<p>Attempt $i: " . ($result['success'] ? 'Success' : 'Failed') . " - {$result['message']}</p>";
    if (!$result['success'] && strpos($result['message'], 'Maximum attempts') !== false) {
        echo "<p style='color: green;'>Maximum attempts enforced correctly</p>";
        break;
    }
}

echo "<h3>Test Summary</h3>";
echo "<p>All OTP system tests completed. Check results above for any issues.</p>";
echo "<p><a href='login.php'>Test Login with OTP</a></p>";
echo "<p><a href='index.php'>Go to Home</a></p>";
?>
