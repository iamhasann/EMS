<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">ผู้ใช้งาน</h3>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-user-cog"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">ผู้ใช้งานทั้งหมด (ผู้ใช้)</p>
                                <h4 class="card-title">30</h4>
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
                                <p class="card-category">ผู้ใช้งานที่ปิดใช้งานแล้ว (ผู้ใช้)</p>
                                <h4 class="card-title">6</h4>
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
                                    <th>ชื่อภาษาไทย</th>
                                    <th>ชื่อ user</th>
                                    <th>กลุ่มผู้ใช้งาน</th>
                                    <th>สถานะ</th>
                                    <th>เครื่องมือ</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ชื่อภาษาไทย</th>
                                    <th>ชื่อ user</th>
                                    <th>กลุ่มผู้ใช้งาน</th>
                                    <th>สถานะ</th>
                                    <th>เครื่องมือ</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>สมชาย ใจดี</td>
                                    <td>SomchaiJD</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>สุภาวรรณ ร่าเริง</td>
                                    <td>SupawanRL</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>ธนากร เก่งกล้า</td>
                                    <td>TanakornKK</td>
                                    <td>องค์กร</td>
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
                                    <td>นารี สวยใส</td>
                                    <td>NareeSS</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>เดชา พัฒนา</td>
                                    <td>DechaP</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>พรทิพย์ งามตา</td>
                                    <td>PorntipNT</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>วิชัย มั่นคง</td>
                                    <td>WichaiMK</td>
                                    <td>องค์กร</td>
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
                                    <td>สุดา น่ารัก</td>
                                    <td>SudaNR</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>อานนท์ เรียนดี</td>
                                    <td>AnonRD</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>กมลวรรณ จริงใจ</td>
                                    <td>KamonwanJJ</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>ชัยยุทธ กล้าหาญ</td>
                                    <td>ChaiyutGH</td>
                                    <td>องค์กร</td>
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
                                    <td>ปรียา สุขสันต์</td>
                                    <td>PreeyaSS</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>เทวินทร์ รุ่งเรือง</td>
                                    <td>TewinRR</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>ลัดดาวัลย์ โชคดี</td>
                                    <td>LaddawanCD</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>สิทธิชัย เข้มแข็ง</td>
                                    <td>SittichaiKM</td>
                                    <td>องค์กร</td>
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
                                    <td>ดวงใจ บริสุทธิ์</td>
                                    <td>DuangjaiBS</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>ภาณุมาศ ตั้งใจ</td>
                                    <td>PanumasTJ</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>รุ่งทิพย์ สดใส</td>
                                    <td>RungtipSD</td>
                                    <td>บุคคลทั่วไป</td>
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
                                    <td>เอกชัย สามัคคี</td>
                                    <td>EkachaiSM</td>
                                    <td>องค์กร</td>
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
                                <tr>
                                    <td>อรัญญา มีสุข</td>
                                    <td>AranyaMS</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>จิรายุส ยิ้มแย้ม</td>
                                    <td>JirayutYY</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>ศิริพร ใจกว้าง</td>
                                    <td>SiripornJK</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>ณัฐพล ไฟแรง</td>
                                    <td>NattaponFL</td>
                                    <td>องค์กร</td>
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
                                <tr>
                                    <td>เบญจมาศ อ่อนหวาน</td>
                                    <td>BenjamasOH</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>ธีรภัทร รอบรู้</td>
                                    <td>TeerapatRR</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>วรรณภา สง่างาม</td>
                                    <td>WannapaSG</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>ภัทรพล มุ่งมั่น</td>
                                    <td>PattaraponMM</td>
                                    <td>องค์กร</td>
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
                                <tr>
                                    <td>สุชาวดี มีเสน่ห์</td>
                                    <td>SuchawadeeMS</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>ยุทธนา เข้มแข็ง</td>
                                    <td>YutthanaKK</td>
                                    <td>บุคคลทั่วไป</td>
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
                                <tr>
                                    <td>พิมลรัตน์ สดชื่น</td>
                                    <td>PimolratSC</td>
                                    <td>บุคคลทั่วไป</td>
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