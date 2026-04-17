<?php
session_start();
$con = new mysqli("localhost", "root", "", "travel_agency");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
if ($id == 0) {
    header("Location: view_packages.php");
    exit();
}

$stmt = $con->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Package not found");
}

$package = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $package['image'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $newImage = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $newImage;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $newImage;
        }
    }

    $update = $con->prepare("UPDATE packages SET name=?, description=?, price=?, image=? WHERE id=?");
    $update->bind_param("ssdsi", $name, $desc, $price, $image, $id);

    if ($update->execute()) {
        echo "<p>Package updated successfully!</p>";
        echo "<a href='view_packages.php'>Back to Packages</a>";
        exit();
    } else {
        echo "<p>Error: ".$con->error."</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Package</title>
  <style>
    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      width: 450px;
      margin: 50px auto;
      background: #fff;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    h2 {
      text-align: center;
      color: #1e3c72;
      margin-bottom: 20px;
    }

    label {
      font-weight: 600;
      display: block;
      margin-top: 12px;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
      outline: none;
      transition: 0.3s;
    }

    input:focus, textarea:focus {
      border-color: #2a5298;
      box-shadow: 0 0 5px rgba(42,82,152,0.5);
    }

    textarea {
      resize: none;
      height: 80px;
    }

    .image-preview {
      text-align: center;
      margin-top: 10px;
    }

    .image-preview img {
      border-radius: 8px;
      border: 2px solid #ddd;
      padding: 4px;
    }

    button {
      width: 100%;
      margin-top: 20px;
      padding: 12px;
      background: linear-gradient(135deg, #2a5298, #1e3c72);
      border: none;
      color: #fff;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      transform: scale(1.02);
    }

    .success {
      background: #e6ffed;
      color: #2e7d32;
      padding: 10px;
      border-radius: 6px;
      text-align: center;
      margin-bottom: 10px;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 10px;
      color: #2a5298;
      text-decoration: none;
      font-weight: 500;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

<div class="container">
  <h2>Edit Travel Package</h2>

  <form method="POST" enctype="multipart/form-data">
    
    <label>Package Name</label>
    <input type="text" name="name" value="<?php echo $package['name']; ?>" required>

    <label>Description</label>
    <textarea name="description" required><?php echo $package['description']; ?></textarea>

    <label>Price (₹)</label>
    <input type="number" step="0.01" name="price" value="<?php echo $package['price']; ?>" required>

    <label>Current Image</label>
    <div class="image-preview">
      <?php if ($package['image']) { ?>
        <img src="../uploads/<?php echo $package['image']; ?>" width="120">
      <?php } else { ?>
        <p>No image available</p>
      <?php } ?>
    </div>

    <label>Upload New Image</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit"> Update Package</button>
  </form>

  <a href="view_packages.php" class="back-link">⬅ Back to Packages</a>
</div>

</body>
</html>