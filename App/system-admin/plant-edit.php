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
        <title>รายการพืช</title>
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
        <link rel="stylesheet" href="../assets/vendor/select2/select2.css"/>

        <!-- Vendors JS -->
        <script src="../assets/vendor/fontawesome/js/all.min.js"></script>
        <script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
        <script src="../assets/vendor/select2/select2.js"></script>

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
                                        <a href="plant-manage">รายการพืช</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a class="active">แก้ไขข้อมูล</a>
                                    </li>
                                </ol>
                            </nav>

                            <span class="active-menu-url">plant-manage</span>
                            <!-- /Breadcrumb & Active menu-->

                            <?php
                                $plantD = $_GET["plantD"] ?? "";
                                
                                $plantID = $_GET["plantID"] ?? "";
                                $userID = $currentUser->userID;
                                
                                function selectPlantData ($database, $plantID){
                                    if(empty($plantID)){
                                        return false;
                                    }

                                    $sql = "SELECT  P.plantID, P.plantName, P.commonName, P.otherName, P.scientificName, P.familyName,
                                                    P.plantTrunk, P.plantLeaf, P.plantFlower, P.plantFruit,
                                                    P.plantCultivation, P.plantPropagation,
                                                    P.plantUtilization
                                            FROM    plants P
                                            WHERE plantID = ?;";
                                    
                                    $stmt = $database->stmt_init(); 
                                    $stmt->prepare($sql);
                                    $stmt->bind_param('s', $plantID);
                                    $stmt->execute();
                                    $plantResult = $stmt-> get_result();
                                    $stmt->close();

                                    if($plantResult->num_rows == 0){
                                        return false;
                                    }

                                    return $plantResult->fetch_assoc();
                                }

                                $plant = selectPlantData($database, $plantID, $userID);
                                //Case data exist
                                if($plant != false){
                                    //Set data array to variable
                                    $plantID = $plant["plantID"];
                                    $plantName = $plant["plantName"];
                                    $commonName = $plant["commonName"];
                                    $otherName = $plant["otherName"];
                                    $scientificName = $plant["scientificName"];
                                    $familyName = $plant["familyName"];

                                    $plantTrunk = htmlspecialchars(strip_tags($plant["plantTrunk"]));
                                    $plantLeaf = htmlspecialchars(strip_tags($plant["plantLeaf"]));
                                    $plantFlower = htmlspecialchars(strip_tags($plant["plantFlower"]));
                                    $plantFruit = htmlspecialchars($plant["plantFruit"]);

                                    $plantCultivation = htmlspecialchars(strip_tags($plant["plantCultivation"]));
                                    $plantPropagation = htmlspecialchars(strip_tags($plant["plantPropagation"]));

                                    $plantUtilization = htmlspecialchars(strip_tags($plant["plantUtilization"]));
                            ?>

                            <div class="row g-3">
                                <!-- Card plant detail form -->
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <h5 class="card-body py-3 mb-0 border-bottom">
                                            <i class="fa-solid fa-seedling me-1"></i>
                                            แก้ไขข้อมูลพืช
                                        </h5>
                                        <form id="formPlantdetail" method="post" action="../data/plant/updatePlant">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill mb-0">
                                                        <i class="fa-solid fa-circle-info text-success me-1"></i>
                                                        ข้อมูลทั่วไป
                                                    </h5>
                                                    <div class="col-12 col-lg-6">
                                                        ชื่อพืช <span class="text-danger">*</span>
                                                        <input type="hidden" name="plantID" value="<?php echo $plantID; ?>">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-message"></i></span>
                                                            <input type="text" name="plantName" class="form-control"  maxlength="100" placeholder="ระบุชื่อพืช" autofocus autocomplete="off" required
                                                            value="<?php echo $plantName; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        ชื่อสามัญ
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-message"></i></span>
                                                            <input type="text" name="commonName" class="form-control" maxlength="100" placeholder="ระบุชื่อสามัญของพืช" autocomplete="off"
                                                            value="<?php echo $commonName; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        ชื่อเรียกอื่น
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-message"></i></span>
                                                            <input type="text" name="otherName" class="form-control" maxlength="200" placeholder="ระบุชื่อเรียกอื่นของพืช" autocomplete="off"
                                                            value="<?php echo $otherName; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        ชื่อวิทยาศาสตร์
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-message"></i></span>
                                                            <input type="text" name="scientificName" class="form-control" maxlength="100" placeholder="ระบุชื่อวิทยาศาสตร์ของพืช" autocomplete="off"
                                                            value="<?php echo $scientificName; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        ชื่อวงศ์
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="fa-regular fa-message"></i></span>
                                                            <input type="text" name="familyName" class="form-control" maxlength="100" placeholder="ระบุชื่อวงศ์ของพืช" autocomplete="off"
                                                            value="<?php echo $familyName; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        หมวดหมู่ของพืช
                                                        <select class="select2 form-select" name="tags[]" multiple>
                                                            <?php
                                                                //Select current plant tags array
                                                                $sql = "SELECT tagID
                                                                FROM tag_lists
                                                                WHERE plantID = ?
                                                                ORDER BY tagID;";

                                                                $stmt = $database->stmt_init(); 
                                                                $stmt->prepare($sql);
                                                                $stmt->bind_param('s', $plantID);
                                                                $stmt->execute();
                                                                $plantTagResult = $stmt-> get_result();
                                                                $stmt->close();

                                                                $plantTagsArray = array();
                                                                if($plantTagResult->num_rows > 0){
                                                                    while($plantTag = $plantTagResult->fetch_assoc()){
                                                                        $plantTagsArray[] = $plantTag['tagID'];
                                                                    }
                                                                }

                                                                //Select tags array
                                                                $sql = "SELECT tagID, tagName
                                                                        FROM tags
                                                                        ORDER BY tagName;";
                                                                    
                                                                $tagResult = $database->query($sql);
                                                                if($tagResult->num_rows > 0){
                                                                    while($tag = $tagResult->fetch_assoc()){
                                                            ?>
                                                                <option value="<?php echo $tag['tagID']; ?>" <?php if(in_array($tag['tagID'], $plantTagsArray)) echo 'selected'?>>
                                                                    <?php echo $tag["tagName"]; ?>
                                                                </option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>


                                                    <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill mt-4 mb-0">
                                                        <i class="fa-solid fa-leaf text-success me-1"></i>
                                                        ลักษณะทางพฤกศาสตร์
                                                    </h5>
                                                    <div class="col-12">
                                                        ลักษณะลำต้น
                                                        <textarea name="plantTrunk" class="form-control" rows="2" placeholder="ระบุลักษณะของลำต้น" maxlength="2024"><?php echo $plantTrunk; ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        ลักษณะใบ
                                                        <textarea name="plantLeaf" class="form-control" rows="2" placeholder="ระบุลักษณะของใบ" maxlength="2024"><?php echo $plantLeaf; ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        ลักษณะดอก
                                                        <textarea name="plantFlower" class="form-control" rows="2" placeholder="ระบุลักษณะของดอก" maxlength="2024"><?php echo $plantFlower; ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        ลักษณะผล/เมล็ด
                                                        <textarea name="plantFruit" class="form-control" rows="2" placeholder="ระบุลักษณะของผล/เมล็ด" maxlength="2024"><?php echo $plantFruit; ?></textarea>
                                                    </div>


                                                    <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill mt-4 mb-0">
                                                        <i class="fa-solid fa-sun-plant-wilt text-success me-1"></i>
                                                        การเพาะปลูกและขยายพันธุ์
                                                    </h5>
                                                    <div class="col-12">
                                                        การเพาะปลูก
                                                        <textarea name="plantCultivation" class="form-control" rows="2" placeholder="ระบุขั้นตอนการเพาะปลูก" maxlength="2024"><?php echo $plantCultivation; ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        การขยายพันธ์ุ
                                                        <textarea name="plantPropagation" class="form-control" rows="2" placeholder="ระบุขั้นตอนการขยายพันธ์ุ" maxlength="2024"><?php echo $plantPropagation; ?></textarea>
                                                    </div>


                                                    <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill mt-4 mb-0">
                                                        <i class="fa-solid fa-plate-wheat text-success me-1"></i>
                                                        การใช้ประโยชน์
                                                    </h5>
                                                    <div class="col-12">
                                                        ประโยชน์/สรรพคุณ
                                                        <textarea name="plantUtilization" class="form-control" rows="2" placeholder="ระบุประโยชน์/สรรพคุณของพืช" maxlength="2024"><?php echo $plantUtilization; ?></textarea>
                                                    </div>


                                                    <h5 class="text-dark fw-bold bg-label-success py-2 rounded-pill mt-4 mb-0">
                                                        <i class="fa-solid fa-images text-success me-1"></i>
                                                        แกเลอรี่ภาพ
                                                    </h5>
                                                    <div class="col-12">
                                                        อัพโหลดภาพพืช
                                                        <div class="input-group">
                                                            <input type="file" id="plantImg" name="plantImg[]" class="form-control" accept=".jpg,.jpeg,.png" multiple/>
                                                            <button id="resetPreviewBtn" class="btn btn-primary">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted mb-4">รองรับไฟล์รูปภาพ JPG, JPEG และ PNG</small>
                                                    
                                                        <div id="imgPreview" class="row row-cols-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2 border border-secondary border-1 p-2 pt-0 rounded shadow-sm mb-3 mt-3 mx-0">
                                                            <div class="col">
                                                                <div class="ratio ratio-1x1">
                                                                    <img class="fit-cover rounded-3 border border-dark" src="../assets/img/plantImgs/default-plant.png"/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row row-cols-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 p-2 pt-0 mb-3 g-2">
                                                            <?php
                                                                //Select plant images to create gallery
                                                                $sql = "SELECT imgID, imgPath
                                                                        FROM plant_images
                                                                        WHERE plantID = ?;";
                                                                $stmt = $database->stmt_init(); 
                                                                $stmt->prepare($sql);
                                                                $stmt->bind_param('s', $plantID);
                                                                $stmt->execute();
                                                                $imgResult = $stmt-> get_result();
                                                                $stmt->close();

                                                                if($imgResult->num_rows > 0){while($img = $imgResult->fetch_assoc()){
                                                            ?>

                                                            <div class="col">
                                                                <div class="card shadow-sm border bg-label-secondary">
                                                                    <div class="card-body p-0 p-md-1">
                                                                        <div class="ratio ratio-1x1">
                                                                            <img class="fit-cover card-img-top border border-dark" src="../assets/img/plantImgs/<?php echo $img["imgPath"]; ?>"/>
                                                                        </div>
                                                                        <a class="btn btn-light deleteBtn w-100 py-0 border mt-0 rounded-0 rounded-bottom"
                                                                        href="../data/plant/deletePlantImg?imgID=<?php echo $img["imgID"]; ?>">
                                                                            <i class="fa-solid fa-trash-can"></i>
                                                                            <span class="d-none d-md-inline ms-1">ลบภาพ</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <?php }} ?>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <button type="submit" class="btn btn-primary me-2">บันทึกข้อมูล</button>
                                                    <a href="#" onclick="goBack()" class="btn btn-label-secondary">ย้อนกลับ</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- /Card plant detail form -->
                            </div>

                            <?php
                                //Case data not found
                                }else{
                                    include("../include/components/card-dataNotFound.php");
                                }
                            ?>
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
            let uploadFile = $('#plantImg');
            let imgPreview = $('#imgPreview');

            $('#formPlantdetail').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let data =  new FormData($('#formPlantdetail')[0]);

                ajaxRequest({
                    type: 'POST',
                    url: form.attr('action'),
                    processData: false,
                    contentType: false,
                    data: data,
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

            function resetImgPreview() {
                imgPreview.empty();
                let imgSrc = '../assets/img/plantImgs/default-plant.png';
                let imgElement = $('<img>').addClass('fit-cover rounded-3 border border-dark').attr('src', imgSrc);
                let ratioElement = $('<div>').addClass('ratio ratio-1x1').append(imgElement);
                let colElement = $('<div>').addClass('col').append(ratioElement);
                            
                imgPreview.append(colElement);
            }

            //Control reset button
            $("#resetPreviewBtn").click(function(e){
                e.preventDefault();
                resetImgPreview();
                uploadFile.val('');
            });

            //Control gallery upload
            uploadFile.on('change', function(e){
                let files = e.target.files;
                if (files.length > 0) {
                    imgPreview.empty();
                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];
                        let reader = new FileReader();
                        
                        reader.onload = function(e) {
                            let imgSrc = e.target.result;
                            let imgElement = $('<img>').addClass('fit-cover rounded-3 border border-dark').attr('src', imgSrc);
                            let ratioElement = $('<div>').addClass('ratio ratio-1x1').append(imgElement);
                            let colElement = $('<div>').addClass('col').append(ratioElement);
                            
                            imgPreview.append(colElement);
                        };
                        reader.readAsDataURL(file);
                    }
                } else {
                    resetImgPreview();
                }
            });

            //Delete img
            $('.deleteBtn').click(function(){
                event.preventDefault();
                let url = $(this).attr('href');
                let parentCol = $(this).closest('.col');
                
                showConfirm({
                    icon: 'question',
                    text: 'ต้องการลบภาพที่เลือกหรือไม่',
                    confirmButtonText: 'ดำเนินการต่อ',
                    confirmCallback: function(){
                        ajaxRequest({
                            type: 'GET',
                            url: url,
                            successCallback: function(response){
                                if(response.status == "success"){
                                    parentCol.remove();
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
        </script>
    </body>
</html>

<?php
    //Close connection
    $database->close();
?>