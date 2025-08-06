<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">ประเภทผู้ใช้งาน</h3>
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
                                                    <a class="dropdown-item" href="#"><i class="fas fa-eye"></i>     ดู</a>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-edit"></i>     แก้ไข</a>
                                                    <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i>     ลบ</a>
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
                                                    <a class="dropdown-item" href="#"><i class="fas fa-eye"></i>     ดู</a>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-edit"></i>     แก้ไข</a>
                                                    <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i>     ลบ</a>
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
                                                    <a class="dropdown-item" href="#"><i class="fas fa-eye"></i>     ดู</a>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-edit"></i>     แก้ไข</a>
                                                    <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i>     ลบ</a>
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
                                                    <a class="dropdown-item" href="#"><i class="fas fa-eye"></i>     ดู</a>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-edit"></i>     แก้ไข</a>
                                                    <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i>     ลบ</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include_once("../footer.php");
    ?>