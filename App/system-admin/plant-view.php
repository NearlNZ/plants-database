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
        <title>รายการพืช</title>
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
                            <!-- Breadcrumb & Active menu-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="plant-manage">รายการพืช</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">ข้อมูลพืช</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="active-menu-url">plant-manage</span>
                            <!-- /Breadcrumb & Active menu-->

                            <?php
                                $plantID = $_GET["plantID"] ?? "";
                                
                                function selectPlantData ($database, $plantID){
                                    if(empty($plantID)){
                                        return false;
                                    }

                                    $sql = "SELECT  P.plantID, P.plantName, P.plantRegist, P.plantView, U.userFname, U.userLname,
                                                    (SELECT count(favID) FROM favorite_plants WHERE plantID = P.plantID) AS favoriteCount
                                            FROM plants P LEFT JOIN users U ON P.userID = U.userID
                                            WHERE plantID = ?";
                                    
                                    $stmt = $database->stmt_init(); 
                                    $stmt->prepare($sql);
                                    $stmt->bind_param('s', $plantID);
                                    $stmt->execute();
                                    $tagResult = $stmt-> get_result();
                                    $stmt->close();

                                    if($tagResult->num_rows == 0){
                                        return false;
                                    }

                                    return $tagResult->fetch_assoc();
                                }

                                $plant = selectPlantData($database, $plantID);
                                
                                //Case data exist
                                if($plant != false){
                                    //Add plant view +1 each time page load
                                    $sql = "UPDATE plants
                                            SET plantView = plantview + 1
                                            WHERE plantID = ?;";
                                    $stmt = $database->stmt_init(); 
                                    $stmt->prepare($sql);
                                    $stmt->bind_param('s', $plantID);
                                    $stmt->execute();
                                    $stmt->close();
                            ?>

                            <div class="row g-3">
                                <?php print_r($plant);?>
                            </div>

                            <?php
                                //Case data not found
                                }else{
                                    include("../include/components/card-dataNotFound.php");
                                }
                            ?>
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
            $('#formAddTag').submit(function(e) {
                e.preventDefault();
                let form = $(this);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
                                    window.location.href="tag-manage";
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

<?php
    //Close connection
    $database->close();
?>