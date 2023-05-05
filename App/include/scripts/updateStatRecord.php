<?php
    $today = date('Y-m-d');
    $lastDay = date('Y-m-d', strtotime('-1 day'));

    //Check for last statistis data, Insert if not exist
    $sql = "SELECT recordID
            FROM stat_records
            WHERE recordDate = '$lastDay';";
    $lastRecord = $database->query($sql);

    if($lastRecord->num_rows == 0){
        //Count current statistis data
        $sql = "SELECT 
                (SELECT count(plantID) FROM plants WHERE plantRegist < '$today') AS plantCount,
                (SELECT count(tagID) FROM tags WHERE tagAdd < '$today') AS tagCount,
                (SELECT count(imgID) FROM plant_images WHERE imgUpload < '$today') AS imgCount,
                (SELECT count(userID) FROM users WHERE userRegist < '$today') AS userCount;";
        $lastStat = $database->query($sql);
        $lastStat = $lastStat->fetch_assoc();

        //Insert new statistics record
        $statID = uniqid("REC-").rand(100,999);

        $sql = "INSERT INTO stat_records(recordID, recordDate, plantCount, tagCount, imgCount, userCount)
                VALUES(?, ?, ?, ?, ?, ?);";

        $stmt = $database->stmt_init(); 
        $stmt->prepare($sql);
        $stmt->bind_param('ssiiii', $statID, $lastDay, $lastStat['plantCount'], $lastStat['tagCount'], $lastStat['imgCount'], $lastStat['userCount']);
        $stmt->execute();
        $result = $stmt-> get_result();
        $stmt->close();
    }
?>