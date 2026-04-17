<?php
session_start();
$con = new mysqli("localhost", "root", "", "travel_agency");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Bookings</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 90%;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    h2 {
      text-align: center;
      color: #007bff;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
    }

    table thead {
      background: #007bff;
      color: #fff;
    }

    table th, table td {
      padding: 12px;
      border: 1px solid #ddd;
    }

    table tr:nth-child(even) {
      background: #f9f9f9;
    }

    table tr:hover {
      background: #eef5ff;
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 18px;
      background: #28a745;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .back-btn:hover {
      background: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>All Bookings</h2>
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Package</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT b.id, p.name AS package_name, b.customer_name, b.email, b.phone, b.booking_date 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id";
        $result = $con->query($sql);

        while($row = $result->fetch_assoc()) {
            echo "<tr>
                  <td>".$row['id']."</td>
                  <td>".$row['package_name']."</td>
                  <td>".$row['customer_name']."</td>
                  <td>".$row['email']."</td>
                  <td>".$row['phone']."</td>
                  <td>".$row['booking_date']."</td>
                  </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
