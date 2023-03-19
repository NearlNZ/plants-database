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
                                        <a class="active">เพิ่มข้อมูล</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="d-none fix-menu">device</span>
                            <!-- /Breadcrumb -->

                            <?php 
                                $deviceID = $_GET['deviceID'] ?? '';
                                $sql = "SELECT deviceID, deviceCameraIP, deviceSerial
                                        FROM device
                                        WHERE deviceID = ?
                                        LIMIT 1;";

                                $stmt = $bpcsDB->prepare($sql);
                                $stmt->bind_param('s', $deviceID);
                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();
                                $device = $result->fetch_assoc();
                            ?>

                            <div class="row g-3">
                                <!-- Card form -->
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <h4 class="card-header">
                                            <i class="fa-solid fa-house-laptop me-1"></i>
                                            เพิ่มข้อมูลอุปกรณ์
                                        </h4>
                                        <div class="card-body">
                                            <form id="formEditDevice" method="post" action="../data/device/updateDevice">
                                                <div class="row g-2">
                                                    <input type="hidden" name="deviceID" value="<?php echo $device['deviceID'];?>">
                                                    <div class="col-12 col-md-6">
                                                        หมายเลข Serial
                                                        <input type="text" name="deviceSerial" class="form-control" value="<?php echo $device['deviceSerial'];?>" placeholder="ระบุหมายเลข Serial..." autofocus autocomplete="off" required>
                                                        <div class="form-text">ตรวจสอบหมายเลข Serial ที่อุปกรณ์</div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        VDO Streaming URL
                                                        <div class="input-group">
                                                            <input id="urlInput" type="url" name="deviceCameraIP" class="form-control" value="<?php echo $device['deviceCameraIP'];?>" placeholder="ระบุ URL สำหรับ VDO Streaming" autocomplete="off">
                                                            <a id="testStreaming" href="#" class="btn btn-primary text-white">
                                                                <i class="fa-solid fa-video"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary me-2">บันทึกข้อมูล</button>
                                                    <a href="device" class="btn btn-label-secondary">ย้อนกลับ</a>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /Account -->
                                        </div>
                                </div>
                                <!-- /Card form -->
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
            $('#formEditDevice').submit(function(e) {
                e.preventDefault();
                var form = $(this);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    errorUrl: '../500',
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
                                    window.location.href="device";
                                }
                            });
                        }else{
                            showResponse({
                                response: response
                            });
                        }
                    }
                });
            });

            //Test Streaming URL
            $('#testStreaming').click(function(){
                let urlInput = $('#urlInput').val();
                if(urlInput != '' && $('#urlInput')[0].checkValidity()){
                    $('#testStreaming').attr("href",urlInput);
                    $('#testStreaming').attr("target","_blank");
                }else{
                    $('#testStreaming').attr("href","#");
                    $('#testStreaming').attr("target","");
                }
                
            });
        </script>
    </body>
</html>