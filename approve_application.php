<?php
include 'db_connection.php';

$db = new Database();
$conn = $db->conn;

$id = intval($_GET['id']);

/* 1. Get application */
$result = mysqli_query($conn, "SELECT * FROM applications WHERE app_id=$id");

if (!$result || mysqli_num_rows($result) == 0) {
    die("Application not found");
}

$data = mysqli_fetch_assoc($result);

/* 2. Approve application */
mysqli_query($conn, "UPDATE applications SET status='approved' WHERE app_id=$id");

/* 3. Insert into students */
mysqli_query($conn, "
INSERT INTO students (
    app_id, fname, mname, lname, course, major,
    bdate, gender, phone, email,
    region, city, barangay, image_path
)
VALUES (
    '{$data['app_id']}',
    '{$data['fname']}',
    '{$data['mname']}',
    '{$data['lname']}',
    '{$data['course']}',
    '{$data['major']}',
    '{$data['bdate']}',
    '{$data['gender']}',
    '{$data['phone']}',
    '{$data['email']}',
    '{$data['region']}',
    '{$data['city']}',
    '{$data['barangay']}',
    '{$data['image_path']}'
)
");

$student_id = mysqli_insert_id($conn);

/* 4. CREATE USER ACCOUNT (AUTO LOGIN) */
$username = $data['email'];
$password = md5("123456"); // default password

mysqli_query($conn, "
INSERT INTO users (username, password, role, student_id, status)
VALUES ('$username', '$password', 'student', $student_id, 'pending')");

/* 5. DONE */
header("Location: admin.php");
exit();
?>