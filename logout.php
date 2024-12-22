<?php
session_start(); // شروع نشست
session_destroy(); // پایان نشست و حذف تمام داده‌های ذخیره شده در جلسه
header("Location: login.php"); // هدایت به صفحه ورود
exit;
?>
