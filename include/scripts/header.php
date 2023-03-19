<?php
    //Check login session & userlevel
    session_start();
    $account = (object) $_SESSION['BPCS-session-account'] ?? null;
    if($account == null){
        header("Location: ../login.php");
        exit();
    }else if($account->level != 'ผู้ดูแล'){
        header("Location: ../login.php");
        exit();
    }

	//Include database connection
	require_once("../data/connect.php");
?>