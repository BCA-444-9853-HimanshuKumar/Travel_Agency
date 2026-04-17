<?php
session_start();
$con = new mysqli("localhost", "root", "", "travel_agency");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Packages</title>
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

    .add-btn {
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

    .add-btn:hover {
      background: #218838;
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

    img {
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .action-btn {
      padding: 6px 12px;
      margin: 2px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .edit-btn {
      background: #ffc107;
      color: #000;
    }

    .edit-btn:hover {
      background: #e0a800;
    }

    .delete-btn {
      background: #dc3545;
      color: #fff;
    }

    .delete-btn:hover {
      background: #c82333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>All Packages</h2>
    <a href="add_package.php" class="add-btn">+ Add New Package</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Price</th>
          <th>Image</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $con->query("SELECT * FROM packages");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['description']."</td>";
            echo "<td>$".$row['price']."</td>";

            // Show image
            echo "<td>";
            if (!empty($row['image'])) {
                echo "<img src='../uploads/".$row['image']."' width='100'>";
            } else {
                echo "No Image";
            }
            echo "</td>";

            // Action buttons
            echo "<td>
                    <a href='edit_package.php?id=".$row['id']."' class='action-btn edit-btn'>Edit</a>
                    <a href='delete_package.php?id=".$row['id']."' class='action-btn delete-btn' onclick='return confirm(\"Delete this package?\")'>Delete</a>
                  </td>";
            echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
