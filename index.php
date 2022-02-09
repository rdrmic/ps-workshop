<?php
    require_once('./src/core/Session.php');
    
    $session = Session::getInstance();
    $response = $session->handleRequest();
    echo $response;
?>
