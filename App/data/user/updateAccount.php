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
    $userID = $_POST['userID'] ?? '';
    $userFname = $_POST['userFname'] ?? '';
    $userLname = $_POST['userLname'] ?? '';
    $oldProfile = $_POST['oldProfile'] ?? 'default-avatar.png';

    $userProfile = (!empty($_FILES['userProfile']['tmp_name'])) ? $_FILES['userProfile'] : $oldProfile;

    //2) Check for required parameter
    if($userID == '' || $userFname == '' || $userLname == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //3) Check if user exist
    $sql = "SELECT userID
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

    //4) Try to upload profile if not null
    if($userProfile != 'default-avatar.png' && $userProfile != $oldProfile){
        $uploaddir = '../../assets/img/avatars/';
        
        //Generate profile img name
        list($name, $extension) = explode(".",$userProfile['name']);
        $name = uniqid("USER-").rand(100,999);
        $file="$name.$extension";
        
        //Copy img to server
        $uploadfile = $uploaddir.$file;
        if (copy($userProfile['tmp_name'], $uploadfile)) {
            $userProfile = $file;

            //Delete old profile img if it not default-avatar.png
            if($oldProfile != "default-avatar.png"){
                $oldProfile = $uploaddir.$oldProfile;
                unlink($oldProfile);
            }           
        }else{
            $response->status = 'warning';
            $response->title = 'เกิดข้อผิดพลาด';
            $response->text = 'ไม่สามารถอัพโหลดรูปที่เลือกได้ โปรดเปลี่ยนรูปแล้วลองอีกครั้ง';
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            $database->close();
            exit();
        }   
    }

    //Pass) Update account
    $sql = "UPDATE users
            SET userFname = ?, userLname = ?, userProfile = ?
            WHERE userID = ?;";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ssss', $userFname, $userLname, $userProfile, $userID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'บันทึกการแก้ไขข้อมูลบัญชีผู้ใช้แล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>