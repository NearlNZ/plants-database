<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //1) Exit if user not verified key yet.
    session_start();
    if (!isset($_SESSION['CSP-session-userID'])) {
        $response->status = "warning";
        $response->title = "เกิดข้อผิดพลาด";
        $response->text = "จำเป็นต้องทำการยืนยันตัวตนก่อนใช้งาน";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //Set variables
    $plantID = $_POST['plantID'] ?? '';
    $plantImg = $_POST['plantImg'] ?? null;

    $plantImg = (!empty($_FILES['plantImg']['tmp_name'])) ? $_FILES['plantImg'] : Null;

    //2) Check for required parameter
    if($plantID == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลที่จำเป็นให้ครบถ้วน';
        
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

    //4) Try to upload profile if not null
    $plantImg = (!empty($_FILES['plantImg']['tmp_name'])) ? $_FILES['plantImg'] : Null;
    if($plantImg != Null){
        $uploaddir = '../../assets/img/plantImgs/';
        $date = date("Y-m-d");
        $plantImg = array();
        
        foreach ($_FILES['plantImg']['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $name = uniqid("IMG-").rand(100,999);

                $extension = pathinfo($_FILES['plantImg']['name'][$key], PATHINFO_EXTENSION);
                $filename = "$name.$extension";
                $uploadfile = $uploaddir . $filename;
                if (move_uploaded_file($_FILES['plantImg']['tmp_name'][$key], $uploadfile)) {
                    //Add plantImg
                    $sql = "INSERT INTO plantimages(imgID, plantID, imgPath, imgUpload)
                            VALUES(?, ?, ?, ?);";
                    
                    $stmt =  $database->stmt_init(); 
                    $stmt->prepare($sql);
                    $stmt->bind_param('ssss', $name, $plantID, $filename, $date);

                    if(!($stmt->execute())){
                        echo $database->error;
                    }else{
                        $stmt->close();
                    }
                } else {
                    // Handle upload error
                    $response->status = 'warning';
                    $response->title = 'เกิดข้อผิดพลาด';
                    $response->text = 'ไม่สามารถอัพโหลดไฟล์ ' . $_FILES['plantImg']['name'][$key] . ' ได้';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    $database->close();
                    exit();
                }
            } else {
                // Handle upload error
                $response->status = 'warning';
                $response->title = 'เกิดข้อผิดพลาด';
                $response->text = 'ไม่สามารถอัพโหลดไฟล์ ' . $_FILES['plantImg']['name'][$key] . ' ได้';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                $database->close();
                exit();
            }
        }
    }

    //Pass) Return message
    $response->status = 'success';
    $response->title = 'ดำเนินการสำเร็จ';
    $response->text = 'เพิ่มรูปภาพพืชสำเร็จแล้ว';
        
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

    $database->close();
    exit();
?>