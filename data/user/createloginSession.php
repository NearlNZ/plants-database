<?php
    header('Content-Type: application/json; charset=utf-8');
    session_start();
    $response = new stdClass();
    require_once("../connect.php");

    //Set parameter
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    //1) Check for required parameter
    if($username == '' || $password == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุชื่อผู้ใช้และรหัสผ่านของท่าน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //2) Check if username exist
    $sql = "SELECT caregiverID, caregiverProfile, username, password
            FROM caregiver
            WHERE username = ? 
            LIMIT 1;";

    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $username);
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
    }

    //3) Check if password not match
    $account=$userResult->fetch_assoc();
    if(!password_verify($password, $account['password'])){
        $response->status = "warning";
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = "รหัสผ่านไม่ถูกต้อง โปรดตรวจสอบอีกครั้ง";

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Pass) Create session for user
    $_SESSION['BPCS-session-userID'] = $account['caregiverID'];


    $response->status = "success";
    $response->text = "กำลังเข้าสู่ระบบ กรุณารอสักครู่...";

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $bpcsDB->close();
    exit();
?>