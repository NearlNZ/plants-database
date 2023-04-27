<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //1) Exit if user not verified.
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
    $plantID = $_GET["plantID"] ?? '';

    //2) Check for required parameter
    if($plantID == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //3) Check if this plant not exist
    $sql = "SELECT plantID
            FROM plants
            WHERE plantID = ?";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $plantID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();

    if($result->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบข้อมูลพืชรายการนี้ในระบบ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //4) Remove all plant img
    $sql = "SELECT imgID, imgPath
            FROM plantimages
            WHERE plantID = ?";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $plantID);
    $stmt->execute();
    $result = $stmt-> get_result();
    $stmt->close();
    if($result -> num_rows > 0){
        while($img = $result->fetch_assoc()){
            $imgFile = "../../assets/img/plantImgs/".$img["imgPath"];
            unlink("$imgFile");
        }
    }

    //Pass) Delete plant
    $sql = "DELETE
            FROM plants
            WHERE plantID = ?;";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $plantID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลบข้อมูลพืชสำเร็จแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>