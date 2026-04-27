<?php
// db_connection.php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "edata_db";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function fetchItems() {
        $data = array();
        $sql = "SELECT DISTINCT `REGION` FROM `cities` ORDER BY `REGION` ASC";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $this->conn->close();
        return $data;
    }
}

// fetch_items.php
header('Content-Type: application/json'); // Indicate JSON response
$database = new Database();
$items = $database->fetchItems();
echo json_encode($items); // Output the data as a JSON array
?>