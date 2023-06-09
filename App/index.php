<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>หน้าหลัก</title>
        <link rel="shortcut icon" href="../assets/img/element/tab-logo.ico" type="image/x-icon">

        <!-- Fonts -->
        <link rel="stylesheet" href="assets/font/Kanit.css"/>

        <!-- Template CSS -->
        <link rel="stylesheet" href="assets/css/template.css"/>
        
        <!-- Core JS -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="assets/vendor/boxicons/boxicons.css"/>

        <!-- Vendors JS -->
        <script src="assets/vendor/fontawesome/js/all.min.js"></script>

        <!-- Page Style -->
        <link rel="stylesheet" href="assets/css/custom-style.css"/>
    </head>
    <body class="body-light">
        <!-- Wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Sidebar -->
                <?php require_once("include/components/sidebar.php");?>
                <!-- /Sidebar -->

                <!-- Page -->
                <div class="layout-page">
                    <!-- Navbar -->
                    <?php require_once("include/components/navbar.php");?>
                    <!-- /Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-fluid py-3 py-lg-5">
                            <div class="row g-3 p-0 justify-content-center">
                                <div class="col-12 col-md-6">
                                    <a class="text-decoration-none" href="login">
                                        <div class="card clickable shadow mb-0">
                                            <div class="card-body mb-0">
                                                <div class="row">
                                                    <p class="h3 text-success text-center p-4 mb-0">เข้าสู่ระบบ</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- /Content -->

                        <!-- Footer -->
                        <?php require_once("include/components/footer.php");?>
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
        <script src="assets/js/template.js"></script>

        <!-- Page JS -->
        <script src="include/scripts/customFunctions.js"></script>
    </body>
</html>