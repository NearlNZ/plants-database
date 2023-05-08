<?php
    //include permission check
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
                                        <a class="active">รายการพืช</a>
                                    </li>
                                </ol>
                            </nav>
                            <!-- /Breadcrumb & Active menu-->

                            <!-- Search -->
                            <div class="card mt-2">
                                <div class="card-body py-3">
                                    <form action="gallery" method="GET">
                                        <div class="row row-cols-2 g-2">
                                            <div class="col-12 col-lg-6">
                                                ชื่อพืช
                                                <input type="text" name="filterWord" class="form-control" placeholder="ค้นหา..." autofocus autocomplete="off"
                                                value="<?php if(isset($_GET["filterWord"])) echo $_GET["filterWord"]; ?>">
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                หมวดหมู่
                                                <div class="input-group">
                                                    <select class="form-select" name="filterCategory">
                                                        <option selected value="">ทั้งหมด</option>

                                                        <?php
                                                            $sql = "SELECT tagID, tagName
                                                                    FROM categories
                                                                    ORDER BY tagName;";
                                                            
                                                            $result = $database->query($sql);
                                                            if($result->num_rows > 0){
                                                                while($category = $result->fetch_assoc()){
                                                        ?>
                                                            <option value="<?php echo $category['tagID']; ?>"
                                                                <?php if(isset($_GET["filterCategory"]) && $_GET["filterCategory"] == $category['tagID']) echo "selected"; ?>>
                                                                <?php echo $category["tagName"]; ?>
                                                            </option>
                                                        <?php
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                    <button type="submit" name="filter" value="true" class="btn btn-primary text-white">
                                                        <i class='bx bx-search-alt'></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Search -->

                            <?php
                                $sql = "SELECT P.plantID, P.plantName, P.plantRegist, C.tagName, U.userFname, U.userLname, (SELECT imgPath FROM plantimages WHERE plantID = P.plantID LIMIT 1) AS plantImg
                                        FROM plants P LEFT JOIN categories C ON P.tagID = C.tagID LEFT JOIN users U ON P.userID = U.userID
                                        WHERE 1=1 ";

                                $filter = array();
                                $filterDatatype = "";

                                //Check if filter send
                                if(isset($_GET["filter"])){
                                    $filterWord = $_GET['filterWord'] ?? '';
                                    $filterCategory = $_GET['filterCategory'] ?? '';

                                    if(!empty($filterWord)){
                                        $sql .= "AND plantName LIKE ? ";
                                        $filter[] = "%$filterWord%";
                                        $filterDatatype .= "s";
                                    }
                                    if(!empty($filterCategory)){
                                        $sql .= "AND P.tagID = ? ";
                                        $filter[] = $filterCategory;
                                        $filterDatatype .= "s";
                                    }
                                }
                                
                                $sql.="ORDER BY plantRegist DESC;";
                                
                                $stmt = $database->prepare($sql);
                                
                                if (!empty($filter)){ 
                                    $stmt->bind_param($filterDatatype, ...$filter);
                                }
                                
                                $stmt->execute();
                                $plantResult = $stmt-> get_result();
                                $stmt->close();

                                $resultCount = $plantResult->num_rows;
                            ?>

                            <!-- Plant gallery -->
                            <div class="row g-2 mt-2">
                                <?php
                                    if($resultCount > 0){
                                        while($plant = $plantResult->fetch_assoc()){
                                            if(empty($plant["plantImg"])) $plant["plantImg"] = "default-plantcover.png";
                                ?>

                                <div class="col-12 col-md-6 col-lg-3 position-relative">
                                    <div class="card">
                                        <div clas="p-0">
                                            <img src="../assets/img/plantImgs/<?php echo $plant["plantImg"];?>" class="card-img-top bg-black w-100 h-100 fit-cover rounded-3" style="max-height:200px;">
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title text-success"><?php echo $plant["plantName"];?></h4>
                                            <ul class="list-unstyled mb-3 mt-3">
                                                <li class="d-flex align-items-center mb-1">
                                                    <i class="fa-regular fa-rectangle-list me-1"></i>
                                                    <span class="fw-semibold mx-2">หมวดหมู่ :</span>
                                                    <span><?php echo $plant["tagName"];?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-1">
                                                    <i class="fa-solid fa-user me-1"></i>
                                                    <span class="fw-semibold mx-2">ผู้ลงทะเบียน :</span>
                                                    <span><?php echo $plant["userFname"]." ".$plant["userLname"];?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-1">
                                                    <i class="fa-solid fa-calendar-day me-1"></i>
                                                    <span class="fw-semibold mx-2">วันลงทะเบียน :</span>
                                                    <span><?php echo date("j/n/Y", strtotime($plant["plantRegist"]));?></span>
                                                </li>
                                            </ul>
                                            <a href="gallery-view?plantID=<?php echo $plant["plantID"];?>" class="btn btn-success w-100">
                                                <i class="fa-solid fa-seedling me-1"></i>
                                                ดูข้อมูลพืช
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                        }
                                    }
                                ?>
                            </div>
                            <!-- Plant gallery -->

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