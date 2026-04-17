<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Travel Agency</title>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  display: flex;
  background: #f1f5f9;
}

/* Sidebar */
.sidebar {
  width: 240px;
  height: 100vh;
  background: linear-gradient(180deg, #0f2027, #203a43, #2c5364);
  position: fixed;
  left: 0;
  top: 0;
  padding: 20px;
  color: #fff;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 22px;
}

.sidebar a {
  display: block;
  padding: 12px;
  margin: 8px 0;
  color: #fff;
  text-decoration: none;
  border-radius: 8px;
  transition: 0.3s;
}

.sidebar a:hover {
  background: rgba(255,255,255,0.15);
  padding-left: 18px;
}

/* Main Content */
.main {
  margin-left: 240px;
  width: 100%;
  padding: 20px;
}

/* Header */
.header {
  background: #fff;
  padding: 15px 20px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.header h1 {
  font-size: 22px;
  color: #2c5364;
}

/* Cards */
.cards {
  margin-top: 25px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.card {
  background: #fff;
  padding: 25px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  transition: 0.3s;
  cursor: pointer;
}

.card:hover {
  transform: translateY(-8px) scale(1.02);
}

.card h2 {
  color: #0f2027;
  margin-bottom: 10px;
}

.card p {
  font-size: 14px;
  color: #666;
}

/* Logout Button */
.logout-btn {
  background: #ff4d4d;
  color: #fff;
  padding: 8px 15px;
  border-radius: 20px;
  text-decoration: none;
  transition: 0.3s;
}

.logout-btn:hover {
  background: #cc0000;
}

/* Mobile Responsive */
@media(max-width: 768px) {
  .sidebar {
    width: 70px;
    padding: 10px;
  }

  .sidebar h2 {
    display: none;
  }

  .sidebar a {
    text-align: center;
    font-size: 12px;
    padding: 10px 5px;
  }

  .main {
    margin-left: 70px;
  }

  .header h1 {
    font-size: 16px;
  }
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Admin</h2>
  <a href="dashboard.php"> Dashboard</a>
  <a href="add_package.php">Add Package</a>
  <a href="view_packages.php"> Packages</a>
  <a href="view_bookings.php">Bookings</a>
  <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="main">

  <!-- Header -->
  <div class="header">
    <h1>Welcome, <?php echo $_SESSION['admin']; ?> </h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <!-- Cards -->
  <div class="cards">

    <div class="card" onclick="location.href='add_package.php'">
      <h2> Add Package</h2>
      <p>Create new travel packages</p>
    </div>

    <div class="card" onclick="location.href='view_packages.php'">
      <h2> Manage Packages</h2>
      <p>Edit or delete packages</p>
    </div>

    <div class="card" onclick="location.href='view_bookings.php'">
      <h2> View Bookings</h2>
      <p>Check customer bookings</p>
    </div>

    <div class="card" onclick="location.href='logout.php'">
      <h2>Logout</h2>
      <p>Securely exit panel</p>
    </div>

  </div>

</div>

</body>
</html>