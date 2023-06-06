<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Account permission check ("all member" permission)
    require_once("../../include/scripts/admin-permission-check.php");

    //Set variables
    $userID = $_POST['userID'] ?? '';
    $userFname = $_POST['userFname'] ?? '';
    $userLname = $_POST['userLname'] ?? '';
    $userLevel = $_POST['userLevel'] ?? '';
    $userStatus = $_POST['userStatus'] ?? '';
    $userCurrentProfile = $_POST['userCurrentProfile'] ?? 'default-avatar.png';
    $userProfile = (!empty($_FILES['userProfile']['tmp_name'])) ? $_FILES['userProfile'] : $userCurrentProfile;

    //==============================================================================
    
    //1) Check for required parameter
    if($userID == "" || $userFname == "" || $userLname == "" || $userLevel == "" || $userStatus == ""){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลที่จำเป็นให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check account existence
    $sql = "SELECT userID, userProfile, userLevel
            FROM users
            WHERE userID = ?
            LIMIT 1;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $userResult = $stmt-> get_result();
    $stmt->close();

    if($userResult->num_rows == 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ไม่พบบัญชีผู้ใช้ในระบบ โปรดตรวจสอบอีกครั้ง';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }
    $user = $userResult->fetch_assoc();

    //3) Check minimum admin account if change level of admin account
    if($user["userLevel"] == "ผู้ดูแลระบบ" && $userLevel != "ผู้ดูแลระบบ"){
        $sql = "SELECT userID
            FROM users
            WHERE userID <> ? AND userLevel = 'ผู้ดูแลระบบ';";

        $stmt =  $database->stmt_init(); 
        $stmt->prepare($sql);
        $stmt->bind_param('s', $userID);
        $stmt->execute();
        $adminResult = $stmt-> get_result();
        $stmt->close();

        if($adminResult->num_rows < 1){
            $response->status = 'warning';
            $response->title = 'เกิดข้อผิดพลาด';
            $response->text = 'ไม่สามารถเปลียนระดับบัญชีผู้ใช้ได้ จำเป็นต้องมีบัญชีสำหรับผู้ดูแลระบบอย่างน้อย 1 บัญชี';
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            $database->close();
            exit();
        }
    }

    //4) Try to upload new profile if not "default-avatar.png"
    if($userProfile != 'default-avatar.png' && $userProfile != $userCurrentProfile){
        $uploaddir = '../../assets/img/avatars/';

        //Generate profile img name
        list($name, $extension) = explode(".",$userProfile['name']);
        $name = uniqid("UIMG-").rand(100,999);
        $file="$name.$extension";
            
        //Copy img to server storage
        $uploadfile = $uploaddir.$file;
        if (copy($userProfile['tmp_name'], $uploadfile)) {
            $userProfile = $file;          
        }else{
            $response->status = 'warning';
            $response->title = 'เกิดข้อผิดพลาด';
            $response->text = 'ไม่สามารถอัพโหลดรูปที่เลือกได้ โปรดเปลี่ยนรูปแล้วลองอีกครั้ง';
                
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            $database->close();
            exit();
        }
    }

    //5) Try to delete old profile image if user upload new
    if($user["userProfile"] != "default-avatar.png" && $userProfile != $userCurrentProfile){
        $imgPath = "../../assets/img/avatars/";
        $img = $imgPath.$user["userProfile"];
        unlink($img);
    }

    //==============================================================================

    //Pass) Update account
    $sql = "UPDATE users
            SET userFname = ?, userLname = ?, userProfile = ?, userLevel = ?, userStatus = ?
            WHERE userID = ?;";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ssssss', $userFname, $userLname, $userProfile, $userLevel, $userStatus, $userID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'บันทึกการแก้ไขข้อมูลผู้ใช้แล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>