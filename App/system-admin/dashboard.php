<?php
    //Include database connection
	require_once("../data/database.php");

    //Include admin account check
    require_once('../include/scripts/admin-header.php');

    //include statistics update
    include("../include/scripts/updateStatRecord.php");
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Dashboard</title>
        <link rel="shortcut icon" href="../assets/img/element/tab-logo.ico" type="image/x-icon">

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
        <link rel="stylesheet" href="../assets/vendor//perfect-scrollbar/perfect-scrollbar.css"/>
        <link rel="stylesheet" href="../assets/vendor/boxicons/boxicons.css"/>

        <!-- Vendors JS -->
        <script src="../assets/vendor/fontawesome/js/all.min.js"></script>
        <script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="../assets/vendor/select2/select2.js"></script>
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
                            
                            <!-- Stat card -->
                            <div class="row">
                                <?php
                                    //Select current count data
                                    $sql = "SELECT 
                                            (SELECT count(plantID) FROM plants) AS plantCount,
                                            (SELECT count(tagID) FROM tags) AS tagCount,
                                            (SELECT count(imgID) FROM plant_images) AS imgCount,
                                            (SELECT count(userID) FROM users) AS userCount;";
                                    
                                    $currentStat = $database->query($sql);
                                    $currentStat = $currentStat->fetch_assoc();

                                    //Select last count data
                                    $sql = "SELECT recordDate, plantCount, tagCount, imgCount, userCount
                                            FROM stat_records
                                            ORDER BY recordDate DESC
                                            LIMIT 1;";
                                    $lastStatResult = $database->query($sql);

                                    $cardData = array();
                                    $lastStat = $lastStatResult->num_rows > 0 ? $lastStatResult->fetch_assoc() : [];

                                    //Set each stat card property to show
                                    foreach ($currentStat as $key => $value){
                                        $currentCount = $value;
                                        $lastCount = $lastStat[$key] ?? 0;

                                        $statColor = "";
                                        $statText = "";
                                        $difference = $currentCount - $lastCount;

                                        if($difference > 0){
                                            $statColor = "text-success";
                                            $statText = "(+".number_format($difference).")";
                                        }
                                        else if($difference < 0){
                                            $statColor = "text-danger";
                                            $statText = "(".number_format($difference).")";
                                        }

                                        $statArray =    array(  
                                                            "currentCount" => number_format($currentCount), 
                                                            "statText" => $statText,
                                                            "statColor" => $statColor
                                                        );

                                        $cardData[$key] = $statArray;
                                    }
                                ?>

                                <!-- Card plant -->
                                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="card-info">
                                                    <p class="card-text">พืชที่ลงทะเบียน</p>
                                                    <div class="d-flex align-items-end mb-2">
                                                        <h4 class="card-title mb-0 me-2">
                                                            <?php echo $cardData["plantCount"]["currentCount"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["plantCount"]["statColor"]; ?>">
                                                            <?php echo $cardData["plantCount"]["statText"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>บันทึกล่าสุด <?php echo date("j/n/Y", strtotime($lastStat["recordDate"])); ?></small>
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
                                                            <?php echo $cardData["tagCount"]["currentCount"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["tagCount"]["statColor"]; ?>">
                                                            <?php echo $cardData["tagCount"]["statText"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>บันทึกล่าสุด <?php echo date("j/n/Y", strtotime($lastStat["recordDate"])); ?></small>
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
                                                    <p class="card-text">คลังภาพ</p>
                                                    <div class="d-flex align-items-end mb-2">
                                                        <h4 class="card-title mb-0 me-2">
                                                            <?php echo $cardData["imgCount"]["currentCount"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["imgCount"]["statColor"]; ?>">
                                                            <?php echo $cardData["imgCount"]["statText"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>บันทึกล่าสุด <?php echo date("j/n/Y", strtotime($lastStat["recordDate"])); ?></small>
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
                                                            <?php echo $cardData["userCount"]["currentCount"]; ?>
                                                        </h4>
                                                        
                                                        <small class="<?php echo $cardData["userCount"]["statColor"]; ?>">
                                                            <?php echo $cardData["userCount"]["statText"]; ?>
                                                        </small>
                                                    </div>
                                                    <small>บันทึกล่าสุด <?php echo date("j/n/Y", strtotime($lastStat["recordDate"])); ?></small>
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