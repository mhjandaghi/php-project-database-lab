<?php
session_start();

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

// متغیر پیام
$error_message = "";

// بررسی ارسال فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    // بررسی موجود بودن شماره موبایل
    $stmt = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // اگر مدیر باشد
            if ($user['is_admin'] == 1) {
                $_SESSION['user_id'] = $user['id']; // Store user ID
                echo "<p style='color: green;'>مدیر سایت وارد شد.</p>";
                header("Location: admin_panel.php");
                exit;
            } else {
                $_SESSION['user_id'] = $user['id']; // Store user ID
                echo "<p style='color: green;'>شما با موفقیت وارد شدید.</p>";
                header("Location: user_panel.php"); // Redirect to main page
                exit;
            }
        } else {
            $error_message = "رمز عبور اشتباه است.";
        }
    } else {
        $error_message = "شماره موبایل وجود ندارد.";
    }
}
?>


<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>ورود</title>
</head>
<body>
    <h1>ورود</h1>
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>شماره موبایل:</label>
        <input type="text" name="phone" required>
        <br>
        <label>رمز عبور:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">ورود</button>
    </form>
    <a href="register.php">ثبت‌نام</a>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
