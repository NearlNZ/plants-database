<?php
    $sql = "SELECT  P.plantID, P.plantName, P.plantView, P.plantRegist,
                    (SELECT count(favID) FROM favorite_plants WHERE plantID = P.plantID) AS favoriteCount
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
                if ($plantResult->num_rows > 0) {$plantIndex = 1; while ($plant = $plantResult->fetch_assoc()) {
            ?>

                <tr>
                    <td>
                        <span class="text-truncate">
                            <?php echo $plantIndex; ?>.
                        </span>
                    </td>
                    <td>
                        <span class="text-truncate">
                            <?php echo $plant["plantName"]; ?>
                        </span>
                    </td>
                    <td>
                        <span class="text-truncate">
                            <?php echo date("j/n/Y", strtotime($plant["plantRegist"])); ?>
                        </span>
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
                    <td class="text-center" width="40px">
                        <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="plant-view?plantID=<?php echo $plant['plantID'];?>">
                                <i class="bx bx-show-alt me-1"></i>
                                แสดงข้อมูลพืช
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

            <?php $plantIndex++;}} else { ?>

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