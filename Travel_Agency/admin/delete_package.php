<?php
session_start();
$con = new mysqli("localhost", "root", "", "travel_agency");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$con->query("DELETE FROM packages WHERE id=$id");

header("Location: view_packages.php");
exit();
?>
