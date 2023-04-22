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
    $tagID = uniqid("TAG-").rand(100,999);
    $tagName = $_POST['tagName'] ?? '';

    //2) Check for required parameter
    if($tagName == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //3) Check if tagName duplicate
    $sql = "SELECT tagID
            FROM tags
            WHERE tagName = ?;";

    $stmt = $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $tagName);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ชื่อหมวดหมู่นี้ได้ทำการลงทะเบียนไปแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //Pass) Create new category
    $sql = "INSERT INTO tags (tagID, tagName)
            VALUES(?, ?);";
    
    $stmt = $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $tagID, $tagName);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'เพิ่มข้อมูลหมวดหมู่พืชสำเร็จ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>