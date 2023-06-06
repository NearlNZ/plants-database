<?php
    header('Content-Type: application/json; charset=utf-8');
    $response = new stdClass();
    require_once("../database.php");

    //Account permission check ("all member" permission)
    require_once("../../include/scripts/member-permission-check.php");
    
    //Set variables
    $currentUser = $_SESSION['CSP-session-userID'] ?? '';

    $plantID = uniqid("P-").rand(100,999);
    $plantName = $_POST["plantName"] ?? '';
    $commonName = $_POST["commonName"] ?? "";
    $otherName = $_POST["otherName"] ?? "";
    $scientificName = $_POST["scientificName"] ?? "";
    $familyName = $_POST["familyName"] ?? "";
    $plantRegist = date("Y-m-d H:i:s");

    $plantTags =  $_POST["tags"] ?? Null;

    $plantTrunk = !empty($_POST["plantTrunk"]) ? nl2br($_POST["plantTrunk"]) : "";
    $plantLeaf = !empty($_POST["plantLeaf"]) ? nl2br($_POST["plantLeaf"]) : "";
    $plantFlower = !empty($_POST["plantFlower"]) ? nl2br($_POST["plantFlower"]) : "";
    $plantFruit = !empty($_POST["plantFruit"]) ? nl2br($_POST["plantFruit"]) : "";

    $plantCultivation = !empty($_POST["plantCultivation"]) ? nl2br($_POST["plantCultivation"]) : "";
    $plantPropagation = !empty($_POST["plantPropagation"]) ? nl2br($_POST["plantPropagation"]) : "";

    $plantUtilization = !empty($_POST["plantUtilization"]) ? nl2br($_POST["plantUtilization"]) : "";

    $plantImg = !empty($_FILES['plantImg']['name'][0]) ? $_FILES['plantImg']['name'] : Null;

    //==============================================================================

    //1) Check for required parameter
    if($plantName == ''){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'โปรดระบุข้อมูลที่จำเป็นให้ครบถ้วน';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }

    //2) Check plant duplicated
    $sql = "SELECT plantID
            FROM plants
            WHERE plantName = ?;";

    $stmt = $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('s', $plantName);
    $stmt->execute();
    $plantResult = $stmt-> get_result();
    $stmt->close();

    if($plantResult->num_rows > 0){
        $response->status = 'warning';
        $response->title = 'เกิดข้อผิดพลาด';
        $response->text = 'ชื่อพืชนี้ได้ทำการลงทะเบียนไปแล้ว';

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $database->close();
        exit();
    }
    
    //3) Insert empty record for foreign key constraint
    $sql = "INSERT INTO plants(plantID, plantname)
            VALUES (?, ?);";

    $stmt = $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('ss', $plantID, $plantName);
    
    if(!($stmt->execute())){
        $stmt->close();
        echo $database->error;
        $database->close();
        exit();
    }

    //Function to remove empty record when error detect
    function removeTempRecord($database, $plantID){
        $sql = "DELETE 
                FROM plants
                WHERE plantID = ?;";

        $stmt = $database->stmt_init(); 
        $stmt->prepare($sql);
        $stmt->bind_param('s', $plantID);
        $stmt->execute();
        $stmt->close();
    }

    //4) Try to upload plant images if not null
    if($plantImg != Null){
        $uploadPath = '../../assets/img/plantImgs/';
        
        foreach ($_FILES['plantImg']['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $name = uniqid("I-").rand(100,999);

                $extension = pathinfo($_FILES['plantImg']['name'][$key], PATHINFO_EXTENSION);
                $filename = "$name.$extension";
                $uploadfile = $uploadPath . $filename;
                if (move_uploaded_file($_FILES['plantImg']['tmp_name'][$key], $uploadfile)) {
                    
                    $sql = "INSERT INTO plant_images(imgID, plantID, userID, imgPath, imgUpload)
                            VALUES(?, ?, ?, ?, ?);";
                    
                    $stmt =  $database->stmt_init(); 
                    $stmt->prepare($sql);
                    $stmt->bind_param('sssss', $name, $plantID, $currentUser, $filename, $plantRegist);

                    if(!($stmt->execute())){
                        echo $database->error;
                        removeTempRecord($database, $plantID);
                    }else{
                        $stmt->close();
                    }
                } else {
                    // Handle upload error
                    removeTempRecord($database, $plantID);
                    $response->status = 'warning';
                    $response->title = 'เกิดข้อผิดพลาด';
                    $response->text = 'ไม่สามารถอัพโหลดไฟล์ ' . $_FILES['plantImg']['name'][$key] . ' ได้';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    $database->close();
                    exit();
                }
            } else {
                // Handle upload error
                removeTempRecord($database, $plantID);
                $response->status = 'warning';
                $response->title = 'เกิดข้อผิดพลาด';
                $response->text = 'ไม่สามารถอัพโหลดไฟล์ ' . $_FILES['plantImg']['name'][$key] . ' ได้';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                $database->close();
                exit();
            }
        }
    }

    //5) try to add tag lists if not null
    if($plantTags != Null){
        foreach ($plantTags as $tag){
            $listID = uniqid("L-").rand(100,999);
            $sql = "INSERT INTO tag_lists(listID, tagID, plantID)
                    VALUES(?, ?, ?);";

            $stmt = $database->stmt_init(); 
            $stmt->prepare($sql);
            $stmt->bind_param('sss', $listID, $tag, $plantID);
            
            if(!($stmt->execute())){
                echo $database->error;
                removeTempRecord($database, $plantID);
            }else{
                $stmt->close();
            }
        }
    }

    //==============================================================================

    //Pass) Update new plant
    $sql = "UPDATE plants
            SET userID = ?, plantName = ?, commonName = ?, otherName = ?, scientificName = ?, familyName = ?, 
                plantTrunk = ?, plantLeaf = ?, plantFlower = ?, plantFruit = ?, plantCultivation = ?, plantPropagation = ?,
                plantUtilization = ?, plantRegist = ?
            WHERE plantID = ?;";
    
    $stmt = $database->stmt_init(); 
    $stmt->prepare($sql);
    $stmt->bind_param('sssssssssssssss',    $currentUser, $plantName, $commonName, $otherName, $scientificName, $familyName, 
                                            $plantTrunk, $plantLeaf, $plantFlower, $plantFruit, $plantCultivation, $plantPropagation,
                                            $plantUtilization, $plantRegist, $plantID);

    if($stmt->execute()){
        $stmt->close();

        $response->status = 'success';
        $response->title = 'ดำเนินการสำเร็จ';
        $response->text = 'เพิ่มข้อมูลพืชสำเร็จ';
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }else{
        removeTempRecord($database, $plantID);
        echo $database->error;
    }

    $database->close();
    exit();
?>