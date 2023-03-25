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
                                        <a href="patient">ผู้ป่วย</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">แก้ไขข้อมูล</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="d-none fix-menu">patient</span>
                            <!-- /Breadcrumb -->

                            <?php
                                $symptomID = $_GET['symptomID'] ?? '';
                                $patientID = $_GET['patientID'] ?? '';

                                $sql = "SELECT symptomID, symptomStart, symptomEnd, symptomDetail
                                        FROM patientsymptom
                                        WHERE symptomID = ?
                                        LIMIT 1;";

                                $stmt = $bpcsDB->prepare($sql);
                                $stmt->bind_param('s', $symptomID);
                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();
                                $symptom = $result->fetch_assoc();
                            ?>

                            <div class="row g-3">
                                <!-- Card form -->
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <h5 class="card-header">
                                            <i class="fa-solid fa-head-side-cough me-1"></i>
                                            แก้ไขข้อมูลอาการป่วย
                                        </h5>
                                        <div class="card-body">
                                            <form id="formEditSymptom" method="post" action="../data/patient/updateSymptom">
                                                <div class="row g-2">
                                                    <input type="hidden" name="symptomID" value="<?php echo $symptomID;?>">
                                                    <div class="col-12 col-md-6">
                                                        วันที่เริ่มเป็น
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                                                            <input type="date" name="symptomStart" class="form-control" required
                                                            value="<?php echo $symptom['symptomStart']?>">   
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        วันที่หาย
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                                                            <input type="date" name="symptomEnd" class="form-control"
                                                            value="<?php echo $symptom['symptomEnd']?>">   
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        รายละเอียด
                                                        <textarea name="symptomDetail" class="form-control" rows="5" required><?php echo $symptom['symptomDetail']?></textarea>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary me-2">บันทึกข้อมูล</button>
                                                    <a id="backBtn" href="patient-edit?patientID=<?php echo $patientID;?>" class="btn btn-label-secondary">ย้อนกลับ</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card form -->
                            </div>
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
            $('#formEditSymptom').submit(function(e) {
                e.preventDefault();
                var form = $(this);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    errorUrl: '../500',
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
                                    window.location.href="patient-edit?patientID=<?php echo $patientID;?>";
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