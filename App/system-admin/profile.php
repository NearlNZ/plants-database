<?php
    //Include database connection
	require_once("../data/database.php");

    //Include admin account check
    require_once('../include/scripts/admin-header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>บัญชีของฉัน</title>
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
        <link rel="stylesheet" href="../assets/vendor//perfect-scrollbar/perfect-scrollbar.css"/>
        <link rel="stylesheet" href="../assets/vendor/boxicons/boxicons.css"/>

        <!-- Vendors JS -->
        <script src="../assets/vendor/fontawesome/js/all.min.js"></script>
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
                            <!-- Card profile banner -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="user-profile-header-banner">
                                            <img src="../assets/img/element/default-profile-banner.jpg" alt="profile banner" class="rounded-top">
                                        </div>
                                        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                                            <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                                                <img src="../assets/img/avatars/<?php echo $currentUser->userProfile; ?>" alt="user avatar" class="d-block ms-0 ms-sm-4 rounded user-profile-img fit-cover">
                                            </div>
                                            <div class="flex-grow-1 mt-3 mt-sm-5">
                                                <div class="d-flex align-items-md-end align-items-center justify-content-between mx-4 flex-sm-row flex-column gap-4">
                                                    <div class="user-profile-info">
                                                        <h4 class="fw-bold">
                                                            <?php echo $currentUser->userFname." ".$currentUser->userLname;?>
                                                        </h4>
                                                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                                            <li class="list-inline-item">
                                                                <i class="fa-solid fa-user text-primary me-1"></i>
                                                                <?php echo $currentUser->username;?>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <i class="fa-solid fa-user-tag text-primary me-1"></i> 
                                                                <?php echo $currentUser->userLevel;?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="row">
                                                        <a href="profile-edit" class="btn btn-primary text-nowrap col-auto me-1">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                            <p class="d-inline d-sm-none d-lg-inline ms-1">แก้ไขข้อมูลผู้ใช้</p>
                                                        </a>
                                                        <a href="#" onclick="goBack()" class="btn btn-label-secondary text-nowrap col-auto">
                                                            <i class="fa-solid fa-chevron-left"></i>
                                                            <p class="d-inline d-sm-none d-lg-inline ms-1">ย้อนกลับ</p>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Card profile banner -->

                            <div class="row g-3">
                                <!-- Left section -->
                                <div class="col-xxl-4 col-xl-12 col-lg-5">
                                    <div class="row p-0 g-3">
                                        <div class="col-md-6 col-lg-12 col-xl-6 col-xxl-12">
                                            <!-- Card profile info -->
                                            <div class="accordion h-100" id="accordionInfo">
                                                <div class="card accordion-item h-100">
                                                    <!-- Card head for "sm" -->
                                                    <h2 class="accordion-header py-2 d-block d-md-none">
                                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#cardInfo" aria-expanded="false" aria-controls="cardInfo" role="tabpanel">
                                                            <i class="fa-solid fa-address-book me-2"></i>
                                                            ข้อมูลบัญชีผู้ใช้
                                                        </button>
                                                    </h2>
                                                    <!-- /Card head for "sm" -->
                                                    <!-- Card head for "md" and above -->
                                                    <h2 class="accordion-header py-1 d-none d-md-block">
                                                        <span class="accordion-button d-block">
                                                            <i class="fa-solid fa-address-book me-2"></i>
                                                            ข้อมูลบัญชีผู้ใช้
                                                        </span>
                                                    </h2>
                                                    <!-- /Card head for "md" and above -->
                                                    <div id="cardInfo" class="accordion-collapse collapse d-md-block" data-bs-parent="#accordionInfo">
                                                        <div class="accordion-body border-top">
                                                            <table class="table table-borderless my-3">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-seondary">
                                                                            <i class="fa-solid fa-message me-2"></i>
                                                                            ชื่อ-สกุล
                                                                        </td>
                                                                        <td class="text-start">
                                                                            <?php echo $currentUser->userFname." ".$currentUser->userLname;?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-seondary">
                                                                            <i class="fa-solid fa-user me-2"></i>
                                                                            Username
                                                                        </td>
                                                                        <td td class="text-start">
                                                                            <?php echo $currentUser->username;?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-seondary">
                                                                            <i class="fa-solid fa-user-tag me-1"></i>
                                                                            ระดับผู้ใช้
                                                                        </td>
                                                                        <td td class="text-start">
                                                                            <?php echo $currentUser->userLevel; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-seondary">
                                                                            <i class="fa-solid fa-calendar me-2"></i>
                                                                            วันลงทะเบียน
                                                                        </td>
                                                                        <td td class="text-start">
                                                                            <?php echo date("j/n/Y", strtotime($currentUser->userRegist));?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold text-seondary">
                                                                            <i class="fa-solid fa-circle-check me-2"></i>
                                                                            สถานะบัญชี
                                                                        </td>
                                                                        <td td class="text-start">
                                                                            <?php
                                                                                $statusText = $currentUser->userStatus;
                                                                                $statusColorClass = "bg-label-success text-success";
                                                                                if($statusText == "บัญชีถูกระงับ"){
                                                                                    $statusColorClass = "bg-label-danger text-danger";
                                                                                }
                                                                            ?>

                                                                            <span class="badge rounded-pill <?php echo $statusColorClass; ?>">
                                                                                <?php echo $statusText; ?>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Card profile info -->
                                        </div>
                                        <div class="col">
                                            <!-- Card profile activities -->
                                            <div class="accordion h-100" id="accordionActivities">
                                                <div class="card accordion-item h-100">
                                                    <!-- Card head for "sm" -->
                                                    <h2 class="accordion-header py-2 d-block d-md-none">
                                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#cardActivities" aria-expanded="true" aria-controls="cardActivities" role="tabpanel">
                                                            <i class="fa-solid fa-arrow-trend-up me-2"></i>
                                                            กิจกรรมของผู้ใช้
                                                        </button>
                                                    </h2>
                                                    <!-- /Card head for "sm" -->
                                                    <!-- Card head for "md" and above -->
                                                    <h2 class="accordion-header py-1 d-none d-md-block">
                                                        <span class="accordion-button d-block">
                                                            <i class="fa-solid fa-arrow-trend-up me-2"></i>
                                                            กิจกรรมของผู้ใช้
                                                        </span>
                                                    </h2>
                                                    <!-- /Card head for "md" and above -->
                                                    <div id="cardActivities" class="accordion-collapse collapse d-md-block" data-bs-parent="#accordionActivities">
                                                        <div class="accordion-body border-top">
                                                            <?php
                                                                //Select user activities (count of plant they add etc.)
                                                                $userID = $currentUser->userID;
                                                                $sql = "SELECT
                                                                        (SELECT count(favID) FROM favorite_plants WHERE userID = '$userID') AS favoriteCount,
                                                                        (SELECT count(plantID) FROM plants WHERE userID = '$userID') AS plantAdd,
                                                                        (SELECT count(tagID) FROM tags WHERE userID = '$userID') AS tagAdd,
                                                                        (SELECT count(imgID) FROM plant_images WHERE userID = '$userID') AS imgAdd,
                                                                        (SELECT max(logTimestamp) FROM login_records WHERE userID = '$userID') AS lastLogin;";
                                                                $activitiesresult = $database->query($sql);
                                                                $activities = $activitiesresult->fetch_assoc();
                                                            ?>

                                                            <table class="table table-borderless my-3">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold">
                                                                            <i class="fa-solid fa-seedling text-success me-2"></i>
                                                                            เพิ่มข้อมูลพืช
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <?php echo number_format($activities["plantAdd"]);?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold">
                                                                            <i class="fa-solid fa-tags text-warning me-2"></i>
                                                                            เพิ่มหมวดหมู่พืช 
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <?php echo number_format($activities["tagAdd"]);?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold">
                                                                            <i class="fa-solid fa-image text-info me-2"></i>
                                                                            เพิ่มภาพพืช
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <?php echo number_format($activities["imgAdd"]);?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-start fw-semibold">
                                                                            <i class="fa-solid fa-heart text-danger me-2"></i>
                                                                            รายการโปรด
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <?php echo number_format($activities["favoriteCount"]);?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            <div class="text-center text-muted">
                                                                ---
                                                                <?php 
                                                                    $lastlogin = !empty($activities["lastLogin"]) ? date("เข้าใช้งานล่าสุดเมื่อ j/n/Y H:i", strtotime($activities["lastLogin"])) : "ไม่มีบันทึกการใช้งาน";
                                                                    echo $lastlogin;
                                                                ?>
                                                                ---
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Card profile activities -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /Left section -->

                                <!-- Right section -->
                                <div class="col">
                                    <!--Card pills data table -->
                                    <?php
                                        $tabList = ['favorite', 'plants', 'login'];
                                        $activeTab = $_GET['tab'] ?? 'favorite';
                                        $activeTab = in_array($activeTab, $tabList) ? $activeTab : 'favorite';
                                    ?>

                                    <div class="nav-align-top h-100">
                                        <ul class="nav nav-pills justify-content-center justify-content-md-start mb-3" role="tablist">
                                            <li class="nav-item">
                                                <a href="?tab=favorite" class="nav-link <?php if($activeTab == 'favorite') echo 'active';?>">
                                                    <i class="fa-solid fa-heart"></i>
                                                    <span class="ms-1 d-none d-md-inline-block">รายการโปรด</span>
                                                </a>
                                            </li>
                                            <li class="nav-item mx-2">
                                                <a href="?tab=plants" class="nav-link <?php if($activeTab == 'plants') echo 'active';?>">
                                                    <i class="fa-solid fa-seedling"></i>
                                                    <span class="ms-1 d-none d-md-inline-block">พืชที่ลงทะเบียน</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="?tab=login" class="nav-link <?php if($activeTab == 'login') echo 'active';?>">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                    <span class="ms-1 d-none d-md-inline-block">การเข้าสู่ระบบ</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content h-100">
                                            <div class="tab-pane fade show active" role="tabpanel">
                                                <?php 
                                                    if($activeTab == 'favorite') include_once("profile-favoriteTab.php");
                                                    else if($activeTab == 'plants') include_once("profile-plantsTab.php");
                                                    else if($activeTab == 'login') include_once("profile-loginTab.php");
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Card pills data table -->
                                </div>
                                <!-- /Right section -->
                            </div>
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
    </body>
</html>

<?php
    //Close connection
    $database->close();
?>