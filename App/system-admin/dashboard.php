<?php
    //Include database connection
	require_once("../data/database.php");

    //include permission check
    require_once('../include/scripts/admin-header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Dashboard</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="../assets/font/Kanit.css"/>

        <!-- Template CSS -->
        <link rel="stylesheet" href="../assets/css/template.css"/>
        
        <!-- Core JS -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/popper.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="../assets/vendor/select2/select2.css"/>
        <link rel="stylesheet" href="../assets/vendor/perfect-scrollbar/perfect-scrollbar.css"/>
        <link rel="stylesheet" href="../assets/vendor/boxicons/boxicons.css"/>

        <!-- Vendors JS -->
        <script src="../assets/vendor/fontawesome/js/all.min.js"></script>
        <script src="../assets/vendor/select2/select2.js"></script>
        <script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- Page Style -->
        <link rel="stylesheet" href="../assets/css/custom-style.css"/>
    </head>
    <body class="body-light">
        <!-- Wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Sidebar -->
                <?php require_once("../include/components/sidebar-admin.php");?>
                <!-- /Sidebar -->

                <!-- Page -->
                <div class="layout-page">
                    <!-- Navbar -->
                    <?php require_once("../include/components/navbar-account.php");?>
                    <!-- /Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-fluid flex-grow-1 container-p-y">
                            <!-- Breadcrumb & Active menu-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a class="active">Dashboard</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="active-menu-url">dashboard</span>
                            <!-- /Breadcrumb & Active menu-->

                            <?php
                                $yesterday = date('Y-m-d', strtotime('-1 day'));

                                //select plants count data
                                $sql = "SELECT 
                                        (SELECT count(plantID) FROM plants) AS totalPlant,
                                        (SELECT count(tagID) FROM tags) AS totalTag,
                                        (SELECT count(imgID) FROM plant_images) AS totalImg,
                                        (SELECT count(userID) FROM users) AS totalUser;";
                                
                                $todayStatResult = $database->query($sql);
                                $todayStat = $todayStatResult->fetch_assoc();
                        
                                //Check statistics record for yesterday
                                $sql = "SELECT recordID
                                        FROM stat_records
                                        WHERE recordDate = '$yesterday';";
                                $recordResult = $database->query($sql);
                        
                                if ($recordResult->num_rows == 0) {
                                        $statID = uniqid("REC-").rand(100,999);
                        
                                        $sql = "INSERT INTO stat_records(recordID, recordDate, totalPlant, totalTag, totalImg, totalUser)
                                                VALUES(?, ?, ?, ?, ?, ?);";
                        
                                        $stmt = $database->stmt_init(); 
                                        $stmt->prepare($sql);
                                        $stmt->bind_param('ssiiii', $statID, $yesterday, $todayStat['totalPlant'], $todayStat['totalTag'], $todayStat['totalImg'], $todayStat['totalUser']);
                                        $stmt->execute();
                                        $result = $stmt-> get_result();
                                        $stmt->close();
                                }

                                //select statistics data
                                $sql = "SELECT totalPlant, totalTag, totalImg, totalUser
                                        FROM stat_records
                                        WHERE recordDate = '$yesterday';";
                                $yesterdayStatResult = $database->query($sql);
                                $yesterdayStat = $yesterdayStatResult->fetch_assoc();

                                $cardData = array();

                                foreach ($todayStat as $key => $value){
                                    $current = $value;
                                    $previous = $yesterdayStat[$key];

                                    $colorClass = "";
                                    $statDiff = "";
                                    $diff = $current - $previous;

                                    if($diff > 0){
                                        $colorClass = "text-success";
                                        $statDiff = "(+".number_format($diff).")";
                                    }
                                    else if($diff < 0){
                                        $colorClass = "text-danger";
                                        $statDiff = "(".number_format($diff).")";
                                    }

                                    $statArray =    array(  
                                                        "current" => number_format($current), 
                                                        "statDiff" => $statDiff,
                                                        "colorClass" => $colorClass
                                                    );

                                    $cardData[$key] = $statArray;
                                }
                            ?>
                            
                            <!-- Stat card -->
                            <div class="row">
                                <!-- Card plant -->
                                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="card-info">
                                                    <p class="card-text">พืชที่ลงทะเบียน</p>
                                                    <div class="d-flex align-items-end mb-2">
                                                        <h4 class="card-title mb-0 me-2">
                                                            <?php echo $cardData["totalPlant"]["current"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["totalPlant"]["colorClass"]; ?>">
                                                            <?php echo $cardData["totalPlant"]["statDiff"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>เปรียบเทียบจาก 1 วันที่แล้ว</small>
                                                </div>
                                                <div class="card-icon">
                                                    <span class="badge bg-label-success rounded">
                                                        <i class="fa-solid fa-seedling fa-xl m-2"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card plant -->

                                <!-- Card tag -->
                                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="card-info">
                                                    <p class="card-text">หมวดหมู่พืช</p>
                                                    <div class="d-flex align-items-end mb-2">
                                                        <h4 class="card-title mb-0 me-2">
                                                            <?php echo $cardData["totalTag"]["current"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["totalTag"]["colorClass"]; ?>">
                                                            <?php echo $cardData["totalTag"]["statDiff"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>เปรียบเทียบจาก 1 วันที่แล้ว</small>
                                                </div>
                                                <div class="card-icon">
                                                    <span class="badge bg-label-warning rounded">
                                                        <i class="fa-solid fa-tags fa-xl m-2"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card tag -->

                                <!-- Card img -->
                                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="card-info">
                                                    <p class="card-text">คลังรูป</p>
                                                    <div class="d-flex align-items-end mb-2">
                                                        <h4 class="card-title mb-0 me-2">
                                                            <?php echo $cardData["totalImg"]["current"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["totalImg"]["colorClass"]; ?>">
                                                            <?php echo $cardData["totalImg"]["statDiff"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>เปรียบเทียบจาก 1 วันที่แล้ว</small>
                                                </div>
                                                <div class="card-icon">
                                                    <span class="badge bg-label-info rounded">
                                                        <i class="fa-solid fa-image fa-xl m-2"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card img -->

                                <!-- Card user -->
                                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="card-info">
                                                    <p class="card-text">บัญชีผู้ใช้</p>
                                                    <div class="d-flex align-items-end mb-2">
                                                        <h4 class="card-title mb-0 me-2">
                                                            <?php echo $cardData["totalUser"]["current"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["totalUser"]["colorClass"]; ?>">
                                                            <?php echo $cardData["totalUser"]["statDiff"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>เปรียบเทียบจาก 1 วันที่แล้ว</small>
                                                </div>
                                                <div class="card-icon">
                                                    <span class="badge bg-label-primary rounded">
                                                        <i class="fa-solid fa-user fa-xl m-2"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card user -->
                            </div>
                            <!-- /Stat card -->
                        </div>
                        <!-- /Content -->

                        <!-- Footer -->
                        <?php require_once("../include/components/footer.php");?>
                        <!-- /Footer -->

                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- /Content wrapper -->
                </div>
                <!-- /Page -->
            </div>

            <!-- Page overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- /Wrapper -->

        <!-- Template JS -->
        <script src="../assets/js/template.js"></script>

        <!-- Page JS -->
        <script src="../include/scripts/customFunctions.js"></script>
        <script>
            
        </script>
    </body>
</html>

<?php
    //Close connection
    $database->close();
?>