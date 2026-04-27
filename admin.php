<?php
session_start();
include 'db_connection.php';

$db = new Database();
$conn = $db->conn;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<h2>Admin Dashboard</h2>

<h3>Pending Applications</h3>

<?php
$result = mysqli_query($conn, "SELECT * FROM applications WHERE status='pending'");

while ($row = mysqli_fetch_assoc($result)) {
    echo "<div style='margin-bottom:10px;'>";
    echo "<b>{$row['fname']} {$row['lname']}</b> - {$row['course']}";

    echo "<br>";

    echo "<a href='approve_application.php?id={$row['app_id']}'>Approve</a> | ";
    echo "<a href='rejected_application.php?id={$row['app_id']}'>Reject</a>";

    echo "</div>";
}
?>

<hr>

<h3> Pending Users (Login Approval)</h3>

<?php
$resultUsers = mysqli_query($conn, "
    SELECT * FROM users 
    WHERE role IN ('admin','student','dean','teacher','cashier','finance') AND status='pending'
");

while ($row = mysqli_fetch_assoc($resultUsers)) {
    echo "<div style='margin-bottom:10px;'>";
    echo "Username: {$row['username']}";
    echo "<br>";
    echo "<a href='approve.php?id={$row['user_id']}'>Approve</a> | ";
    echo "<a href='reject.php?id={$row['user_id']}'>Reject</a>";
    echo "</div>";
}
?>

<hr>

<h3>Approved Students</h3>

<?php
$result2 = mysqli_query($conn, "SELECT * FROM students");

while ($row = mysqli_fetch_assoc($result2)) {
    echo "<div>";
    echo "{$row['fname']} {$row['lname']} - {$row['course']}";
    echo "</div>";
}
?>

<hr>

<h3>System Users</h3>

<?php
$result3 = mysqli_query($conn, "SELECT * FROM users");

while ($row = mysqli_fetch_assoc($result3)) {
    echo "<div>";
    echo "Username: {$row['username']}";
    echo "</div>";
}
?>

<br>
<a href="logout.php">Logout</a>