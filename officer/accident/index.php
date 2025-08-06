<?php
ob_start(); // เปิดใช้งาน output buffering
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
include_once("../sidebar.php");

// กำหนดตัวแปรสำหรับการกรอง
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// เชื่อมต่อฐานข้อมูล
require_once "../../config/database.php";

// ฟังก์ชันดึงข้อมูล
function getEmergencyCases($pdo, $start_date = '', $end_date = '') {
    $sql = "SELECT * FROM emergency_cases";
    $params = [];
    
    if (!empty($start_date) && !empty($end_date)) {
        $sql .= " WHERE record_date BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $start_date;
        $params[':end_date'] = $end_date;
    } elseif (!empty($start_date)) {
        $sql .= " WHERE record_date >= :start_date";
        $params[':start_date'] = $start_date;
    } elseif (!empty($end_date)) {
        $sql .= " WHERE record_date <= :end_date";
        $params[':end_date'] = $end_date;
    }
    
    $sql .= " ORDER BY record_date DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ฟังก์ชันนับสถิติ
function getEmergencyStats($pdo, $start_date = '', $end_date = '') {
    $stats = [
        'total_cases' => 0,
        'most_common_type' => ['type' => '', 'count' => 0]
    ];
    
    try {
        // นับจำนวนเคสทั้งหมด
        $sql_total = "SELECT COUNT(*) FROM emergency_cases";
        if (!empty($start_date) || !empty($end_date)) {
            $sql_total .= " WHERE ";
            $conditions = [];
            if (!empty($start_date)) $conditions[] = "record_date >= :start_date";
            if (!empty($end_date)) $conditions[] = "record_date <= :end_date";
            $sql_total .= implode(" AND ", $conditions);
        }
        
        $stmt = $pdo->prepare($sql_total);
        if (!empty($start_date)) $stmt->bindParam(':start_date', $start_date);
        if (!empty($end_date)) $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        $stats['total_cases'] = $stmt->fetchColumn();
        
        // หาประเภทที่พบมากที่สุด
        $sql_type = "SELECT 
                        CASE 
                            WHEN injections = (SELECT MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) THEN 'ฉีดยา'
                            WHEN suction = (SELECT MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) THEN 'ดูดเสมหะ'
                            WHEN suture_removal = (SELECT MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) THEN 'ตัดไหม'
                            WHEN wound_care = (SELECT MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) THEN 'ทำแผล'
                            WHEN nebulizer = (SELECT MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) THEN 'พ่นยา'
                            WHEN suturing = (SELECT MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) THEN 'เย็บแผล'
                            WHEN iv_fluids = (SELECT MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) THEN 'ให้น้ำเกลือ'
                        END AS most_common_type,
                        MAX(GREATEST(injections, suction, suture_removal, wound_care, nebulizer, suturing, iv_fluids)) AS max_count
                    FROM emergency_cases";
        
        $stmt = $pdo->prepare($sql_type);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['most_common_type'] = $result;
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
    
    return $stats;
}

// ดึงข้อมูลตามเงื่อนไขการกรอง
$cases = getEmergencyCases($pdo, $start_date, $end_date);
$stats = getEmergencyStats($pdo, $start_date, $end_date);

// การจัดการฟอร์มเพิ่ม/แก้ไข/ลบ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add_case'])) {
            // เพิ่มข้อมูลใหม่
            $sql = "INSERT INTO emergency_cases (
                record_date, injections, suction, suture_removal, 
                wound_care, nebulizer, suturing, iv_fluids
            ) VALUES (
                :record_date, :injections, :suction, :suture_removal, 
                :wound_care, :nebulizer, :suturing, :iv_fluids
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':record_date' => $_POST['prg_date'],
                ':injections' => $_POST['injections'] ?? 0,
                ':suction' => $_POST['suction'] ?? 0,
                ':suture_removal' => $_POST['suture_removal'] ?? 0,
                ':wound_care' => $_POST['wound_care'] ?? 0,
                ':nebulizer' => $_POST['nebulizer'] ?? 0,
                ':suturing' => $_POST['suturing'] ?? 0,
                ':iv_fluids' => $_POST['iv_fluids'] ?? 0
            ]);
            
            $_SESSION['success'] = "เพิ่มข้อมูลเรียบร้อยแล้ว";
            ob_end_clean(); // ล้าง buffer ก่อน redirect
            header("Location: ../accident/");
            exit;
            
        } elseif (isset($_POST['edit_case'])) {
            // แก้ไขข้อมูล
            $sql = "UPDATE emergency_cases SET 
                injections = :injections, 
                suction = :suction, 
                suture_removal = :suture_removal, 
                wound_care = :wound_care, 
                nebulizer = :nebulizer, 
                suturing = :suturing, 
                iv_fluids = :iv_fluids
                WHERE id = :id";
                
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':injections' => $_POST['injections'] ?? 0,
                ':suction' => $_POST['suction'] ?? 0,
                ':suture_removal' => $_POST['suture_removal'] ?? 0,
                ':wound_care' => $_POST['wound_care'] ?? 0,
                ':nebulizer' => $_POST['nebulizer'] ?? 0,
                ':suturing' => $_POST['suturing'] ?? 0,
                ':iv_fluids' => $_POST['iv_fluids'] ?? 0,
                ':id' => $_POST['case_id']
            ]);
            
            $_SESSION['success'] = "แก้ไขข้อมูลเรียบร้อยแล้ว";
            header("Location: index.php");
            exit;
            
        } elseif (isset($_POST['delete_case'])) {
            // ลบข้อมูล
            $stmt = $pdo->prepare("DELETE FROM emergency_cases WHERE id = :id");
            $stmt->execute([':id' => $_POST['case_id']]);
            
            $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
            header("Location: index.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        error_log("Database error: " . $e->getMessage());
    }
}

