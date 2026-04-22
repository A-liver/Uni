<?php
session_start();

if ($_SESSION['role'] != 'dean') {
    header("Location: login.php");
}
?>

<h1>Dean Dashboard</h1>
<p>Welcome Dean: <?php echo $_SESSION['username']; ?></p>

<a href="change_password.php">Change Password</a>
<a href="logout.php">Logout</a>