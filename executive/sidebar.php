<?php
require_once "../../config/database.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../auth/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ศูนย์การแพทย์ฉุกเฉิน</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <link
        rel="icon"
        href="../../assets/img/kaiadmin/LOGO.png"
        type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../../assets/css/demo.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="../home/" class="logo" aria-expanded="false">
                        <img
                            src="../../assets/img/kaiadmin/WLOGO.png"
                            alt="navbar brand"
                            class="navbar-brand"
                            height="70" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item <?= ($currentDir == 'home') ? 'active' : '' ?>">
                            <a
                                href="../home/"
                                class="collapsed"
                                aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>หน้าแรก</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">รายงาน</h4>
                        </li>
                        <li class="nav-item <?= ($currentDir == 'accident') ? 'active' : '' ?>">
                            <a href="../accident/" class="collapsed" aria-expanded="false">
                                <i class="fas fa-briefcase-medical"></i>
                                <p>งานอุบัติเหตุฉุกเฉิน</p>
                            </a>
                        </li>
                        <li class="nav-item <?= ($currentDir == 'event') ? 'active' : '' ?>">
                            <a href="../event/" class="collapsed" aria-expanded="false">
                                <i class="fas fa-ambulance"></i>
                                <p>พื้นที่ออกเหตุและเวลา</p>
                            </a>
                        </li>
                        <li class="nav-item <?= ($currentDir == 'finance') ? 'active' : '' ?>">
                            <a href="../finance/" class="collapsed" aria-expanded="false">
                                <i class="fas fa-dollar-sign"></i>
                                <p>สรุปรายงานการเงิน</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">ตั้งค่าระบบ</h4>
                        </li>
                        <li class="nav-item <?= ($currentDir == 'user') ? 'active' : '' ?>">
                            <a href="../user/" class="collapsed" aria-expanded="false">
                                <i class="fas fa-user-cog"></i>
                                <p>ผู้ใช้งาน</p>
                            </a>
                        </li>
                        <li class="nav-item <?= ($currentDir == 'usertype') ? 'active' : '' ?>">
                            <a href="../usertype/" class="collapsed" aria-expanded="false">
                                <i class="fas fa-users-cog"></i>
                                <p>ประเภทผู้ใช้งาน</p>
                            </a>
                        </li>
                        <li class="nav-item <?= ($currentDir == 'referee') ? 'active' : '' ?>">
                            <a href="../referee/" class="collapsed" aria-expanded="false">
                                <i class="fas fa-ambulance"></i>
                                <p>รถรีเฟอร์</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img
                                src="../../assets/img/kaiadmin/WLOGO.png"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="70" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav
                    class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li
                                class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                                <a
                                    class="nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    role="button"
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                    <i class="fa fa-search"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                placeholder="ค้นหา ..."
                                                class="form-control" />
                                        </div>
                                    </form>
                                </ul>
                            </li>
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a
                                    class="dropdown-toggle profile-pic"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img
                                            src="../../assets/img/profile3.png"
                                            alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">ผู้บริหาร,</span>
                                        <span class="fw-bold"><?php echo htmlspecialchars($_SESSION["first_name"]); ?></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img
                                                        src="../../assets/img/profile3.png"
                                                        alt="image profile"
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4><?php echo htmlspecialchars($_SESSION["first_name"]); ?></h4>
                                                    <p class="text-muted"><?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                                                    <a
                                                        href="../profile/"
                                                        class="btn btn-xs btn-secondary btn-sm">โปรไฟล์</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="../../auth/logout.php" aria-expanded="false">ออกจากระบบ</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div class="container">