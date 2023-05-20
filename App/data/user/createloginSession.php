<?php
    header('Content-Type: application/json; charset=utf-8');
    session_start();
    $response = new stdClass();
    require_once("../database.php");

    //==============================================================================

    //Set parameter
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    //1) Check for required parameter
    if($username == '' || $password == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุชื่อผู้ใช้และรหัสผ่านของท่าน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check if username exist
    $sql = "SELECT userID, password, userLevel, userStatus
            FROM users
            WHERE username = ? 
            LIMIT 1;";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $username);
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

    //3) Check if password not match
    $user=$userResult->fetch_assoc();
    if(!password_verify($password, $user['password'])){
        $response->status = "warning";
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = "รหัสผ่านไม่ถูกต้อง โปรดตรวจสอบอีกครั้ง";

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //4) Check if account suspended
    if($user['userStatus'] == "บัญชีถูกระงับ"){
        $response->status = "warning";
        $response->title = 'จำกัดการใช้งาน';
        $response->text = "บัญชีผู้ใช้ของท่านถูกระงับการใช้งานชั่วคราวโดยผู้ดูแลระบบ";

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //==============================================================================

    //Pass) Create session for user
    $_SESSION['CSP-session-userID'] = $user['userID'];
    $_SESSION['CSP-session-userLevel'] = $user['userLevel'];

    $response->status = "success";
    $response->text = "กำลังเข้าสู่ระบบ กรุณารอสักครู่...";

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

    //==============================================================================

    function getDeviceType() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $deviceTypes = array(
            'สมาร์ทโฟน' => array('iPhone', 'Android', 'Windows Phone'),
            'แท็บเล็ต' => array('iPad', 'Android Tablet'),
            'คอมพิวเตอร์' => array('Windows', 'Macintosh', 'Linux')
        );

        foreach ($deviceTypes as $device => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($userAgent, $keyword) !== false) {
                    return $device;
                }
            }
        }

        return 'อุปกรณ์ที่ไม่รู้จัก';
    }

    //Timestamp last login
    $userID = $user['userID'];
    $logID = uniqid("LOG-").rand(100,999);
    $logTimestamp = date('Y-m-d H:i:s');
    $logDevice = getDeviceType();

    $sql = "INSERT INTO login_records (logID, logTimestamp, logDevice, userID)
            VALUES (?, ?, ?, ?);";

    $stmt =  $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ssss', $logID, $logTimestamp, $logDevice, $userID);
    $stmt->execute();
    $stmt->close();

    $database->close();
    exit();
?>