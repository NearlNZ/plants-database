<?php
    //include permission check
    require_once('../include/scripts/header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Dashboard</title>

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
                <?php require_once("../include/components/sidebar-officer.php");?>
                <!-- /Sidebar -->

                <!-- Page -->
                <div class="layout-page">
                    <!-- Navbar -->
                    <?php require_once("../include/components/navbar-officer.php");?>
                    <!-- /Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-fluid flex-grow-1 container-p-y">
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a>BPCS</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">การใช้งาน</a>
                                    </li>
                                </ol>
                            </nav>
                            <!-- /Breadcrumb -->

                            <!-- Search -->
                            <div class="card px-lg-2 mt-2">
                                <div class="card-body py-3">
                                    <form action="dashboard" method="post">
                                        <div class="row row-cols-2 g-2">
                                            <div class="col-12 col-lg-6">
                                                ชื่อผู้ป่วย
                                                <input type="text" name="filterText" class="form-control" placeholder="ค้นหา..." autofocus autocomplete="off">
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                สถานะการใช้งาน
                                                <div class="input-group">
                                                    <select class="form-select" name="filterStatus">
                                                        <option selected value="">ทั้งหมด</option>
                                                        <option value="กำลังใช้งาน">กำลังใช้งาน</option>
                                                        <option value="สิ้นสุดการใช้งาน">สิ้นสุดการใช้งาน</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary text-white">
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
                                $filterText = $_POST['filterText'] ?? '';
                                $filterStatus = $_POST['filterStatus'] ?? '';
                                $filter = array();
                                $filterType = "";

                                $sql = "SELECT U.useID, P.patientName, P.patientLastname, C.caregiverName, C.caregiverLastname, U.useStatus, U.useStartDate, U.useEndDate, D.deviceSerial
                                        FROM deviceusing U INNER JOIN patient P ON U.patientID = P.patientID INNER JOIN caregiver C on C.caregiverID = U.caregiverID INNER JOIN device D on D.deviceID = U.deviceID
                                        WHERE 1=1 ";

                                if(!empty($filterText)){
                                    $sql .= "AND (patientName LIKE ? OR patientLastname LIKE ?) ";
                                    $filter[] = "%$filterText%";
                                    $filter[] = "%$filterText%";
                                    $filterType .= "ss";
                                }

                                if(!empty($filterStatus)){
                                    $sql .= "AND useStatus = ? ";
                                    $filter[] = $filterStatus;
                                    $filterType .= "s";
                                }

                                $stmt = $bpcsDB->prepare($sql);
                                if (!empty($filter)){ 
                                    $stmt->bind_param($filterType, ...$filter);
                                }

                                $sql.="ORDER BY useStartDate DESC;";

                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();

                                $resultCount = $result->num_rows;
                            ?>
                            
                            <!-- Data card -->
                            <div class="card shadow mt-3">
                                <div class="card-header mb-0">
                                    <!-- Add new -->
                                    <a class="btn btn-success py-3 py-lg-2 col-12 col-lg-auto shadow-sm" href="use-add">
                                        <i class="fa-solid fa-plus fa-xl me-2"></i>
                                        เพิ่มการใช้งาน
                                    </a>

                                    <p class="h5 mt-3">
                                        ข้อมูลทั้งหมด <?php echo $resultCount?> รายการ
                                    </p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover card-table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>วันเริ่มใช้งาน</th>
                                                <th>วันสิ้นสุดการใช้งาน</th>
                                                <th>ชื่อผู้ป่วย</th>
                                                <th>หมายเลข Serial</th>
                                                <th>ชื่อผู้ดูแล</th>
                                                <th>สถานะ</th>
                                                <th>จัดการข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php 
                                            if($resultCount > 0){ while($use = $result->fetch_assoc()){
                                        ?>

                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($use["useStartDate"]));?></td>
                                                <td><?php echo $use["useEndDate"] != '0000-00-00' ? date('d/m/Y', strtotime($use["useEndDate"])) : "-";?></td>
                                                <td><?php echo $use['patientName']." ".$use['patientLastname'];?></td>
                                                <td><?php echo $use['deviceSerial'];?></td>
                                                <td><?php echo $use['caregiverName']." ".$use['caregiverLastname'];?></td>
                                                <td>
                                                    <?php if($use['useStatus'] == "กำลังใช้งาน"){ ?>
                                                        <span class="badge bg-success w-100 py-2">กำลังใช้งาน</span>
                                                    <?php }else{ ?>
                                                            <span class="badge bg-label-secondary w-100 py-2">สิ้นสุดการใช้งาน</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item deleteBtn" href="../data/device/deleteDevice?deviceID=<?php echo $use['useID'];?>">
                                                            <i class="fa-solid fa-x me-1"></i>
                                                            ยกเลิกการใช้งาน
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        
                                        <?php } }else{ ?>

                                            <tr>
                                                <td class="text-center text-warning py-4" colspan="7">
                                                    <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                    ไม่พบข้อมูลการใช้งานอุปกรณ์
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
                                        timer: 1500,
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