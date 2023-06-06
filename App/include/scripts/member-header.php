<?php
    //Check login session
    session_start();
    $userID = $_SESSION['CSP-session-userID'] ?? null;
    $userLevel = $_SESSION['CSP-session-userLevel'] ?? null;

    if($userID == null){
        header("Location: ../logout.php");
        exit();
    }

    if($userLevel == null || $userLevel != "สมาชิก"){
        header("Location: ../logout.php");
        exit();
    }
    
    //Get account data
    $sql = "SELECT userID, userFname, userLname, userProfile, username, userLevel, userRegist, userStatus
            FROM users
            WHERE userID = ?;";
    
    $stmt = $database->prepare($sql);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    //Check account exist
    if($result->num_rows == 0){
        header("Location: ../logout.php");
        exit();
    }

    //Create user account object
    $currentUser = (object) $result->fetch_assoc();

    //Check account suspended
    if($currentUser->userStatus == "บัญชีถูกระงับ"){
        header("Location: ../logout.php");
        exit();
    }
?>