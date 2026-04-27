<?php
include 'db_connection.php';

$db = new Database();
$conn = $db->conn;

$user_id = intval($_GET['id']);

mysqli_query($conn, "UPDATE users SET status='approved' WHERE user_id=$user_id");

header("Location: admin.php");
exit();
?>