<?php
session_start();

if ($_SESSION['role'] != 'teacher') {
    header("Location: login.php");
}
?>

<h1>Teacher Dashboard</h1>
<p>Welcome Teacher: <?php echo $_SESSION['username']; ?></p>

<a href="change_password.php">Change Password</a>
<a href="logout.php">Logout</a>