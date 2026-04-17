<?php 
$con = mysqli_connect("localhost","root","","travel_agency");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tour Packages - Travel Agency</title>
  <link rel="stylesheet" href="css/style.css">

  <style>
    /* ===== Page Layout Fix ===== */
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex: 1;
    }

    /* ===== Package Cards ===== */
    .packages {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }

    .card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      padding: 15px;
      text-align: center;
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.2);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 15px;
    }

    .card h2 {
      color: #007bff;
      margin-bottom: 10px;
    }

    .card p {
      color: #555;
      margin: 10px 0;
    }

    .price {
      font-size: 18px;
      color: #28a745;
      font-weight: bold;
    }

    .btn {
      display: inline-block;
      margin-top: 12px;
      padding: 10px 18px;
      background: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 25px;
    }

    .btn:hover {
      background: #0056b3;
    }
  </style>
</head>

<body>

<!-- ===== Navbar ===== -->
<header>
  <nav>
    <h2>Travel Agency</h2>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="packages.php" class="active">Packages</a></li>
      <li><a href="booking.php">Booking</a></li>
      <li><a href="admin/login.php">Admin</a></li>
    </ul>
  </nav>
</header>

<!-- ===== Main Content ===== -->
<main>

  <h1 style="text-align:center; margin:30px 0; color:#2c3e50;">
    Available Tour Packages
  </h1>

  <div class="packages">
    <?php
    $result = $con->query("SELECT * FROM packages");

    if($result && $result->num_rows > 0){
      while($row = $result->fetch_assoc()) {
        echo "<div class='card'>";

        if (!empty($row['image'])) {
          echo "<img src='uploads/".$row['image']."' alt='".$row['name']."'>";
        }

        echo "<h2>".$row['name']."</h2>";
        echo "<p>".$row['description']."</p>";
        echo "<p class='price'>₹".$row['price']."</p>";
        echo "<a class='btn' href='booking.php?package_id=".$row['id']."'>Book Now</a>";
        echo "</div>";
      }
    } else {
      echo "<p style='text-align:center;'>No packages available</p>";
    }
    ?>
  </div>

</main>

<!-- ===== Footer ===== -->
<footer>
  &copy; <?php echo date("Y"); ?> Travel Agency. All rights reserved.
</footer>

</body>
</html>