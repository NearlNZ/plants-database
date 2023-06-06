<?php
    session_start();
    
    if(!(isset($_SESSION['CSP-session-userID']) && isset($_SESSION['CSP-session-userLevel']))){
        header('Location: login');
        exit();
    }
        
    $level = $_SESSION['CSP-session-userLevel'];

    if($level == "ผู้ดูแลระบบ"){
        header('Location: system-admin/dashboard');
        exit();
    }
    else if($level == "สมาชิก"){
        header('Location: system-member/collection');
        exit();
    }
    else{
        header('Location: logout');
        exit();
    }
?>