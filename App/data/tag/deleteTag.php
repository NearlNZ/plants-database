<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //1) Exit if user not verified.
    session_start();
    if (!isset($_SESSION['CSP-session-userID'])) {
        echo "จำเป็นต้องทำการยืนยันตัวตนก่อนใช้งาน";
        $database->close();
        exit();
    }

    //Set parameter
    $tagID = $_GET["tagID"] ?? '';

    //2) Check for required parameter
    if($tagID == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //3) Check if this cate not exist
    $sql = "SELECT tagID
            FROM tags
            WHERE tagID = ?";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $tagID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบข้อมูลหมวดหมู่นี้ในระบบ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //Pass) Delete category
    $sql = "DELETE
            FROM tags
            WHERE tagID = ?;";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $tagID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลบข้อมูลหมวดหมู่สำเร็จแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>