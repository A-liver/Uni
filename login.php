<?php
session_start();
require_once "db_connection.php";

$db = new Database();
$conn = $db->getConnection();

if (isset($_POST['login'])) {

    $username = $_POST['username']; // email in your system
    $password = md5($_POST['password']);

    $username = mysqli_real_escape_string($conn, $username);

    $sql = "SELECT * FROM users 
            WHERE username='$username' 
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $user = mysqli_fetch_assoc($result);

    if ($user) {

        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username']; // keep login identity

        // 🔥 OPTION A: MAP EMAIL → student_id
        if ($user['role'] == 'student') {

            $res = mysqli_query($conn, "
                SELECT student_id 
                FROM students 
                WHERE email = '{$user['username']}'
                LIMIT 1
            ");

            $student = mysqli_fetch_assoc($res);

            if (!$student) {
                die("No matching student record found for this email.");
            }

            $_SESSION['student_id'] = (int)$student['student_id'];
        }

        // routing
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } elseif ($user['role'] == 'teacher') {
            header("Location: teacher_dashboard.php");
        } elseif ($user['role'] == 'cashier') {
            header("Location: cashier_dashboard.php");
        } elseif ($user['role'] == 'finance') {
            header("Location: finance_dashboard.php");
        } elseif ($user['role'] == 'dean') {
            header("Location: dean_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }

        exit();

    } else {
        echo "Invalid login";
    }
}
?>

<form method="POST">
    <h2>Login Test</h2>
    <input type="text" name="username" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<a href="signup.php">Signup</a>