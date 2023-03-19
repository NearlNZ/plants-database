<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../connect.php");

    //1) Exit if user not verified yet.
    session_start();
    if (!isset($_SESSION['BPCS-session-account'])) {
        $response->status = "warning";
        $response->title = "เกิดข้อผิดพลาด";
        $response->text = "จำเป็นต้องทำการยืนยันตัวตนก่อนใช้งาน";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Set parameter
    $deviceID = uniqid("DEVICE-").rand(100,999);
    $deviceSerial = $_POST['deviceSerial'] ?? null; 
    $deviceCameraIP = $_POST['deviceCameraIP'] ?? null;
    $deviceRegist = date('Y-m-d');
    $deviceStatus = "อุปกรณ์ว่าง";

    //2) Check for required parameter
    if($deviceSerial == null){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุหมายเลข Serial ของอุปกรณ์';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //3) Check if serial already exist
    $sql = "SELECT deviceID
            FROM device
            WHERE deviceSerial = ?;";

    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $deviceSerial);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'อุปกรณ์นี้ได้ทำการลงทะเบียนไปแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //4) Validate URL
    if(!empty($deviceCameraIP) && !filter_var($deviceCameraIP, FILTER_VALIDATE_URL)){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุ VDO Streaming URL ที่ถูกต้อง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Pass) Create new device
    $sql = "INSERT INTO device(deviceID, deviceSerial, deviceCameraIP, deviceRegist, deviceStatus)
            VALUES(?, ?, ?, ?, ?);";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('sssss', $deviceID, $deviceSerial, $deviceCameraIP, $deviceRegist, $deviceStatus);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลงทะเบียนอุปกรณ์สำเร็จแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>