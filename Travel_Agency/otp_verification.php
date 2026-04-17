<?php 
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';
require_once 'config/otp_system.php';

// Check if user is coming from login with OTP
if (!isset($_SESSION['otp_user_id']) || !isset($_SESSION['otp_phone'])) {
    header("Location: login.php");
    exit();
}

// Initialize OTP system
$otpSystem = new OTPSystem();

// Handle OTP verification
if(isset($_POST['verify_otp'])) {
    $otp_code = $_POST['otp_code'];
    $phone_number = $_SESSION['otp_phone'];
    
    $result = $otpSystem->verifyOTP($phone_number, $otp_code);
    
    if ($result['success']) {
        // OTP verified successfully - complete login
        $user_id = $_SESSION['otp_user_id'];
        
        // Get user details
        $query = "SELECT * FROM users WHERE id = '$user_id'";
        $result = mysqli_query($con, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Set session variables
            $_SESSION['user'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['otp_verified'] = true;
            
            // Clear OTP session variables
            unset($_SESSION['otp_user_id']);
            unset($_SESSION['otp_phone']);
            
            header("Location: index.php");
            exit();
        }
    } else {
        $error = $result['message'];
    }
}

// Handle OTP resend
if(isset($_POST['resend_otp'])) {
    $phone_number = $_SESSION['otp_phone'];
    
    if ($otpSystem->canResendOTP($phone_number)) {
        $otpCode = $otpSystem->generateOTP($phone_number);
        if ($otpCode) {
            if ($otpSystem->sendOTP($phone_number, $otpCode)) {
                $success = "New OTP sent to your phone number.";
            } else {
                $error = "Failed to send OTP. Please try again.";
            }
        } else {
            $error = "Failed to generate OTP. Please try again.";
        }
    } else {
        $error = "Please wait before requesting another OTP.";
    }
}

// Get remaining time for current OTP
$remainingTime = $otpSystem->getRemainingTime($_SESSION['otp_phone']);
$phoneNumber = $_SESSION['otp_phone'];
// Mask phone number for display
$maskedPhone = substr($phoneNumber, 0, 2) . '*****' . substr($phoneNumber, -3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>OTP Verification - Travel Agency</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .otp-container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .otp-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .otp-header h2 {
      color: #007bff;
      margin-bottom: 10px;
    }
    
    .otp-info {
      background: #e7f3ff;
      border: 1px solid #b3d9ff;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 25px;
      text-align: center;
      font-size: 14px;
      color: #004085;
    }
    
    .otp-inputs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      justify-content: center;
    }
    
    .otp-input {
      width: 50px;
      height: 50px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      border: 2px solid #ddd;
      border-radius: 8px;
      transition: border-color 0.3s;
    }
    
    .otp-input:focus {
      border-color: #007bff;
      outline: none;
    }
    
    .otp-input.filled {
      border-color: #28a745;
    }
    
    .otp-actions {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }
    
    .verify-btn {
      background: linear-gradient(135deg, #007bff, #0056b3);
      flex: 1;
    }
    
    .resend-btn {
      background: linear-gradient(135deg, #6c757d, #5a6268);
      flex: 1;
    }
    
    .timer {
      text-align: center;
      margin-bottom: 20px;
      font-size: 14px;
      color: #666;
    }
    
    .timer.warning {
      color: #dc3545;
      font-weight: bold;
    }
    
    .back-link {
      text-align: center;
      margin-top: 20px;
    }
    
    .back-link a {
      color: #007bff;
      text-decoration: none;
      font-size: 14px;
    }
    
    .back-link a:hover {
      text-decoration: underline;
    }
    
    @media (max-width: 480px) {
      .otp-inputs {
        gap: 5px;
      }
      
      .otp-input {
        width: 40px;
        height: 40px;
        font-size: 18px;
      }
      
      .otp-actions {
        flex-direction: column;
      }
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
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
          <li><a href="admin/login.php">Admin</a></li>
        </ul>
      </nav>
    </header>

    <!-- OTP Verification Section -->
    <section class="auth-section">
      <a href="login.php" class="home-btn">n Back to Login</a>
      
      <div class="auth-container">
        <div class="auth-card otp-container">
          <div class="otp-header">
            <h2>Verify Your Phone</h2>
            <p>We've sent a 6-digit code to your phone</p>
          </div>
          
          <div class="otp-info">
            <strong>OTP sent to:</strong><br>
            <?php echo $maskedPhone; ?>
          </div>
          
          <?php if(isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
          <?php endif; ?>
          
          <?php if(isset($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
          <?php endif; ?>
          
          <div class="timer" id="otpTimer">
            <?php if ($remainingTime > 0): ?>
              <span id="countdown"><?php echo $remainingTime; ?></span> seconds remaining
            <?php else: ?>
              <span class="warning">OTP has expired. Please request a new one.</span>
            <?php endif; ?>
          </div>
          
          <form method="post" class="auth-form" id="otpForm">
            <div class="otp-inputs">
              <input type="text" name="otp1" maxlength="1" class="otp-input" required>
              <input type="text" name="otp2" maxlength="1" class="otp-input" required>
              <input type="text" name="otp3" maxlength="1" class="otp-input" required>
              <input type="text" name="otp4" maxlength="1" class="otp-input" required>
              <input type="text" name="otp5" maxlength="1" class="otp-input" required>
              <input type="text" name="otp6" maxlength="1" class="otp-input" required>
              <input type="hidden" name="otp_code" id="otpCode">
            </div>
            
            <div class="otp-actions">
              <button type="submit" name="verify_otp" class="auth-btn verify-btn">Verify OTP</button>
              <button type="submit" name="resend_otp" class="auth-btn resend-btn">Resend OTP</button>
            </div>
          </form>
          
          <div class="back-link">
            <a href="login.php">n Back to Login</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      &copy; <?php echo date("Y"); ?> Travel Agency. All rights reserved.
    </footer>
  </div>

  <script>
    // OTP Input Management
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpCodeInput = document.getElementById('otpCode');
    
    otpInputs.forEach((input, index) => {
      input.addEventListener('input', function(e) {
        const value = e.target.value;
        
        if (value.length === 1) {
          input.classList.add('filled');
          // Move to next input
          if (index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
          }
        } else {
          input.classList.remove('filled');
        }
        
        // Update hidden OTP code
        updateOTPCode();
      });
      
      input.addEventListener('keydown', function(e) {
        // Handle backspace
        if (e.key === 'Backspace' && e.target.value === '') {
          if (index > 0) {
            otpInputs[index - 1].focus();
            otpInputs[index - 1].value = '';
            otpInputs[index - 1].classList.remove('filled');
          }
        }
      });
      
      // Handle paste
      input.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedData = e.clipboardData.getData('text');
        if (pastedData.length === 6 && /^\d+$/.test(pastedData)) {
          otpInputs.forEach((otpInput, i) => {
            otpInput.value = pastedData[i];
            otpInput.classList.add('filled');
          });
          updateOTPCode();
          otpInputs[5].focus();
        }
      });
    });
    
    function updateOTPCode() {
      const otpCode = Array.from(otpInputs).map(input => input.value).join('');
      otpCodeInput.value = otpCode;
    }
    
    // Countdown Timer
    let timeLeft = <?php echo $remainingTime; ?>;
    const countdownElement = document.getElementById('countdown');
    const timerElement = document.getElementById('otpTimer');
    
    if (timeLeft > 0) {
      const timer = setInterval(() => {
        timeLeft--;
        if (countdownElement) {
          countdownElement.textContent = timeLeft;
        }
        
        if (timeLeft <= 0) {
          clearInterval(timer);
          if (timerElement) {
            timerElement.innerHTML = '<span class="warning">OTP has expired. Please request a new one.</span>';
          }
        }
      }, 1000);
    }
    
    // Focus first input on page load
    window.addEventListener('load', () => {
      otpInputs[0].focus();
    });
    
    // Form validation
    document.getElementById('otpForm').addEventListener('submit', function(e) {
      const otpCode = otpCodeInput.value;
      if (otpCode.length !== 6 || !/^\d{6}$/.test(otpCode)) {
        e.preventDefault();
        alert('Please enter a valid 6-digit OTP code');
        otpInputs[0].focus();
      }
    });
  </script>
</body>
</html>
