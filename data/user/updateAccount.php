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
    $caregiverID = $_POST['caregiverID'] ?? '';
    $caregiverName = $_POST['caregiverName'] ?? '';
    $caregiverLastname = $_POST['caregiverLastname'] ?? '';
    $caregiverTel = $_POST['caregiverTel'] ?? '';
    $caregiverLineToken = $_POST['caregiverLineToken'] ?? '';
    $oldProfile = $_POST['oldProfile'] ?? 'default-avatar.png';

    $caregiverProfile = (!empty($_FILES['caregiverProfile']['tmp_name'])) ? $_FILES['caregiverProfile'] : $oldProfile;

    //2) Check for required parameter
    if($caregiverID == '' || $caregiverName == '' || $caregiverLastname == '' || $caregiverTel == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลในการแก้ไขบัญชีผู้ใช้ให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //3) Check if user exist
    $sql = "SELECT caregiverID
            FROM caregiver
            WHERE caregiverID = ?
            LIMIT 1;";

    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $caregiverID);
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

    //4) Try to upload profile if not null
    if($caregiverProfile != 'default-avatar.png' && $caregiverProfile != $oldProfile){
        $uploaddir = '../../assets/img/avatars/';
        
        //Generate profile img name
        list($name, $extension) = explode(".",$caregiverProfile['name']);
        $name = uniqid("IMG-").rand(100,999);
        $file="$name.$extension";
        
        //Copy img to server
        $uploadfile = $uploaddir.$file;
        if (copy($caregiverProfile['tmp_name'], $uploadfile)) {
            $caregiverProfile = $file;

            //Delete old profile img if it not default-avatar.png
            if($oldProfile != "default-avatar.png"){
                $oldProfile = $uploaddir.$oldProfile;
                unlink($oldProfile);
            }           
        }else{
            $response->status = 'warning';
            $response->title = 'เกิดข้อผิดพลาด';
            $response->text = 'ไม่สามารถอัพโหลดรูปที่เลือกได้ โปรดเปลี่ยนรูป';
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            $bpcsDB->close();
            exit();
        }   
    }

    //Pass) Update account
    $sql = "UPDATE caregiver
            SET caregiverName = ?, caregiverLastname = ?, caregiverTel = ?, caregiverLineToken = ?, caregiverProfile = ?
            WHERE caregiverID = ?;";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ssssss', $caregiverName, $caregiverLastname, $caregiverTel, $caregiverLineToken, $caregiverProfile, $caregiverID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'บันทึกการแก้ไขข้อมูลบัญชีผู้ใช้แล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>