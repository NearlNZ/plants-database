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
        <link rel="stylesheet" href="../assets/vendor/select2/select2.css"/>
        <link rel="stylesheet" href="../assets/vendor/perfect-scrollbar/perfect-scrollbar.css"/>
        <link rel="stylesheet" href="../assets/vendor/boxicons/boxicons.css"/>

        <!-- Vendors JS -->
        <script src="../assets/vendor/fontawesome/js/all.min.js"></script>
        <script src="../assets/vendor/select2/select2.js"></script>
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
                                        <a class="active">ข้อมูลพืช</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="active-menu-url">plant</span>
                            <!-- /Breadcrumb & Active menu-->

                            <!-- Search card -->
                            <div class="card mt-2">
                                <div class="card-body py-3">
                                    <form action="" method="GET">
                                        <div class="row g-2">
                                            <div class="col-12 col-lg-6">
                                                ชื่อพืช
                                                <input type="text" name="word" class="form-control" placeholder="ค้นหา..." autofocus autocomplete="off"
                                                value="<?php if(isset($_GET["word"])) echo $_GET["word"]; ?>">
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
                                                                if($result->num_rows > 0){
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
                                                        <button type="submit" name="filter" value="true" class="btn btn-primary text-white w-100">
                                                            <i class='bx bx-search-alt'></i>
                                                            <span class="d-inline d-lg-none p-0">ค้นหา</span>
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
                                $sql = "SELECT  P.plantID, P.plantName, P.plantRegist, TL.tagID,
                                                U.userFname, U.userLname
                                        FROM    plants P 
                                                LEFT JOIN tag_lists TL ON P.plantID = TL.PlantID
                                                LEFT JOIN users U ON P.userID = U.userID
                                        WHERE 1=1 ";

                                $filter = array();
                                $filterDatatype = "";

                                //Check if filter send
                                if(isset($_GET["filter"])){
                                    $filterWord = $_GET['word'] ?? '';
                                    $filterTag = $_GET['tag'] ?? '';

                                    if(!empty($filterWord)){
                                        $sql .= "AND plantName LIKE ? ";
                                        $filter[] = "%$filterWord%";
                                        $filterDatatype .= "s";
                                    }
                                    if(!empty($filterTag) && $filterTag != "All"){
                                        $sql .= "AND TL.tagID = ? ";
                                        $filter[] = $filterTag;
                                        $filterDatatype .= "s";
                                    }
                                }
                                
                                $sql.= "GROUP BY plantID
                                        ORDER BY plantRegist, plantName DESC;";

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
                                                <th>ลำดับ</th>
                                                <th>ชื่อพืช</th>
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
            //Select
            $(function(){
                select = $(".select2");
                select.length&&select.each(function(){
                    var element=$(this);
                    element.wrap('<div class="position-relative"></div>').select2({
                        dropdownParent:element.parent()
                    });
                });
            });

            //Delete record
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