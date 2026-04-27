<?php
include 'db_connection.php';

$db = new Database();
$conn = $db->conn; // 🔥 FIX CONNECTION

$id = $_GET['id'];

// (optional safety)
$id = intval($id);

// reject application
mysqli_query($conn, "UPDATE applications SET status='rejected' WHERE app_id=$id");

header("Location: admin.php");
exit();
?>