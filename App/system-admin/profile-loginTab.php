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
                    $iconClass = array(
                        "คอมพิวเตอร์" => ["color" => "success", "icon" => "fa-solid fa-laptop"],
                        "สมาร์ทโฟน" => ["color" => "warning", "icon" => "fa-solid fa-mobile-screen-button"],
                        "แท็บเล็ต" => ["color" => "info", "icon" => "fa-solid fa-tablet-screen-button"],
                        "อุปกรณ์ที่ไม่รู้จัก" => ["color" => "secondary", "icon" => "fa-solid fa-question"]
                    );
                    while ($record = $loginResult->fetch_assoc()) {
                        $device = $record["logDevice"];    
            ?>

                <tr>
                    <td class="text-center" width="40px">
                        <i class="<?php echo $iconClass[$device]["icon"].' text-'.$iconClass[$device]["color"]; ?> fa-lg"></i>
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