<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF']));

// Handle approval action
if (isset($_POST['approve_user'])) {
    require_once "../../config/database.php";

    $user_id = $_POST['user_id'];
    $action = $_POST['action']; // 'approve', 'reject', หรือ 'inactive'

    try {
        // กำหนดคำสั่ง SQL ที่ชัดเจนตาม action
        switch ($action) {
            case 'approve':
                $sql = "UPDATE users_tb SET status = 'approved' WHERE id = :user_id";
                $success_msg = 'อนุมัติผู้ใช้เรียบร้อยแล้ว';
                break;
            case 'reject':
                $sql = "UPDATE users_tb SET status = 'rejected' WHERE id = :user_id";
                $success_msg = 'ปฏิเสธผู้ใช้เรียบร้อยแล้ว';
                break;
            case 'inactive':
                $sql = "UPDATE users_tb SET status = 'inactive' WHERE id = :user_id";
                $success_msg = 'ปิดใช้งานผู้ใช้เรียบร้อยแล้ว';
                break;
            default:
                throw new Exception('การกระทำไม่ถูกต้อง');
        }

        // ตรวจสอบว่ามีคำสั่ง SQL หรือไม่
        if (empty($sql)) {
            throw new Exception('ไม่พบคำสั่ง SQL ที่จะดำเนินการ');
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = $success_msg;
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
    exit;
}

// Fetch users from database
require_once "../../config/database.php";
$users = [];

try {
    $sql = "SELECT id, first_name, last_name, username, email, phone, role, status, created_at 
            FROM users_tb 
            ORDER BY created_at DESC";

    if (empty($sql)) {
        throw new Exception('ไม่พบคำสั่ง SQL สำหรับดึงข้อมูล');
    }

    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาดในการดึงข้อมูลผู้ใช้: ' . $e->getMessage();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

include_once("../sidebar.php");
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h3 class="fw-bold mb-3">ผู้ใช้งาน</h3>
            <div class="ms-md-auto py-2 py-md-0">
                <div class="btn-group">
                    <a href="?tambah" class="btn btn-primary btn-round">เพิ่มข้อมูล</a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-user-cog"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">ผู้ใช้งานทั้งหมด</p>
                                    <h4 class="card-title">
                                        <?php
                                        try {
                                            $stmt = $pdo->query("SELECT COUNT(*) FROM users_tb");
                                            echo htmlspecialchars($stmt->fetchColumn());
                                        } catch (PDOException $e) {
                                            echo "0";
                                            error_log("Error counting users: " . $e->getMessage());
                                        }
                                        ?>
                                    </h4>
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
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">รออนุมัติ</p>
                                    <h4 class="card-title">
                                        <?php
                                        try {
                                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_tb WHERE status = :status");
                                            $stmt->execute([':status' => 'pending']);
                                            echo htmlspecialchars($stmt->fetchColumn());
                                        } catch (PDOException $e) {
                                            echo "0";
                                            error_log("Error counting pending users: " . $e->getMessage());
                                        }
                                        ?>
                                    </h4>
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
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-user-times"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">ปฏิเสธ/ปิดใช้งาน</p>
                                    <h4 class="card-title">
                                        <?php
                                        try {
                                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_tb WHERE status IN (:status1, :status2)");
                                            $stmt->execute([
                                                ':status1' => 'rejected',
                                                ':status2' => 'inactive'
                                            ]);
                                            echo htmlspecialchars($stmt->fetchColumn());
                                        } catch (PDOException $e) {
                                            echo "0";
                                            error_log("Error counting rejected/inactive users: " . $e->getMessage());
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

        <!-- Users Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ผู้ใช้งาน</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>ชื่อผู้ใช้</th>
                                        <th>อีเมล</th>
                                        <th>กลุ่มผู้ใช้งาน</th>
                                        <th>สถานะ</th>
                                        <th>วันที่สมัคร</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <?php
                                                $role_text = '';
                                                switch ($user['role']) {
                                                    case 'admin':
                                                        $role_text = 'ผู้ดูแลระบบ';
                                                        break;
                                                    case 'executive':
                                                        $role_text = 'ผู้บริหาร';
                                                        break;
                                                    case 'officer':
                                                        $role_text = 'เจ้าหน้าที่';
                                                        break;
                                                    default:
                                                        $role_text = htmlspecialchars($user['role']);
                                                }
                                                echo $role_text;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                switch ($user['status']) {
                                                    case 'pending':
                                                        echo '<span class="badge badge-warning">รออนุมัติ</span>';
                                                        break;
                                                    case 'approved':
                                                        echo '<span class="badge badge-success">อนุมัติแล้ว</span>';
                                                        break;
                                                    case 'rejected':
                                                        echo '<span class="badge badge-danger">ปฏิเสธ</span>';
                                                        break;
                                                    case 'inactive':
                                                        echo '<span class="badge badge-secondary">ปิดใช้งาน</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-light">' . htmlspecialchars($user['status']) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <!-- View Button -->
                                                    <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Edit Button -->
                                                    <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <?php if ($user['status'] == 'pending'): ?>
                                                        <!-- Approve Button -->
                                                        <form method="post" style="display: inline;" onsubmit="return confirm('คุณต้องการอนุมัติผู้ใช้นี้หรือไม่?')">
                                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" name="approve_user" class="btn btn-sm btn-success me-1" title="อนุมัติ">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Reject Button -->
                                                        <form method="post" style="display: inline;" onsubmit="return confirm('คุณต้องการปฏิเสธผู้ใช้นี้หรือไม่?')">
                                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" name="approve_user" class="btn btn-sm btn-danger" title="ปฏิเสธ">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    <?php elseif ($user['status'] == 'approved'): ?>
                                                        <!-- Deactivate Button -->
                                                        <form method="post" style="display: inline;" onsubmit="return confirm('คุณต้องการปิดใช้งานผู้ใช้นี้หรือไม่?')">
                                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                                            <input type="hidden" name="action" value="inactive">
                                                            <button type="submit" name="approve_user" class="btn btn-sm btn-secondary" title="ปิดใช้งาน">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <!-- Reactivate Button -->
                                                        <form method="post" style="display: inline;" onsubmit="return confirm('คุณต้องการเปิดใช้งานผู้ใช้นี้หรือไม่?')">
                                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" name="approve_user" class="btn btn-sm btn-success" title="เปิดใช้งาน">
                                                                <i class="fas fa-user-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>ชื่อผู้ใช้</th>
                                        <th>อีเมล</th>
                                        <th>กลุ่มผู้ใช้งาน</th>
                                        <th>สถานะ</th>
                                        <th>วันที่สมัคร</th>
                                        <th>เครื่องมือ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

    <?php if (isset($_GET['tambah']) && !isset($_POST['edit'])): ?>
        <!-- Add User Form (existing code) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
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
                                    <div class="form-group">
                                        <label for="prefix" class="form-label">คำนำหน้า</label>
                                        <select class="form-control" name="prefix" id="prefix">
                                            <option value="">เลือกคำนำหน้า</option>
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">ชื่อไทย</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="โปรดระบุ" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">นามสกุลไทย</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="โปรดระบุ" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email" class="form-label">อีเมล</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="โปรดระบุ" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="username" class="form-label">ชื่อผู้ใช้งาน / username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="โปรดระบุ" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password" class="form-label">รหัสผ่าน</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="โปรดระบุ" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role" class="form-label">กลุ่มผู้ใช้งาน</label>
                                        <select class="form-control" name="role" id="role" required>
                                            <option value="">เลือกกลุ่มผู้ใช้งาน</option>
                                            <option value="admin">ผู้ดูแลระบบ</option>
                                            <option value="executive">ผู้บริหาร</option>
                                            <option value="officer">เจ้าหน้าที่</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="โปรดระบุ">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">บันทึก</button>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-danger">ย้อนกลับ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Approval Modal (Optional - for better UX) -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ยืนยันการดำเนินการ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="approvalMessage">คุณต้องการดำเนินการนี้หรือไม่?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <form id="approvalForm" method="post" style="display: inline;">
                    <input type="hidden" name="user_id" id="modalUserId">
                    <input type="hidden" name="action" id="modalAction">
                    <button type="submit" name="approve_user" class="btn" id="modalSubmitBtn">ยืนยัน</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced approval with modal (optional)
    function showApprovalModal(userId, action, userName) {
        const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
        const message = document.getElementById('approvalMessage');
        const submitBtn = document.getElementById('modalSubmitBtn');

        document.getElementById('modalUserId').value = userId;
        document.getElementById('modalAction').value = action;

        if (action === 'approve') {
            message.textContent = `คุณต้องการอนุมัติผู้ใช้ "${userName}" หรือไม่?`;
            submitBtn.className = 'btn btn-success';
            submitBtn.textContent = 'อนุมัติ';
        } else {
            message.textContent = `คุณต้องการปฏิเสธผู้ใช้ "${userName}" หรือไม่?`;
            submitBtn.className = 'btn btn-danger';
            submitBtn.textContent = 'ปฏิเสธ';
        }

        modal.show();
    }

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