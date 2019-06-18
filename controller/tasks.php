<?php
    session_start();

    include_once '../config/Database.php';
    include_once '../model/Task.php';
    include_once '../view/taskview.php';
    include_once '../model/Admin.php';
 
    $database = new Database();
    $db = $database->connect();

    $tasks = new Task($db);

    $result = $tasks->getAllTasks();
    $num = $result->rowCount();

    if($num > 0) {
        $tasks_array = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($tasks_array, $row);
        }
  
        $task_view = new TaskView;
        $task_view->sort_by =        isset($_GET['sort_by'])? $_GET['sort_by']: "id";
        $task_view->sort_order_asc = isset($_GET['sort_order_asc'])? $_GET['sort_order_asc']: true;
        $task_view->current_page =   isset($_GET['current_page'])? $_GET['current_page']:1;
        $task_view->showTasks($tasks_array);
        
    } else {
        echo 'No tasks returned from database'; 
    }