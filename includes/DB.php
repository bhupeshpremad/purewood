<?php
class DB {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getRow($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
