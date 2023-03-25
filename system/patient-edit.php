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
                                $patientID = $_GET['patientID'] ?? '';
                                $sql = "SELECT patientID, patientName, patientLastname, patientGender, patientBirthdate, patientWeight, patientHeight, patientProfile
                                        FROM patient
                                        WHERE patientID = ?
                                        LIMIT 1;";

                                $stmt = $bpcsDB->prepare($sql);
                                $stmt->bind_param('s', $patientID);
                                $stmt->execute();
                                $result = $stmt-> get_result();
                                $stmt->close();
                                $patient = $result->fetch_assoc();

                                //Set variable
                                $patientID = $patient['patientID'] ?? '';
                                $patientName = $patient['patientName'] ?? '';
                                $patientLastname = $patient['patientLastname'] ?? '';
                                $patientGender = $patient['patientGender'] ?? '';
                                $patientBirthdate = $patient['patientBirthdate'] ?? '';
                                $patientWeight = $patient['patientWeight'] ?? '';
                                $patientHeight = $patient['patientHeight'] ?? '';
                                $patientProfile = $patient['patientProfile'] ?? 'default-avatar.png';
                            ?>

                            <div class="row">
                                <!-- Card form -->
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <h5 class="card-header text-muted mb-0">
                                            <i class="fa-solid fa-user-injured me-1"></i>
                                            ข้อมูลทั่วไปของผู้ป่วย
                                        </h5>
                                        <div class="card-body">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <img src="../assets/img/avatars/<?php echo $patientProfile;?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                                                <div class="button-wrapper">
                                                    <label id="uploadButton" class="btn btn-primary me-2 mb-4" tabindex="0">
                                                        <span class="d-none d-sm-block">อัพโหลดรูปภาพ</span>
                                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                                    </label>
                                                    <button id="fileReset" type="button" class="btn btn-label-secondary mb-4">
                                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">รีเซ็ต</span>
                                                    </button>
                                                    <p class="text-muted mb-0">รองรับไฟล์รูปภาพ JPG, JPEG และ PNG</p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-0">

                                        <div class="card-body">
                                            <form id="formAddPatient" method="POST" action="../data/patient/updatePatient">
                                                <div class="row g-3">
                                                    <input type="file" id="patientProfile" class="d-none" name="patientProfile" accept="jpg,.jpeg,.png" maxlength="1000000">
                                                    <input type="text" class="d-none" name="oldProfile" value="<?php echo $patientProfile;?>">
                                                    <input type="hidden" name="patientID" value="<?php echo $patientID;?>">
                                                    <div class="col-12 col-md-6">
                                                        ชื่อ
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                                            <input type="text" name="patientName" class="form-control" placeholder="ระบุชื่อ" autofocus autocomplete="off" required
                                                            value="<?php echo $patientName;?>">   
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        นามสกุล
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                                            <input type="text" name="patientLastname" class="form-control" placeholder="ระบุนามสกุล" autocomplete="off" required
                                                            value="<?php echo $patientLastname;?>">   
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        เพศ
                                                        <select class="form-select" name="patientGender">
                                                            <option <?php if($patientGender == "ชาย") echo "selected";?> value="ชาย">ชาย</option>
                                                            <option <?php if($patientGender == "หญิง") echo "selected";?> value="หญิง">หญิง</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        วัน/เดือน/ปี เกิด
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                                                            <input type="date" name="patientBirthdate" class="form-control" required
                                                            value="<?php echo $patientBirthdate;?>">   
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        น้ำหนัก (กิโลกรัม)
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-solid fa-weight-scale"></i></span>
                                                            <input type="number" name="patientWeight" step="0.01" class="form-control" required
                                                            value="<?php echo $patientWeight;?>">   
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        ส่วนสูง (เซนติเมตร)
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-solid fa-arrows-up-down"></i></span>
                                                            <input type="number" name="patientHeight" step="0.01" class="form-control" required
                                                            value="<?php echo $patientHeight;?>">   
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary me-2">บันทึกข้อมูล</button>
                                                    <a href="patient" class="btn btn-label-secondary">ย้อนกลับ</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card form -->

                                <?php
                                    $sql = "SELECT symptomID, symptomDetail, symptomStart, symptomEnd
                                            FROM patientsymptom
                                            WHERE patientID = ?
                                            ORDER BY symptomStart DESC;";
                                            
                                    $stmt = $bpcsDB->prepare($sql);
                                    $stmt->bind_param('s', $patientID);
                                    $stmt->execute();
                                    $result = $stmt-> get_result();
                                    $stmt->close();

                                    $resultCount = $result->num_rows;
                                ?>

                                <!-- Card symptom-->
                                <div class="col-12">
                                    <div class="card">
                                        <h5 class="card-header text-muted">
                                            <!-- Add new -->
                                            <a class="btn btn-success py-3 py-lg-2 col-12 col-lg-auto shadow-sm" href="patient-symptom-add?patientID=<?php echo $patientID;?>">
                                                <i class="fa-solid fa-plus fa-xl me-2"></i>
                                                บันทึกอาการป่วย
                                            </a>
                                            
                                            <p class="h5 mt-3">
                                                ข้อมูลทั้งหมด <?php echo $resultCount;?> รายการ
                                            </p>
                                        </h5>

                                        <?php
                                            $sql = "SELECT symptomID, symptomDetail, symptomStart, symptomEnd
                                                    FROM patientsymptom
                                                    WHERE patientID = ?
                                                    ORDER BY symptomStart DESC;";
                                            
                                            $stmt = $bpcsDB->prepare($sql);
                                            $stmt->bind_param('s', $patientID);
                                            $stmt->execute();
                                            $result = $stmt-> get_result();
                                            $stmt->close();

                                            $resultCount = $result->num_rows;
                                        ?>

                                        <div class="table-responsive">
                                            <table class="table table-hover card-table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>รายละเอียด</th>
                                                        <th>วันที่เริ่มเป็น</th>
                                                        <th>วันที่หาย</th>
                                                        <th>จัดการข้อมูล</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                <?php 
                                                    if($resultCount > 0){ while($symptom = $result->fetch_assoc()){
                                                ?>

                                                <tr>
                                                    <td><?php echo $symptom["symptomDetail"];?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($symptom["symptomStart"]));?></td>
                                                    <td>
                                                        <?php echo $symptom["symptomEnd"] != '0000-00-00' ? date('d/m/Y', strtotime($symptom["symptomEnd"])) : "-";?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="patient-symptom-edit?symptomID=<?php echo $symptom['symptomID'];?>&patientID=<?php echo $patientID;?>">
                                                                <i class="bx bx-edit-alt me-1"></i>
                                                                แก้ไขข้อมูล
                                                            </a>
                                                            <a class="dropdown-item deleteBtn" href="../data/patient/deleteSymptom?symptomID=<?php echo $symptom['symptomID'];?>">
                                                                <i class="bx bx-trash me-1"></i>
                                                                ลบข้อมูล
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <?php } }else{ ?>

                                                    <tr>
                                                        <td class="text-center text-warning py-3" colspan="4">
                                                            <i class="fa-solid fa-triangle-exclamation fa-xl me-1"></i>
                                                            ไม่พบบันทึกข้อมูลอาการป่วย
                                                        </td>
                                                    </tr>

                                                <?php } ?>

                                                </tbody>
                                            </table>
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
            //Update profile
            $('#formAddPatient').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData($('#formAddPatient')[0]);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: data,
                    processData: false,
                    contentType: false,
                    errorUrl: '../500',
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
                                    window.location.href="patient";
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

            //Delete symptom
            $('.deleteBtn').click(function(){
                event.preventDefault();
                let url = $(this).attr('href');
                
                showConfirm({
                    icon: 'question',
                    text: 'ต้องลบข้อมูลอาการป่วยออกจากระบบหรือไม่',
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

            //Control avartar upload
            let uploadedAvatar = document.getElementById("uploadedAvatar");
            const fileInput = document.getElementById("patientProfile");
            const uploadButton = document.getElementById("uploadButton");
            const fileReset = document.getElementById("fileReset");
            if(uploadedAvatar){
                const oldAvartar = uploadedAvatar.src;
                uploadButton.onclick=()=>{
                    fileInput.click();
                }

                fileInput.onchange=()=>{
                    fileInput.files[0]&&(uploadedAvatar.src=window.URL.createObjectURL(fileInput.files[0]));
                }
                
                fileReset.onclick=()=>{
                    fileInput.value="";
                    uploadedAvatar.src = oldAvartar;
                }
            }
        </script>
    </body>
</html>