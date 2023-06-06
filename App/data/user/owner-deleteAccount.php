<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Account permission check ("only admin" permission)
    require_once("../../include/scripts/member-permission-check.php");

    //Set variables
    $currentUser = $_SESSION['CSP-session-userID'];

    //==============================================================================

    //1) Check account existence
    $sql = "SELECT userID, userProfile, userLevel
            FROM users
            WHERE userID = ?
            LIMIT 1;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $currentUser);
    $stmt->execute();
    $userResult = $stmt-> get_result();
    $stmt->close();

    if($userResult->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบบัญชีผู้ใช้ในระบบ โปรดตรวจสอบอีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    $user = $userResult->fetch_assoc();

    //2) Check minimum admin account if delete admin account
    if($user["userLevel"] == "ผู้ดูแลระบบ"){
        $sql = "SELECT userID
            FROM users
            WHERE userID <> ? AND userLevel = 'ผู้ดูแลระบบ';";

        $stmt =  $database->stmt_init(); 
        $stmt->prepare($sql);
        $stmt->bind_param('s', $userID);
        $stmt->execute();
        $adminResult = $stmt-> get_result();
        $stmt->close();

        if($adminResult->num_rows < 1){
            $response->status = 'warning';
            $response->title = 'เกิดข้อผิดพลาด';
            $response->text = 'ไม่สามารถลบบัญชีผู้ใช้ได้ จำเป็นต้องมีบัญชีสำหรับผู้ดูแลระบบอย่างน้อย 1 บัญชี';
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            $database->close();
            exit();
        }
    }

    //3) Delete account profile image if not "default-avatar.png"
    if($user["userProfile"] != "default-avatar.png"){
        $imgPath = "../../assets/img/avatars/";
        $img = $imgPath.$user["userProfile"];
        unlink($img);
    }

    //==============================================================================

    //Pass) Delete account
    $sql = "DELETE
            FROM users
            WHERE userID = ?;";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $currentUser);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลบบัญชีผู้สำเร็จแล้ว กำลังออกจากระบบ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>