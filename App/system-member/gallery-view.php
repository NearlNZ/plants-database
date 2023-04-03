<?php
    //include permission check
    require_once('../include/scripts/member-header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>รายการพืช</title>

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
                <?php require_once("../include/components/sidebar-member.php");?>
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
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="gallery">รายการพืช</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">ข้อมูลพืช</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="d-none fix-menu">gallery</span>
                            <!-- /Breadcrumb -->

                            <?php
                                if(!isset($_GET["plantID"])){
                                    echo "<script>window.location.href='gallery';</script>";
                                    exit();
                                }else{
                                    $plantID = $_GET["plantID"];

                                    $sql = "SELECT P.plantID, P.plantName, P.plantRegist, plantDescription, C.cateName, U.userFname, U.userLname
                                            FROM plants P LEFT JOIN categories C ON P.cateID = C.cateID LEFT JOIN users U ON P.userID = U.userID
                                            WHERE plantID = ?
                                            LIMIT 1;";
                                    
                                    $stmt = $database->prepare($sql);
                                    $stmt->bind_param('s', $plantID);
                                    $stmt->execute();
                                    $plantResult = $stmt-> get_result();
                                    $stmt->close();
                                    $plant = $plantResult->fetch_assoc();
                                }
                            ?>

                            <div class="row g-3">
                                <!-- Card form -->
                                <div class="col-12">
                                    <div class="card mb-2">
                                        <h5 class="card-header">
                                            <i class="fa-solid fa-seedling me-1"></i>
                                            ข้อมูลพืช
                                        </h5>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-12 col-md-6">
                                                    ชื่อพืช
                                                    <input type="text" class="form-control" placeholder="ไม่มีชื่อ"
                                                    value="<?php echo $plant['plantName']; ?>" readonly>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    หมวดหมู่พืช
                                                    <input type="text" class="form-control" placeholder="ไม่มีหมวดหมู่"
                                                    value="<?php echo $plant['cateName']; ?>" readonly>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    ผู้ลงทะเบียน
                                                    <input type="text" class="form-control" placeholder="ไม่มีข้อมูลผู้ลงทะเบียน"
                                                    value="<?php echo $plant['userFname']." ".$plant['userLname']; ?>" readonly>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    วันที่ลงทะเบียน
                                                    <input type="text" class="form-control" placeholder="ไม่ได้ระบุวันลงทะเบียน"
                                                    value="<?php echo date("d/m/Y", strtotime($plant["plantRegist"])); ?>" readonly>
                                                </div>
                                                <div class="col-12">
                                                    รายละเอียดพืช
                                                    <textarea class="form-control" rows="3" readonly><?php echo $plant["plantDescription"]; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <a href="gallery" class="btn btn-primary">ย้อนกลับ</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card form -->

                                <!-- Card Img -->
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <h5 class="card-header mb-0">
                                            <i class="fa-solid fa-image me-1"></i>
                                            รูปภาพของพืช
                                        </h5>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <?php
                                                    $sql = "SELECT imgID, imgPath
                                                            FROM plantImages
                                                            WHERE plantID = ?;";
                                                    
                                                    $stmt = $database->prepare($sql);
                                                    $stmt->bind_param('s', $plantID);
                                                    $stmt->execute();
                                                    $imgResult = $stmt-> get_result();
                                                    $stmt->close();
                                                    
                                                    $imgCount = $imgResult->num_rows;
                                                    
                                                    if($imgCount > 0){
                                                        while($img = $imgResult->fetch_assoc()){
                                                ?>

                                                    <div class="col-6 col-md-3 col-lg-2 position-relative">
                                                        <div class="card clickable p-0 shadow border">
                                                            <a href="../assets/img/plantImgs/<?php echo $img['imgPath']; ?>" target="_blank">
                                                                <img src="../assets/img/plantImgs/<?php echo $img['imgPath']; ?>" class="w-100 h-100 fit-contain rounded-3" style="max-height:260px;">
                                                            </a>
                                                        </div>
                                                    </div>

                                                <?php
                                                        }
                                                    }else{
                                                ?>
                                                        <div class="text-center p-0 text-warning">
                                                            <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                            ไม่มีรูปภาพสำหรับแสดง
                                                        </div>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card Img -->
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
            $('#formAddPlant').submit(function(e) {
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
                                    window.location.href="plant";
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

            $('#formAddImg').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData($('#formAddImg')[0]);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: data,
                    processData: false,
                    contentType: false,
                    errorUrl: '../500',
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
                                    window.location.reload();
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

            $('.deleteBtn').click(function(){
                event.preventDefault();
                let url = $(this).attr('href');
                
                ajaxRequest({
                    type: 'GET',
                    url: url,
                    errorUrl: '../500',
                    successCallback: function(response){
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function(){
                                    window.location.reload();
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
        </script>
    </body>
</html>