<?php
// اتصال به پایگاه داده
$servername = "localhost";
$username = "root";
$password = "";
$database = "virgool_blog";

$conn = new mysqli($servername, $username, $password, $database);

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// دریافت اطلاعات پست
$post_id = $_GET['id'] ?? 0;
$post_stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$post_stmt->bind_param("i", $post_id);
$post_stmt->execute();
$post = $post_stmt->get_result()->fetch_assoc();

// دریافت کامنت‌ها
$comments_stmt = $conn->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY comment_date DESC");
$comments_stmt->bind_param("i", $post_id);
$comments_stmt->execute();
$comments = $comments_stmt->get_result();

// ارسال کامنت
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $content = $_POST['content'];

    $comment_stmt = $conn->prepare("INSERT INTO comments (post_id, name, email, content) VALUES (?, ?, ?, ?)");
    $comment_stmt->bind_param("isss", $post_id, $name, $email, $content);
    $comment_stmt->execute();

    header("Location: post.php?id=" . $post_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title><?php echo $post['title']; ?></title>
</head>
<body>
    <h1><?php echo $post['title']; ?></h1>
    <p><strong>نویسنده:</strong> <?php echo $post['author']; ?></p>
    <p><?php echo $post['content']; ?></p>
    <p><strong>تاریخ انتشار:</strong> <?php echo $post['publish_date']; ?></p>
    <p><strong>تعداد لایک‌ها:</strong> <?php echo $post['likes']; ?></p>

    <h2>نظرات</h2>
    <?php while ($comment = $comments->fetch_assoc()): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <p><strong>نام:</strong> <?php echo $comment['name']; ?></p>
            <p><?php echo $comment['content']; ?></p>
            <p><strong>تاریخ:</strong> <?php echo $comment['comment_date']; ?></p>
        </div>
    <?php endwhile; ?>

    <h2>ارسال نظر</h2>
    <form method="post" action="">
        <label>نام:</label>
        <input type="text" name="name" required>
        <br>
        <label>ایمیل:</label>
        <input type="email" name="email" required>
        <br>
        <label>متن نظر:</label>
        <textarea name="content" required></textarea>
        <br>
        <button type="submit">ارسال نظر</button>

    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
