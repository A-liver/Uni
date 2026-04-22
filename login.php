<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users 
            WHERE username='$username' 
            AND password='$password'";

    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {

        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = $user['status'];

        // approval check
        if ($user['status'] != 'approved') {
            echo "Account not approved yet!";
            exit();
        }

        // role routing
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } elseif ($user['role'] == 'teacher') {
            header("Location: teacher.php");
        } elseif ($user['role'] == 'cashier') {
            header("Location: cashier.php");
        } elseif ($user['role'] == 'finance') {
            header("Location: finance.php");
        } elseif ($user['role'] == 'dean') {
            header("Location: dean.php");
        } else {
            header("Location: student.php");
        }

    } else {
        echo "Invalid login";
    }
}
?>

<form method="POST">
    <h2>Login Test</h2>
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="login">Login</button>
</form>

<a href="signup.php">Signup</a>