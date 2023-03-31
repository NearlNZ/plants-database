<?php
    session_start();
    
    if(!(isset($_SESSION['CSP-session-userID']) && isset($_SESSION['CSP-session-userLevel']))){
        header('Location: login');
        exit();
    }
        
    $level = $_SESSION['CSP-session-userLevel'];

    if($level == "อาจารย์"){
        header('Location: system-teacher/dashboard');
        exit();
    }
    else if($level == "นักศึกษา"){
        header('Location: system-student/dashboard');
        exit();
    }
    else{
        header('Location: login');
        exit();
    }
?>