<?php
session_start();
$con = new mysqli("localhost", "root", "", "travel_agency");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<p style='color:red;text-align:center;'>Image upload failed!</p>";
        }
    }

    $sql = "INSERT INTO packages (name, description, price, image) VALUES ('$name','$desc','$price','$image')";
    if ($con->query($sql)) {
        echo "<p style='color:green;text-align:center;'>✅ Package added successfully!</p>";
    } else {
        echo "<p style='color:red;text-align:center;'>Error: " . $con->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Package</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 500px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #007bff;
    }

    label {
      display: block;
      font-weight: bold;
      margin: 10px 0 5px;
      color: #333;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    textarea {
      resize: none;
      height: 100px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #0056b3;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
    }

    .back-link a {
      color: #007bff;
      text-decoration: none;
      font-size: 14px;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Add New Package</h2>
    <form method="POST" enctype="multipart/form-data">
      <label for="name">Package Name</label>
      <input type="text" name="name" id="name" required>

      <label for="description">Description</label>
      <textarea name="description" id="description" required></textarea>

      <label for="price">Price</label>
      <input type="number" name="price" id="price" step="0.01" required>

      <label for="image">Upload Image</label>
      <input type="file" name="image" id="image" accept="image/*">

      <button type="submit"> Add Package</button>
    </form>
    <div class="back-link">
      <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
