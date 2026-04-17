<?php 
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';

if(isset($_POST['login'])) {
    $login_input = mysqli_real_escape_string($con, $_POST['login_input']); // Can be email or username
    $password = $_POST['password'];
    
    // Debug: Show what we're trying to login with (remove in production)
    // error_log("Trying to login with: " . $login_input);
    
    // Check if input is email or username
    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM users WHERE email='$login_input' AND status='active'";
    } else {
        $query = "SELECT * FROM users WHERE username='$login_input' AND status='active'";
    }
    
    $result = mysqli_query($con, $query);
    
    if(!$result) {
        $error = "Database error: " . mysqli_error($con);
    } elseif(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username/email or password";
        }
    } else {
        $error = "Invalid username/email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Travel Agency</title>
  <link rel="stylesheet" href="css/style.css">
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
          <?php if (isLoggedIn()): ?>
            <li><a href="booking.php">Booking</a></li>
            <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
          <?php else: ?>
            <li><a href="login.php" class="active">Login</a></li>
            <li><a href="register.php">Register</a></li>
          <?php endif; ?>
          <li><a href="admin/login.php">Admin</a></li>
        </ul>
      </nav>
    </header>

    <!-- Login Section -->
    <section class="auth-section">
      <a href="index.php" class="home-btn">🏠 Home</a>
      
      <div class="auth-container">
        <div class="auth-card">
          <h2>Welcome Back</h2>
          <p>Login to your account to continue your journey</p>
          
          <?php if(isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
          <?php endif; ?>
          
          <form method="post" class="auth-form" id="loginForm">
            <div class="form-group">
              <label for="login_input">Username or Email</label>
              <input type="text" id="login_input" name="login_input" placeholder="Enter username or email" required>
              <span class="input-icon">📧</span>
            </div>
            
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Enter your password" required>
              <span class="input-icon">🔒</span>
              <button type="button" class="password-toggle" onclick="togglePassword()">👁️</button>
            </div>
            
            <div class="form-options">
              <label class="checkbox">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
              </label>
              <a href="#" class="forgot-link" onclick="showForgotPassword()">Forgot password?</a>
            </div>
            
            <button type="submit" name="login" class="auth-btn" id="loginBtn">Login</button>
          </form>
          
          <!-- Social Login Options -->
          <div class="social-login">
            <div class="social-divider">
              <span>OR</span>
            </div>
            <div class="social-buttons">
              <a href="#" class="social-btn google" onclick="socialLogin('google')">
                <span>🌐</span>
                <span>Google</span>
              </a>
              <a href="#" class="social-btn facebook" onclick="socialLogin('facebook')">
                <span>📘</span>
                <span>Facebook</span>
              </a>
              <a href="#" class="social-btn twitter" onclick="socialLogin('twitter')">
                <span>🐦</span>
                <span>Twitter</span>
              </a>
            </div>
          </div>
          
          <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      &copy; <?php echo date("Y"); ?> Travel Agency. All rights reserved.
    </footer>
  </div>
</body>
</html>
