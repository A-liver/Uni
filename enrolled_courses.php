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

/* 🔥 GET STUDENT ID FROM SESSION */
if (!isset($_SESSION['student_id']) || $_SESSION['student_id'] <= 0) {
    die("Student session not found or invalid student_id");
}

$student_id = (int) $_SESSION['student_id'];

/* VALIDATE SUBJECT ID (CN still used ONLY to fetch subject) */
if (!isset($_GET['id'])) {
    die("No subject selected");
}

$cn = (int) $_GET['id'];

/* GET SUBJECT */
$query = mysqli_query($conn, "
    SELECT * 
    FROM subjectssem120182019 
    WHERE CN=$cn
");

$subject = mysqli_fetch_assoc($query);

if (!$subject) {
    die("Subject not found");
}

/* 🔥 USE REAL IDENTITY (SUBJECT CODE + COURSE) */
$subject_code = $subject['SUBJECT'];
$course = $subject['COURSE'];

/* CHECK DUPLICATE (FIXED) */
$check = mysqli_query($conn, "
    SELECT id 
    FROM student_subjects 
    WHERE student_id = $student_id 
    AND subject_code = '$subject_code'
    AND course = '$course'
");

if (mysqli_num_rows($check) > 0) {
    echo "<script>
        alert('Already enrolled in this subject!');
        window.location.href='add_subject.php';
    </script>";
    exit;
}

/* INSERT ENROLLMENT */
$insert = mysqli_query($conn, "
    INSERT INTO student_subjects (
        student_id,
        cn,
        course,
        subject_code,
        description,
        units,
        room,
        instructor,
        term,
        sem,
        sy
    ) VALUES (
        $student_id,
        $cn,
        '$course',
        '$subject_code',
        '{$subject['DESC']}',
        '{$subject['UNIT']}',
        '{$subject['ROOM']}',
        '{$subject['INSTRUCTOR']}',
        '{$subject['TERM']}',
        '{$subject['SEM']}',
        '{$subject['SY']}'
    )
");

if (!$insert) {
    die("Insert failed: " . mysqli_error($conn));
}

echo "<script>
    alert('Subject added to enrolled courses!');
    window.location.href='student_dashboard.php?course={$course}';
</script>";
?>