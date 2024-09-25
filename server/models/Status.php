<?php
class Status
{
    private $conn;
    private $table_name = "task_status";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getStates()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
