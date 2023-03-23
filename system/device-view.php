<?php
    //include permission check
    require_once('../include/scripts/header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Device</title>

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
                                        <a href="device">อุปกรณ์</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">ข้อมูล</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="d-none fix-menu">device</span>
                            <!-- /Breadcrumb -->

                            <?php 
                                $deviceID = $_GET['deviceID'] ?? '0';
                                $sql = "SELECT deviceID, deviceCameraIP, deviceSerial, deviceStatus, deviceRegist
                                        FROM device
                                        WHERE deviceID = ?
                                        LIMIT 1;";

                                $stmt = $bpcsDB->prepare($sql);
                                $stmt->bind_param('s', $deviceID);
                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();
                                $device = $result->fetch_assoc();

                                //Set variable
                                $deviceID = $device['deviceID'] ?? '';
                                $deviceCameraIP = $device['deviceCameraIP'] ?? '';
                                $deviceSerial = $device['deviceSerial'] ?? '';
                                $deviceStatus = $device['deviceStatus'] ?? '';
                                $deviceRegist = $device['deviceRegist'] ?? '';
                            ?>

                            <div class="row g-3">
                                <!-- Card VDO preview -->
                                <div class="col-12 col-md-6 col-xl-5">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <img id="camera-image" class="w-100 h-100 fit-contain rounded-3 border" style="max-height:260px;"
                                            src="<?php echo !empty($deviceCameraIP) ? $deviceCameraIP: '../assets/img/common/element/no-vdo.jpg';?>">
                                        </div>
                                    </div>
                                </div>
                                <!-- Card VDO preview -->

                                <!-- Card form -->
                                <div class="col">
                                    <div class="card h-100">
                                        <h5 class="card-header text-muted">
                                            <i class="fa-solid fa-house-laptop me-1"></i>
                                            ข้อมูลอุปกรณ์
                                        </h5>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-4">
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-check"></i>
                                                    <span class="fw-semibold mx-2">สถานะอุปกรณ์ : </span>
                                                    <?php if($deviceStatus == "กำลังใช้งาน"){ ?>
                                                        <span class="badge bg-success py-2">กำลังใช้งาน</span>
                                                    <?php }else{ ?>
                                                        <span class="badge bg-label-secondary py-2">อุปกรณ์ว่าง</span>
                                                    <?php } ?>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-hashtag"></i>
                                                    <span class="fw-semibold mx-2">หมายเลข Serial : </span>
                                                    <span><?php echo $deviceSerial;?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-solid fa-video"></i>
                                                    <span class="fw-semibold mx-2">VDO Streaming URL : </span>
                                                    <span><?php echo !empty($deviceCameraIP) ? $deviceCameraIP : "Not available";?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <i class="fa-regular fa-calendar-check"></i>
                                                    <span class="fw-semibold mx-2">วันที่ลงทะเบียน : </span>
                                                    <span><?php echo $deviceRegist=='' ? $deviceRegist : date('d/m/Y', strtotime($deviceRegist));?></span>
                                                </li>
                                            </ul>
                                            <div class="mt-0">
                                                <a href="device" class="btn btn-secondary">ย้อนกลับ</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card form -->
                            </div>

                            <!-- Card history -->
                            <div class="col mt-3">
                                <div class="card">
                                    <h5 class="card-header text-muted">
                                        <i class="fa-solid fa-clock-rotate-left me-1"></i>
                                        ประวัติการใช้งานอุปกรณ์
                                        <p class="h5 mt-3">
                                            ข้อมูลทั้งหมด 0 รายการ
                                        </p>
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover card-table table-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>วันที่เริ่ม</th>
                                                    <th>วันที่สิ้นสุด</th>
                                                    <th>ผู้ป่วย</th>
                                                    <th>ผู้ดูแล</th>
                                                    <th>สถานะ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-warning py-3" colspan="6">
                                                        <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                        ไม่พบข้อมูลการใช้งานอุปกรณ์
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /Card history -->
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
            var img = document.getElementById('camera-image');
            var maxLoadTime = 1000;

            var imgLoadTimeout = setTimeout(function() {
                img.onerror = img.onload = null;
                img.src = '../assets/img/common/element/no-vdo.jpg';
            }, maxLoadTime);

            img.onerror = img.onload = function() {
                clearTimeout(imgLoadTimeout);
            };
        </script>
    </body>
</html>