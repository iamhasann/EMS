<?php
session_start();
ob_start(); // เปิดใช้งาน output buffering

$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");

// กำหนดตัวแปรและค่าเริ่มต้น
$amount = "";
$amount_err = "";

// ประมวลผลข้อมูลฟอร์มเมื่อส่งมา
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['amount'])) {
    // รับวันที่ปัจจุบัน
    date_default_timezone_set('Asia/Bangkok');
    $currentDate = new DateTime();
    $record_date = $currentDate->format('Y-m-d');

    // ตรวจสอบยอดเงิน
    $input_amount = trim($_POST["amount"]);
    if (empty($input_amount)) {
        $amount_err = "กรุณากรอกยอดเงิน";
    } elseif (!is_numeric($input_amount)) {
        $amount_err = "กรุณากรอกตัวเลขเท่านั้น";
    } elseif ($input_amount <= 0) {
        $amount_err = "ยอดเงินต้องมากกว่า 0";
    } else {
        $amount = $input_amount;
    }

    if (empty($amount_err)) {
        $sql = "INSERT INTO financial_records (record_date, amount) VALUES (:record_date, :amount)";

        try {
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':record_date', $record_date);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);

            if ($stmt->execute()) {
                ob_end_clean(); // ล้าง buffer ก่อน redirect
                $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
                header("location: ../finance/");
                exit();
            } else {
                ob_end_clean();
                $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
                header("location: index.php?tambah");
                exit();
            }
        } catch (PDOException $e) {
            ob_end_clean();
            $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
            header("location: ../finance/?tambah");
            exit();
        }
    } else {
        $_SESSION['error'] = $amount_err;
        header("location: ../finance/?tambah");
        exit();
    }
}
?>

<div class="page-inner">
    <?php if (!isset($_GET['tambah']) && !isset($_POST['edit']) && !isset($_POST['details'])) { ?>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h3 class="fw-bold mb-3">สรุปรายงานการเงิน</h3>
            <div class="ms-md-auto py-2 py-md-0">
                <div class="btn-group">
                    <a href="?tambah" class="btn btn-primary btn-round">เพิ่มข้อมูล</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-filter"></i><b> ตัวกรองสรุปรายงานการเงิน</b>
                    </h5>
                    <form>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">ค้นหา</label>
                                    <input type="text" class="form-control" placeholder="รหัส/ชื่อผู้บันทึก" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">ตั้งแต่วันที่</label>
                                    <input type="date" class="form-control" placeholder="ตั้งแต่วันที่" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">ถึงวันที่</label>
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
                                    <h4 class="card-title">
                                        <?php
                                        try {
                                            $sql_total = "SELECT SUM(amount) as total FROM financial_records";
                                            $stmt = $pdo->prepare($sql_total);
                                            $stmt->execute();

                                            $row_total = $stmt->fetch(PDO::FETCH_ASSOC);

                                            // Check if there's a result and handle null case
                                            $total = $row_total['total'] ?? 0;
                                            echo number_format($total, 2);
                                        } catch (PDOException $e) {
                                            error_log("Database error: " . $e->getMessage());
                                            echo "0.00"; // Show default value on error
                                        }
                                        ?>
                                    </h4>
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
                            <?php
                            try {
                                // ใช้ PDO แทน MySQLi
                                $sql = "SELECT * FROM financial_records ORDER BY record_date DESC";
                                $stmt = $pdo->query($sql);

                                if ($stmt->rowCount() > 0) {
                                    echo "<table id='basic-datatables' class='display table table-striped table-hover'>";
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>วันที่บันทึก</th>";
                                    echo "<th>ยอดเงิน (บาท)</th>";
                                    echo "<th>เครื่องมือ</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tfoot>";
                                    echo "<tr>";
                                    echo "<th>วันที่บันทึก</th>";
                                    echo "<th>ยอดเงิน (บาท)</th>";
                                    echo "<th>เครื่องมือ</th>";
                                    echo "</tr>";
                                    echo "</tfoot>";
                                    echo "<tbody>";

                                    while ($row = $stmt->fetch()) {
                                        echo "<tr>";
                                        echo "<td>" . date('d M Y', strtotime($row['record_date'])) . "</td>";
                                        echo "<td>" . number_format($row['amount'], 2) . "</td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        // View Button
                                        echo "<button class='btn btn-sm btn-info me-1' title='ดูรายละเอียด'>";
                                        echo "<i class='fas fa-eye'></i>";
                                        echo "</button>";
                                        // Edit Button
                                        echo "<button class='btn btn-sm btn-warning me-1' title='แก้ไข'>";
                                        echo "<i class='fas fa-edit'></i>";
                                        echo "</button>";
                                        // Delete Button
                                        echo "<button class='btn btn-sm btn-danger me-1' title='ลบ'>";
                                        echo "<i class='fas fa-trash'></i>";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }

                                    echo "</tbody>";
                                    echo "</table>";
                                } else {
                                    echo "<p class='lead'><em>ไม่พบข้อมูลรายงานการเงิน</em></p>";
                                }
                            } catch (PDOException $e) {
                                echo "<p class='text-danger'>เกิดข้อผิดพลาด: " . htmlspecialchars($e->getMessage()) . "</p>";
                                error_log("Database error: " . $e->getMessage());
                            }
                            ?>
                        </div>
                    </div>
                </div>
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
            <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded">' . htmlspecialchars($_SESSION['error']) . '</div>';
                            unset($_SESSION['error']);
                        }
                        if (isset($_SESSION['success'])) {
                            echo '
            <div class="alert alert-success col-8 mx-auto text-center p-2 border rounded">' . htmlspecialchars($_SESSION['success']) . '</div>';
                            unset($_SESSION['success']);
                        }
                    }
                    ?>
                    <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="card-header">
                            <div class="card-title">แบบฟอร์มบันทึกรายงานการเงิน</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    date_default_timezone_set('Asia/Bangkok');
                                    setlocale(LC_TIME, 'th_TH.UTF-8');

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
                                    ?>
                                    <div class="form-group">
                                        <label>วันที่บันทึก</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($thaiDateString) ?>" readonly>
                                        <input type="hidden" name="record_date" value="<?= htmlspecialchars($currentDate->format('Y-m-d')) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="amount">ยอดเงิน (บาท)</label>
                                        <input
                                            type="number"
                                            class="form-control <?php echo (!empty($amount_err)) ? 'is-invalid' : ''; ?>"
                                            id="amount"
                                            name="amount"
                                            placeholder="กรอกยอดเงิน (บาท)"
                                            value="<?php echo htmlspecialchars($amount); ?>"
                                            step="0.01"
                                            min="0.01"
                                            required>
                                        <?php if (!empty($amount_err)): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($amount_err); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">บันทึก</button>
                            <a href="index.php" class="btn btn-danger">ย้อนกลับ</a>
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