<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Account permission check ("all member" permission)
    require_once("../../include/scripts/member-permission-check.php");

    //Set variables
    $userID = $_POST['userID'] ?? '';
    $currentUser = $_SESSION['CSP-session-userID'];
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    //==============================================================================

    //1) Check for required parameter
    if($userID == '' || $currentPassword == '' || $newPassword == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลที่จำเป็นให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check if user exist
    $sql = "SELECT userID, password
            FROM users
            WHERE userID = ?
            LIMIT 1;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $stmt->close();

    $user = $userResult->fetch_assoc();
    
    if($userResult->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบบัญชีผู้ใช้ในระบบ โปรดตรวจสอบอีกครั้ง';

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    };

    //3) Check account owner
    if ($user["userID"] != $currentUser) {
        $response->status = "warning";
        $response->title = "ไม่สามารถดำเนินการได้";
        $response->text = "ไม่สามารถรีเซ็ตรหัสผ่านของสมาชิกอื่นได้";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //4) Check password correction
    if(!password_verify($currentPassword, $user['password'])){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'รหัสผ่านปัจจุบันไม่ถูกต้อง โปรดตรวจสอบอีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //==============================================================================

    //Pass) Update password
    $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE users 
            SET password = ?
            WHERE userID = ?";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $hashPassword, $userID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'บันทึกรหัสใหม่แล้ว จะมีผลในการเข้าสู่ระบบครั้งถัดไป';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>