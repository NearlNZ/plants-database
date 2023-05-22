<?php
    $sql = "SELECT  P.plantID, P.plantName, P.plantView, P.plantRegist,
                    (SELECT count(favID) FROM favorite_plants WHERE plantID = P.plantID) AS favoriteCount
            FROM    plants P 
            LEFT JOIN favorite_plants FP ON P.plantID = FP.plantID
            WHERE P.userID = '$userID'
            GROUP BY P.plantID;";

    $plantResult = $database->query($sql);
?>

<div class="mb-3">
    <span class="h5">
        <i class="fa-solid fa-seedling text-success me-1"></i>
        รายการพืชที่ลงทะเบียน
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
                        <a href="plant-view?plantID=<?php echo $plant['plantID'];?>" class="btn btn-primary btn-icon rounded-pill"
                        data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="left"
                        data-bs-original-title="แสดงข้อมูลพืช">
                            <i class="fa-regular fa-eye"></i>
                        </a>
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