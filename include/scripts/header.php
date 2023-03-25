<?php
    //Check login session
    session_start();
    $userID = $_SESSION['BPCS-session-userID'] ?? null;
    if($userID == null){
        header("Location: ../login.php");
        exit();
    }

	//Include database connection
	require_once("../data/connect.php");
    
    //Get account data
    $sql = "SELECT caregiverID, username, caregiverProfile
            FROM caregiver
            WHERE caregiverID = ?;";
    
    $stmt = $bpcsDB->prepare($sql);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    //Check account exist
    if($result->num_rows == 0){
        header("Location: ../login.php");
        exit();
    }

    $account = (object) $result->fetch_assoc();
?>