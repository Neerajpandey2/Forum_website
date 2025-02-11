<?php
        
        session_start();
        session_unset();
        session_destroy();
        header("location:/PHP/Forum_website1/index.php");
        exit();
?>