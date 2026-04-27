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

/* 🔥 ENSURE STUDENT SESSION */
if (!isset($_SESSION['student_id']) || $_SESSION['student_id'] <= 0) {
    die("Student session not found");
}

$student_id = (int) $_SESSION['student_id'];

if (!isset($_GET['id'])) {
    die("No subject selected");
}

$enroll_id = (int) $_GET['id'];

/* 🔥 DELETE ONLY OWN SUBJECT */
$delete = mysqli_query($conn, "
    DELETE FROM student_subjects
    WHERE id = $enroll_id
    AND student_id = $student_id
");

if ($delete) {
    echo "<script>
        alert('Subject dropped successfully!');
        window.location.href='student_subjects.php';
    </script>";
} else {
    echo "Error dropping subject: " . mysqli_error($conn);
}
?>

<a href="student_dashboard.php">Go back to Dashboard</a>