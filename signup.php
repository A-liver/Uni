<h2>User Sign Up</h2>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <select name="roles" id="roles">
  	<option value="" disabled selected>Select Role</option>
  	<option value="student">Student</option>
  	<option value="dean">Dean</option>
 	<option value="teacher">Teacher</option>
  	<option value="finance">Finance</option>
    <option value="cashier">Cashier</option>
    </select>

<br><br>
    <button type="submit" name="signup">Sign Up</button>
</form>

<br>
<a href="login.php">Back to Login</a>

<?php
include 'db.php';

if (isset($_POST['signup'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']); // MD5 as required
    $role = $_POST['roles'];;
    $status = 'pending';

    // check if username exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    
    if (mysqli_num_rows($check) > 0) {
        echo "Username already exists!";
    } else {

        $sql = "INSERT INTO users (username, password, role, status)
                VALUES ('$username', '$password', '$role', '$status')";

        if (mysqli_query($conn, $sql)) {
            echo "Signup successful! Wait for admin approval.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
