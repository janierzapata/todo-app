<?php
include_once(__DIR__ . '/../models/Task.php');
include_once(__DIR__ . '/../config/Database.php');

class TaskController
{
    private $db;
    private $task;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->task = new Task($this->db);
    }

    public function getAllTasks()
    {
        $stmt = $this->task->getTasks();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
    }

    public function createTask()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->name)) {
            echo json_encode(array('message' => 'Nombre de tarea requerido.'));
            return;
        }

        if ($data) {
        }

        $this->task->name = $data->name;
        $this->task->status_id = 1;
        $this->task->created_at = date('Y-m-d H:i:s');

        if ($this->task->createTask()) {
            echo json_encode(array('message' => 'Tarea creada.'));
        } else {
            echo json_encode(array('message' => 'Error al crear tarea.'));
        }
    }

    public function updateTask()
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->task->id = $data->id;
        $this->task->status_id = $data->status_id;

        if ($this->task->updateTask()) {
            echo json_encode(array('message' => 'Estado de tarea actualizado.'));
        } else {
            echo json_encode(array('message' => 'Error al actualizar estado.'));
        }
    }

    public function deleteTask()
    {
        $data = json_decode(file_get_contents("php://input"));
        $this->task->id = $data->id;

        if ($this->task->deleteTask()) {
            echo json_encode(array('message' => 'Tarea eliminada.'));
        } else {
            echo json_encode(array('message' => 'Error al eliminar tarea.'));
        }
    }

}
