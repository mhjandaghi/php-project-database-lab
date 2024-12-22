<?php
session_start();

$servername = "localhost";
$username = "your_username"; // نام کاربری دیتابیس
$password = "your_password"; // رمز عبور دیتابیس
$database = "virgool_blog"; // نام پایگاه داده

$conn = new mysqli($servername, $username, $password, $database);

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// اگر کاربر وارد نشده، مجاز به دیدن این صفحه نباشد
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // Validate form data
    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ssi", $title, $content, $user_id);

        if ($stmt->execute()) {
            echo "پست جدید با موفقیت ذخیره شد.";
            header('Location: index.php'); // Redirect to main page
        } else {
            echo "خطایی در ذخیره‌سازی پست پیش آمد.";
        }
    } else {
        echo "لطفاً عنوان و محتوای پست را وارد کنید.";
    }
}
?>

<h1>ایجاد پست جدید</h1>
<form method="POST">
    <label for="title">عنوان پست:</label>
    <input type="text" name="title" required>
    <label for="content">محتوای پست:</label>
    <textarea name="content" required></textarea>
    <input type="submit" value="ذخیره">
</form>
