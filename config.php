<?php
$servername = "localhost";
$username = "username"; // نام کاربری دیتابیس
$password = "password"; // رمز عبور دیتابیس
$database = "database_name"; // نام پایگاه داده

// اتصال به دیتابیس
$conn = new mysqli($servername, $username, $password, $database);

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
