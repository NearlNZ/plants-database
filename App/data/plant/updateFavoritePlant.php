<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Account permission check ("all member" permission)
    require_once("../../include/scripts/member-permission-check.php");

    //Set variables
    $plantID = $_GET['plantID'] ?? '';
    $currentUser = $_SESSION['CSP-session-userID'];

    //==============================================================================
    
    //1) Check for required parameter
    if($plantID == ""){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลที่จำเป็นให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check plant existence
    $sql = "SELECT plantID
            FROM plants
            WHERE plantID = ?
            LIMIT 1;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $plantID);
    $stmt->execute();
    $plantResult = $stmt-> get_result();
    $stmt->close();

    if($plantResult->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบข้อมูลพืชในระบบ โปรดตรวจสอบอีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //3) Check current favorite status
    $sql = "SELECT favID
            FROM favorite_plants
            WHERE plantID = ? AND userID = ?;";
    
    $stmt = $database->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $plantID, $currentUser);
    $stmt->execute();
    $favoriteResult = $stmt-> get_result();
    $stmt->close();

    //==============================================================================

    //Pass) Update favorite plant
    if($favoriteResult->num_rows > 0){
        // Case exist delete favorite list
        $favoritePlant = $favoriteResult->fetch_assoc();
        $favID = $favoritePlant["favID"];
        
        $sql = "DELETE
                FROM favorite_plants
                WHERE favID = '$favID';";
        $stmt =  $database->stmt_init(); 
        $stmt->prepare($sql);

        if($stmt->execute()){
            $stmt->close();
            $response->text = 'นำรายการดังกล่าวออกจากรายการโปรดแล้ว';
            $response->isFavorite = false;
        }else{
            echo $database->error;
            $database->close();
            exit();
        }
    }else{
        // Case not exist insert new favorite list
        $favID = uniqid("F-").rand(100,999);

        $sql = "INSERT INTO favorite_plants(favID, plantID, userID)
                VALUES (?, ?, ?);";
        $stmt =  $database->stmt_init(); 
        $stmt->prepare($sql);
        $stmt->bind_param('sss', $favID, $plantID, $currentUser);

        if($stmt->execute()){
            $stmt->close();
            $response->text = 'เพิ่มรายการดังกล่าวเป็นรายการโปรดแล้ว';
            $response->isFavorite = true;
        }else{
            echo $database->error;
            $database->close();
            exit();
        }
    }

    // Return resrponse data
    $sql = "SELECT count(favID) AS favoriteCount
            FROM favorite_plants
            WHERE plantID = ?;";
    
    $stmt = $database->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param('s', $plantID);
    $stmt->execute();
    $favoriteResult = $stmt-> get_result();
    $stmt->close();

    $plant = $favoriteResult->fetch_assoc();
    $response->status = 'success';
    $response->title = 'ดำเนินการสำเร็จ';
    $response->favoriteCount = number_format($plant['favoriteCount']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
    $database->close();
    exit();
?>