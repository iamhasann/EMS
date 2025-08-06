<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");
?>
<div class="page-inner">
    <?php if (!isset($_GET['tambah']) && !isset($_POST['edit']) && !isset($_POST['details'])) { ?>
        <div class="page-header">
            <h3 class="fw-bold mb-3">รถรีเฟอร์</h3>
            <div class="ms-md-auto py-2 py-md-0">
                <div class="btn-group">
                    <a href="?tambah" class="btn btn-primary btn-round">เพิ่มข้อมูล</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-ambulance"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">รถรีเฟอร์ทั้งหมด (คัน)</p>
                                    <h4 class="card-title">2</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">รถรีเฟอร์ที่ปิดใช้งานแล้ว (คัน)</p>
                                    <h4 class="card-title">1</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ผู้ใช้งาน</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                id="basic-datatables"
                                class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>รหัสรถรีเฟอร์</th>
                                        <th>สถานะ</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>รหัสรถรีเฟอร์</th>
                                        <th>สถานะ</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>50012</td>
                                        <td><span class="badge badge-warning">พบเหตุ</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-success me-1" title="ดูกล้อง">
                                                    <i class="fas fa-video"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>50012</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-success me-1" title="ดูกล้อง">
                                                    <i class="fas fa-video"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }
            if (isset($_GET['tambah']) && !isset($_POST['edit'])) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <?php
                                if (isset($_GET['tambah'])) {
                                    if (isset($_SESSION['error'])) {
                                        echo '
                                        <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">' . $_SESSION['error'] . '</div>';
                                        unset($_SESSION['error']);
                                    }
                                }
                                ?>
                                <form role="form" method="post" action="./index.php" enctype="multipart/form-data">
                                    <div class="card-header d-flex">
                                        <div class="card-title">แบบฟอร์มบันทึก</div>
                                        <div class="form-check form-switch d-inline-block ms-auto">
                                            <label class="form-check-label me-2" for="view_main">สถานะ</label>
                                            <input class="form-check-input" type="checkbox" name="view_main" id="view_main" checked>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?php
                                                date_default_timezone_set('Asia/Bangkok');
                                                setlocale(LC_TIME, 'th_TH.UTF-8'); // Set locale to Thai

                                                $currentDate = new DateTime();
                                                $thaiMonths = [
                                                    'January' => 'มกราคม',
                                                    'February' => 'กุมภาพันธ์',
                                                    'March' => 'มีนาคม',
                                                    'April' => 'เมษายน',
                                                    'May' => 'พฤษภาคม',
                                                    'June' => 'มิถุนายน',
                                                    'July' => 'กรกฎาคม',
                                                    'August' => 'สิงหาคม',
                                                    'September' => 'กันยายน',
                                                    'October' => 'ตุลาคม',
                                                    'November' => 'พฤศจิกายน',
                                                    'December' => 'ธันวาคม'
                                                ];
                                                $monthName = $thaiMonths[$currentDate->format('F')];
                                                $thaiYear = $currentDate->format('Y') + 543;

                                                $thaiDateString = $currentDate->format('d') . ' ' . $monthName . ' ' . $thaiYear;
                                                $gregorianDate = $currentDate->format('Y-m-d');
                                                ?>
                                                <div class="form-group">
                                                    <label for="largeInput">วันที่บันทึก</label>
                                                    <input type="text" class="form-control" id="thai_date_display" value="<?= $thaiDateString ?>" readonly>
                                                    <input type="hidden" id="prg_date" name="prg_date" value="<?= $gregorianDate ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name_th" class="form-label">รหัสรถรีเฟอร์</label>
                                                    <input type="number" class="form-control" id="name_th" name="name_th" placeholder="โปรดระบุ">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name_en" class="form-label">ผู้ดูแลรถ</label>
                                                    <div class="dropdown">
                                                        <button class="form-control text-start dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                            เลือกผู้ดูแลรถ
                                                        </button>
                                                        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#">มูฮัมหมัด บากอ</a></li>
                                                            <li><a class="dropdown-item" href="#">อันวา สีดิ</a></li>
                                                            <li><a class="dropdown-item" href="#">อับดุลเลาะ มูฮัมหมัด</a></li>
                                                            <li><a class="dropdown-item" href="#">อิสมาแอ เจะมะ</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-action">
                                        <button class="btn btn-success">บันทึก</button>
                                        <button class="btn btn-danger">ย้อนกลับ</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
</div>
<?php
include_once("../footer.php");
?>