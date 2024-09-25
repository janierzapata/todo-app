<?php
class Task
{
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $name;
    public $created_at;
    public $status_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getTasks()
    {
        $query = "SELECT tasks.id, tasks.name, tasks.creation_date, task_status.status_name 
                  FROM " . $this->table_name . " 
                  JOIN task_status ON tasks.status_id = task_status.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function createTask()
    {
        $query = "INSERT INTO " . $this->table_name . " (name, status_id) VALUES (:name, :status_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':status_id', $this->status_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateTask()
    {
        $query = "UPDATE " . $this->table_name . " SET status_id = :status_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status_id', $this->status_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteTask()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
