<?php
session_start();
include 'db_connection.php';
$db = new Database();
$conn = $db->conn;

$sub = $_GET['sub'];
$sy = $_GET['sy'];
$sem = $_GET['sem'];

// Fetch students alphabetically by last name
$sql = "SELECT ss.*, s.lname, s.fname 
        FROM student_subjects ss 
        JOIN students s ON ss.student_id = s.student_id 
        WHERE ss.subject_code='$sub' AND ss.sy='$sy' AND ss.sem='$sem' 
        ORDER BY s.lname ASC";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head><title>Class Grading</title></head>
<body>
    <h2>Class List: <?php echo $sub; ?> (<?php echo $sy; ?>)</h2>
    <button onclick="window.print()">Print Grade Sheet</button>
    <form method="POST" action="save_grades.php">
        <table border="1" cellpadding="5" style="margin-top:10px;">
            <tr>
                <th>Student Name</th><th>1st</th><th>2nd</th><th>3rd</th><th>Final</th><th>Remarks</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo $row['lname'].", ".$row['fname']; ?></td>
                <td><input type="number" step="0.01" name="g1[<?php echo $row['id']; ?>]" value="<?php echo $row['g1']; ?>"></td>
                <td><input type="number" step="0.01" name="g2[<?php echo $row['id']; ?>]" value="<?php echo $row['g2']; ?>"></td>
                <td><input type="number" step="0.01" name="g3[<?php echo $row['id']; ?>]" value="<?php echo $row['g3']; ?>"></td>
                <td><input type="number" step="0.01" name="fin[<?php echo $row['id']; ?>]" value="<?php echo $row['final']; ?>"></td>
                <td><input type="text" name="rem[<?php echo $row['id']; ?>]" value="<?php echo $row['remarks']; ?>"></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <br><button type="submit">Save All Grades</button>
    </form>
</body>
</html>
