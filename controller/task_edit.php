<?php 

    include_once '../config/Database.php';
    include_once '../model/Task.php';
  
    $database = new Database();
    $db = $database->connect();

    $task = new Task($db);

    $task->id = isset($_POST['id'])? $_POST['id'] : NULL;
    $task->username = isset($_POST['username'])? $_POST['username'] : NULL;
    $task->email =    isset($_POST['email'])? $_POST['email'] : NULL;
    $task->text =     isset($_POST['text'])? $_POST['text'] : NULL;
    $task->completed =isset($_POST['completed'])? $_POST['completed'] : 0;

    $result = $task->editTask();

    if($result) {
        echo 'The task was edited';
    }    
    else {
        echo 'No tasks returned from database'; 
    }