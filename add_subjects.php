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

/* 🔥 ENSURE STUDENT SESSION EXISTS */
if (!isset($_SESSION['student_id']) || $_SESSION['student_id'] <= 0) {
    die("Student session not found. Please login again.");
}

$student_id = (int) $_SESSION['student_id'];

/* GET SELECTED COURSE */
$selected_course = isset($_GET['course']) ? $_GET['course'] : "";

/* LOAD COURSES */
$courses = mysqli_query($conn, "
    SELECT DISTINCT COURSE 
    FROM subjectssem120182019 
    ORDER BY COURSE ASC
");

/* LOAD SUBJECTS */
$subjects = [];

if ($selected_course != "") {

    $selected_course = mysqli_real_escape_string($conn, $selected_course);

    $query = "
        SELECT * 
        FROM subjectssem120182019 
        WHERE COURSE='$selected_course' 
        ORDER BY SUBJECT ASC
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subjects by Course</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        select {
            padding: 10px;
            width: 300px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #007bff;
            color: white;
        }

        .btn {
            padding: 5px 10px;
            background: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background: darkgreen;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Select Course</h2>

    <!-- COURSE DROPDOWN -->
    <form method="GET">
        <select name="course" onchange="this.form.submit()" required>
            <option value="">-- Select Course --</option>

            <?php while ($c = mysqli_fetch_assoc($courses)) { ?>
                <option value="<?= $c['COURSE'] ?>" <?= ($selected_course == $c['COURSE']) ? 'selected' : '' ?>>
                    <?= $c['COURSE'] ?>
                </option>
            <?php } ?>

        </select>
    </form>

    <hr>

    <!-- SUBJECT LIST -->
    <?php if ($selected_course != "") { ?>

        <h3>Subjects for: <?= $selected_course ?></h3>

        <table>
            <tr>
                <th>CN</th>
                <th>Subject</th>
                <th>Description</th>
                <th>Units</th>
                <th>Time Start</th>
                <th>Time End</th>
                <th>Room</th>
                <th>Action</th>
            </tr>

            <?php foreach ($subjects as $s) { ?>
                <tr>
                    <td><?= $s['CN'] ?></td>
                    <td><?= $s['SUBJECT'] ?></td>
                    <td><?= $s['DESC'] ?></td>
                    <td><?= $s['UNIT'] ?></td>
                    <td><?= $s['TSTART'] ?></td>
                    <td><?= $s['TEND'] ?></td>
                    <td><?= $s['ROOM'] ?></td>
                    <td>
                        <!-- ✅ FIXED: CN used for lookup ONLY -->
                        <a class="btn" href="enrolled_courses.php?id=<?= $s['CN'] ?>&course=<?= $s['COURSE'] ?>">
                            Add
                        </a>
                    </td>
                </tr>
            <?php } ?>

        </table>

    <?php } else { ?>
        <p>Please select a course to view subjects.</p>
    <?php } ?>

</div>

</body>
</html>