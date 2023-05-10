<?php
    //include permission check
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
                            <div class="row">
                                <!-- Card form -->
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <h5 class="card-header mb-0">
                                            <i class="fa-solid fa-address-card me-1"></i>
                                            ข้อมูลบัญชีผู้ใช้
                                        </h5>
                                        <div class="card-body">
                                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                <img src="../assets/img/avatars/<?php echo $user->userProfile;?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
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
                                            <form id="formUpdateProfile" method="POST" action="../data/user/updateAccount">
                                                <div class="row g-3">
                                                    <input type="file" id="userProfile" class="d-none" name="userProfile" accept=".jpg,.jpeg,.png">
                                                    <input type="text" class="d-none" name="oldProfile" value="<?php echo $user->userProfile;?>">
                                                    <input type="hidden" name="userID" value="<?php echo $user->userID;?>">
                                                    <div class="col-12 col-md-6">
                                                        ชื่อ
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-comment"></i></span>
                                                            <input type="text" name="userFname" class="form-control" value="<?php echo $user->userFname;?>" placeholder="ระบุชื่อ" autofocus autocomplete="off" required>   
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        นามสกุล
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-comment"></i></span>
                                                            <input type="text" name="userLname" class="form-control" value="<?php echo $user->userLname;?>" placeholder="ระบุนามสกุล" autocomplete="off" required>   
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary me-2">บันทึกข้อมูล</button>
                                                    <a href="profile" class="btn btn-label-secondary">ย้อนกลับ</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Card form -->

                                <!-- /Card reset password -->
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <h5 class="card-header mb-0">
                                            <i class="fa-solid fa-key me-1"></i>
                                            รีเซ็ตรหัสผ่าน
                                        </h5>
                                        <div class="card-body">
                                            <form id="formResetPassword" method="POST" action="../data/user/resetPassword">
                                                <div class="row g-3">
                                                    <input type="hidden" name="userID" value="<?php echo $user->userID;?>">
                                                    <div class="col-12 col-md-6">
                                                        ยืนยันรหัสผ่านปัจจุบัน
                                                        <div class="form-password-toggle">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                                                <input type="password" name="oldPassword" class="form-control" value="" placeholder="ระบุรหัสผ่านปัจจุบัน" autocomplete="off" required>   
                                                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        รหัสผ่านใหม่
                                                        <div class="form-password-toggle">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                                                <input type="password" name="password" class="form-control" value="" placeholder="ระบุรหัสผ่านใหม่" autocomplete="off" required>   
                                                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary me-2">รีเซ็ตรหัสผ่าน</button>
                                                    <button type="reset" class="btn btn-label-secondary">ยกเลิก</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
            $('#formUpdateProfile').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let data = new FormData($('#formUpdateProfile')[0]);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: data,
                    processData: false,
                    contentType: false,
                    errorUrl: '../requestError',
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
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
            });

            //Reset password
            $('#formResetPassword').submit(function(e) {
                e.preventDefault();
                let form = $(this);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
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
            });

            //Control avartar upload
            let uploadedAvatar = document.getElementById("uploadedAvatar");
            const fileInput = document.getElementById("userProfile");
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