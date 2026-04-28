<?php
session_start();
include 'db_connection.php';

$db = new Database();
$conn = $db->conn;

/* 🔒 SECURITY CHECK */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'cashier') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cashier Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        .dashboard {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        form {
            margin-bottom: 20px;
        }
        label {
            margin-right: 10px;
            font-weight: bold;
        }
        input[type="text"], select, input[type="number"] {
            padding: 6px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background: #f0f0f0;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Cashier Dashboard</h1>
<p>Welcome Cashier: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
<hr>

<!-- Student Search Form -->
<h2>Search Student</h2>
<form method="post">
    <label>Student Name:</label>
    <input type="text" name="student_name" placeholder="Search name..." required>

    <label>SY:</label>
    <select name="school_year" required>
        <option value="2024-2025">2024-2025</option>
        <option value="2025-2026">2025-2026</option>
        <option value="2026-2027">2026-2027</option>
    </select>

    <label>Sem:</label>
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

    $sql = "SELECT * FROM students 
            WHERE fname LIKE '%$student_name%' 
               OR mname LIKE '%$student_name%' 
               OR lname LIKE '%$student_name%'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($student = mysqli_fetch_assoc($result)) {
            $student_id = $student['student_id'];

            echo "<div class='dashboard'>";
            echo "<h2>Student Dashboard</h2>";

            $fullname = htmlspecialchars($student['fname']) . " " .
                        htmlspecialchars($student['mname']) . " " .
                        htmlspecialchars($student['lname']);
            echo "<h3>Student: $fullname</h3>";
            echo "<p><strong>Course:</strong> " . htmlspecialchars($student['course']) . "</p>";
            echo "<p><strong>School Year:</strong> " . htmlspecialchars($school_year) . "</p>";
            echo "<p><strong>Semester:</strong> " . htmlspecialchars($semester) . "</p>";

            /* 📑 Billing */
            $billing_sql = "SELECT * FROM billing_master 
                            WHERE school_year='$school_year' AND semester='$semester'";
            $billing_result = mysqli_query($conn, $billing_sql);

            echo "<h3>Billing Records</h3>";
            $total_billing = 0;
            if ($billing_result && mysqli_num_rows($billing_result) > 0) {
                echo "<table><tr><th>Fee Type</th><th>Amount</th></tr>";
                while ($bill = mysqli_fetch_assoc($billing_result)) {
                    $total_billing += $bill['amount'];
                    echo "<tr><td>" . htmlspecialchars($bill['fee_type']) . "</td><td>" . number_format($bill['amount'], 2) . "</td></tr>";
                }
                echo "</table>";
                echo "<p><strong>Total Billing:</strong> " . number_format($total_billing, 2) . "</p>";
            } else {
                echo "<p>No billing records found for this SY/SEM.</p>";
            }

            /* 💵 Payments */
            $payment_sql = "SELECT * FROM payments 
                            WHERE student_id=$student_id 
                              AND school_year='$school_year' 
                              AND semester='$semester'";
            $payment_result = mysqli_query($conn, $payment_sql);

            echo "<h3>Payments</h3>";
            $total_payments = 0;
            if ($payment_result && mysqli_num_rows($payment_result) > 0) {
                echo "<table><tr><th>Amount</th><th>Date</th></tr>";
                while ($pay = mysqli_fetch_assoc($payment_result)) {
                    $total_payments += $pay['amount'];
                    echo "<tr><td>" . number_format($pay['amount'], 2) . "</td><td>" . htmlspecialchars($pay['payment_date']) . "</td></tr>";
                }
                echo "</table>";
                echo "<p><strong>Total Payments:</strong> " . number_format($total_payments, 2) . "</p>";
            } else {
                echo "<p>No payments found for this SY/SEM.</p>";
            }

            /* Balance */
            $balance = $total_billing - $total_payments;
            echo "<p><strong>Remaining Balance:</strong> " . number_format($balance, 2) . "</p>";

            /* Payment Form */
            echo "<h3>Make a Payment</h3>";
            echo "<form method='post'>
                    <input type='hidden' name='student_id' value='$student_id'>
                    <input type='hidden' name='school_year' value='$school_year'>
                    <input type='hidden' name='semester' value='$semester'>
                    <label>Enter Payment:</label>
                    <input type='number' step='0.01' name='payment_amount' required>
                    <input type='submit' name='make_payment' value='Pay Now'>
                  </form>";

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
        echo "<p class='success'>Payment received successfully!</p>";
    } else {
        echo "<p class='error'>Error recording payment.</p>";
    }
}
?>

<p>
    <a href="change_password.php">Change Password</a> | 
    <a href="logout.php">Logout</a>
</p>

</body>
</html>
