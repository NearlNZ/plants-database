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
    $symptomID = $_GET['symptomID'] ?? '';

    //2) Check for required parameter
    if($symptomID == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุรหัสของอาการป่วย';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //3) Check if symptom data exist
    $sql = "SELECT symptomID
    FROM patientsymptom
    WHERE symptomID = ?;";

    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $symptomID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบข้อมูลบันทึกอาการป่วย โปรดตรวจสอบอีกครั้ง';

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $bpcsDB->close();
        exit();
    }

    //Pass) Delete device
    $sql = "DELETE FROM patientSymptom
            WHERE symptomID = ?;";
    
    $stmt =  $bpcsDB->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $symptomID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลบข้อมูลอาการป่วยออกจากระบบสำเร็จแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $bpcsDB->error;
    }

    $bpcsDB->close();
    exit();
?>