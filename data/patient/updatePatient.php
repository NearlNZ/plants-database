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
    $patientID = $_POST['patientID'] ?? '';
    $patientName = $_POST['patientName'] ?? '';
    $patientLastname = $_POST['patientLastname'] ?? '';
    $patientGender = $_POST['patientGender'] ?? '';
    $patientBirthdate = $_POST['patientBirthdate'] ?? '';
    $patientWeight = $_POST['patientWeight'] ?? '';
    $patientHeight = $_POST['patientHeight'] ?? '';
    $oldProfile = $_POST['oldProfile'] ?? 'default-avatar.png';

    $patientProfile = (!empty($_FILES['patientProfile']['tmp_name'])) ? $_FILES['patientProfile'] : $oldProfile;

    //2) Check for required parameter
    if($patientID == '' || $patientName == '' || $patientLastname == '' || $patientGender == '' || $patientBirthdate == '' || $patientWeight == '' || $patientHeight == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลในการแก้ไขข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //3) Check if patient exist
    $sql = "SELECT patientID
            FROM patient
            WHERE patientID = ?
            LIMIT 1;";
            
    $stmt = $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $patientID);
    $stmt->execute();
    $patientResult = $stmt-> get_result();
    $stmt->close();

    if($patientResult->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบข้อมูลผู้ป่วยในระบบ โปรดตรวจสอบอีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //4) Check if patient duplicate
    $sql = "SELECT patientID
            FROM patient
            WHERE patientName = ? AND patientLastname = ? AND patientID <> ?
            LIMIT 1;";
    
    $stmt = $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('sss', $patientName, $patientLastname, $patientID);
    $stmt->execute();
    $patientResult = $stmt-> get_result();
    $stmt->close();

    if($patientResult->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ผู้ป่วยได้ทำการลงทะเบียนในระบบไปแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //5) Try to upload profile if not null
    if($patientProfile != 'default-avatar.png' && $patientProfile != $oldProfile){
        $uploaddir = '../../assets/img/avatars/';
        
        //Generate profile img name
        list($name, $extension) = explode(".",$patientProfile['name']);
        $name = uniqid("IMG-").rand(100,999);
        $file="$name.$extension";
        
        //Copy img to server
        $uploadfile = $uploaddir.$file;
        if (copy($patientProfile['tmp_name'], $uploadfile)) {
            $patientProfile = $file;

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

    //Pass) Update patient data
    $sql = "UPDATE patient
            SET patientName = ?, patientLastname = ?, patientGender = ?, patientBirthdate = ?, patientWeight = ?, patientHeight = ?, patientProfile = ?
            WHERE patientID = ?;";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ssssddss', $patientName, $patientLastname, $patientGender, $patientBirthdate, $patientWeight, $patientHeight, $patientProfile, $patientID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'บันทึกข้อมูลผู้ป่วยสำเร็จ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>