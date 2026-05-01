<?php
include 'db_connection.php';

$db = new Database();
$conn = $db->getConnection(); // ✅ use getConnection()

// Safety check: make sure id is passed
if (!isset($_GET['id'])) {
    die("Application not found");
}

$id = intval($_GET['id']); // sanitize input

// Reject application
mysqli_query($conn, "UPDATE applications SET status='rejected' WHERE app_id=$id");

// Redirect back to admin
header("Location: admin.php");
exit();
?>
