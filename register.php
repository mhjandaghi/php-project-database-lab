<?php
// اتصال به پایگاه داده
$servername = "localhost";
$username = "root";
$password = ""; // معمولاً خالی است
$database = "virgool_blog";

$conn = new mysqli($servername, $username, $password, $database);

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// متغیرهای پیام
$success_message = "";
$error_message = "";

// بررسی ارسال فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // اعتبارسنجی داده‌ها
    if (strlen($phone) !== 11) {
        $error_message = "شماره موبایل باید 11 رقم باشد.";
    } elseif ($password !== $confirm_password) {
        $error_message = "رمز عبور و تأیید رمز عبور مطابقت ندارند.";
    } elseif (strlen($password) < 8) {
        $error_message = "رمز عبور باید حداقل 8 کاراکتر باشد.";
    } else {
        // بررسی موجود بودن شماره موبایل
        $stmt = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "این شماره موبایل قبلاً ثبت شده است.";
        } else {
            // ثبت کاربر
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (phone_number, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $phone, $hashed_password);

            if ($stmt->execute()) {
                $success_message = "ثبت‌نام با موفقیت انجام شد. اکنون می‌توانید وارد شوید.";
            } else {
                $error_message = "مشکلی در ثبت‌نام به وجود آمد.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>ثبت‌نام</title>
</head>
<body>
    <h1>ثبت‌نام</h1>
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>شماره موبایل:</label>
        <input type="text" name="phone" required>
        <br>
        <label>رمز عبور:</label>
        <input type="password" name="password" required>
        <br>
        <label>تأیید رمز عبور:</label>
        <input type="password" name="confirm_password" required>
        <br>
        <button type="submit">ثبت‌نام</button>
    </form>
    <a href="login.php">ورود</a>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
