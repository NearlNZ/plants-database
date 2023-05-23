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
        <title>คอลเล็คชั่นพืช</title>
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
        <link rel="stylesheet" href="../assets/vendor/select2/select2.css"/>
        <link rel="stylesheet" href="../assets/vendor//perfect-scrollbar/perfect-scrollbar.css"/>
        <link rel="stylesheet" href="../assets/vendor/boxicons/boxicons.css"/>

        <!-- Vendors JS -->
        <script src="../assets/vendor/fontawesome/js/all.min.js"></script>
        <script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="../assets/vendor/select2/select2.js"></script>
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
                                        <a class="active">คอลเล็คชั่นพืช</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="active-menu-url">collection</span>
                            <!-- /Breadcrumb & Active menu-->

                            <!-- Search card -->
                            <div class="card mt-2">
                                <div class="card-body p-3">
                                    <form action="" method="GET">
                                        <div class="row g-2">
                                            <div class="col-12 col-lg-6">
                                                ชื่อพืช
                                                <input type="search" name="name" class="form-control" placeholder="ค้นหา..." autofocus autocomplete="off"
                                                value="<?php if(isset($_GET["name"])) echo $_GET["name"]; ?>">
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                หมวดหมู่
                                                <div class="row g-3 g-lg-2">
                                                    <div class="col-12 col-lg">
                                                        <select class="select2 form-select" name="tag">
                                                            <option selected value="All">ทั้งหมด</option>

                                                            <?php
                                                                $sql = "SELECT tagID, tagName
                                                                        FROM tags
                                                                        ORDER BY tagName;";
                                                                    
                                                                $tagResult = $database->query($sql);
                                                                if($tagResult->num_rows > 0){
                                                                    while($tag = $tagResult->fetch_assoc()){
                                                            ?>
                                                                <option value="<?php echo $tag['tagID']; ?>"
                                                                <?php if(isset($_GET["tag"]) && $_GET["tag"] == $tag['tagID']) echo "selected"; ?>>
                                                                    <?php echo $tag["tagName"]; ?>
                                                                </option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <!-- Search button-->
                                                    <div class="col-12 col-lg-auto">
                                                        <button type="submit" name="search" value="true" class="btn btn-primary text-white w-100">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                            <span class="d-inline d-lg-none p-0 ms-2">ค้นหา</span>
                                                        </button>
                                                    </div>
                                                    <!-- /Search button-->
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Search card -->

                            <?php
                                $userID = $currentUser->userID;
                                $sql = "SELECT  P.plantID, P.plantName, P.plantView,
                                                FP.favID AS currentUserFavorite,
                                                (SELECT COUNT(favID) FROM favorite_plants WHERE plantID = P.plantID) AS favoriteCount,
                                                (SELECT COUNT(imgID) FROM plant_images WHERE plantID = P.plantID) AS imgCount,
                                                (SELECT imgPath FROM plant_images WHERE plantID = P.plantID ORDER BY imgUpload LIMIT 1) AS coverImage
                                        FROM    plants P
                                                LEFT JOIN tag_lists TL ON P.plantID = TL.PlantID
                                                LEFT JOIN plant_images PI ON P.plantID = PI.plantID
                                                LEFT JOIN users U ON P.userID = U.userID
                                                LEFT JOIN favorite_plants FP ON P.plantID = FP.plantID AND FP.userID = '$userID'
                                        WHERE 1=1 ";

                                $filter = array();
                                $filterDatatype = "";

                                //Check if filter send
                                if(isset($_GET["search"])){
                                    $searchName = $_GET['name'] ?? '';
                                    $searchTag = $_GET['tag'] ?? '';

                                    if(!empty($searchName)){
                                        $sql .= "AND plantName LIKE ? ";
                                        $filter[] = "%$searchName%";
                                        $filterDatatype .= "s";
                                    }
                                    if(!empty($searchTag) && $searchTag != "All"){
                                        $sql .= "AND TL.tagID = ? ";
                                        $filter[] = $searchTag;
                                        $filterDatatype .= "s";
                                    }
                                }
                                
                                $sql.= "GROUP BY P.plantID
                                        ORDER BY P.plantRegist DESC, P.plantName;";

                                $stmt = $database->prepare($sql);
                                if (!empty($filter)){ 
                                    $stmt->bind_param($filterDatatype, ...$filter);
                                }
                                
                                $stmt->execute();
                                $plantResult = $stmt-> get_result();
                                $stmt->close();

                                $resultCount = $plantResult->num_rows;
                                if($resultCount > 0){
                            ?>
                            
                            <!-- Collection card container -->
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 mt-2 g-3">
                                <?php 
                                    while($plant = $plantResult->fetch_assoc()){
                                        $plantImage = !empty($plant["coverImage"]) ? $plant["coverImage"] : "default-plant.png";
                                        $favoriteColorClass = !empty($plant['currentUserFavorite']) ? 'text-danger' : 'text-light';
                                        $isFavorite = !empty($plant['currentUserFavorite']) ? 'true' : 'false';
                                ?>

                                <!-- Collection card -->
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="img-container-3by2">
                                            <img class="card-img-top fit-cover" alt="<?php echo $plant["plantName"]; ?>"
                                            src="../assets/img/plantImgs/<?php echo $plantImage; ?>" />

                                            <div class="card-img-overlay text-end">
                                                <a href="../data/plant/updateFavoritePlant?plantID=<?php echo $plant["plantID"]; ?>" 
                                                class="btn btn-light btn-icon rounded-pill favorite-button <?php echo $favoriteColorClass; ?>">      
                                                    <i class="fa-solid fa-heart fa-lg"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h5 class="card-title text-center fw-semibold">
                                                <?php echo $plant["plantName"]; ?>
                                            </h5>
                                            <div class="row text-center my-3 py-2 py-lg-0">
                                                <div class="col-4 text-primary">
                                                    <h5 class="mb-1"><?php echo number_format($plant['imgCount']); ?></h5>
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                                <div class="col-4 text-secondary">
                                                    <h5 class="mb-1"><?php echo number_format($plant['plantView']); ?></h5>
                                                    <i class="fa-solid fa-eye "></i>
                                                </div>
                                                <div class="col-4 text-danger">
                                                    <h5 class="favorite-count mb-1"><?php echo number_format($plant['favoriteCount']); ?></h5>
                                                    <i class="fa-solid fa-heart"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="plant-view?plantID=<?php echo $plant["plantID"]; ?>" 
                                                class="btn btn-label-primary rounded-pill w-100">
                                                    <i class="fa-solid fa-seedling me-1"></i>
                                                    ดูข้อมูลพืช
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Collection card -->

                                <?php } ?>
                            </div>

                            <?php }else{ ?>
                            <div class="row mt-2 g-3">
                                <div class="col-12">
                                        <div class="card h-100">
                                            <div class="card-body justify-content-center align-items-center d-flex">
                                                <div class="text-center py-5">
                                                    <img class="img-fluid mt-3" width="520px" src="../assets/img/page/plant-not-found.jpg" alt="data not found" />
                                                    <p class="h3 mt-3 fw-bold text-success">
                                                        ไม่พบข้อมูลพืชสำหรับแสดงผล
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <!-- /Collection card container -->
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
            //Select
            $(function(){
                select = $(".select2");
                select.length&&select.each(function(){
                    let element=$(this);
                    element.wrap('<div class="position-relative"></div>').select2({
                        dropdownParent:element.parent()
                    });
                });
            });

            //Control favorite button
            $('.favorite-button').on('click', function(event) {
                event.preventDefault();
                let button = $(this);
                let url = button.attr('href');

                ajaxRequest({
                    type: 'GET',
                    url: url,
                    successCallback: function(response){
                        if(response.status == "success"){
                            let isFavorite = response.isFavorite;
                            let favoriteCount = response.favoriteCount;
                            let parentCard = button.closest('.card');
                            let favoriteCountElement = parentCard.find('.favorite-count');

                            if (isFavorite === true) {
                                button.removeClass('text-light').addClass('text-danger');
                            } else {
                                button.removeClass('text-danger').addClass('text-light');
                            }

                            favoriteCountElement.text(favoriteCount);
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