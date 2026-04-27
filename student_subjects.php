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

/* 🔥 USE REAL STUDENT ID */
if (!isset($_SESSION['student_id'])) {
    die("Student session not found");
}

$student_id = $_SESSION['student_id'];

/* GET ENROLLED SUBJECTS */
$query = mysqli_query($conn, "
    SELECT *
    FROM student_subjects
    WHERE student_id = '$student_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Subjects</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
        }

        .btn {
            padding: 5px 10px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background: darkred;
        }
    </style>

</head>
<body>

<div class="container">

    <h2>My Enrolled Subjects</h2>

    <table>
        <tr>
            <th>CN</th>
            <th>Subject Code</th>
            <th>Description</th>
            <th>Units</th>
            <th>Room</th>
            <th>Instructor</th>
            <th>Term</th>
            <th>Semester</th>
            <th>SY</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $row['cn'] ?></td>
                <td><?= $row['subject_code'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['units'] ?></td>
                <td><?= $row['room'] ?></td>
                <td><?= $row['instructor'] ?></td>
                <td><?= $row['term'] ?></td>
                <td><?= $row['sem'] ?></td>
                <td><?= $row['sy'] ?></td>
                <td>
                    <a class="btn" href="drop_subject.php?id=<?= $row['id'] ?>">
                        Drop
                    </a>
                </td>
            </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>

<a href="student_dashboard.php">Go back to Dashboard</a>