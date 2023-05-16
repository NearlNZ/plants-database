<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Request Error</title>
        <link rel="shortcut icon" href="assets/img/element/tab-logo.ico" type="image/x-icon">

        <!-- Fonts -->
        <link rel="stylesheet" href="assets/font/Kanit.css" />

        <!-- Template CSS -->
        <link rel="stylesheet" href="assets/css/template.css" />

        <!-- Core JS -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="assets/vendor/boxicons/boxicons.css" />

        <!-- Vendors JS -->
        <script src="assets/vendor/fontawesome/js/all.min.js"></script>
        <script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- Page Style -->
        <link rel="stylesheet" href="assets/css/custom-style.css"/>
    </head>
    <body class="body-light">
        <!-- Content -->
        <div class="container-xxl">
            <div class="container-middle">
                <div class="py-4 text-center width-700">
                    <h2 class="mb-2 mx-2">Request Error :(</h2>
                    <p class="mb-4 mx-2 h5">Oops! 😖 An error occurred while processing your request.</p>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body py-3">
                                <span class="h3">
                                    <i class="fa-solid fa-file-circle-exclamation"></i>
                                    Error Log
                                </span>
                                <hr class="mb-0">
                                <div id="logContainer" class="text-danger text-start mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-primary mt-4" onclick="window.close();">
                        Close this page and go back
                    </a>
                </div>
            </div>
        </div>
        <!-- / Content -->
        
        <!-- Template JS -->
        <script src="assets/js/template.js"></script>

        <!-- Page JS -->
        <script src="include/scripts/customFunctions.js"></script>
        <script>
            const errorLog = localStorage.getItem('GR-localStorage-errorLog');
            if(errorLog){
                $('#logContainer').html(errorLog);
                localStorage.removeItem('GR-localStorage-errorLog');
            }else{
                $('#logContainer').html('<strong>Nothing</strong> to show here. I wonnder why this page showing up.');
            }
        </script>
    </body>
</html>