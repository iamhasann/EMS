<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");
?>
<div class="page-inner">

    <div class="page-header">
        <h3 class="fw-bold mb-3">โปรไฟล์</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">ข้อมูลส่วนตัว</h4>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="profile-picture">
                            <div class="avatar avatar-xxl mb-3">
                                <img src="../../assets/img/profile3.png" alt="..." class="avatar-img rounded-circle" />
                            </div>
                            <div class="btn-group mx-2">
                                <a href="#" class="btn btn-primary">อัพโหลดรูปภาพ</a>
                            </div>
                            <div class="btn-group">
                                <a href="#" class="btn btn-danger btn-border">ล้างรูปภาพ</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">คำนำหน้า</label>
                                    <input type="text" class="form-control" id="name_en" name="name_en" placeholder="นาย" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">ชื่อไทย</label>
                                    <input type="text" class="form-control" id="name_en" name="name_en" placeholder="มูฮัมหมัด" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_th" class="form-label">นามสกุลไทย</label>
                                    <input type="text" class="form-control" id="name_th" name="name_th" placeholder="บากอ" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="name_en" name="name_en" placeholder="มูฮัมหมัด@gmail.com" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">ชื่อผู้ใช้งาน / username</label>
                                    <input type="text" class="form-control" id="name_en" name="name_en" placeholder="hammad" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">รหัสผ่าน</label>
                                    <input type="password" class="form-control" id="name_en" name="name_en" placeholder="โปรดระบุ" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">กลุ่มผู้ใช้งาน</label>
                                    <input type="password" class="form-control" id="name_en" name="name_en" placeholder="โปรดระบุ" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="number" class="form-control" id="name_en" name="name_en" placeholder="โปรดระบุ" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
include_once("../footer.php");
?>