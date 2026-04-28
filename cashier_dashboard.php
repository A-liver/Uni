<?php
session_start();
include 'db_connection.php';

$db = new Database();
$conn = $db->conn;

/* 🔒 SECURITY CHECK */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    header("Location: login.php");
    exit();
}
?>

<h1>Cashier Dashboard</h1>
<p>Welcome Cashier: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

<hr>

<!-- Student Search Form -->
<h2>Search Student</h2>
<form method="post">
    Student Name: <input type="text" name="student_name" placeholder="Search name..." required>
    SY: 
    <select name="school_year" required>
        <option value="2024-2025">2024-2025</option>
        <option value="2025-2026">2025-2026</option>
        <option value="2026-2027">2026-2027</option>
    </select>
    Sem: 
    <select name="semester" required>
        <option value="1st">1st Semester</option>
        <option value="2nd">2nd Semester</option>
    </select>
    <input type="submit" name="search" value="Search">
</form>

<?php
/* 🔎 Search student and show billing + payments immediately */
if (isset($_POST['search'])) {
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $school_year  = mysqli_real_escape_string($conn, $_POST['school_year']);
    $semester     = mysqli_real_escape_string($conn, $_POST['semester']);

    // Find student by name
    $sql = "SELECT * FROM students WHERE fname LIKE '%$student_name%' OR lname LIKE '%$student_name%'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($student = mysqli_fetch_assoc($result)) {
            echo "<h2>Student Dashboard</h2>";
            echo "<div style='border:1px solid #ccc; padding:15px; margin:10px;'>";

            echo "<h3><strong>Student: </strong>" 
            . htmlspecialchars($student['fname']) . " " 
            . htmlspecialchars($student['mname']) . " " 
			. htmlspecialchars($student['lname']) . "</h3>";
            echo "<p><strong>Course:</strong> " . htmlspecialchars($student['course']) . "</p>";
            echo "<p><strong>School Year:</strong> " . htmlspecialchars($school_year) . "</p>";
            echo "<p><strong>Semester:</strong> " . htmlspecialchars($semester) . "</p>";
			
			$student_id = $student['student_id'];


            /* 📑 Fixed Billing from billing_master */
            $billing_sql = "SELECT * FROM billing_master 
                            WHERE school_year='$school_year' AND semester='$semester'";
            $billing_result = mysqli_query($conn, $billing_sql);

            echo "<h3>Billing Records</h3>";
            $total_billing = 0;
            if ($billing_result && mysqli_num_rows($billing_result) > 0) {
                echo "<table border='1' cellpadding='5'><tr><th>Fee Type</th><th>Amount</th></tr>";
                while ($bill = mysqli_fetch_assoc($billing_result)) {
                    $total_billing += $bill['amount'];
                    echo "<tr><td>" . htmlspecialchars($bill['fee_type']) . "</td><td>" . htmlspecialchars($bill['amount']) . "</td></tr>";
                }
                echo "</table>";
                echo "<p><strong>Total Billing:</strong> " . number_format($total_billing, 2) . "</p>";
            } else {
                echo "<p>No billing records found for this SY/SEM.</p>";
            }

            /* 💵 Payments for this student */
            $payment_sql = "SELECT * FROM payments 
                            WHERE student_id=$student_id 
                            AND school_year='$school_year' 
                            AND semester='$semester'";
            $payment_result = mysqli_query($conn, $payment_sql);

            echo "<h3>Payments</h3>";
            $total_payments = 0;
            if ($payment_result && mysqli_num_rows($payment_result) > 0) {
                echo "<table border='1' cellpadding='5'><tr><th>Amount</th><th>Date</th></tr>";
                while ($pay = mysqli_fetch_assoc($payment_result)) {
                    $total_payments += $pay['amount'];
                    echo "<tr><td>" . htmlspecialchars($pay['amount']) . "</td><td>" . htmlspecialchars($pay['payment_date']) . "</td></tr>";
                }
                echo "</table>";
                echo "<p><strong>Total Payments:</strong> " . number_format($total_payments, 2) . "</p>";
            } else {
                echo "<p>No payments found for this SY/SEM.</p>";
            }

            /* Remaining balance */
            $balance = $total_billing - $total_payments;
            echo "<p><strong>Remaining Balance:</strong> " . number_format($balance, 2) . "</p>";

            /* Payment form directly in dashboard */
            echo "<h3>Make a Payment</h3>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='student_id' value='$student_id'>";
            echo "<input type='hidden' name='school_year' value='$school_year'>";
            echo "<input type='hidden' name='semester' value='$semester'>";
            echo "<label for='payment_amount'>Enter Payment:</label>";
            echo "<input type='number' step='0.01' name='payment_amount' required>";
            echo "<input type='submit' name='make_payment' value='Pay Now'>";
            echo "</form>";

            echo "</div>";
        }
    } else {
        echo "<p>No student found.</p>";
    }
}

/* 💵 Record payment */
if (isset($_POST['make_payment']) && !empty($_POST['student_id'])) {
    $student_id  = intval($_POST['student_id']);
    $school_year = mysqli_real_escape_string($conn, $_POST['school_year']);
    $semester    = mysqli_real_escape_string($conn, $_POST['semester']);
    $amount      = floatval($_POST['payment_amount']);

    $insert_payment = "INSERT INTO payments (student_id, school_year, semester, amount, payment_date) 
                       VALUES ($student_id, '$school_year', '$semester', $amount, NOW())";
    if (mysqli_query($conn, $insert_payment)) {
        echo "<p style='color:green;'>Payment received successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error recording payment.</p>";
    }
}
?>

<p>
    <a href="change_password.php">Change Password</a> | 
    <a href="logout.php">Logout</a>
</p>
