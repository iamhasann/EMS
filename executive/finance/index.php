<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");

// ดึงค่าการกรองจาก URL หากมี
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// สร้างเงื่อนไข WHERE สำหรับการค้นหา
$whereConditions = [];
$params = [];

if (!empty($start_date)) {
    $whereConditions[] = "record_date >= :start_date";
    $params[':start_date'] = $start_date;
}

if (!empty($end_date)) {
    $whereConditions[] = "record_date <= :end_date";
    $params[':end_date'] = $end_date;
}

// คำสั่ง SQL พื้นฐาน
$sql = "SELECT * FROM financial_records";
$sql_total = "SELECT SUM(amount) as total FROM financial_records";

// เพิ่มเงื่อนไข WHERE หากมี
if (!empty($whereConditions)) {
    $whereClause = " WHERE " . implode(" AND ", $whereConditions);
    $sql .= $whereClause;
    $sql_total .= $whereClause;
}

// เรียงลำดับตามวันที่ล่าสุด
$sql .= " ORDER BY record_date DESC";

// ดึงข้อมูล
try {
    // ดึงยอดรวมทั้งหมด
    $stmt_total = $pdo->prepare($sql_total);
    $stmt_total->execute($params);
    $total_result = $stmt_total->fetch(PDO::FETCH_ASSOC);
    $total_amount = $total_result['total'] ?? 0;

    // ดึงรายการทั้งหมด
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $_SESSION['error'] = "เกิดข้อผิดพลาดในการดึงข้อมูล";
    $total_amount = 0;
    $records = [];
}
?>

<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">สรุปรายงานการเงิน</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <h5 class="mb-3 text-primary">
                    <i class="fas fa-filter"></i><b> ตัวกรองสรุปรายงานการเงิน</b>
                </h5>
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label">ตั้งแต่วันที่</label>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="start_date"
                                    name="start_date" 
                                    value="<?= htmlspecialchars($start_date) ?>"
                                />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label">ถึงวันที่</label>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="end_date"
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
                                    <a href="?" class="btn btn-secondary ms-2">
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
    <div class="row">
        <div class="col-sm-6 col-md-12">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">สรุปรายงานการเงินทั้งหมด (บาท)</p>
                                <h4 class="card-title"><?= number_format($total_amount, 2) ?></h4>
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
                    <h4 class="card-title">สรุปรายงานการเงิน</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if (!empty($records)): ?>
                            <table id='basic-datatables' class='display table table-striped table-hover'>
                                <thead>
                                    <tr>
                                        <th>วันที่บันทึก</th>
                                        <th>ยอดเงิน (บาท)</th>
                                        <th>ผู้บันทึก</th>
                                        <th>หมายเหตุ</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $row): ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($row['record_date'])) ?></td>
                                            <td><?= number_format($row['amount'], 2) ?></td>
                                            <td><?= htmlspecialchars($row['recorded_by'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['note'] ?? '') ?></td>
                                            <td>
                                                <div class='btn-group'>
                                                    <button class='btn btn-sm btn-info me-1' title='ดูรายละเอียด'>
                                                        <i class='fas fa-eye'></i>
                                                    </button>
                                                    <button class='btn btn-sm btn-warning me-1' title='แก้ไข'>
                                                        <i class='fas fa-edit'></i>
                                                    </button>
                                                    <button class='btn btn-sm btn-danger me-1' title='ลบ'>
                                                        <i class='fas fa-trash'></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class='lead'><em>ไม่พบข้อมูลรายงานการเงิน</em></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once("../footer.php");
?>