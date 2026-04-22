<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
}
?>

<h2>Admin Panel</h2>

<h3>Pending Users</h3>

<?php
$result = mysqli_query($conn, "SELECT * FROM users WHERE status='pending'");

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['username'];

    echo " <a href='approve.php?id=".$row['id']."'>Approve</a>";
    echo " <a href='reject.php?id=".$row['id']."'>Reject</a>";

    echo "<br>";
}
?>

<h3>All Accounts</h3>

<?php
$result_all = mysqli_query($conn, "SELECT * FROM users");

while ($row = mysqli_fetch_assoc($result_all)) {
    echo "Username: " . $row['username'];
    echo " | Role: " . $row['role'];
    echo " | Status: " . $row['status'];

    echo "<br>";
}
?>

<a href="change_password.php">Change Password</a>
<a href="logout.php">Logout</a>
