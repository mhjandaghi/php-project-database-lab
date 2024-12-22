<?php
header('Content-Type: application/json'); // مشخص کردن نوع خروجی به عنوان JSON

// اتصال به پایگاه داده
$servername = "localhost";
$username = "root";
$password = "";
$database = "virgool_blog";

$conn = new mysqli($servername, $username, $password, $database);

// بررسی اتصال
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// بررسی درخواست AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];

    // افزایش تعداد لایک‌ها
    $stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    // بازیابی تعداد لایک جدید
    $stmt = $conn->prepare("SELECT likes FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($likes);
    $stmt->fetch();

    // بازگشت تعداد لایک جدید به‌صورت JSON
    echo json_encode(['likes' => $likes]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);
?>
