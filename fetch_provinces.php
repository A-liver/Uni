<?php
$r=$_REQUEST['r'];
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "edata_db";
$conn;
// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

 $data = array();
        $sql = "SELECT  PROVINCE  FROM cities where REGION ='$r' ORDER BY PROVINCE ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
// Close connection
$conn->close();
    
header('Content-Type: application/json'); // Indicate JSON response
echo json_encode($data); // Output the data as a JSON array
?>