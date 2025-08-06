<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");
?>
<div class="page-inner">
    <?php if (!isset($_GET['tambah']) && !isset($_POST['edit']) && !isset($_POST['details'])) { ?>
        <div class="page-header">
            <h3 class="fw-bold mb-3">พื้นที่ออกเหตุและเวลา</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-filter"></i><b> ตัวกรองพื้นที่ออกเหตุและเวลา</b>
                    </h5>
                    <form>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">ค้นหา</label>
                                    <input type="text" class="form-control" placeholder="พิมพ์ชื่อผู้ป่วย/รหัสงาน/ชื่อผู้บันทึก" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">ผลการปฏิบัติงาน</label>
                                    <div class="dropdown">
                                        <button class="form-control text-start dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            เลือกผลการปฏิบัติงาน
                                        </button>
                                        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="#">พบเหตุ</a></li>
                                            <li><a class="dropdown-item" href="#">ไม่พบเหตุ</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">ตั้งแต่วันที่</label>
                                    <input type="date" class="form-control" placeholder="ตั้งแต่วันที่" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name_en" class="form-label">ถึงวันที่</label>
                                    <input type="date" class="form-control" placeholder="ถึงวันที่" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end align-items-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> ค้นหา
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-ambulance"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">พื้นที่ออกเหตุและเวลาทั้งหมด (ครั้ง)</p>
                                    <h4 class="card-title">1,597</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">งานออกเหตุต่อเขตรับผิดชอบ (ครั้ง)</p>
                                    <h4 class="card-title">998</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div
                                    class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-map"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">งานออกเหตุนอกเขตรับผิดชอบ (ครั้ง)</p>
                                    <h4 class="card-title">599</h4>
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
                        <div class="card-title">สถานะรถรีเฟอร์</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>รหัสรถรีเฟอร์</th>
                                        <th>สถานะ</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>50012</td>
                                        <td><span class="badge badge-warning">พบเหตุ</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <form role="form" method="post" action="../event/">
                                                    <button class="btn btn-sm btn-success me-1" name="details" title="ดูกล้อง">
                                                        <i class="fas fa-video"></i>
                                                    </button>
                                                </form>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">พื้นที่ออกเหตุและเวลา</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                id="basic-datatables"
                                class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ประทับเวลา (วันที่)</th>
                                        <th>ผลการปฏิบัติงาน</th>
                                        <th>ชื่อผู้ป่วย</th>
                                        <th>ผู้สรุปรายงาน</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ประทับเวลา (วันที่)</th>
                                        <th>ผลการปฏิบัติงาน</th>
                                        <th>ชื่อผู้ป่วย</th>
                                        <th>ผู้สรุปรายงาน</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>26 พฤษภาคม 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นายหามะ วาเลาะดี</td>
                                        <td>นายอันวาร์ แวมะเด็ง</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>27 พฤษภาคม 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นางสาวฟาตีเมาะ อาแว</td>
                                        <td>นายอดินันท์ สาแม</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>28 พฤษภาคม 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นายมูฮัมหมัด รอมลี</td>
                                        <td>นางสาวซูไฮลา มะแซ</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>29 พฤษภาคม 2568</td>
                                        <td><span class="badge badge-danger">ไม่พบเหตุ</span></td>
                                        <td>นายอดิศักดิ์ เจ๊ะมะ</td>
                                        <td>นายอามีน ปาเยาะ</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>30 พฤษภาคม 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นางสาวอาอีเซาะ เจ๊ะลี</td>
                                        <td>นายอับดุลรอฮิม วาเลาะ</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>31 พฤษภาคม 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นายมูฮัมหมัดซาการียา เจ๊ะดือราแม</td>
                                        <td>นายซาการียา สาและ</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>1 มิถุนายน 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นางสาวมายา อิบรอฮิม</td>
                                        <td>นายอับดุลเลาะ กาซอ</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>2 มิถุนายน 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นายซากี เจ๊ะมะ</td>
                                        <td>นายมูฮัมหมัดฟาอิส บือราเฮง</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>3 มิถุนายน 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นางสาวนูรียะห์ อาแว</td>
                                        <td>นายมะซากี เจะมะ</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>4 มิถุนายน 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นายมะซากี มะแซ</td>
                                        <td>นางสาวซูมัยยะห์ สะแลแม</td>
                                        <td>
                                            <div class="btn-group">
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
                                        <td>5 มิถุนายน 2568</td>
                                        <td><span class="badge badge-success">พบเหตุ</span></td>
                                        <td>นายรอซาลี วาเฮ็ง</td>
                                        <td>นายมูซา บือราเฮง</td>
                                        <td>
                                            <div class="btn-group">
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
                </div>
            </div>
        </div>
    <?php }
    if (isset($_POST['details'])) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">กล้องหน้ารถ</h4>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <video id="video" autoplay muted playsinline controls style="width: 100%;">
                                    <source src="/kaiadmin-lite-1.2.0/executive/event/stream.m3u8" type="application/x-mpegURL" />
                                </video>

                                <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
                                <script>
                                    const video = document.getElementById('video');
                                    const source = video.querySelector("source").src;

                                    if (Hls.isSupported()) {
                                        const hls = new Hls();
                                        hls.loadSource(source);
                                        hls.attachMedia(video);
                                        hls.on(Hls.Events.MANIFEST_PARSED, function() {
                                            video.play();
                                        });
                                    } else if (video.canPlayType("application/vnd.apple.mpegurl")) {
                                        video.src = source;
                                        video.addEventListener('loadedmetadata', function() {
                                            video.play();
                                        });
                                    }
                                </script>


                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <a href="../event/" class="btn btn-danger">ย้อนกลับ</a>
                    </div>
                </div>
            </div>
        </div>
</div>
<?php } ?>
</div>
<?php
include_once("../footer.php");
?>