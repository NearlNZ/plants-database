<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../connect.php");

    //1) Exit if user not verified yet.
    session_start();
    if (!isset($_SESSION['BPCS-session-userID'])) {
        $response->status = "warning";
        $response->title = "เกิดข้อผิดพลาด";
        $response->text = "จำเป็นต้องทำการยืนยันตัวตนก่อนใช้งาน";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Set parameter
    $patientID = $_GET['patientID'] ?? '';

    //2) Check for required parameter
    if($patientID == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุรหัสของผู้ป่วย';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //3) Check if patient exist
    $sql = "SELECT patientID, patientProfile
            FROM patient
            WHERE patientID = ?;";

    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $patientID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่มีข้อมูลผู้ป่วยที่ระบุในระบบ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //4) Check if patient using device
    $patient = $result->fetch_assoc();
    $oldProfile = $patient['patientProfile'];

    $sql = "SELECT U.useID, D.deviceSerial
            FROM deviceusing U INNER JOIN device D ON U.deviceID = D.deviceID
            WHERE U.patientID = ? AND U.useStatus = 'กำลังใช้งาน';";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $patientID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows > 0){
        $device = $result->fetch_assoc();
        $deviceSerial = $device['deviceSerial'];
        
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ผู้ป่วยอยู่กำลังใช้งานอุปกรณ์ '.$deviceSerial.' โปรดหยุดการใช้งานอุปกรณ์ก่อนลบข้อมูลผู้ป่วย';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Pass) Delete patient
    $sql = "DELETE FROM patient
            WHERE patientID = ?;";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $patientID);

    if($stmt->execute()){
        $stmt->close();
        
        //Delete profile img if it not default-avatar.png
        if($oldProfile != "default-avatar.png"){
            $oldProfile = "../../assets/img/avatars/".$oldProfile;
            unlink($oldProfile);
        }

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลบข้อมูลผู้ป่วยออกจากระบบสำเร็จแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>