<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../connect.php");

    //1) Exit if user not verified key yet.
    session_start();
    if (!isset($_SESSION['BPCS-session-userID']) && (!isset($_SESSION['BPCS-session-keyVerified']) || $_SESSION['BPCS-session-keyVerified'] != true)) {
        $response->status = "warning";
        $response->title = "เกิดข้อผิดพลาด";
        $response->text = "จำเป็นต้องทำการยืนยันตัวตนก่อนใช้งาน";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Set parameter
    $userID = $_POST['caregiverID'] ?? null; 
    $oldPassword = $_POST['oldPassword'] ?? null;
    $password = $_POST['password'] ?? null;

    //2) Check for required parameter
    if($userID == null || $oldPassword == null || $password == null){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลในการรีเซ็ตรหัสผ่านให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

   //3) Check if user exist
    $sql = "SELECT password
            FROM caregiver
            WHERE caregiverID = ?
            LIMIT 1;";

    $stmt =  $bpcsDB->stmt_init(); 
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
        $bpcsDB->close();
        exit();
    };

    //Check if old password correct
    $user = $userResult->fetch_assoc();
    if(!password_verify($oldPassword, $user['password'])){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'รหัสผ่านไม่ถูกต้อง โปรดตรวจสอบอีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Pass) Create new account
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE caregiver SET password = ?
            WHERE caregiverID = ?";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $hashPassword, $userID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'รีเซ็ตรหัสผ่านของท่านแล้ว จะมีผลในการเข้าสู่ระบบครั้งถัดไป';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>