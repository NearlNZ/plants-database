<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Set parameter
    $userID = uniqid("USER-").rand(100,999);
    $userFname = $_POST['userFname'] ?? '';
    $userLname = $_POST['userLname'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $userRegist = date("Y-m-d");

    $userProfile = (!empty($_FILES['userProfile']['tmp_name'])) ? $_FILES['userProfile'] : 'default-avatar.png';

    //1) Check for required parameter
    if($userFname == '' || $userLname == '' || $password=="" || $username==""){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check if user already exist
    $sql = "SELECT userID
            FROM users
            WHERE username = ?
            LIMIT 1;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $userResult = $stmt-> get_result();
    $stmt->close();

    if($userResult->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ชื่อผู้ใช้นีิ้ได้ทำการลงทะเบียนไปแล้ว';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //3) Try to upload profile if not null
    $uploaddir = '../../assets/img/avatars/';
        
    //Generate profile img name
    if($userProfile != 'default-avatar.png'){
        list($name, $extension) = explode(".",$userProfile['name']);
        $name = uniqid("USER-").rand(100,999);
        $file="$name.$extension";
            
        //Copy img to server
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

    //Pass) Create account
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(userID, userFname, userLname, userProfile, username, password, userRegist)
            VALUES(?, ?, ?, ?, ?, ?, ?)";
    
    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('sssssss', $userID, $userFname, $userLname, $userProfile, $username, $hashPassword, $userRegist);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'ลงทะเบียนบัญชีผู้ใช้สำเร็จ กลับสู่หน้า Login';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        echo $database->error;
    }

    $database->close();
    exit();
?>