// ดึงข้อมูลสำหรับแก้ไข
$edit_case = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM emergency_cases WHERE id = :id");
    $stmt->execute([':id' => $_GET['edit']]);
    $edit_case = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="page-inner">
    <?php if (!isset($_GET['tambah']) && !isset($_GET['edit'])) { ?>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h3 class="fw-bold mb-3">งานอุบัติเหตุฉุกเฉิน</h3>
            <div class="ms-md-auto py-2 py-md-0">
                <div class="btn-group">
                    <a href="?tambah" class="btn btn-primary btn-round">เพิ่มข้อมูล</a>
                </div>
            </div>
        </div>
        
        <!-- ตัวกรองข้อมูล -->
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-filter"></i><b> ตัวกรองงานอุบัติเหตุฉุกเฉิน</b>
                    </h5>
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">ตั้งแต่วันที่</label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        name="start_date" 
                                        value="<?= htmlspecialchars($start_date) ?>"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">ถึงวันที่</label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        name="end_date" 
                                        value="<?= htmlspecialchars($end_date) ?>"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end align-items-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> ค้นหา
                                    </button>
                                    <?php if (!empty($start_date) || !empty($end_date)): ?>
                                        <a href="../accident/" class="btn btn-secondary ms-2">
                                            <i class="fas fa-times"></i> ล้างการค้นหา
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- สถิติ -->
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-briefcase-medical"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">งานอุบัติเหตุฉุกเฉินทั้งหมด (ครั้ง)</p>
                                    <h4 class="card-title"><?= htmlspecialchars($stats['total_cases']) ?></h4>
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
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">ประเภทงานอุบัติเหตุฉุกเฉินมากที่สุด (ประเภท)</p>
                                    <h4 class="card-title">
                                        <?= htmlspecialchars($stats['most_common_type']['max_count'] ?? 0) ?> 
                                        (<?= htmlspecialchars($stats['most_common_type']['most_common_type'] ?? 'ไม่มีข้อมูล') ?>)
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ตารางข้อมูล -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">งานอุบัติเหตุฉุกเฉิน</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php if (!empty($cases)): ?>
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ประทับเวลา (วันที่)</th>
                                            <th>ฉีดยา</th>
                                            <th>ดูดเสมหะ</th>
                                            <th>ตัดไหม</th>
                                            <th>ทำแผล</th>
                                            <th>พ่นยา</th>
                                            <th>เย็บแผล</th>
                                            <th>ให้น้ำเกลือ</th>
                                            <th>เครื่องมือ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cases as $case): ?>
                                            <tr>
                                                <td><?= date('d M Y', strtotime($case['record_date'])) ?></td>
                                                <td><?= htmlspecialchars($case['injections']) ?></td>
                                                <td><?= htmlspecialchars($case['suction']) ?></td>
                                                <td><?= htmlspecialchars($case['suture_removal']) ?></td>
                                                <td><?= htmlspecialchars($case['wound_care']) ?></td>
                                                <td><?= htmlspecialchars($case['nebulizer']) ?></td>
                                                <td><?= htmlspecialchars($case['suturing']) ?></td>
                                                <td><?= htmlspecialchars($case['iv_fluids']) ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="?edit=<?= $case['id'] ?>" class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" style="display:inline" onsubmit="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้?')">
                                                            <input type="hidden" name="case_id" value="<?= $case['id'] ?>">
                                                            <button type="submit" name="delete_case" class="btn btn-sm btn-danger" title="ลบ">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="lead"><em>ไม่พบข้อมูลรายงานการเงิน</em></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php } else { ?>
        <!-- ฟอร์มเพิ่ม/แก้ไขข้อมูล -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form role="form" method="post" action="./index.php">
                        <div class="card-header">
                            <div class="card-title">
                                <?= isset($_GET['edit']) ? 'แก้ไขข้อมูล' : 'แบบฟอร์มบันทึก' ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    date_default_timezone_set('Asia/Bangkok');
                                    setlocale(LC_TIME, 'th_TH.UTF-8');
                                    
                                    $currentDate = new DateTime();
                                    $thaiMonths = [
                                        'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                                        'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                                        'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                                        'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
                                    ];
                                    
                                    $monthName = $thaiMonths[$currentDate->format('F')];
                                    $thaiYear = $currentDate->format('Y') + 543;
                                    $thaiDateString = $currentDate->format('d') . ' ' . $monthName . ' ' . $thaiYear;
                                    $gregorianDate = isset($edit_case) ? $edit_case['record_date'] : $currentDate->format('Y-m-d');
                                    ?>
                                    <div class="form-group">
                                        <label>วันที่บันทึก</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            value="<?= htmlspecialchars($thaiDateString) ?>" 
                                            readonly
                                        >
                                        <input 
                                            type="hidden" 
                                            name="prg_date" 
                                            value="<?= htmlspecialchars($gregorianDate) ?>" 
                                            required
                                        >
                                        <?php if (isset($_GET['edit'])): ?>
                                            <input type="hidden" name="case_id" value="<?= htmlspecialchars($_GET['edit']) ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ฟิลด์ข้อมูล -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">ฉีดยา</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="injections"
                                            placeholder="กรอกตัวเลข"
                                            value="<?= isset($edit_case) ? htmlspecialchars($edit_case['injections']) : 0 ?>"
                                            min="0"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">ทำแผล</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="wound_care"
                                            placeholder="กรอกตัวเลข"
                                            value="<?= isset($edit_case) ? htmlspecialchars($edit_case['wound_care']) : 0 ?>"
                                            min="0"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">เย็บแผล</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="suturing"
                                            placeholder="กรอกตัวเลข"
                                            value="<?= isset($edit_case) ? htmlspecialchars($edit_case['suturing']) : 0 ?>"
                                            min="0"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">ตัดไหม</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="suture_removal"
                                            placeholder="กรอกตัวเลข"
                                            value="<?= isset($edit_case) ? htmlspecialchars($edit_case['suture_removal']) : 0 ?>"
                                            min="0"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">พ่นยา</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="nebulizer"
                                            placeholder="กรอกตัวเลข"
                                            value="<?= isset($edit_case) ? htmlspecialchars($edit_case['nebulizer']) : 0 ?>"
                                            min="0"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">ดูดเสมหะ</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="suction"
                                            placeholder="กรอกตัวเลข"
                                            value="<?= isset($edit_case) ? htmlspecialchars($edit_case['suction']) : 0 ?>"
                                            min="0"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">ให้น้ำเกลือ</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="iv_fluids"
                                            placeholder="กรอกตัวเลข"
                                            value="<?= isset($edit_case) ? htmlspecialchars($edit_case['iv_fluids']) : 0 ?>"
                                            min="0"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" name="<?= isset($_GET['edit']) ? 'edit_case' : 'add_case' ?>" class="btn btn-success">
                                บันทึก
                            </button>
                            <a href="index.php" class="btn btn-danger">ย้อนกลับ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert && alert.parentNode) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            }, 5000);
        });
    });
</script>
<?php
include_once("../footer.php");
?>