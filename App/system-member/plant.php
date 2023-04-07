<?php
    //include permission check
    require_once('../include/scripts/member-header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>จัดการข้อมูลพืช</title>

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
                                        <a class="active">ข้อมูลพืช</a>
                                    </li>
                                </ol>
                            </nav>
                            <!-- /Breadcrumb -->

                            <!-- Search -->
                            <div class="card mt-2">
                                <div class="card-body py-3">
                                    <form action="plant" method="GET">
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
                                                            $sql = "SELECT cateID, cateName
                                                                    FROM categories
                                                                    ORDER BY cateName;";
                                                            
                                                            $result = $database->query($sql);
                                                            if($result->num_rows > 0){
                                                                while($category = $result->fetch_assoc()){
                                                        ?>
                                                            <option value="<?php echo $category['cateID']; ?>"
                                                                <?php if(isset($_GET["filterCategory"]) && $_GET["filterCategory"] == $category['cateID']) echo "selected"; ?>>
                                                                <?php echo $category["cateName"]; ?>
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
                                $sql = "SELECT P.plantID, P.plantName, P.plantRegist, C.cateName, U.userFname, U.userLname
                                        FROM plants P LEFT JOIN categories C ON P.cateID = C.cateID LEFT JOIN users U ON P.userID = U.userID
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
                                        $sql .= "AND P.cateID = ? ";
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
                            
                            <!-- Data card -->
                            <div class="card shadow mt-3">
                                <div class="card-header mb-0">
                                    <!-- Add new -->
                                    <div class="row g-2">
                                        <a class="btn btn-success py-3 py-lg-2 col-12 col-lg-auto shadow-sm me-2" href="plant-add">
                                            <i class="fa-solid fa-plus fa-xl me-2"></i>
                                            เพิ่มข้อมูลพืช
                                        </a>
                                        <a class="btn btn-primary text-white py-3 py-lg-2 col-12 col-lg-auto shadow-sm d-none d-lg-block" onclick="printReport()">
                                            <i class="fa-solid fa-print me-2"></i>
                                            พิมพ์รายงาน
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover card-table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ลำดับที่</th>
                                                <th>ชื่อพืช</th>
                                                <th>หมวดหมู่พืช</th>
                                                <th>ผู้ลงทะเบียน</th>
                                                <th>วันที่ลงทะเบียน</th>
                                                <th class="not-print">จัดการข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php 
                                            if($resultCount > 0){ $plantIndex = 1; while($plant = $plantResult->fetch_assoc()){
                                        ?>

                                            <tr>
                                                <td><?php echo $plantIndex; ?></td>
                                                <td><?php echo $plant["plantName"]; ?></td>
                                                <td><?php echo $plant["cateName"]; ?></td>
                                                <td><?php echo $plant["userFname"]." ".$plant["userLname"]; ?></td>
                                                <td><?php echo date("d/m/Y", strtotime($plant["plantRegist"])); ?></td>
                                                <td class="not-print">
                                                    <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="plant-edit?plantID=<?php echo $plant['plantID'];?>">
                                                            <i class="bx bx-edit-alt me-1"></i>
                                                            แก้ไขข้อมูล
                                                        </a>
                                                        <a class="dropdown-item deleteBtn" href="../data/plant/deletePlant?plantID=<?php echo $plant['plantID'];?>">
                                                            <i class="bx bx-trash me-1"></i>
                                                            ลบข้อมูล
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        
                                        <?php $plantIndex++;} }else{ ?>

                                            <tr>
                                                <td class="text-center text-warning py-4" colspan="6">
                                                    <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                    ไม่พบข้อมูลพืช
                                                </td>
                                            </tr>

                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /Data card -->

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
            $('.deleteBtn').click(function(){
                event.preventDefault();
                let url = $(this).attr('href');
                
                showConfirm({
                    icon: 'question',
                    text: 'ต้องการนำอุปกรณ์ออกจากระบบหรือไม่',
                    confirmButtonText: 'ดำเนินการต่อ',
                    confirmCallback: function(){
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
                    }
                });
            });
        </script>
    </body>
</html>