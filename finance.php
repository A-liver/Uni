<?php
session_start();

if ($_SESSION['role'] != 'finance') {
    header("Location: login.php");
}
?>

<h1>Finance Dashboard</h1>
<p>Welcome Finance: <?php echo $_SESSION['username']; ?></p>

<a href="change_password.php">Change Password</a>
<a href="logout.php">Logout</a>