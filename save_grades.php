<?php
include 'db_connection.php';
$db = new Database();
$conn = $db->conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST['g1'] as $id => $val) {
        $g1 = $_POST['g1'][$id];
        $g2 = $_POST['g2'][$id];
        $g3 = $_POST['g3'][$id];
        $fin = $_POST['fin'][$id];
        $rem = mysqli_real_escape_string($conn, $_POST['rem'][$id]);
        
        mysqli_query($conn, "UPDATE student_subjects SET g1='$g1', g2='$g2', g3='$g3', final='$fin', remarks='$rem' WHERE id='$id'");
    }
    echo "<script>alert('Grades Saved!'); window.location.href='teacher_dashboard.php';</script>";
}
?>
