<?php
include_once(__DIR__ . '/../models/Status.php');
include_once(__DIR__ . '/../config/Database.php');

class StatusController
{
    private $db;
    private $status;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->status = new Status($this->db);
    }

    public function getStates()
    {
        $stmt = $this->status->getStates();
        $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($statuses);
    }
}
