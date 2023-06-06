<?php
    //Include database connection
    require_once("../data/database.php");

    //Include member account check
    require_once('../include/scripts/member-header.php');
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
                                $userID = $currentUser->userID;
                                
                                function selectPlantData ($database, $plantID, $currentUser){
                                    if(empty($plantID)){
                                        return false;
                                    }

                                    $sql = "SELECT  P.plantID, P.plantName, P.commonName, P.otherName, P.scientificName, P.familyName,
                                                    P.plantTrunk, P.plantLeaf, P.plantFlower, P.plantFruit,
                                                    P.plantCultivation, P.plantPropagation,
                                                    P.plantUtilization,
                                                    (SELECT imgPath FROM plant_images WHERE plantID = P.plantID ORDER BY imgUpload LIMIT 1) AS coverImage,
                                                    (SELECT userID FROM favorite_plants WHERE plantID = P.plantID AND userID = '$currentUser') AS currentUserFavorite
                                            FROM    plants P 
                                            WHERE plantID = ?;";
                                    
                                    $stmt = $database->stmt_init(); 
                                    $stmt->prepare($sql);
                                    $stmt->bind_param('s', $plantID);
                                    $stmt->execute();
                                    $plantResult = $stmt-> get_result();
                                    $stmt->close();

                                    if($plantResult->num_rows == 0){
                                        return false;
                                    }

                                    return $plantResult->fetch_assoc();
                                }

                                $plant = selectPlantData($database, $plantID, $userID);
                                
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

                                    //Set data array to variable
                                    $plantID = $plant["plantID"];
                                    $plantName = $plant["plantName"];
                                    $commonName = !empty($plant["commonName"]) ? $plant["commonName"] : "-";
                                    $otherName = !empty($plant["otherName"]) ? $plant["otherName"] : "-";
                                    $scientificName = !empty($plant["scientificName"]) ? $plant["scientificName"] : "-";
                                    $familyName = !empty($plant["familyName"]) ? $plant["familyName"] : "-";

                                    $plantTrunk = !empty($plant["plantTrunk"]) ? $plant["plantTrunk"] : "-";
                                    $plantLeaf = !empty($plant["plantLeaf"]) ? $plant["plantLeaf"] : "-";
                                    $plantFlower = !empty($plant["plantFlower"]) ? $plant["plantFlower"] : "-";
                                    $plantFruit = !empty($plant["plantFruit"]) ? $plant["plantFruit"] : "-";

                                    $plantCultivation = !empty($plant["plantCultivation"]) ? $plant["plantCultivation"] : "-";
                                    $plantPropagation = !empty($plant["plantPropagation"]) ? $plant["plantPropagation"] : "-";

                                    $plantUtilization = !empty($plant["plantUtilization"]) ? $plant["plantUtilization"] : "-";

                                    $plantCoverImage = !empty($plant["coverImage"]) ? $plant["coverImage"] : "default-plant.png";
                                    $favoriteButtonColor = !empty($plant['currentUserFavorite']) ? 'text-danger' : 'text-light';
                            ?>

                            <!-- Card plant detail -->
                            <div class="card mt-2">
                                <div class="card-body p-2 p-md-4">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-5 col-xl-6 col-xxl-5">
                                            <div class="ratio ratio-4x3">
                                                <img class="fit-cover rounded-3 border shadow-sm" alt="<?php echo $plantName; ?>"
                                                src="../assets/img/plantImgs/<?php echo $plantCoverImage; ?>"/>

                                                <div class="card-img-overlay text-end">
                                                    <a href="../data/plant/updateFavoritePlant?plantID=<?php echo $plantID; ?>" 
                                                    class="btn btn-light btn-icon rounded-pill favorite-button <?php echo $favoriteButtonColor; ?>">      
                                                        <i class="fa-solid fa-heart fa-lg"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center g-2 mt-1">
                                                <div class="col-auto">
                                                    <a href="#" onclick="goBack()" class="btn btn-label-secondary w-100">
                                                        <i class="fa-solid fa-chevron-left"></i>
                                                        <p class="d-none d-md-inline d-lg-none d-xl-inline ms-1">ย้อนกลับ</p>
                                                    </a>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="plant-edit?plantID=<?php echo $plantID; ?>" class="btn btn-primary w-100">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                        <p class="d-none d-md-inline d-lg-none d-xl-inline ms-1">แก้ไขข้อมูล</p>
                                                    </a>
                                                </div>
                                                <!-- <div class="col-auto">
                                                    <a href="#" onclick="" class="btn btn-success w-100">
                                                        <i class="fa-solid fa-print"></i>
                                                        <p class="d-none d-md-inline d-lg-none d-xl-inline ms-1">พิมพ์ข้อมูล</p>
                                                    </a>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-7 col-xl-6 col-xxl-7">
                                            <div class="ms-lg-3 p-2">
                                                <div class="border-bottom border-dark mb-2">
                                                    <p class="fs-3 fw-bold text-dark text-center text-lg-start text-truncate mb-1">
                                                        <?php echo $plantName; ?>
                                                    </p>
                                                </div>

                                                <!-- Tags container -->
                                                <div class="text-center text-lg-start mb-2">
                                                    <?php
                                                        //Query tags list
                                                        $sql = "SELECT  T.tagID, T.tagName,
                                                                        (SELECT count(*) FROM tag_lists WHERE tagID = T.tagID) as plantCount
                                                                FROM tags T INNER JOIN tag_lists TL ON T.tagID = TL.tagID
                                                                WHERE TL.plantID = ?;";
                                                        $stmt = $database->stmt_init(); 
                                                        $stmt->prepare($sql);
                                                        $stmt->bind_param('s', $plantID);
                                                        $stmt->execute();
                                                        $tagResult = $stmt-> get_result();
                                                        $stmt->close();

                                                        if($tagResult->num_rows > 0){while($tag = $tagResult->fetch_assoc()){
                                                            $tagName = $tag['tagName'];
                                                            $plantCount = $tag['plantCount'];
                                                    ?>
                                                            <a class="btn btn-sm btn-dark rounded-pill me-1" 
                                                            href="collection?tag=<?php echo $tagName;?>&search=true">
                                                                <i class="fa-solid fa-tag me-1"></i>
                                                                <?php echo "$tagName ($plantCount)"; ?>
                                                            </a>
                                                    <?php
                                                        }}
                                                    ?>
                                                </div>
                                                <!-- /Tags container -->

                                                <!-- Plant detail container -->
                                                <div class="row mt-3">
                                                    <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill">
                                                        <i class="fa-solid fa-circle-info text-success me-1"></i>
                                                        ข้อมูลทั่วไป
                                                    </h5>

                                                    <dt class="col-sm-3 text-truncate text-dark fw-bold">
                                                        ชื่อพืช
                                                    </dt>
                                                    <dd class="col-sm-9 mb-3">
                                                        <?php echo $plantName; ?>
                                                    </dd>

                                                    <dt class="col-sm-3 text-truncate text-dark fw-bold">
                                                        ชื่อสามัญ
                                                    </dt>
                                                    <dd class="col-sm-9 mb-3">
                                                        <?php echo $commonName; ?>
                                                    </dd>

                                                    <dt class="col-sm-3 text-truncate text-dark fw-bold">
                                                        ชื่อเรียกอื่น
                                                    </dt>
                                                    <dd class="col-sm-9 mb-3">
                                                        <?php echo $otherName; ?>
                                                    </dd>

                                                    <dt class="col-sm-3 text-truncate text-dark fw-bold">
                                                        ชื่อวิทยาศาสตร์
                                                    </dt>
                                                    <dd class="col-sm-9 mb-3">
                                                        <?php echo $scientificName; ?>
                                                    </dd>

                                                    <dt class="col-sm-3 text-truncate text-dark fw-bold">
                                                        ชื่อวงศ์
                                                    </dt>
                                                    <dd class="col-sm-9">
                                                        <?php echo $familyName; ?>
                                                    </dd>
                                                </div>
                                                <!-- Plant detail container -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Plant botanical detail container -->
                                    <div class="row p-2 mt-3">
                                        <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill">
                                            <i class="fa-solid fa-leaf text-success me-1"></i>
                                            ลักษณะทางพฤกศาสตร์
                                        </h5>

                                        <dt class="col-sm-3 col-lg-2 text-truncate text-dark fw-bold">
                                            ลักษณะลำต้น
                                        </dt>
                                        <dd class="col-sm-9 col-lg-10 mb-3">
                                            <?php echo $plantTrunk; ?>
                                        </dd>

                                        <dt class="col-sm-3 col-lg-2 text-truncate text-dark fw-bold">
                                            ลักษณะใบ
                                        </dt>
                                        <dd class="col-sm-9 col-lg-10 mb-3">
                                            <?php echo $plantLeaf; ?>
                                        </dd>

                                        <dt class="col-sm-3 col-lg-2 text-truncate text-dark fw-bold">
                                            ลักษณะดอก
                                        </dt>
                                        <dd class="col-sm-9 col-lg-10 mb-3">
                                            <?php echo $plantFlower; ?>
                                        </dd>

                                        <dt class="col-sm-3 col-lg-2 text-truncate text-dark fw-bold">
                                            ลักษณะผล/เมล็ด
                                        </dt>
                                        <dd class="col-sm-9 col-lg-10">
                                            <?php echo $plantFruit; ?>
                                        </dd>
                                    </div>
                                    <!-- /Plant botanical detail container -->

                                    <!-- Plant cultivation detail container -->
                                    <div class="row p-2 mt-3">
                                        <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill">
                                            <i class="fa-solid fa-sun-plant-wilt text-success me-1"></i>
                                            การเพาะปลูกและขยายพันธุ์
                                        </h5>

                                        <dt class="col-sm-3 col-lg-2 text-truncate text-dark fw-bold">
                                            การเพาะปลูก
                                        </dt>
                                        <dd class="col-sm-9 col-lg-10 mb-3">
                                            <?php echo $plantCultivation; ?>
                                        </dd>

                                        <dt class="col-sm-3 col-lg-2 text-truncate text-dark fw-bold">
                                            การขยายพันธ์ุ
                                        </dt>
                                        <dd class="col-sm-9 col-lg-10 mb-3">
                                            <?php echo $plantPropagation; ?>
                                        </dd>
                                    </div>
                                    <!-- /Plant cultivation detail container -->

                                    <!-- Plant utilization detail container -->
                                    <div class="row p-2 mt-3">
                                        <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill">
                                            <i class="fa-solid fa-plate-wheat text-success me-1"></i>
                                            การใช้ประโยชน์
                                        </h5>

                                        <dt class="col-sm-3 col-lg-2 text-truncate text-dark fw-bold">
                                            ประโยชน์/สรรพคุณ
                                        </dt>
                                        <dd class="col-sm-9 col-lg-10 mb-3">
                                            <?php echo $plantUtilization; ?>
                                        </dd>
                                    </div>
                                    <!-- /Plant utilization detail container -->

                                    <!-- Plant gallery container -->
                                    <div class="row justify-content-center p-2 mt-3">
                                        <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill">
                                            <i class="fa-solid fa-images text-success me-1"></i>
                                            แกเลอรี่ภาพ
                                        </h5>
                                        
                                        <div class="col-12">
                                            <div class="row row-cols-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2 mt-1">
                                                <?php
                                                    //Select plant images to create gallery
                                                    $sql = "SELECT imgPath
                                                            FROM plant_images
                                                            WHERE plantID = ?;";
                                                    $stmt = $database->stmt_init(); 
                                                    $stmt->prepare($sql);
                                                    $stmt->bind_param('s', $plantID);
                                                    $stmt->execute();
                                                    $imgResult = $stmt-> get_result();
                                                    $stmt->close();

                                                    if($imgResult->num_rows > 0){while($img = $imgResult->fetch_assoc()){
                                                ?>

                                                <div class="col">
                                                    <div class="clickable modal-control" data-bs-target="#galleryModal" data-bs-toggle="modal"
                                                    data-modal-title="<?php echo $plantName; ?>">
                                                        <div class="ratio ratio-1x1">
                                                            <img class="fit-cover rounded-3 border border-dark" src="../assets/img/plantImgs/<?php echo $img["imgPath"]; ?>"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php }}else{ ?>

                                                <div class="col">
                                                    <div class="clickable modal-control" data-bs-target="#galleryModal" data-bs-toggle="modal"
                                                    data-modal-title="ไม่มีภาพสำหรับแสดง">
                                                        <div class="ratio ratio-1x1">
                                                            <img class="fit-cover rounded-3 border border-dark" src="../assets/img/plantImgs/default-plant.png"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Plant gallery container -->
                                </div>
                            </div>
                            <!-- /Card plant detail -->

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

        <!-- Modal gallery -->
        <div class="modal fade show" id="galleryModal" tabindex="-1" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex justify-content-center w-100 p-0 m-0">
                            <div id="modalTitle" class="fs-3 fw-bold text-dark text-center text-truncate ms-3"></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-2 p-lg-3">
                        <div class="ratio ratio-4x3">
                            <img id="modalImage" class="fit-cover rounded-3" src="../assets/img/plantImgs/default-plant.png"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Modal gallery -->

        <!-- Template JS -->
        <script src="../assets/js/template.js"></script>

        <!-- Page JS -->
        <script src="../include/scripts/customFunctions.js"></script>
        <script>
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
                            let parentCard = button.closest('.card');

                            if (isFavorite === true) {
                                button.removeClass('text-light').addClass('text-danger');
                            } else {
                                button.removeClass('text-danger').addClass('text-light');
                            }
                        }else{
                            showResponse({
                                response: response
                            });
                        }
                    }
                });
            });

            //Control modal
            $('.modal-control').click(function() {
                let imgSrc = $(this).find('img').attr('src');
                let modalTitle = $(this).data('modal-title');

                $('#modalImage').attr('src', imgSrc);
                $('#modalTitle').text(modalTitle);
            });
        </script>
    </body>
</html>

<?php
    //Close connection
    $database->close();
?>