<?php
session_start();

$con = new mysqli("localhost", "root", "", "travel_agency");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $con->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password' LIMIT 1";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - Travel Agency</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #74ebd5, #ACB6E5);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.2);
      width: 350px;
      text-align: center;
      animation: fadeIn 1s ease-in-out;
    }

    .login-box h2 {
      margin-bottom: 20px;
      color: #007bff;
    }

    .login-box p {
      color: red;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .login-box input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .login-box input:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 6px rgba(0,123,255,0.4);
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      border: none;
      color: #fff;
      font-size: 16px;
      border-radius: 25px;
      cursor: pointer;
      margin-top: 15px;
      transition: background 0.3s, transform 0.2s;
    }

    .login-box button:hover {
      background: #0056b3;
      transform: scale(1.05);
    }

    .home-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      padding: 10px 20px;
      background: #fff;
      color: #007bff;
      text-decoration: none;
      border-radius: 8px;
      border: 2px solid #007bff;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .home-btn:hover {
      background: #007bff;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }
  </style>
</head>
<body>
  <a href="../index.php" class="home-btn">🏠 Home</a>
  <div class="login-box">
    <h2>🔑 Admin Login</h2>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Enter Username" required>
      <input type="password" name="password" placeholder="Enter Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
