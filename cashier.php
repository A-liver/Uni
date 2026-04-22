<?php
session_start();

if ($_SESSION['role'] != 'cashier') {
    header("Location: login.php");
}
?>

<h1>Cashier Dashboard</h1>
<p>Welcome Cashier: <?php echo $_SESSION['username']; ?></p>

<a href="change_password.php">Change Password</a>
<a href="logout.php">Logout</a>