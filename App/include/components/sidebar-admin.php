<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- Brand -->
    <div class="app-brand demo">
        <a class="app-brand-link mt-3">
            <i class="fa-solid fa-seedling text-success h3"></i>
            <span class="h4 fw-bolder ms-2">CS-Plants</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <!-- /Brand -->

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item">
            <a href="dashboard" class="menu-link">
                <i class="menu-icon fa-solid fa-chart-column"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- Plant -->
        <li class="menu-item">
            <a href="plant-manage" class="menu-link">
                <i class="menu-icon fa-solid fa-seedling"></i>
                <div>รายการพืช</div>
            </a>
        </li>

        <!-- Tag -->
        <li class="menu-item">
            <a href="tag-manage" class="menu-link">
                <i class="menu-icon fa-solid fa-tags"></i>
                <div>หมวดหมู่พืช</div>
            </a>
        </li>

        <!-- User -->
        <li class="menu-item">
            <a href="user-manage" class="menu-link">
                <i class="menu-icon fa-solid fa-user"></i>
                <div>บัญชีผู้ใช้</div>
            </a>
        </li>

        <!-- Report -->
        <li class="menu-item havesub">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fa-solid fa-file-contract"></i>
                <div>พิมพ์รายงาน</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>รายการพืช</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>รายการบัญชีผู้ใช้</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Logout -->
        <li class="menu-item">
            <a  href="#" class="menu-link" onclick="logout()">
                <i class="menu-icon fa-solid fa-power-off"></i>
                <div>ออกจากระบบ</div>
            </a>
        </li>
    </ul>
</aside>