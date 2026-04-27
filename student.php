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

<h2>🎓 Student Dashboard</h2>

<hr>

<h3>👤 My Profile</h3>

<?php if ($student): ?>
    <p><b>Name:</b> <?= $student['fname'] . " " . $student['lname'] ?></p>
    <p><b>Course:</b> <?= $student['course'] ?></p>
    <p><b>Major:</b> <?= $student['major'] ?></p>
    <p><b>Birthdate:</b> <?= $student['bdate'] ?></p>
    <p><b>Gender:</b> <?= $student['gender'] ?></p>
    <p><b>Email:</b> <?= $student['email'] ?></p>
    <p><b>Address:</b> <?= $student['region'] . ", " . $student['city'] ?></p>

    <?php if (!empty($student['image_path'])): ?>
        <img src="<?= $student['image_path'] ?>" width="120">
    <?php endif; ?>

<?php else: ?>
    <p>No student record found.</p>
<?php endif; ?>

<hr>

<h3>🔐 Account Info</h3>
<p><b>Username:</b> <?= $user['username'] ?></p>
<p><b>Role:</b> <?= $user['role'] ?></p>

<hr>


<a href="change_password.php">Change Password</a>

<hr>

<a href="student_dashboard.php ">Go back to Dashboard</a>

<hr>

<a href="logout.php">Logout</a>