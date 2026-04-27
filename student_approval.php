<?php
include 'db_connection.php';

$db = new Database();
$conn = $db->conn;

/* 1. Get ID safely */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid application ID");
}

/* 2. Get application */
$result = mysqli_query($conn, "SELECT * FROM applications WHERE app_id=$id");

if (!$result || mysqli_num_rows($result) == 0) {
    die("Application not found");
}

$data = mysqli_fetch_assoc($result);

/* 3. Approve application */
mysqli_query($conn, "UPDATE applications SET status='approved' WHERE app_id=$id");

/* 4. Insert into students */
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

/* 5. Create user account */
$student_id = mysqli_insert_id($conn);

$email = $data['email'];
$password = md5("123456");

mysqli_query($conn, "
INSERT INTO users (username, password, role, student_id)
VALUES ('$email', '$password', 'student', $student_id)
");

header("Location: admin.php");
exit();
?>