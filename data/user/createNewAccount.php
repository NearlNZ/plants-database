<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../connect.php");

    //1) Exit if user not verified key yet.
    session_start();
    if (!isset($_SESSION['BPCS-session-account']) && (!isset($_SESSION['BPCS-session-keyVerified']) || $_SESSION['BPCS-session-keyVerified'] != true)) {
        $response->status = "warning";
        $response->title = "เกิดข้อผิดพลาด";
        $response->text = "จำเป็นต้องทำการยืนยันตัวตนก่อนใช้งาน";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Set parameter
    $username = $_POST['username'] ?? null; 
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;
    $level = $_POST['userLevel'] ?? 'Officer';
    $department = $_POST['userDepartment'] ?? '';

    //2) Check for required parameter
    if($username == null || $password == null){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลในการสร้างบัญชีผู้ใช้ให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //3) Check if password and confirm password not match when confirm password not null
    if($confirmPassword != null && $password != $confirmPassword){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'รหัสผ่านไม่ตรงกัน โปรดตรวจสอบและลองใหม่อีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //4) Check if username already exist
    $sql = "SELECT user_id
            FROM user_Account
            WHERE user_name = ?;";

    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'Username นี้ได้ทำการลงทะเบียนไปแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Pass) Create new account
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user_Account(user_name, user_password, user_level, user_department)
            VALUES(?, ?, ?, ?);";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ssss', $username, $hashPassword, $level, $department);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'เพิ่มบัญชีผู้ใหม่เข้าสู่ระบบแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>