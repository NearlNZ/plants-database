<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>ลงทะเบียนสมาชิก</title>
        <link rel="shortcut icon" href="assets/img/element/tab-logo.ico" type="image/x-icon">

        <!-- Fonts -->
        <link rel="stylesheet" href="assets/font/Kanit.css"/>

        <!-- Template CSS -->
        <link rel="stylesheet" href="assets/css/template.css"/>

        <!-- Core JS -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="assets/vendor//perfect-scrollbar/perfect-scrollbar.css"/>
        <link rel="stylesheet" href="assets/vendor/boxicons/boxicons.css"/>

        <!-- Vendors JS -->
        <script src="assets/vendor/fontawesome/js/all.min.js"></script>
        <script src="assets/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- Page Style -->
        <link rel="stylesheet" href="assets/css/custom-style.css"/>
    </head>

    <body class="body-dark">
        <!-- Content -->
        <div class="container-middle p-4">
            <div class="row justify-content-center">
                <!-- Card form -->
                <div class="col-12 col-md-8 col-lg-7">
                    <div class="card mb-2">
                        <h5 class="card-body fw-bold border-bottom mb-0 py-3 text-center">
                            <i class="fa-solid fa-user-large me-1"></i>
                            ลงทะเบียนผู้ใช้ใหม่
                        </h5>
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="assets/img/avatars/default-avatar.png" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
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
                            <form id="formCreateProfile" method="POST" action="data/user/createNewAccount">
                                <div class="row g-3">
                                    <input type="file" id="userProfile" class="d-none" name="userProfile" accept=".jpg,.jpeg,.png">
                                    <div class="col-12">
                                        ชื่อ
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-regular fa-message"></i></span>
                                            <input type="text" name="userFname" class="form-control" value="" maxlength="50" placeholder="ระบุชื่อ" autofocus autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        นามสกุล
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-regular fa-message"></i></span>
                                            <input type="text" name="userLname" class="form-control" value="" maxlength="50" placeholder="ระบุนามสกุล" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        Username
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" name="username" class="form-control" value="" maxlength="50" placeholder="ระบุ Username" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        รหัสผ่าน
                                        <div class="form-password-toggle">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                                <input type="password" name="password" class="form-control" value="" minlength="8" maxlength="12" placeholder="ระบุรหัสผ่าน" autocomplete="off" required>
                                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-text">ความยาวรหัสผ่าน 8-12 ตัวอักษร</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">บันทึกข้อมูล</button>
                                    <a href="login" class="btn btn-label-secondary">ย้อนกลับ</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <p class="small text-white mt-2 head-2 text-center">Copyright &copy; 2023, ❤️Surapat Thippakdee</p> 
                </div>
                <!-- /Card form -->
                
            </div>

        </div>
        <!-- / Content -->

        <!-- Template JS -->
        <script src="assets/js/template.js"></script>

        <!-- Page JS -->
        <script src="include/scripts/customFunctions.js"></script>
        <script>
            //Create profile
            $('#formCreateProfile').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let data = new FormData($('#formCreateProfile')[0]);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    data: data,
                    processData: false,
                    contentType: false,
                    errorUrl: 'requestError',
                    successCallback: function(response) {
                        if(response.status == "success"){
                            showResponse({
                                response: response,
                                timer: 2000,
                                callback: function() {
                                    window.location.href="login";
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