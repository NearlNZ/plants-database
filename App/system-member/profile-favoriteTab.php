<?php
    $sql = "SELECT  P.plantID, P.plantName, P.otherName, P.plantView,
                    (SELECT count(favID) FROM favorite_plants WHERE plantID = P.plantID) AS favoriteCount,
                    (SELECT imgPath FROM plant_images WHERE plantID = P.plantID ORDER BY imgUpload LIMIT 1) AS coverImage
            FROM    plants P 
            LEFT JOIN favorite_plants FP ON P.plantID = FP.plantID
            WHERE FP.userID = '$userID';";

    $plantResult = $database->query($sql);
?>

<div class="mb-3">
    <span class="h5">
        <i class="fa-solid fa-heart text-danger me-1"></i>
        รายการโปรด
    </span>
</div>
<div id="loginTable" class="table-responsive table-scroll-y mb-0 border-top border-bottom">
    <table class="table table-nowrap mb-0">
        <tbody class="border-top">
            <?php
                if ($plantResult->num_rows > 0) {while ($plant = $plantResult->fetch_assoc()) {
                    $plantCoverImage = !empty($plant["coverImage"]) ? $plant["coverImage"] : "default-plant.png";
            ?>

                <tr>
                    <td>
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="avatar-wrapper">
                                <div class="avatar me-2">
                                    <img src="../assets/img/plantImgs/<?php echo $plantCoverImage;?>" alt="<?php echo $plantName;?>" class="rounded fit-cover">
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-truncate" style="max-width: 300px;">
                                    <?php echo $plant["plantName"]; ?>
                                </span>
                                <small class="text-truncate text-muted" style="max-width: 300px;">
                                    <?php echo !empty($plant["otherName"]) ? $plant["otherName"] : ""; ?>
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="d-inline-block w-50">
                            <i class="fa-solid fa-eye text-secondary me-1"></i>
                            <?php echo number_format($plant["plantView"]); ?>
                        </span>
                        <span class="d-inline-block ms-2">
                            <i class="fa-solid fa-heart text-danger me-1"></i>
                            <?php echo number_format($plant["favoriteCount"]); ?>
                        </span>
                    </td>
                    <td class="text-center cell-fit">
                        <button type="button" class="btn btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="plant-view?plantID=<?php echo $plant['plantID'];?>">
                                <i class="bx bx-show-alt me-1"></i>
                                แสดงข้อมูล
                            </a>

                            <?php if($userID == $currentUser->userID){ ?>
                            <a class="dropdown-item deleteBtn" href="../data/plant/updateFavoritePlant?plantID=<?php echo $plant['plantID'];?>">
                                <i class="bx bx-x me-1"></i>
                                ยกเลิกรายการโปรด
                            </a>
                            <?php } ?>

                        </div>
                    </td>    
                </tr>

            <?php }} else { ?>

                <tr>
                    <td class="text-center text-muted py-3" colspan="5">
                        --- ไม่พบข้อมูลสำหรับแสดงผล ---
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</div>
<script>
    //Delete record
    $('.deleteBtn').click(function(){
        event.preventDefault();
        let url = $(this).attr('href');
                
        showConfirm({
            icon: 'question',
            text: 'ต้องการนำรายการที่เลือกออกหรือไม่',
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