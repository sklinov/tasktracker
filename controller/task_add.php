<?php

    include_once '../config/Database.php';
    include_once '../model/Task.php';

    $database = new Database();
    $db = $database->connect();

    $task = new Task($db);
    $task->username = isset($_POST['username'])? $_POST['username'] : NULL;
    $task->email =    isset($_POST['email'])? $_POST['email'] : NULL;
    $task->text =     isset($_POST['text'])? $_POST['text'] : NULL;
    $task->completed =isset($_POST['completed'])? $_POST['completed'] : 0;

    $result = $task->addTask();

    if($result) {
        echo 'The task was added';
    }    
    else {
        echo 'No tasks returned from database'; 
    }