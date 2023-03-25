<?php
    //include permission check
    require_once('../include/scripts/header.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Patient</title>

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
                                        <a class="active">ผู้ป่วย</a>
                                    </li>
                                </ol>
                            </nav>
                            <!-- /Breadcrumb -->

                            <!-- Search -->
                            <div class="card px-lg-2 mt-2">
                                <div class="card-body py-3">
                                    <form action="patient" method="post">
                                        <div class="row row-cols-2 g-2">
                                            <div class="col-12">
                                                ชื่อผู้ป่วย
                                                <div class="input-group">
                                                    <input type="text" name="filterText" class="form-control" placeholder="ค้นหา..." autofocus autocomplete="off">
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
                                $filter = array();
                                $filterType = "";

                                $sql = "SELECT patientID, patientProfile, patientName, patientLastname, patientGender, YEAR(FROM_DAYS(DATEDIFF(NOW(),`patientBirthdate`))) AS patientAge, patientWeight, patientHeight
                                        FROM patient
                                        WHERE 1=1 ";

                                if(!empty($filterText)){
                                    $sql .= "AND (patientName LIKE ? OR patientLastname LIKE ?) ";
                                    $filter[] = "%$filterText%";
                                    $filter[] = "%$filterText%";
                                    $filterType .= "ss";
                                }

                                $stmt = $bpcsDB->prepare($sql);
                                if (!empty($filter)){ 
                                    $stmt->bind_param($filterType, ...$filter);
                                }

                                $sql.="ORDER BY patientID DESC;";

                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();

                                $resultCount = $result->num_rows;
                            ?>
                            
                            <!-- Data card -->
                            <div class="card shadow mt-3">
                                <div class="card-header mb-0">
                                    <!-- Add new -->
                                    <a class="btn btn-success py-3 py-lg-2 col-12 col-lg-auto shadow-sm" href="patient-add">
                                        <i class="fa-solid fa-plus fa-xl me-2"></i>
                                        ลงทะเบียนผู้ป่วย
                                    </a>

                                    <p class="h5 mt-3">
                                        ข้อมูลทั้งหมด <?php echo $resultCount?> รายการ
                                    </p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover card-table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>รูปโปรไฟล์</th>
                                                <th>ชื่อ-สกุล</th>
                                                <th>เพศ</th>
                                                <th>อายุ</th>
                                                <th>จัดการข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php 
                                            if($resultCount > 0){ while($patient = $result->fetch_assoc()){
                                        ?>

                                            <tr>
                                                <td>
                                                    <div class="avatar">
                                                        <img src="../assets/img/avatars/<?php echo $patient["patientProfile"];?>" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </td>
                                                <td><?php echo $patient["patientName"]." ".$patient["patientLastname"];?></td>
                                                <td><?php echo $patient["patientGender"];?></td>
                                                <td><?php echo $patient["patientAge"]." ปี";?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="patient-view?patientID=<?php echo $patient['patientID'];?>">
                                                            <i class="fa-solid fa-user-injured me-1"></i>    
                                                            ดูข้อมูลผู้ป่วย
                                                        </a>
                                                        <a class="dropdown-item" href="patient-edit?patientID=<?php echo $patient['patientID'];?>">
                                                            <i class="bx bx-edit-alt me-1"></i>
                                                            แก้ไขข้อมูล
                                                        </a>
                                                        <a class="dropdown-item deleteBtn" href="../data/patient/deletePatient?patientID=<?php echo $patient['patientID'];?>">
                                                            <i class="bx bx-trash me-1"></i>
                                                            ลบข้อมูลผู้ป่วย
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        
                                        <?php } }else{ ?>

                                            <tr>
                                                <td class="text-center text-warning py-4" colspan="6">
                                                    <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                    ไม่พบข้อมูลผู้ป่วยที่ลงทะเบียน
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
                    text: 'ต้องลบข้อมูลผู้ป่วยออกจากระบบหรือไม่',
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