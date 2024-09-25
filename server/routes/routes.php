<?php
include_once(__DIR__ . '/../controllers/TaskController.php');
include_once(__DIR__ . '/../controllers/StatusController.php');

$taskController = new TaskController();
$statusController = new StatusController();
header("Access-Control-Allow-Origin: *");  // Permite todas las solicitudes
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if (isset($_GET['route'])) {
    $route = $_GET['route'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['route'] === 'tasks') {
        $taskController->getAllTasks();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['route'] === 'tasks') {
        $taskController->createTask();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $_GET['route'] === 'tasks') {
        $taskController->updateTask();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $_GET['route'] === 'tasks') {
        $taskController->deleteTask();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['route'] === 'states') {
        $statusController->getStates();
    }


}


