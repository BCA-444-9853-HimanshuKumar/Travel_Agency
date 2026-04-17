<?php
$conn = new mysqli("localhost", "root", "", "travel_agency");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT reviews.*, users.username AS user_name, packages.name AS package_name 
        FROM reviews
        JOIN users ON reviews.user_id = users.id
        JOIN packages ON reviews.package_id = packages.id
        ORDER BY reviews.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Reviews</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', Arial, sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
}

/* Container */
.container {
    width: 90%;
    max-width: 900px;
    margin: auto;
    padding: 20px;
}

/* Heading */
h2 {
    text-align: center;
    color: #fff;
    margin-bottom: 25px;
}

/* Review Card */
.review-box {
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    transition: 0.3s;
}

.review-box:hover {
    transform: translateY(-5px);
}

/* User Name */
.review-box h3 {
    margin: 0;
    color: #333;
}

/* Package */
.package {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

/* Stars */
.stars {
    color: gold;
    font-size: 18px;
    margin: 5px 0;
}

/* Comment */
.comment {
    margin: 10px 0;
    color: #444;
}

/* Date */
.date {
    font-size: 12px;
    color: #999;
}

/* No Reviews */
.no-review {
    text-align: center;
    color: #fff;
    background: rgba(255,255,255,0.2);
    padding: 15px;
    border-radius: 10px;
}

/* Responsive */
@media(max-width: 600px) {
    .review-box {
        padding: 15px;
    }
}
</style>

</head>
<body>

<div class="container">
    <h2>⭐ User Reviews</h2>

<?php
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
        <div class="review-box">
            <h3><?php echo htmlspecialchars($row['user_name']); ?></h3>
            
            <div class="package">
                <b>Package:</b> <?php echo htmlspecialchars($row['package_name']); ?>
            </div>

            <div class="stars">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo ($i <= $row['rating']) ? "⭐" : "☆";
                }
                ?>
            </div>

            <div class="comment">
                <?php echo htmlspecialchars($row['comment']); ?>
            </div>

            <div class="date">
                <?php echo $row['created_at']; ?>
            </div>
        </div>

<?php
    }
} else {
    echo "<div class='no-review'>No reviews found 😔</div>";
}
?>

</div>

</body>
</html>