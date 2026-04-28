<?php
session_start();
include 'db_connection.php';
$db = new Database();
$conn = $db->conn;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Teacher Dashboard</title></head>
<body>
    <h2>Teacher Schedule Dashboard</h2>
    <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
        <tr style="background-color: #f2f2f2;">
            <th>Subject Code</th><th>School Year</th><th>Semester</th><th>Action</th>
        </tr>
        <?php
        // Fetches unique schedules from the enrollment table
        $query = mysqli_query($conn, "SELECT DISTINCT subject_code, sy, sem FROM student_subjects");
        while($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                    <td>{$row['subject_code']}</td>
                    <td>{$row['sy']}</td>
                    <td>{$row['sem']}</td>
                    <td><a href='class_list.php?sub={$row['subject_code']}&sy={$row['sy']}&sem={$row['sem']}'>View Students & Enter Grades</a></td>
                  </tr>";
        }
        ?>
    </table>
    <br><a href="logout.php">Logout</a>
</body>
</html>
