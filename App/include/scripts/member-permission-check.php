<?php
    session_start();

    //1) Check session validation
    if (!isset($_SESSION['CSP-session-userID'])) {
        $response->status = "warning";
        $response->title = "จำกัดสิทธิ์การใช้งาน";
        $response->text = "จำเป็นต้องทำการเข้าสู่ระบบก่อนใช้งาน";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check account existence
    $sql = "SELECT userID, userLevel, userStatus
            FROM users
            WHERE userID = ?;";
    
    $stmt = $database->prepare($sql);
    $stmt->bind_param('s', $_SESSION['CSP-session-userID']);
    $stmt->execute();
    $currentAccountResult = $stmt-> get_result();
    $stmt->close();

    if ($currentAccountResult->num_rows == 0) {
        $response->status = "warning";
        $response->title = "จำกัดสิทธิ์การใช้งาน";
        $response->text = "ไม่สามารถระบุตัวตนได้ ไม่พบบัญชีผู้ใช้ของท่านในระบบ";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    $currentAcc = $currentAccountResult->fetch_assoc();

    //3) Check account suspended status
    if ($currentAcc["userStatus"] != "บัญชีปกติ") {
        $response->status = "warning";
        $response->title = "ไม่สามารถดำเนินการได้";
        $response->text = "บัญชีผู้ใช้ของท่านถูกระงับการใช้งานชั่วคราวโดยผู้ดูแลระบบ";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }
?>