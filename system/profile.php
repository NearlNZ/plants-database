<?php
    //include permission check
    require_once('../include/scripts/header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Profile</title>

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
                <?php require_once("../include/components/sidebar-officer.php");?>
                <!-- /Sidebar -->

                <!-- Page -->
                <div class="layout-page">
                    <!-- Navbar -->
                    <?php require_once("../include/components/navbar-officer.php");?>
                    <!-- /Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-fluid flex-grow-1 container-p-y">
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a>BPCS</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">บัญชีผู้ใช้</a>
                                    </li>
                                </ol>
                            </nav>
                            <!-- /Breadcrumb -->

                            <?php 
                                $userID = $account->id;
                                $sql = "SELECT caregiverID, caregiverName, caregiverLastname, caregiverProfile, caregiverTel, caregiverLineToken, username
                                        FROM caregiver
                                        WHERE caregiverID = ?
                                        LIMIT 1;";

                                $stmt = $bpcsDB->prepare($sql);
                                $stmt->bind_param('s', $userID);
                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();
                                $caregiver = $result->fetch_assoc();

                                //Set variable
                                $caregiverID = $caregiver["caregiverID"] ?? '';
                                $caregiverName = $caregiver["caregiverName"] ?? '';
                                $caregiverLastname = $caregiver["caregiverLastname"] ?? '';
                                $caregiverProfile = $caregiver["caregiverProfile"] ?? '';
                                $caregiverTel = $caregiver["caregiverTel"] ?? '';
                                $caregiverLineToken = $caregiver["caregiverLineToken"] ?? '';
                                $username = $caregiver["username"] ?? '';
                            ?>

                            <!-- Card header -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="user-profile-header-banner">
                                            <img src="../assets/img/common/element/profile-banner.png" alt="Banner image" class="rounded-top">
                                        </div>
                                        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                                            <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                                                <img src="../assets/img/avatars/<?php echo $caregiverProfile?>" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                                            </div>
                                            <div class="flex-grow-1 mt-3 mt-sm-5">
                                                <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                                    <div class="user-profile-info">
                                                        <h4><?php echo "$caregiverName $caregiverLastname"?></h4>
                                                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                                            <li class="list-inline-item fw-semibold">
                                                                <i class="fa-solid fa-user me-1"></i>
                                                                <?php echo $username?>
                                                            </li>
                                                            <li class="list-inline-item fw-semibold">
                                                                <i class="fa-solid fa-user-tag me-1"></i>
                                                                ผู้ดูแล
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
                                        <h5 class="card-header text-muted mb-0">
                                            <i class="fa-solid fa-user-doctor me-1"></i>
                                            ข้อมูลผู้ใช้
                                        </h5>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-2 mt-3">
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-user"></i>
                                                    <span class="fw-semibold mx-2">ชื่อ-สกุล:</span>
                                                    <span><?php echo "$caregiverName $caregiverLastname";?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-user"></i>
                                                    <span class="fw-semibold mx-2">Username:</span>
                                                    <span><?php echo $username;?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-phone"></i>
                                                    <span class="fw-semibold mx-2">โทรศัพท์:</span>
                                                    <span><?php echo $caregiverTel;?></span>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <i class="fa-brands fa-line fa-lg"></i>
                                                    <span class="fw-semibold mx-2">line Token:</span>
                                                    <span><?php echo substr($caregiverLineToken, 0, 8).'***'.substr($caregiverLineToken, -4);?></span>
                                                </li>
                                            </ul>
                                            <div class="mt-5">
                                                <a href="profile-edit" class="btn btn-primary me-2">แก้ไขข้อมูลบัญชีผู้ใช้</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Card profile -->
                                </div>
                                <div class="col">
                                    <!-- Card patient -->
                                    <div class="card h-100">
                                        <h5 class="card-header text-muted">
                                            <i class="fa-solid fa-user-injured me-1"></i>
                                            ประวัติการดูแลผู้ป่วย
                                            <p class="h5 mt-3">
                                                ข้อมูลทั้งหมด 0 รายการ
                                            </p>
                                        </h5>
                                        <div class="table-responsive scroll-y maxh-200">
                                            <table class="table table-hover card-table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>วันที่เริ่ม</th>
                                                        <th>วันที่สิ้นสุด</th>
                                                        <th>ผู้ป่วย</th>
                                                        <th>สถานะ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center text-warning py-3" colspan="4">
                                                            <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                            ไม่พบข้อมูลการดูแลผู้ป่วย
                                                        </td>
                                                    </tr>
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