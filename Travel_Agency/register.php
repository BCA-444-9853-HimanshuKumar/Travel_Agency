<?php 
session_start();
require_once 'config/database.php';

if(isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirm_password = $_POST['confirm_password'];
    
    // Check if passwords match
    if($_POST['password'] != $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email='$email'";
        $check_result = mysqli_query($con, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            $error = "Email already exists";
        } else {
            // Check if username already exists
            $check_username = "SELECT * FROM users WHERE username='$username'";
            $username_result = mysqli_query($con, $check_username);
            
            if(mysqli_num_rows($username_result) > 0) {
                $error = "Username already exists";
            } else {
                // Insert new user
                $insert_query = "INSERT INTO users (full_name, username, email, phone, password, role, status) VALUES ('$full_name', '$username', '$email', '$phone', '$password', 'user', 'active')";
                if(mysqli_query($con, $insert_query)) {
                    $_SESSION['user'] = $email;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'user';
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Travel Agency</title>
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
          <li><a href="booking.php">Booking</a></li>
          <li><a href="admin/login.php">Admin</a></li>
        </ul>
      </nav>
    </header>

    <!-- Register Section -->
    <section class="auth-section">
      <div class="auth-container">
        <div class="auth-card">
          <h2>Create Account</h2>
          <p>Join us and start your dream journey today</p>
          
          <?php if(isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
          <?php endif; ?>
          
          <form method="post" class="auth-form">
            <div class="form-group">
              <label for="full_name">Full Name</label>
              <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            </div>
            
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" placeholder="Choose a username" required>
            </div>
            
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
            
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>
            
            <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            
            <div class="form-options">
              <label class="checkbox">
                <input type="checkbox" name="terms" required>
                <span>I agree to the Terms & Conditions</span>
              </label>
            </div>
            
            <button type="submit" name="register" class="auth-btn">Create Account</button>
          </form>
          
          <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
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
