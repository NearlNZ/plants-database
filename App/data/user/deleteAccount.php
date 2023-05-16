<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Account permission check ("only admin" permission)
    require_once("../../include/scripts/admin-permission-check.php");

    //Set parameter
    $userID = $_GET['userID'] ?? '';

    //==============================================================================

    //1) Check for required parameter
    if($userID == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check account existence
    $sql = "SELECT userID, userProfile
            FROM users
            WHERE userID = ?
            LIMIT 1;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $userID);
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
    $stmt->bind_param('s', $userID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลบบัญชีผู้ใช้ที่เลือกสำเร็จแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>