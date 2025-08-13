<?php
// Include config file
require_once "../config/database.php";

// Define variables and initialize with empty values
$first_name = $last_name = $email = $phone = $role = "";
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$email_err = $phone_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // เก็บค่า POST และ validate ข้อมูลพื้นฐาน
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $role = trim($_POST["role"] ?? "");

    // Validate email format
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "รูปแบบอีเมลไม่ถูกต้อง";
    }

    // Validate phone number (basic validation)
    if (!empty($phone) && !preg_match('/^[0-9\-\+\(\)\s]+$/', $phone)) {
        $phone_err = "รูปแบบเบอร์โทรศัพท์ไม่ถูกต้อง";
    }

    // Validate username
    if (empty(trim($_POST["username"] ?? ""))) {
        $username_err = "กรุณากรอกชื่อผู้ใช้";
    } elseif (strlen(trim($_POST["username"])) < 3) {
        $username_err = "ชื่อผู้ใช้ต้องมีอย่างน้อย 3 ตัวอักษร";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "ชื่อผู้ใช้ควรประกอบด้วยตัวอักษร ตัวเลข และ _ เท่านั้น";
    } else {
        try {
            // Check if username already exists
            $sql = "SELECT id FROM users_tb WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $username_err = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว";
            } else {
                $username = trim($_POST["username"]);
            }
        } catch (PDOException $e) {
            error_log("Database error checking username: " . $e->getMessage());
            $username_err = "เกิดข้อผิดพลาดในการตรวจสอบชื่อผู้ใช้";
        }
    }

    // Check if email already exists (if provided)
    if (!empty($email) && empty($email_err)) {
        try {
            $sql = "SELECT id FROM users_tb WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $email_err = "อีเมลนี้ถูกใช้ไปแล้ว";
            }
        } catch (PDOException $e) {
            error_log("Database error checking email: " . $e->getMessage());
            $email_err = "เกิดข้อผิดพลาดในการตรวจสอบอีเมล";
        }
    }

    // Validate password
    if (empty(trim($_POST["password"] ?? ""))) {
        $password_err = "กรุณาใส่รหัสผ่าน";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', trim($_POST["password"]))) {
        $password_err = "รหัสผ่านต้องมีตัวพิมพ์เล็ก ตัวพิมพ์ใหญ่ และตัวเลขอย่างน้อย 1 ตัว";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"] ?? ""))) {
        $confirm_password_err = "กรุณายืนยันรหัสผ่าน";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "รหัสผ่านไม่ตรงกัน";
        }
    }

    // ถ้าไม่มี error ให้บันทึกลงฐานข้อมูล
    if (
        empty($username_err) && empty($password_err) && empty($confirm_password_err)
        && empty($email_err) && empty($phone_err)
    ) {

        try {
            // Begin transaction
            $pdo->beginTransaction();

            $sql = "INSERT INTO users_tb (first_name, last_name, email, phone, role, username, password, status, created_at) 
                    VALUES (:first_name, :last_name, :email, :phone, :role, :username, :password, 'pending', NOW())";

            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);

            // Hash password with stronger options
            $hashed_password = password_hash($password, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536,
                'time_cost' => 4,
                'threads' => 3
            ]);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $user_id = $pdo->lastInsertId();

                // Commit transaction
                $pdo->commit();

                // Log successful registration
                error_log("New user registered: ID $user_id, Username: $username, Email: $email");

                // Set success message in session
                session_start();
                $_SESSION['registration_success'] = "ลงทะเบียนสำเร็จ! รอการอนุมัติจากผู้ดูแลระบบ";

                // Redirect after success
                header("location: ../auth/");
                exit();
            } else {
                $pdo->rollBack();
                throw new Exception("Failed to execute insert statement");
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log("Registration error: " . $e->getMessage());
            $username_err = "เกิดข้อผิดพลาดในการลงทะเบียน โปรดลองอีกครั้งในภายหลัง";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ศูนย์การแพทย์ฉุกเฉิน - ลงทะเบียน</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../assets/img/kaiadmin/LOGO.png" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- Template CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="assets/css/style-preset.css">
    <link rel="stylesheet" href="../assets/css/demo.css" />
</head>

<body>
    <!-- Pre-loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="../auth/"><img src="../assets/img/kaiadmin/BLOGO.png" alt="img" width="10%"></a>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>ลงทะเบียน</b></h3>
                        </div>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="registerForm" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">ชื่อภาษาไทย <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control"
                                            name="first_name"
                                            value="<?php echo htmlspecialchars($first_name); ?>"
                                            placeholder="ชื่อ"
                                            required>
                                        <div class="invalid-feedback">กรุณากรอกชื่อ</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">นามสกุลภาษาไทย <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control"
                                            name="last_name"
                                            value="<?php echo htmlspecialchars($last_name); ?>"
                                            placeholder="นามสกุล"
                                            required>
                                        <div class="invalid-feedback">กรุณากรอกนามสกุล</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                                    name="username"
                                    value="<?php echo htmlspecialchars($username); ?>"
                                    placeholder="ชื่อผู้ใช้ (อย่างน้อย 3 ตัวอักษร)"
                                    pattern="[a-zA-Z0-9_]{3,}"
                                    title="ชื่อผู้ใช้ต้องมีอย่างน้อย 3 ตัวอักษร และประกอบด้วยตัวอักษร ตัวเลข และ _ เท่านั้น"
                                    required>
                                <div class="invalid-feedback">
                                    <?php echo $username_err ? $username_err : 'ชื่อผู้ใช้ต้องมีอย่างน้อย 3 ตัวอักษร และประกอบด้วยตัวอักษร ตัวเลข และ _ เท่านั้น'; ?>
                                </div>
                                <small class="form-text text-muted">ใช้ได้เฉพาะตัวอักษรภาษาอังกฤษ ตัวเลข และ _ เท่านั้น</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                                    name="email"
                                    value="<?php echo htmlspecialchars($email); ?>"
                                    placeholder="อีเมล"
                                    required>
                                <div class="invalid-feedback">
                                    <?php echo $email_err ? $email_err : 'กรุณากรอกอีเมลให้ถูกต้อง'; ?>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="tel"
                                    class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>"
                                    name="phone"
                                    value="<?php echo htmlspecialchars($phone); ?>"
                                    placeholder="เบอร์โทรศัพท์"
                                    pattern="[0-9\-\+\(\)\s]+"
                                    title="กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง">
                                <div class="invalid-feedback">
                                    <?php echo $phone_err ? $phone_err : 'กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง'; ?>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">ตำแหน่ง <span class="text-danger">*</span></label>
                                <select class="form-control" name="role" required>
                                    <option value="">เลือกตำแหน่ง</option>
                                    <option value="officer" <?php echo ($role == 'officer') ? 'selected' : ''; ?>>
                                        เจ้าหน้าที่
                                    </option>
                                    <option value="executive" <?php echo ($role == 'executive') ? 'selected' : ''; ?>>
                                        ผู้บริหาร
                                    </option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกตำแหน่ง</div>
                                <small class="form-text text-muted">หมายเหตุ: ตำแหน่งแอดมินจะถูกกำหนดโดยแอดมินระบบเท่านั้น</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password"
                                                class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                                                name="password"
                                                id="password"
                                                value="<?php echo htmlspecialchars($password); ?>"
                                                placeholder="รหัสผ่าน (อย่างน้อย 8 ตัวอักษร)"
                                                minlength="8"
                                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                                title="รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวพิมพ์เล็ก ตัวพิมพ์ใหญ่ และตัวเลข"
                                                required>
                                        </div>
                                        <div class="invalid-feedback">
                                            <?php echo $password_err ? $password_err : 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวพิมพ์เล็ก ตัวพิมพ์ใหญ่ และตัวเลข'; ?>
                                        </div>
                                        <div class="password-requirements">
                                            <small>รหัสผ่านต้องประกอบด้วย:</small>
                                            <ul>
                                                <li id="length-req">อย่างน้อย 8 ตัวอักษร</li>
                                                <li id="lower-req">ตัวพิมพ์เล็ก (a-z)</li>
                                                <li id="upper-req">ตัวพิมพ์ใหญ่ (A-Z)</li>
                                                <li id="number-req">ตัวเลข (0-9)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password"
                                                class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
                                                name="confirm_password"
                                                id="confirm_password"
                                                value="<?php echo htmlspecialchars($confirm_password); ?>"
                                                placeholder="ยืนยันรหัสผ่าน"
                                                required>
                                        </div>
                                        <div class="invalid-feedback">
                                            <?php echo $confirm_password_err ? $confirm_password_err : 'กรุณายืนยันรหัสผ่าน'; ?>
                                        </div>
                                        <div id="password-match" class="form-text"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    ฉันยอมรับ <a href="#" class="link-primary">เงื่อนไขการใช้งาน</a> และ
                                    <a href="#" class="link-primary">นโยบายความเป็นส่วนตัว</a>
                                </label>
                                <div class="invalid-feedback">กรุณายอมรับเงื่อนไขการใช้งาน</div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" name="register" class="btn btn-primary btn-lg">
                                    ลงทะเบียน
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <p>มีบัญชีอยู่แล้ว? <a href="../auth/" class="link-primary">เข้าสู่ระบบ</a></p>
                            </div>
                        </form>
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                บัญชีจะสามารถใช้งานได้หลังจากแอดมินยืนยันการลงทะเบียนเรียบร้อยแล้ว
                            </small>
                        </div>
                    </div>
                </div>
                <div class="auth-footer row">
                    <div class="col-12 text-center">
                        <p class="text-muted">© 2024 ศูนย์การแพทย์ฉุกเฉิน. สงวนลิขสิทธิ์.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/fonts/custom-font.js"></script>
    <script src="assets/js/pcoded.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;

            // Check length
            const lengthReq = document.getElementById('length-req');
            if (password.length >= 8) {
                lengthReq.className = 'requirement-met';
            } else {
                lengthReq.className = 'requirement-unmet';
            }

            // Check lowercase
            const lowerReq = document.getElementById('lower-req');
            if (/[a-z]/.test(password)) {
                lowerReq.className = 'requirement-met';
            } else {
                lowerReq.className = 'requirement-unmet';
            }

            // Check uppercase
            const upperReq = document.getElementById('upper-req');
            if (/[A-Z]/.test(password)) {
                upperReq.className = 'requirement-met';
            } else {
                upperReq.className = 'requirement-unmet';
            }

            // Check numbers
            const numberReq = document.getElementById('number-req');
            if (/\d/.test(password)) {
                numberReq.className = 'requirement-met';
            } else {
                numberReq.className = 'requirement-unmet';
            }

            // Check password match
            checkPasswordMatch();
        });

        // Real-time password match validation
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchDiv = document.getElementById('password-match');

            if (confirmPassword === '') {
                matchDiv.textContent = '';
                matchDiv.className = 'form-text';
            } else if (password === confirmPassword) {
                matchDiv.textContent = '✓ รหัสผ่านตรงกัน';
                matchDiv.className = 'form-text text-success';
            } else {
                matchDiv.textContent = '✗ รหัสผ่านไม่ตรงกัน';
                matchDiv.className = 'form-text text-danger';
            }
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });

        // Username validation
        document.querySelector('input[name="username"]').addEventListener('input', function() {
            const username = this.value;
            const isValid = /^[a-zA-Z0-9_]{3,}$/.test(username);

            if (username.length > 0 && !isValid) {
                this.setCustomValidity('ชื่อผู้ใช้ต้องมีอย่างน้อย 3 ตัวอักษร และประกอบด้วยตัวอักษร ตัวเลข และ _ เท่านั้น');
            } else {
                this.setCustomValidity('');
            }
        });

        // Email validation
        document.querySelector('input[name="email"]').addEventListener('input', function() {
            const email = this.value;
            const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            if (email.length > 0 && !isValid) {
                this.setCustomValidity('กรุณากรอกอีเมลให้ถูกต้อง');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>

</body>

</html>