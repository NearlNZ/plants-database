<?php
    session_start();
    
    if(!(isset($_SESSION['CSP-session-userID']) && isset($_SESSION['CSP-session-userLevel']))){
        header('Location: login');
        exit();
    }
        
    $level = $_SESSION['CSP-session-userLevel'];

    if($level == "สมาชิก"){
        header('Location: system-member/gallery');
        exit();
    }
    else{
        header('Location: login');
        exit();
    }
?>