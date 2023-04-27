<?php
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        //select plants count data
        $sql = "SELECT 
                (SELECT count(plantID) FROM plants) AS plantCount,
                (SELECT count(tagID) FROM tags) AS tagCount,
                (SELECT count(imgID) FROM plant_images) AS imgCount,
                (SELECT count(userID) FROM users) AS userCount;";
        
        $todayStatResult = $database->query($sql);
        $todayStat = $todayStatResult->fetch_assoc();

        //Check statistics record for yesterday
        $sql = "SELECT statID
                FROM stat_records
                WHERE statDate = '$yesterday';";
        $recordResult = $database->query($sql);

        if ($recordResult->num_rows == 0) {
                $statID = uniqid("REC-").rand(100,999);

                $sql = "INSERT INTO stat_records(recordID, recordDate, totalPlant, totalTag, totalImg, totalUser)
                        VALUES(?, ?, ?, ?, ?, ?);";
                $database->query($sql);

                $stmt = $database->stmt_init(); 
                $stmt->prepare($sql);
                $stmt->bind_param('ssiiii', $statID, $yesterday, $todayStat['plantCount'], $todayStat['tagCount'], $todayStat['imgCount'], $todayStat['userCount']);
                $stmt->execute();
                $result = $stmt-> get_result();
                $stmt->close();
        }
?>