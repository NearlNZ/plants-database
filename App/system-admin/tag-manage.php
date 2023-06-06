<?php
    //Include database connection
	require_once("../data/database.php");

    //include permission check
    require_once('../include/scripts/admin-header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>หมวดหมู่พืช</title>
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
                                        <a class="active">หมวดหมู่พืช</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="active-menu-url">tag-manage</span>
                            <!-- /Breadcrumb & Active menu-->

                            <!-- Search card -->
                            <div class="card mt-2">
                                <div class="card-body p-3">
                                    <form action="" method="GET">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                ชื่อหมวดหมู่
                                                <div class="row g-3 g-lg-2">
                                                    <div class="col-12 col-lg">
                                                        <input type="search" name="name" class="form-control" placeholder="ค้นหา..." autofocus autocomplete="off"
                                                        value="<?php if(isset($_GET["name"])) echo $_GET["name"]; ?>">
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
                                $sql = "SELECT  T.tagID, T.tagName, T.tagAdd, U.userID, U.username, 
                                                (SELECT count(*) FROM tag_lists WHERE tagID = T.tagID) as plantCount
                                        FROM    Tags T LEFT JOIN users U ON U.userID = T.userID
                                        WHERE 1=1 ";

                                $filter = array();
                                $filterDatatype = "";

                                //Check if filter send
                                if(isset($_GET["search"])){
                                    $searchName = $_GET['name'] ?? '';

                                    if(!empty($searchName)){
                                        $sql .= "AND tagName LIKE ? ";
                                        $filter[] = "%$searchName%";
                                        $filterDatatype .= "s";
                                    }
                                }
                                
                                $sql.= "ORDER BY plantCount DESC, tagName;";

                                $stmt = $database->prepare($sql);
                                
                                if (!empty($filter)){ 
                                    $stmt->bind_param($filterDatatype, ...$filter);
                                }
                                
                                $stmt->execute();
                                $tagResult = $stmt-> get_result();
                                $stmt->close();

                                $resultCount = $tagResult->num_rows;
                            ?>
                            
                            <!-- Data card -->
                            <div class="card mt-3">
                                <div class="card-body py-3">
                                    <!-- Action -->
                                    <div class="row g-2">
                                        <a class="btn btn-success col-12 col-lg-auto shadow-sm me-2" href="tag-add">
                                            <i class="fa-solid fa-plus me-2"></i>
                                            เพิ่มข้อมูล
                                        </a>
                                    </div>
                                    <!-- /Action -->
                                </div>
                                <div id="dataTable" class="table-responsive">
                                    <table class="table table-hover card-table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ลำดับที่</th>
                                                <th>ชื่อหมวดหมู่</th>
                                                <th>วันที่ลงทะเบียน</th>
                                                <th>จำนวนพืช</th>
                                                <th class="text-center">จัดการข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php 
                                            if($resultCount > 0){ $tagIndex = 1; while($tag = $tagResult->fetch_assoc()){
                                        ?>

                                            <tr>
                                                <td class="text-center cell-fit">
                                                    <?php echo number_format($tagIndex); ?>
                                                </td>
                                                <td>
                                                    <?php echo $tag["tagName"]; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="d-block">
                                                            <?php echo date("j/n/Y", strtotime($tag["tagAdd"])); ?>
                                                        </span>
                                                        <small class="text-muted">
                                                            <?php
                                                                $accountLink = '';
                                                                if($tag['userID'] != $currentUser->userID){
                                                                    $accountLink = "href='account-view?userID=".$tag['userID']."'";
                                                                }
                                                                else if($tag['userID'] == $currentUser->userID){
                                                                    $accountLink = "href='profile'";
                                                                }
                                                            ?>

                                                            เพิ่มโดย
                                                            <a class="text-secondary text-decoration-underline" <?php if($accountLink != '') echo $accountLink;?>>
                                                                <?php echo !empty($tag["username"]) ? $tag["username"] : "(บัญชีที่ถูกลบ)"; ?>
                                                            </a>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <i class="fa-solid fa-seedling text-success me-1"></i>
                                                    <?php echo number_format($tag["plantCount"]); ?>
                                                </td>
                                                <td class="text-center cell-fit">
                                                    <button type="button" class="btn btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="tag-edit?tagID=<?php echo $tag['tagID'];?>">
                                                            <i class="bx bx-edit-alt me-1"></i>
                                                            แก้ไขข้อมูล
                                                        </a>
                                                        <a class="dropdown-item deleteBtn" href="../data/tag/deleteTag?tagID=<?php echo $tag['tagID'];?>">
                                                            <i class="bx bx-trash me-1"></i>
                                                            ลบข้อมูล
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        
                                        <?php $tagIndex++;} }else{ ?>

                                            <tr>
                                                <td class="text-center text-muted py-3" colspan="5">
                                                    --- ไม่พบข้อมูลสำหรับแสดงผล ---
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
                    let element=$(this);
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
                    text: 'ต้องการลบข้อมูลที่เลือกหรือไม่',
                    confirmButtonText: 'ดำเนินการต่อ',
                    confirmCallback: function(){
                        ajaxRequest({
                            type: 'GET',
                            url: url,
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

<?php
    //Close connection
    $database->close();
?>