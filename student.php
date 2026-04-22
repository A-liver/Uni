<?php
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
}
?>

<h1>Student Dashboard</h1>
<p>Welcome Student: <?php echo $_SESSION['username']; ?></p>

<a href="change_password.php">Change Password</a>
<a href="logout.php">Logout</a>