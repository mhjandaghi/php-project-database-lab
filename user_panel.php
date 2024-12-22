<?php
session_start();

// بررسی ورود کاربر
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// اطلاعات کاربر
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$phone_number = $_SESSION['phone_number'];
$is_admin = $_SESSION['is_admin'];
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>پنل کاربری</title>
</head>
<body>
    <h1>خوش آمدید به پنل کاربری</h1>
    <p>شماره موبایل: <?php echo $phone_number; ?></p>
    <p>نام: <?php echo $name; ?></p>

    <?php if ($is_admin): ?>
        <p>شما مدیر سایت هستید.</p>
        <a href="admin_panel.php">ورود به پنل مدیریت</a>
    <?php else: ?>
        <p>شما کاربر عادی هستید.</p>
    <?php endif; ?>

    <a href="logout.php">خروج</a>
</body>
</html>
