<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect based on role
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] === "executive") {
            header("location: ../executive/");
        } elseif ($_SESSION["role"] === "officer") {
            header("location: ../officer/");
        } else {
            // Default redirect for other roles
            header("location: ../executive/");
        }
    } else {
        header("location: ../executive/");
    }
    exit;
}

// Include config file
require_once "../config/database.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "กรุณากรอกชื่อผู้ใช้";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "กรุณากรอกรหัสผ่านของคุณ";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        try {
            // Prepare a select statement - ดึงข้อมูลผู้ใช้
            $sql = "SELECT id, first_name, last_name, email, phone, role, username, password, status FROM users_tb WHERE username = :username";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            
            // Execute the prepared statement
            if ($stmt->execute()) {
                // Check if username exists
                if ($stmt->rowCount() == 1) {
                    // Fetch user data
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Check if account is approved
                    if ($user['status'] != 'approved') {
                        $username_err = "บัญชีนี้ยังไม่ได้รับการอนุมัติจากผู้ดูแลระบบ";
                    } elseif (password_verify($password, $user['password'])) {
                        // Password is correct, start a new session
                        session_regenerate_id(true); // Regenerate session ID for security
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $user['id'];
                        $_SESSION["first_name"] = $user['first_name'];
                        $_SESSION["last_name"] = $user['last_name'];
                        $_SESSION["email"] = $user['email'];
                        $_SESSION["phone"] = $user['phone'];
                        $_SESSION["role"] = $user['role'];
                        $_SESSION["username"] = $user['username'];
                        
                        // Optional: Update last login time
                        try {
                            $update_sql = "UPDATE users_tb SET last_login = NOW() WHERE id = :id";
                            $update_stmt = $pdo->prepare($update_sql);
                            $update_stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
                            $update_stmt->execute();
                        } catch(PDOException $e) {
                            // Log error but don't stop login process
                            error_log("Failed to update last login: " . $e->getMessage());
                        }
                        
                        // Redirect user based on role
                        if ($user['role'] === "executive") {
                            header("location: ../executive/");
                        } elseif ($user['role'] === "officer") {
                            header("location: ../officer/");
                        } else {
                            // Default redirect for other roles or undefined roles
                            header("location: ../executive/");
                        }
                        exit;
                    } else {
                        // Display an error message if password is not valid
                        $password_err = "รหัสผ่านที่คุณป้อนไม่ถูกต้อง";
                        
                        // Optional: Log failed login attempt
                        error_log("Failed login attempt for username: " . $username . " from IP: " . $_SERVER['REMOTE_ADDR']);
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "ไม่พบบัญชีที่มีชื่อผู้ใช้นั้น";
                    
                    // Optional: Log failed login attempt
                    error_log("Login attempt with non-existent username: " . $username . " from IP: " . $_SERVER['REMOTE_ADDR']);
                }
            }
        } catch(PDOException $e) {
            // Log the error and show generic message
            error_log("Database error in login: " . $e->getMessage());
            $username_err = "เกิดข้อผิดพลาดในระบบ โปรดลองอีกครั้งในภายหลัง";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<!-- [Head] start -->

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ศูนย์การแพทย์ฉุกเฉิน - เข้าสู่ระบบ</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <link
        rel="icon"
        href="../assets/img/kaiadmin/LOGO.png"
        type="image/x-icon" />

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

    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="assets/css/style-preset.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css" />
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="#"><img src="../assets/img/kaiadmin/BLOGO.png" alt="img" width="10%"></a>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>เข้าสู่ระบบ</b></h3>
                        </div>

                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group mb-3">
                                <label class="form-label">ชื่อผู้ใช้หรืออีเมล</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="ชื่อผู้ใช้หรืออีเมล">
                                    <div class="invalid-feedback"><?php echo $username_err; ?></div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">รหัสผ่าน</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="password" placeholder="รหัสผ่าน">
                                    <div class="invalid-feedback"><?php echo $password_err; ?></div>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" name="login" class="btn btn-primary">
                                    <i class="fa fa-sign-in-alt"></i> เข้าสู่ระบบ
                                </button>
                            </div>
                            <div class="text-center mt-3">
                                <p>ยังไม่มีบัญชีใช่ไหม? <a href="register.php" class="link-primary">ลงทะเบียน</a></p>
                            </div>
                        </form>


                        <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group mb-3 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                <label class="form-label">ชื่อผู้ใช้หรืออีเมล</label>
                                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="ชื่อผู้ใช้หรืออีเมล">
                                <span class="help-block"><?php echo $username_err; ?></span>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" required>
                                <span class="help-block"><?php echo $password_err; ?></span>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" name="login" class="btn btn-primary">
                                    เข้าสู่ระบบ
                                </button>
                            </div>
                        </form> -->

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                หมายเหตุ: บัญชีใหม่จะต้องได้รับการยืนยันจากแอดมินก่อนเข้าใช้งาน
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
    <!-- [ Main Content ] end -->

    <!-- Required Js -->
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/fonts/custom-font.js"></script>
    <script src="assets/js/pcoded.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>

    <script>
        layout_change('light');
        change_box_container('false');
        layout_rtl_change('false');
        preset_change("preset-1");
        font_change("Public-Sans");
    </script>
</body>
<!-- [Body] end -->

</html>