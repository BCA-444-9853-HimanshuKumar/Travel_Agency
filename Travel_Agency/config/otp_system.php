<?php
// OTP System for Phone Number Verification
class OTPSystem {
    private $con;
    private $otpLength = 6;
    private $otpExpiry = 10; // minutes
    
    public function __construct() {
        global $con;
        $this->con = $con;
        $this->createOTPTable();
    }
    
    private function createOTPTable() {
        $sql = "CREATE TABLE IF NOT EXISTS otp_verifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            phone_number VARCHAR(20) NOT NULL,
            otp_code VARCHAR(10) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NOT NULL,
            is_used BOOLEAN DEFAULT FALSE,
            attempts INT DEFAULT 0,
            INDEX idx_phone (phone_number),
            INDEX idx_expires (expires_at)
        )";
        
        if (!mysqli_query($this->con, $sql)) {
            error_log("Error creating OTP table: " . mysqli_error($this->con));
        }
    }
    
    public function generateOTP($phoneNumber) {
        // Clean phone number
        $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
        
        // Check if there's an existing unexpired OTP
        $this->invalidatePreviousOTP($phoneNumber);
        
        // Generate new OTP
        $otpCode = $this->generateRandomOTP();
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$this->otpExpiry} minutes"));
        
        // Store OTP in database
        $sql = "INSERT INTO otp_verifications (phone_number, otp_code, expires_at) 
                VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $phoneNumber, $otpCode, $expiresAt);
        
        if (mysqli_stmt_execute($stmt)) {
            return $otpCode;
        } else {
            error_log("Error storing OTP: " . mysqli_error($this->con));
            return false;
        }
    }
    
    private function generateRandomOTP() {
        return str_pad(rand(0, pow(10, $this->otpLength) - 1), $this->otpLength, '0', STR_PAD_LEFT);
    }
    
    private function cleanPhoneNumber($phoneNumber) {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Ensure it starts with country code for India (91)
        if (strlen($phoneNumber) == 10) {
            $phoneNumber = '91' . $phoneNumber;
        } elseif (strlen($phoneNumber) == 11 && substr($phoneNumber, 0, 1) == '0') {
            $phoneNumber = '91' . substr($phoneNumber, 1);
        }
        
        return $phoneNumber;
    }
    
    private function invalidatePreviousOTP($phoneNumber) {
        $sql = "UPDATE otp_verifications SET is_used = TRUE 
                WHERE phone_number = ? AND is_used = FALSE AND expires_at > NOW()";
        
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
        mysqli_stmt_execute($stmt);
    }
    
    public function verifyOTP($phoneNumber, $otpCode) {
        $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
        
        // Update attempts
        $this->incrementAttempts($phoneNumber);
        
        $sql = "SELECT id, expires_at, attempts FROM otp_verifications 
                WHERE phone_number = ? AND otp_code = ? AND is_used = FALSE AND expires_at > NOW()";
        
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $phoneNumber, $otpCode);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check if attempts exceeded (max 3 attempts)
            if ($row['attempts'] >= 3) {
                return ['success' => false, 'message' => 'Maximum attempts exceeded. Please request a new OTP.'];
            }
            
            // Mark OTP as used
            $this->markOTPAsUsed($row['id']);
            
            return ['success' => true, 'message' => 'OTP verified successfully'];
        } else {
            return ['success' => false, 'message' => 'Invalid or expired OTP'];
        }
    }
    
    private function incrementAttempts($phoneNumber) {
        $sql = "UPDATE otp_verifications SET attempts = attempts + 1 
                WHERE phone_number = ? AND is_used = FALSE AND expires_at > NOW()";
        
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
        mysqli_stmt_execute($stmt);
    }
    
    private function markOTPAsUsed($otpId) {
        $sql = "UPDATE otp_verifications SET is_used = TRUE WHERE id = ?";
        
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $otpId);
        mysqli_stmt_execute($stmt);
    }
    
    public function sendOTP($phoneNumber, $otpCode) {
        // For development/demo, we'll use a simple SMS simulation
        // In production, integrate with real SMS API like Twilio, Fast2SMS, etc.
        
        $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
        
        // Simulate SMS sending (for demo purposes)
        $message = "Your Travel Agency OTP is: {$otpCode}. Valid for {$this->otpExpiry} minutes. Do not share this OTP.";
        
        // Log the OTP for development (remove in production)
        error_log("OTP for {$phoneNumber}: {$otpCode}");
        
        // For demo, return success. In production, integrate with SMS API
        return $this->sendSMS($phoneNumber, $message);
    }
    
    private function sendSMS($phoneNumber, $message) {
        // Development mode - just log the message
        if (defined('DEV_MODE') && DEV_MODE) {
            error_log("SMS to {$phoneNumber}: {$message}");
            return true;
        }
        
        // Production SMS integration (example with Fast2SMS)
        $apiKey = 'YOUR_SMS_API_KEY'; // Get from SMS provider
        $senderId = 'TRAVEL';
        
        $url = "https://www.fast2sms.com/dev/bulkV2";
        $postData = [
            'authorization' => $apiKey,
            'sender_id' => $senderId,
            'message' => $message,
            'numbers' => $phoneNumber,
            'route' => 'v3',
            'language' => 'english'
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData)
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
    }
    
    public function canResendOTP($phoneNumber) {
        $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
        
        // Check if there's an OTP sent in the last 2 minutes
        $sql = "SELECT created_at FROM otp_verifications 
                WHERE phone_number = ? AND created_at > DATE_SUB(NOW(), INTERVAL 2 MINUTE) 
                ORDER BY created_at DESC LIMIT 1";
        
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_num_rows($result) === 0;
    }
    
    public function getRemainingTime($phoneNumber) {
        $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
        
        $sql = "SELECT expires_at FROM otp_verifications 
                WHERE phone_number = ? AND is_used = FALSE AND expires_at > NOW() 
                ORDER BY created_at DESC LIMIT 1";
        
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $expiresAt = strtotime($row['expires_at']);
            $now = time();
            $remaining = $expiresAt - $now;
            
            return max(0, $remaining);
        }
        
        return 0;
    }
    
    public function cleanupExpiredOTPs() {
        $sql = "DELETE FROM otp_verifications WHERE expires_at < NOW() OR is_used = TRUE";
        mysqli_query($this->con, $sql);
    }
}
?>
