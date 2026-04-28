<?php
session_start();
include('db_connection.php');

// Create database object and get connection
$db = new Database();
$conn = $db->getConnection();

// Security check: only allow dean role
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'dean'){
    header("Location: login.php");
    exit();
}

// --- Queries for summary cards ---
$total_students = $conn->query("SELECT COUNT(*) AS count FROM students")->fetch_assoc()['count'];
$pending_applications = $conn->query("SELECT COUNT(*) AS count FROM applications WHERE status='pending'")->fetch_assoc()['count'];
$total_courses = $conn->query("SELECT COUNT(*) AS count FROM courses")->fetch_assoc()['count'];
$total_payments = $conn->query("SELECT COUNT(*) AS count FROM payments")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dean Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { margin-bottom: 20px; }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; }
        .dashboard { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { background: #f4f4f4; padding: 20px; border-radius: 8px; text-align: center; flex: 1; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #333; color: #fff; }
    </style>
</head>
<body>
    <h1>Welcome, Dean</h1>
    <nav>
        <a href="approve_application.php">Approve Applications</a> |
        <a href="rejected_application.php">View Rejected Applications</a> |
        <a href="class_list.php">Class Lists</a> |
        <a href="save_grades.php">Manage Grades</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Summary Cards -->
    <div class="dashboard">
        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo $total_students; ?></p>
        </div>
        <div class="card">
            <h3>Pending Applications</h3>
            <p><?php echo $pending_applications; ?></p>
        </div>
        <div class="card">
            <h3>Courses Offered</h3>
            <p><?php echo $total_courses; ?></p>
        </div>
        <div class="card">
            <h3>Payments Recorded</h3>
            <p><?php echo $total_payments; ?></p>
        </div>
    </div>

    <!-- Recent Applications Table -->
    <h2>Recent Applications</h2>
<table>
    <tr><th>Student</th><th>Course</th><th>Status</th></tr>
    <?php
    $result = $conn->query("SELECT CONCAT(a.fname, ' ', a.lname) AS student_name, 
                                   a.course, 
                                   a.status 
                            FROM applications a
                            ORDER BY a.created_at DESC 
                            LIMIT 5");

    if($result && $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr><td>{$row['student_name']}</td><td>{$row['course']}</td><td>{$row['status']}</td></tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No recent applications found</td></tr>";
    }
    ?>
</table>

</body>
</html>
