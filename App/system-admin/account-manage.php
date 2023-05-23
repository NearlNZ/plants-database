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
        <title>บัญชีผู้ใช้</title>
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
                                        <a class="active">บัญชีผู้ใช้</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="active-menu-url">account-manage</span>
                            <!-- /Breadcrumb & Active menu-->

                            <!-- Search card -->
                            <div class="card mt-2">
                                <div class="card-body p-3">
                                    <form action="" method="GET">
                                        <div class="row g-2">
                                            <div class="col-12 col-lg-6">
                                                ชื่อผู้ใช้ / Username
                                                <input type="search" name="name" class="form-control" placeholder="ค้นหา..." autofocus autocomplete="off"
                                                value="<?php if(isset($_GET["name"])) echo $_GET["name"]; ?>">
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                ระดับผู้ใช้
                                                <select class="select2 form-select" name="level">
                                                    <option selected value="All">ทั้งหมด</option>
                                                    <option <?php if(isset($_GET["level"]) && $_GET["level"] == "ผู้ดูแลระบบ") echo "selected";?> value="ผู้ดูแลระบบ">
                                                        ผู้ดูแลระบบ
                                                    </option>
                                                    <option <?php if(isset($_GET["level"]) && $_GET["level"] == "สมาชิก") echo "selected";?> value="สมาชิก">
                                                        สมาชิก
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                สถานะบัญชี
                                                <div class="row g-3 g-lg-2">
                                                    <div class="col-12 col-lg">
                                                        <select class="select2 form-select" name="status">
                                                            <option selected value="All">ทั้งหมด</option>
                                                            <option <?php if(isset($_GET["status"]) && $_GET["status"] == "บัญชีปกติ") echo "selected";?> value="บัญชีปกติ">
                                                                บัญชีปกติ
                                                            </option>
                                                            <option <?php if(isset($_GET["status"]) && $_GET["status"] == "บัญชีถูกระงับ") echo "selected";?> value="บัญชีถูกระงับ">
                                                                บัญชีถูกระงับ
                                                            </option>
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
                                $sql = "SELECT  U.userID, U.userFname, U.userLname, U.userProfile, U.username, 
                                                U.userLevel, U.userRegist, U.userStatus
                                        FROM    users U
                                        WHERE 1=1 ";

                                $filter = array();
                                $filterDatatype = "";

                                //Check if filter send
                                if(isset($_GET["search"])){
                                    $searchName = $_GET['name'] ?? '';
                                    $searchLevel = $_GET['level'] ?? '';
                                    $searchStatus = $_GET['status'] ?? '';

                                    if(!empty($searchName)){
                                        $sql .= "AND (userFname LIKE ? OR username LIKE ?) ";
                                        $filter[] = "%$searchName%";
                                        $filter[] = "%$searchName%";
                                        $filterDatatype .= "ss";
                                    }
                                    if(!empty($searchLevel) && $searchLevel != "All"){
                                        $sql .= "AND userLevel = ? ";
                                        $filter[] = $searchLevel;
                                        $filterDatatype .= "s";
                                    }
                                    if(!empty($searchStatus) && $searchStatus != "All"){
                                        $sql .= "AND userStatus = ? ";
                                        $filter[] = $searchStatus;
                                        $filterDatatype .= "s";
                                    }
                                }
                                
                                $sql.= "ORDER BY userRegist DESC, userFname, userLname;";

                                $stmt = $database->prepare($sql);
                                
                                if (!empty($filter)){ 
                                    $stmt->bind_param($filterDatatype, ...$filter);
                                }
                                
                                $stmt->execute();
                                $accountResult = $stmt-> get_result();
                                $stmt->close();

                                $resultCount = $accountResult->num_rows;
                            ?>
                            
                            <!-- Data card -->
                            <div class="card mt-3">
                                <div class="card-body py-3">
                                    <!-- Action -->
                                    <div class="row g-2">
                                        <a class="btn btn-success col-12 col-lg-auto shadow-sm me-2" href="account-add">
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
                                                <th>บัญชีผู้ใช้</th>
                                                <th width="10px"></th>
                                                <th class="ps-2">ระดับผู้ใช้</th>
                                                <th>วันที่ลงทะเบียน</th>
                                                <th>สถานะบัญชี</th>
                                                <th class="text-center" width="150px">จัดการข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php 
                                            if($resultCount > 0){ $accountIndex = 1; while($account = $accountResult->fetch_assoc()){
                                        ?>

                                            <tr>
                                                <td><?php echo number_format($accountIndex); ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-start align-items-center user-name">
                                                        <div class="avatar-wrapper">
                                                            <div class="avatar me-2">
                                                                <img src="../assets/img/avatars/<?php echo $account["userProfile"];?>" alt="user profile" class="rounded-circle">
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-truncate">
                                                                <?php echo $account["userFname"]." ".$account["userLname"];?>
                                                            </span>
                                                            <small class="text-truncate text-muted">
                                                                <?php echo $account["username"];?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end px-0" width="10px">
                                                    <?php 
                                                        $levelText = $account["userLevel"];
                                                        $levelIconClass = "";
                                                        if($levelText == "ผู้ดูแลระบบ"){
                                                            $levelIconClass = "fa-solid fa-crown text-warning";
                                                        }
                                                    ?>
                                                    
                                                    <i class="<?php echo $levelIconClass; ?>"></i>
                                                </td>
                                                <td class="text-start ps-2">
                                                    <?php echo $levelText;?>
                                                </td>
                                                <td>
                                                    <?php echo date("j/n/Y", strtotime($account["userRegist"]));?>
                                                </td>
                                                <td td class="text-start">
                                                    <?php
                                                        $statusText = $account["userStatus"];
                                                        $statusColorClass = "bg-label-success text-success";
                                                        if($statusText == "บัญชีถูกระงับ"){
                                                            $statusColorClass = "bg-label-danger text-danger";
                                                        }
                                                    ?>

                                                    <span class="badge rounded-pill <?php echo $statusColorClass; ?>">
                                                        <?php echo $statusText; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <?php if ($account["userID"] != $currentUser->userID){ ?>
                                                            <a class="dropdown-item" href="account-view?userID=<?php echo $account['userID'];?>">
                                                                <i class="bx bx-show-alt me-1"></i>
                                                                ดูข้อมูล
                                                            </a>
                                                            <a class="dropdown-item" href="account-edit?userID=<?php echo $account['userID'];?>">
                                                                <i class="bx bx-edit-alt me-1"></i>
                                                                แก้ไขข้อมูล
                                                            </a>
                                                            <a class="dropdown-item deleteBtn" href="../data/user/deleteAccount?userID=<?php echo $account['userID'];?>">
                                                                <i class="bx bx-trash me-1"></i>
                                                                ลบข้อมูล
                                                            </a>
                                                        <?php }else{ ?>
                                                            <a class="dropdown-item" href="profile">
                                                                <i class="bx bx-show-alt me-1"></i>
                                                                ดูข้อมูล
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        
                                        <?php $accountIndex++;} }else{ ?>

                                            <tr>
                                                <td class="text-center text-muted py-3" colspan="7">
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
                    title: "ลบบัญชีผู้ใช้",
                    text: 'การลบบัญชีผู้ใช้จะไม่สามารถกู้คืนข้อมูลในภายหลังได้',
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