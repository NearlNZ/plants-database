<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //1) Exit if user not verified key yet.
    session_start();
    if (!isset($_SESSION['CSP-session-userID'])) {
        $response->status = "warning";
        $response->title = "เกิดข้อผิดพลาด";
        $response->text = "จำเป็นต้องทำการยืนยันตัวตนก่อนใช้งาน";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //Set parameter
    $userID = $_POST['userID'] ?? null; 
    $oldPassword = $_POST['oldPassword'] ?? null;
    $password = $_POST['password'] ?? null;

    //2) Check for required parameter
    if($userID == null || $oldPassword == null || $password == null){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

   //3) Check if user exist
    $sql = "SELECT password
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
    };

    //Check if old password correct
    $user = $userResult->fetch_assoc();
    if(!password_verify($oldPassword, $user['password'])){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'รหัสผ่านไม่ถูกต้อง โปรดตรวจสอบอีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //Pass) Create new account
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

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
        $response->text = 'รีเซ็ตรหัสผ่านของท่านแล้ว จะมีผลในการเข้าสู่ระบบครั้งถัดไป';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>