<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "school_db";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // 🔥 ADD THIS
    public function getConnection() {
        return $this->conn;
    }

    public function getCourses() {
        $data = array();
        $sql = "SELECT COURSE, `DESC`, MAJOR FROM courses ORDER BY `DESC` ASC, MAJOR ASC";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
}
?>