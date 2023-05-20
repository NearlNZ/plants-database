<?php
    $sql = "SELECT logTimestamp, logDevice
            FROM login_records
            WHERE userID = '$userID'
            ORDER BY logTimestamp DESC;";

    $loginResult = $database->query($sql);
?>

<div class="mb-3">
    <span class="h5">
        <i class="fa-solid fa-clock-rotate-left text-muted me-1"></i>
        บันทึกการเข้าสู่ระบบ
    </span>
</div>
<div id="loginTable" class="table-responsive table-scroll-y mb-0 border-top border-bottom">
    <table class="table table-nowrap mb-0">
        <tbody class="border-top">
            <?php
                if ($loginResult->num_rows > 0) {
                    while ($record = $loginResult->fetch_assoc()) {
                        $device = $record["logDevice"];
                        $colorClass = "secondary";
                        $iconClass = "fa-solid fa-question";

                        if($device == "คอมพิวเตอร์"){
                            $colorClass = "warning";
                            $iconClass = "fa-solid fa-laptop";
                        }
                        else if($device == "สมาร์ทโฟน"){
                            $colorClass = "info";
                            $iconClass = "fa-solid fa-mobile-screen-button";
                        }
                        else if($device == "แท็บเล็ต"){
                            $colorClass = "success";
                            $iconClass = "fa-solid fa-tablet-screen-button";
                        }
            ?>

                <tr>
                    <td width="40px">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="icon-circle bg-label-<?php echo $colorClass; ?>">
                                <i class="<?php echo $iconClass; ?> fa-xl"></i>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-truncate">
                            <?php echo $device; ?>
                        </span>
                    </td>    
                    <td>
                        <span class="text-truncate">
                            <i class="fa-regular fa-calendar me-1"></i>
                            <?php echo date("j/n/Y", strtotime($record["logTimestamp"])); ?>
                        </span>
                    </td>
                    <td class="">
                        <span class="text-truncate">
                            <i class="fa-regular fa-clock me-1"></i>
                            <?php echo date("H:i:s", strtotime($record["logTimestamp"])); ?>
                        </span>
                    </td>    
                </tr>

            <?php }} else { ?>

                <tr>
                    <td class="text-center text-muted py-3" colspan="4">
                        --- ไม่พบข้อมูลสำหรับแสดงผล ---
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</div>