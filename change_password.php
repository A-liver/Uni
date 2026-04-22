<?php
session_start();
include 'db.php';

// must be logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if (isset($_POST['change'])) {

    $current = md5($_POST['current_password']);
    $new = md5($_POST['new_password']);
    $confirm = md5($_POST['confirm_password']);

    // check current password
    $check = mysqli_query($conn, "SELECT * FROM users 
                                  WHERE username='$username' 
                                  AND password='$current'");

    if (mysqli_num_rows($check) == 0) {
        echo "Current password is wrong!";
    }else if ($current == $new) {
        echo "New password cannot be the same as current password!";
    } else {
        if ($new != $confirm) {
            echo "New password does not match!";
        } else {

            $sql = "UPDATE users 
                    SET password='$new' 
                    WHERE username='$username'";

            if (mysqli_query($conn, $sql)) {
                echo "Password changed successfully!";
            } else {
                echo "Error updating password!";
            }
        }
    }
}
?>

<h2>Change Password</h2>

<form method="POST">
    <input type="password" name="current_password" placeholder="Current Password" required><br><br>
    <input type="password" name="new_password" placeholder="New Password" required><br><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
    <button type="submit" name="change">Change Password</button>
</form>

<br>
<a href="logout.php">Logout</a>