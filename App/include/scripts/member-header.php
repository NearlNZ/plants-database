<?php
    //Check login session
    session_start();
    $userID = $_SESSION['CSP-session-userID'] ?? null;
    $userLevel = $_SESSION['CSP-session-userLevel'] ?? null;

    if($userID == null){
        header("Location: ../login.php");
        exit();
    }

    if($userLevel == null || $userLevel != "สมาชิก"){
        header("Location: ../login.php");
        exit();
    }

	//Include database connection
	require_once("../data/database.php");
    
    //Get account data
    $sql = "SELECT userID, userFname, userLname, userProfile, username, userLevel, userRegist
            FROM users
            WHERE userID = ?;";
    
    $stmt = $database->prepare($sql);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    //Check account exist
    if($result->num_rows == 0){
        header("Location: ../login.php");
        exit();
    }

    $user = (object) $result->fetch_assoc();
?>