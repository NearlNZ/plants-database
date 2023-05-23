<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Account permission check ("all member" permission)
    require_once("../../include/scripts/member-permission-check.php");

    //Set variables
    $tagID = uniqid("T-").rand(100,999);
    $tagAdd = date('Y-m-d H:i:s');
    $tagName = $_POST['tagName'] ?? '';
    $currentUser = $_SESSION['CSP-session-userID'] ?? '';

    //==============================================================================

    //1) Check for required parameter
    if($tagName == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลที่จำเป็นให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check tagName duplicated
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

    //==============================================================================

    //Pass) Create new tag
    $sql = "INSERT INTO tags (tagID, userID, tagName, tagAdd)
            VALUES(?, ?, ?, ?);";
    
    $stmt = $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ssss', $tagID, $currentUser, $tagName, $tagAdd);

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