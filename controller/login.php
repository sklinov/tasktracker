<?php
    session_start();

    include_once '../config/Database.php';
    include_once '../model/Admin.php';

    $database = new Database();
    $db = $database->connect();

    $admin = new Admin($db);
    $admin->username = isset($_POST['username'])? $_POST['username']: 'default';
    $admin->password = isset($_POST['password'])? $_POST['password']: 'default';

    if($admin->username && $admin->password)
    {
        $result = $admin->AdminLogin();
        $temp = $result->fetchAll();
        $admin->admin_logged_in = $temp[0][0];
        $_SESSION['is_admin'] = $admin->admin_logged_in;
    }
    
    