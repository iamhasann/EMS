<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");
?>
<div class="page-inner">
    <?php if (!isset($_GET['tambah']) && !isset($_POST['edit']) && !isset($_POST['details'])) { ?>
        <div class="page-header">
            <h3 class="fw-bold mb-3">ประเภทผู้ใช้งาน</h3>
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
                                    <i class="fas fa-users-cog"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">ประเภทผู้ใช้งานทั้งหมด (ประเภท)</p>
                                    <h4 class="card-title">4</h4>
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
                                    <i class="fas fa-user-alt-slash"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">ประเภทผู้ใช้งานที่ปิดใช้งานแล้ว (ประเภท)</p>
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
                        <h4 class="card-title">ประเภทผู้ใช้งาน</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                id="basic-datatables"
                                class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>กลุ่มผู้ใช้งาน (TH)</th>
                                        <th>กลุ่มผู้ใช้งาน (EN)</th>
                                        <th>วันที่เพิ่ม</th>
                                        <th>สถานะ</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>กลุ่มผู้ใช้งาน (TH)</th>
                                        <th>กลุ่มผู้ใช้งาน (EN)</th>
                                        <th>วันที่เพิ่ม</th>
                                        <th>สถานะ</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>ผู้บริหาร</td>
                                        <td>Executive</td>
                                        <td>21/5/68</td>
                                        <td><span class="badge badge-success">ใช้งาน</span></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button
                                                    class="btn btn-label-primary me-2"
                                                    type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-eye"></i> ดู</a>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> แก้ไข</a>
                                                        <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> ลบ</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ผู้ดูแลระบบ</td>
                                        <td>Admin</td>
                                        <td>21/5/68</td>
                                        <td><span class="badge badge-success">ใช้งาน</span></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button
                                                    class="btn btn-label-primary me-2"
                                                    type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-eye"></i> ดู</a>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> แก้ไข</a>
                                                        <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> ลบ</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>เจ้าหน้าที่</td>
                                        <td>Officer</td>
                                        <td>21/5/68</td>
                                        <td><span class="badge badge-success">ใช้งาน</span></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button
                                                    class="btn btn-label-primary me-2"
                                                    type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-eye"></i> ดู</a>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> แก้ไข</a>
                                                        <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> ลบ</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ผู้บริหารสูงสุด</td>
                                        <td>Super Executive</td>
                                        <td>21/5/68</td>
                                        <td><span class="badge badge-danger">ปิดใช้งาน</span></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button
                                                    class="btn btn-label-primary me-2"
                                                    type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-eye"></i> ดู</a>
                                                        <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> แก้ไข</a>
                                                        <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> ลบ</a>
                                                    </li>
                                                </ul>
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
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name_th" class="form-label">ชื่อภาษาไทย</label>
                                                    <input type="text" class="form-control" id="name_th" name="name_th" placeholder="โปรดระบุ">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name_en" class="form-label">ชื่อภาษาอังกฤษ</label>
                                                    <input type="text" class="form-control" id="name_en" name="name_en" placeholder="โปรดระบุ">
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