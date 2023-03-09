<?php

    session_start();

    require 'db_inc.php';
    $db = new DataBase();

    
    $page = $_REQUEST['page'] ?? "index";

    $controllerFile = 'Controller/'.$page.'.php';

    if(file_exists($controllerFile)){
        require $controllerFile;
    }

?>