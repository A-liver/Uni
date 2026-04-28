<?php
session_start();
include 'db_connection.php'; // Using your existing connection file

$db = new Database();
$conn = $db->getConnection();

// 1. SECURE THE PAGE: Only Finance and Cashier roles allowed
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance' && $_SESSION['role'] !== 'cashier')) {
    echo "<script>alert('Access Denied. Finance Admins Only.'); window.location='login.php';</script>";
    exit();
}

$student_data = null;
$subjects = [];
$total_units = 0;
$total_paid = 0.00;

if (isset($_POST['fetch_records'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['student_search']);
    $query = "SELECT * FROM students WHERE fname LIKE '%$search_query%' OR lname LIKE '%$search_query%' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($student_data = mysqli_fetch_assoc($result)) {
        $sid = $student_data['student_id'];
        
        // 1. Get Subject Units
        $u_res = mysqli_query($conn, "SELECT * FROM student_subjects WHERE student_id = '$sid'");
        while($row = mysqli_fetch_assoc($u_res)) {
            $subjects[] = $row;
            $total_units += $row['units'];
        }

        // 2. Get Payments Made
        $p_res = mysqli_query($conn, "SELECT SUM(amount_paid) AS paid FROM payments WHERE student_id = '$sid'");
        $total_paid = mysqli_fetch_assoc($p_res)['paid'] ?? 0;
    }
}

// FETCH EXACT AMOUNTS FROM YOUR NEW DATABASE TABLE
$fees_res = mysqli_query($conn, "SELECT * FROM fee_schedules");
$fee_list = [];
$tuition_rate = 0;
while($f = mysqli_fetch_assoc($fees_res)) {
    if($f['category'] == 'Tuition') { $tuition_rate = $f['amount']; }
    else { $fee_list[] = $f; }
}

$total_tuition = $total_units * $tuition_rate;
$total_non_tuition = 0;
foreach($fee_list as $f) { $total_non_tuition += $f['amount']; }

$grand_total = $total_tuition + $total_non_tuition;
$balance = $grand_total - $total_paid;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Finance Admin</title>
    <style>
        :root { --primary: #004d40; --accent: #ff6f00; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .card { background: white; max-width: 1000px; margin: auto; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: var(--primary); color: white; padding: 20px; }
        .grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px; padding: 20px; }
        .fee-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .total-box { background: #fff3e0; padding: 20px; border-radius: 8px; border-left: 5px solid var(--accent); margin-top: 15px; }
        .balance { font-size: 1.8rem; color: #d32f2f; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <form method="POST" style="text-align:center;">
                <input type="text" name="student_search" placeholder="Search Student Name..." style="padding:10px; width:300px; border-radius:5px; border:none;">
                <button type="submit" name="fetch_records" style="padding:10px 20px; background:var(--accent); color:white; border:none; border-radius:5px; cursor:pointer;">SEARCH</button>
            </form>
        </div>

        <?php if ($student_data): ?>
        <div style="padding: 20px; border-bottom: 1px solid #eee;">
            <h2 style="margin:0;"><?php echo strtoupper($student_data['fname'] . " " . $student_data['lname']); ?></h2>
            <small>ID: <?php echo $student_data['student_id']; ?> | <?php echo $student_data['course']; ?></small>
        </div>

        <div class="grid">
            <div>
                <h3>📝 Academic Load</h3>
                <table width="100%" style="border-collapse:collapse;">
                    <tr style="background:#f8f9fa;"><th>Code</th><th align="center">Units</th></tr>
                    <?php foreach($subjects as $sub): ?>
                    <tr><td style="padding:10px; border-bottom:1px solid #eee;"><?php echo $sub['subject_code']; ?></td><td align="center"><?php echo $sub['units']; ?></td></tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div>
                <h3>💰 Detailed Assessment</h3>
                <div class="fee-item"><span>Tuition (₱<?php echo $tuition_rate; ?> x <?php echo $total_units; ?>)</span> <strong>₱<?php echo number_format($total_tuition, 2); ?></strong></div>
                <?php foreach($fee_list as $f): ?>
                    <div class="fee-item" style="font-size:0.85rem;"><span><?php echo $f['fee_name']; ?></span> <span>₱<?php echo number_format($f['amount'], 2); ?></span></div>
                <?php endforeach; ?>

                <div class="total-box">
                    <div class="fee-item"><span>Total Assessment:</span> <strong>₱<?php echo number_format($grand_total, 2); ?></strong></div>
                    <div class="fee-item" style="color:green;"><span>Total Payments (Cashier):</span> <strong>- ₱<?php echo number_format($total_paid, 2); ?></strong></div>
                    <hr>
                    <div class="fee-item"><span>CURRENT BALANCE:</span> <span class="balance">₱<?php echo number_format($balance, 2); ?></span></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
