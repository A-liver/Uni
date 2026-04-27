<?php
session_start();
include 'db_connection.php';

$db = new Database();
$conn = $db->conn;

/* 🔒 SECURITY CHECK */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

/* GET STUDENT INFO */
$sql = "SELECT * FROM students WHERE email='$username'";
$result = mysqli_query($conn, $sql);
$student = mysqli_fetch_assoc($result);

/* GET USER INFO */
$sql2 = "SELECT * FROM users WHERE username='$username'";
$result2 = mysqli_query($conn, $sql2);
$user = mysqli_fetch_assoc($result2);
?>


<h2>Student Dashboard</h2>

<ul>
  <li><a href="student_subjects.php">View Subjects</a></li>
  <li><a href="add_subjects.php">Add Subject</a></li>
  <li><a href="student.php">View Account</a></li>
  <li><a href="billing.php">Billing</a></li>
  <li><a href="exam_permit.php">Exam Permit</a></li>
</ul>


<hr>

<a href="logout.php">Logout</a>