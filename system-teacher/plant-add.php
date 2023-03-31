<?php
    //include permission check
    require_once('../include/scripts/teacher-header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>ข้อมูลพืช</title>

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
                <?php require_once("../include/components/sidebar-teacher.php");?>
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
                                        <a href="plant">ข้อมูลพืช</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">เพิ่มข้อมูล</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="d-none fix-menu">plant</span>
                            <!-- /Breadcrumb -->

                            <div class="row g-3">
                                <!-- Card form -->
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <h5 class="card-header">
                                            <i class="fa-solid fa-seedling me-1"></i>
                                            เพิ่มข้อมูลพืช
                                        </h5>
                                        <div class="card-body">
                                            <form id="formAddPlant" method="post" action="../data/plant/addNewPlant">
                                                <div class="row g-2">
                                                    <div class="col-12 col-md-6">
                                                        ชื่อพืช
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-comment"></i></span>
                                                            <input type="text" name="plantName" class="form-control" placeholder="ระบุชื่อพืช" autofocus autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        หมวดหมู่พืช
                                                        <select class="form-select" name="cateID">
                                                            <option selected value="">ไม่ระบุ</option>

                                                            <?php
                                                                $sql = "SELECT cateID, cateName
                                                                        FROM categories
                                                                        ORDER BY cateName;";
                                                                    
                                                                $result = $database->query($sql);
                                                                if($result->num_rows > 0){
                                                                    while($category = $result->fetch_assoc()){
                                                                        echo '<option value="'.$category["cateID"].'">'.$category["cateName"].'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        รายละเอียดพืช
                                                        <textarea name="plantDescription" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary me-2">บันทึกข้อมูล</button>
                                                    <a href="plant" class="btn btn-label-secondary">ย้อนกลับ</a>
                                                </div>
                                            </form>
                                        </div>
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
        </script>
    </body>
</html>