<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //1) Exit if user not verified yet.
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
    $cateID = $_POST["cateID"] ?? '';
    $cateName = $_POST["cateName"] ?? '';

    //2) Check for required parameter
    if($cateID == '' || $cateName == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุรายละเอียดให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //3) Check if this cate not exist
    $sql = "SELECT cateID
            FROM categories
            WHERE cateID = ?";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $cateID);
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

    //4) Check if cateName already exist
    $sql = "SELECT cateID
            FROM categories
            WHERE cateName = ? AND cateID <> ?;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $cateName, $cateID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'หมวดหมู่นี้ได้ทำการลงทะเบียนไปแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //Pass) Update category
    $sql = "UPDATE categories
            SET cateName = ?
            WHERE cateID = ?;";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $cateName, $cateID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'แก้ไขข้อมูลหมวดหมู่พืชสำเร็จแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>