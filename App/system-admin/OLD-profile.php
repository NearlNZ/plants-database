<?php
    //include permission check
    require_once('../include/scripts/admin-header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>บัญชีผู้ใช้</title>
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
        <link rel="stylesheet" href="../assets/vendor/perfect-scrollbar/perfect-scrollbar.css"/>
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
                            <!-- Card header -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="user-profile-header-banner">
                                            <img src="../assets/img/element/profile-banner.png" alt="Banner image" class="rounded-top">
                                        </div>
                                        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                                            <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                                                <img src="../assets/img/avatars/<?php echo $user->userProfile; ?>" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                                            </div>
                                            <div class="flex-grow-1 mt-3 mt-sm-5">
                                                <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                                    <div class="user-profile-info">
                                                        <h4><?php echo "$user->userFname $user->userLname"; ?></h4>
                                                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                                            <li class="list-inline-item fw-semibold">
                                                                <i class="fa-solid fa-user me-1"></i>
                                                                <?php echo $user->username; ?>
                                                            </li>
                                                            <li class="list-inline-item fw-semibold">
                                                                <i class="fa-solid fa-user-tag me-1"></i>
                                                                <?php echo $user->userLevel; ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Card header -->

                            <div class="row g-3">
                                <div class="col-xl-4 col-lg-5 col-md-5">
                                    <!-- Card profile -->
                                    <div class="card h-100">
                                        <h5 class="card-header mb-0">
                                            <i class="fa-solid fa-address-card me-1"></i>
                                            ข้อมูลบัญชีผู้ใช้
                                        </h5>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-2 mt-3">
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-regular fa-comment"></i>
                                                    <span class="fw-semibold mx-2">ชื่อ-สกุล:</span>
                                                    <span><?php echo "$user->userFname $user->userLname";?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-user"></i>
                                                    <span class="fw-semibold mx-2">Username:</span>
                                                    <span><?php echo $user->username;?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-user-tag"></i>
                                                    <span class="fw-semibold mx-2">ตำแหน่ง:</span>
                                                    <span><?php echo $user->userLevel;?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-calendar-day"></i>
                                                    <span class="fw-semibold mx-2">วันที่ลงทะเบียน:</span>
                                                    <span><?php echo date("j/n/Y", strtotime($user->userRegist));?></span>
                                                </li>
                                            </ul>
                                            <div class="mt-5">
                                                <a href="profile-edit" class="btn btn-primary me-2">แก้ไขข้อมูลบัญชีผู้ใช้</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Card profile -->
                                </div>

                                <?php
                                    $sql = "SELECT P.plantID, P.plantName, P.plantRegist, C.tagName
                                    FROM plants P LEFT JOIN categories C ON P.tagID = C.tagID
                                    WHERE P.userID = ?;";
                                    
                                    $stmt = $database->prepare($sql);
                                    $stmt->bind_param('s', $_SESSION['CSP-session-userID']);
                                    $stmt->execute();
                                    $plantResult = $stmt-> get_result();
                                    $stmt->close();

                                    $resultCount = $plantResult->num_rows;
                                ?>

                                <div class="col">
                                    <!-- Card patient -->
                                    <div class="card h-100">
                                        <h5 class="card-header">
                                            <i class="fa-solid fa-seedling me-1"></i>
                                            พืชที่ฉันลงทะเบียน
                                        </h5>
                                        <div class="table-responsive scroll-y maxh-200">
                                            <table class="table table-hover card-table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>ลำดับที่</th>
                                                        <th>ชื่อพืช</th>
                                                        <th>หมวดหมู่พืช</th>
                                                        <th>วันที่ลงทะเบียน</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                <?php 
                                                    if($resultCount > 0){ $plantIndex = 1; while($plant = $plantResult->fetch_assoc()){
                                                ?>

                                                    <tr>
                                                        <td><?php echo $plantIndex; ?></td>
                                                        <td><?php echo $plant["plantName"]; ?></td>
                                                        <td><?php echo $plant["tagName"]; ?></td>
                                                        <td><?php echo date("j/n/Y", strtotime($plant["plantRegist"])); ?></td>
                                                    </tr>

                                                    <?php $plantIndex++;} }else{ ?>

                                                    <tr>
                                                        <td class="text-center text-warning py-3" colspan="4">
                                                            <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                            ไม่พบข้อมูลพืชที่ลงทะเบียน
                                                        </td>
                                                    </tr>

                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /Card patient -->
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
    </body>
</html>