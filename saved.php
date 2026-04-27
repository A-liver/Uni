<?php
$conn = new mysqli("localhost", "root", "", "school_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Data
$sy = $_POST['sy'] ?? '';
$sem = $_POST['sem'] ?? '';
$course = $_POST['course'] ?? '';
$major = $_POST['major'] ?? null;

$fname = $_POST['fname'] ?? '';
$mname = $_POST['mname'] ?? '';
$lname = $_POST['lname'] ?? '';

$bdate = $_POST['bdate'] ?? '';
$age = $_POST['age'] ?? 0;
$gender = $_POST['gender'] ?? '';

$religion = $_POST['religion'] ?? '';
$ethnicity = $_POST['ethnicity'] ?? '';

$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';

$country = $_POST['country'] ?? '';
$region = $_POST['region'] ?? '';
$city = $_POST['city'] ?? '';
$barangay = $_POST['barangay'] ?? '';

$guardian_name = $_POST['guardian_name'] ?? '';
$guardian_contact = $_POST['guardian_contact'] ?? '';
$guardian_address = $_POST['guardian_address'] ?? '';

// Image
$imagePath = '';

if (isset($_FILES['imageInput']) && $_FILES['imageInput']['error'] == 0) {
    $imageName = time() . "_" . basename($_FILES['imageInput']['name']);
    $targetFile = $uploadDir . $imageName;

    if (move_uploaded_file($_FILES['imageInput']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    }
}

// INSERT (NO province)
$stmt = $conn->prepare("INSERT INTO applications 
(sy, sem, course, major, fname, mname, lname, bdate, age, gender, religion, ethnicity, phone, email, country, region, city, barangay, guardian_name, guardian_contact, guardian_address, image_path)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "ssssssssisssssssssssss",
    $sy, $sem, $course, $major,
    $fname, $mname, $lname,
    $bdate, $age, $gender,
    $religion, $ethnicity,
    $phone, $email,
    $country, $region, $city, $barangay,
    $guardian_name, $guardian_contact, $guardian_address,
    $imagePath
);

if ($stmt->execute()) {
    echo "<h2>Student Registered Successfully!</h2>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<a href="login.php">login</a>