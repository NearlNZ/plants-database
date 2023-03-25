<?php
    //include permission check
    require_once('../include/scripts/header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Patient</title>

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
                                        <a href="patient">ผู้ป่วย</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">ข้อมูล</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="d-none fix-menu">patient</span>
                            <!-- /Breadcrumb -->

                            <?php 
                                $patientID = $_GET['patientID'];
                                $sql = "SELECT patientID, patientName, patientLastname, patientProfile, patientGender, patientBirthdate, patientWeight, patientHeight
                                        FROM patient
                                        WHERE patientID = ?
                                        LIMIT 1;";

                                $stmt = $bpcsDB->prepare($sql);
                                $stmt->bind_param('s', $patientID);
                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();
                                $patient = $result->fetch_assoc();

                                //Set variable
                                $patientID = $patient["patientID"] ?? '';
                                $patientName = $patient["patientName"] ?? '';
                                $patientLastname = $patient["patientLastname"] ?? '';
                                $patientProfile = $patient["patientProfile"] ?? '';
                                $patientGender = $patient["patientGender"] ?? '';
                                $patientBirthdate = $patient["patientBirthdate"] ?? '';
                                $patientWeight = $patient["patientWeight"] ?? '';
                                $patientHeight = $patient["patientHeight"] ?? '';
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
                                                <img src="../assets/img/avatars/<?php echo $patientProfile;?>" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                                            </div>
                                            <div class="flex-grow-1 mt-3 mt-sm-5">
                                                <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                                    <div class="user-profile-info">
                                                        <h4><?php echo "$patientName $patientLastname";?></h4>
                                                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                                            <li class="list-inline-item fw-semibold">
                                                                <i class="fa-solid fa-user-tag me-1"></i>
                                                                ผู้ป่วย
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
                                            <i class="fa-solid fa-user-injured me-1"></i>
                                            ข้อมูลทั่วไปของผู้ป่วย
                                        </h5>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-2 mt-3">
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-user"></i>
                                                    <span class="fw-semibold mx-2">ชื่อ-สกุล:</span>
                                                    <span><?php echo "$patientName $patientLastname";?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-venus-mars"></i>
                                                    <span class="fw-semibold mx-1">เพศ:</span>
                                                    <span><?php echo $patientGender;?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-calendar-day"></i>
                                                    <span class="fw-semibold mx-2">วัน/เดือน/ปี เกิด:</span>
                                                    <span><?php echo date('d/m/Y', strtotime($patientBirthdate));?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-weight-scale"></i>
                                                    <span class="fw-semibold mx-2">น้ำหนัก:</span>
                                                    <span><?php echo $patientWeight;?> กิโลกรัม</span>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <i class="fa-solid fa-arrows-up-down"></i>
                                                    <span class="fw-semibold mx-2">ส่วนสูง:</span>
                                                    <span><?php echo $patientHeight;?> เซนติเมตร</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /Card profile -->
                                </div>

                                <?php
                                    $sql = "SELECT symptomID, symptomDetail, symptomStart, symptomEnd
                                            FROM patientsymptom
                                            WHERE patientID = ?
                                            ORDER BY symptomStart DESC;";
                                            
                                    $stmt = $bpcsDB->prepare($sql);
                                    $stmt->bind_param('s', $patientID);
                                    $stmt->execute();
                                    $result = $stmt-> get_result();
                                    $stmt->close();

                                    $resultCount = $result->num_rows;
                                ?>

                                <div class="col">
                                    <!-- Card patient -->
                                    <div class="card h-100">
                                        <h5 class="card-header text-muted">
                                            <i class="fa-solid fa-head-side-cough me-1"></i>
                                            บันทึกอาการป่วย
                                            <p class="h5 mt-3">
                                                ข้อมูลทั้งหมด <?php echo $resultCount;?> รายการ
                                            </p>
                                        </h5>
                                        <div class="table-responsive scroll-y maxh-200">
                                            <table class="table table-hover card-table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>รายละเอียด</th>
                                                        <th>วันที่เริ่มเป็น</th>
                                                        <th>วันที่หาย</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                <?php 
                                                    if($resultCount > 0){ while($symptom = $result->fetch_assoc()){
                                                ?>

                                                <tr>
                                                    <td><?php echo $symptom["symptomDetail"];?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($symptom["symptomStart"]));?></td>
                                                    <td>
                                                        <?php echo $symptom["symptomEnd"] != '0000-00-00' ? date('d/m/Y', strtotime($symptom["symptomEnd"])) : "-";?>
                                                    </td>
                                                </tr>

                                                <?php } }else{ ?>

                                                    <tr>
                                                        <td class="text-center text-warning py-3" colspan="4">
                                                            <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                            ไม่พบบันทึกข้อมูลอาการป่วย
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