<?php
include 'db.php';

$id = $_GET['id'];

mysqli_query($conn, "UPDATE users SET status='rejected' WHERE id='$id'");

header("Location: admin.php");
?>