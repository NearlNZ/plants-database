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
        <title>บัญชีของฉัน</title>

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
                                                <img src="../assets/img/avatars/<?php echo $user->userProfile; ?>" alt="profile" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                                            </div>
                                            <div class="flex-grow-1 mt-3 mt-sm-5">
                                                <div class="d-flex align-items-md-end align-items-center justify-content-between mx-4 flex-sm-row flex-column gap-4">
                                                    <div class="user-profile-info">
                                                        <h4 class="fw-bold">
                                                            <?php echo $user->userFname." ".$user->userLname;?>
                                                        </h4>
                                                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                                            <li class="list-inline-item">
                                                                <i class="fa-solid fa-user text-primary me-1"></i>
                                                                <?php echo $user->username;?>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <i class="fa-solid fa-user-tag text-primary me-1"></i> 
                                                                <?php echo $user->userLevel;?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <a href="profile-edit" class="btn btn-primary text-nowrap">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                        <p class="d-inline d-sm-none d-md-inline ms-1">แก้ไขข้อมูลผู้ใช้</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Card profile banner -->

                            <div class="row g-3">
                                <div class="col-xxl-4 col-lg-5">
                                    <div class="row p-0 g-3">
                                        <div class="col-md-6 col-lg-12">
                                            <!-- Card profile info -->
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="text-muted">ข้อมูลผู้ใช้</p>
                                                    <table class="table table-borderless p-0 m-0">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-start fw-semibold text-seondary">
                                                                    <i class="fa-solid fa-comment me-2"></i>
                                                                    ชื่อ-สกุล
                                                                </td>
                                                                <td class="text-start">
                                                                    <?php echo $user->userFname." ".$user->userLname;?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start fw-semibold text-seondary">
                                                                    <i class="fa-solid fa-user me-2"></i>
                                                                    Username
                                                                </td>
                                                                <td td class="text-start">
                                                                    <?php echo $user->username;?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start fw-semibold text-seondary">
                                                                    <i class="fa-solid fa-user-tag me-1"></i>
                                                                    ระดับผู้ใช้
                                                                </td>
                                                                <td td class="text-start">
                                                                    <?php echo $user->userLevel;?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start fw-semibold text-seondary">
                                                                    <i class="fa-solid fa-calendar me-2"></i>
                                                                    ลงทะเบียน
                                                                </td>
                                                                <td td class="text-start">
                                                                    <?php echo date("j/n/Y", strtotime($user->userRegist));?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start fw-semibold text-seondary">
                                                                    <i class="fa-solid fa-clock-rotate-left me-2"></i>
                                                                    ใช้งานล่าสุด
                                                                </td>
                                                                <td td class="text-start">
                                                                    <?php 
                                                                        $lastlogin = !empty($user->userLastLogin) ? date("j/n/Y - H:i", strtotime($user->userLastLogin)) : "ผู้ใช้ไม่มีการเข้าสู่ระบบ";
                                                                        echo $lastlogin;
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /Card profile info -->
                                        </div>
                                        <div class="col">
                                            <!-- Card profile activities -->
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <?php
                                                        //Select user activities (count of plant they add etc.)
                                                        $userID = $user->userID;
                                                        $sql = "SELECT
                                                                (SELECT count(favID) FROM favorite_plants WHERE userID = '$userID') AS favoriteCount,
                                                                (SELECT count(plantID) FROM plants WHERE userID = '$userID') AS plantAdd,
                                                                (SELECT count(tagID) FROM tags WHERE userID = '$userID') AS tagAdd,
                                                                (SELECT count(imgID) FROM plant_images WHERE userID = '$userID') AS imgAdd;";
                                                        $activitiesresult = $database->query($sql);
                                                        $activities = $activitiesresult->fetch_assoc();
                                                    ?>

                                                    <p class="text-muted">กิจกรรมของผู้ใช้</p>
                                                    <table class="table table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-start fw-semibold">
                                                                    <i class="fa-solid fa-heart text-danger me-2"></i>
                                                                    รายการโปรด
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($activities["favoriteCount"]);?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start fw-semibold">
                                                                    <i class="fa-solid fa-seedling text-success me-2"></i>
                                                                    เพิ่มข้อมูลพืช
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($activities["plantAdd"]);?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start fw-semibold">
                                                                    <i class="fa-solid fa-tags text-warning me-2"></i>
                                                                    เพิ่มหมวดหมู่ 
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($activities["tagAdd"]);?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start fw-semibold">
                                                                    <i class="fa-solid fa-image text-info me-2"></i>
                                                                    เพิ่มภาพพืช
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo number_format($activities["imgAdd"]);?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- Card profile activities -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <!-- Card login history -->
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <p class="text-muted">ประวัติการเข้าใช้งาน</p>

                                        </div>
                                    </div>
                                    <!-- /Card profile activities -->
                                </div>
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
        <script>
            
        </script>
    </body>
</html>

<?php
    //Close connection
    $database->close();
?>