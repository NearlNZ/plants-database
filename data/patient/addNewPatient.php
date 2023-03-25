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
    $patientID = uniqid("PAT-").rand(100,999);
    $patientName = $_POST['patientName'] ?? '';
    $patientLastname = $_POST['patientLastname'] ?? '';
    $patientGender = $_POST['patientGender'] ?? '';
    $patientBirthdate = $_POST['patientBirthdate'] ?? '';
    $patientWeight = $_POST['patientWeight'] ?? '';
    $patientHeight = $_POST['patientHeight'] ?? '';
    $oldProfile = 'default-avatar.png';

    $patientProfile = (!empty($_FILES['patientProfile']['tmp_name'])) ? $_FILES['patientProfile'] : $oldProfile;

    //2) Check for required parameter
    if($patientName == '' || $patientLastname == '' || $patientGender == '' || $patientBirthdate == '' || $patientWeight == '' || $patientHeight == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลในการลงทะเบียนให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //3) Check if patient exist
    $sql = "SELECT patientID
            FROM patient
            WHERE patientName = ? AND patientLastname = ?
            LIMIT 1;";
    
    $stmt = $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $patientName, $patientLastname);
    $stmt->execute();
    $userResult = $stmt-> get_result();
    $stmt->close();

    if($userResult->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ผู้ป่วยได้ทำการลงทะเบียนในระบบไปแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //4) Try to upload profile if not null
    if($patientProfile != 'default-avatar.png'){
        $uploaddir = '../../assets/img/avatars/';
        
        //Generate profile img name
        list($name, $extension) = explode(".",$patientProfile['name']);
        $name = uniqid("IMG-").rand(100,999);
        $file="$name.$extension";
        
        //Copy img to server
        $uploadfile = $uploaddir.$file;
        if (copy($patientProfile['tmp_name'], $uploadfile)) {
            $patientProfile = $file;          
        }else{
            $response->status = 'warning';
            $response->title = 'เกิดข้อผิดพลาด';
            $response->text = 'ไม่สามารถอัพโหลดรูปที่เลือกได้ โปรดเปลี่ยนรูป';
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            $bpcsDB->close();
            exit();
        }   
    }

    //Pass) Add patient account
    $sql = "INSERT INTO patient(patientID, patientName, patientLastname, patientGender, patientBirthdate, patientWeight, patientHeight, patientProfile)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?);";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('sssssdds', $patientID, $patientName, $patientLastname, $patientGender, $patientBirthdate, $patientWeight, $patientHeight, $patientProfile);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลงทะเบียนผู้ป่วยสำเร็จ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>