<?php
// Database Connection Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virgool_blog";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// SQL to create the database
$sql = "CREATE DATABASE IF NOT EXISTS virgool_blog";
$conn->query($sql);

// SQL to create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(11) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
)";
$conn->query($sql);

// SQL to create contact_requests table
$sql = "CREATE TABLE IF NOT EXISTS contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL
)";
$conn->query($sql);


$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);


// Insert Admin Account
$admin_phone = "09191112222";
$admin_password = password_hash("12345678", PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (phone_number, password, is_admin) VALUES ('$admin_phone', '$admin_password', 'admin')";
$conn->query($sql);

?>



<!DOCTYPE html>
<html>
<head>
    <title>Virgool Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .post {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        footer {
            margin-top: 20px;
            padding: 20px;
            background-color: #f4f4f4;
            border-top: 1px solid #ccc;
            text-align: center;
        }
        footer h2 {
            margin-bottom: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.like-button').click(function(e) {
            e.preventDefault();

            const postId = $(this).data('post-id');
            const likeButton = $(this);
            const likeCount = likeButton.closest('.post').find('.like-count');

            $.ajax({
                url: 'like.php',
                type: 'POST',
                data: { post_id: postId },
                success: function(response) {
                    const data = JSON.parse(response);
                    likeCount.text(data.likes); // به‌روزرسانی تعداد لایک
                },
                error: function() {
                    alert('مشکلی در لایک کردن پست رخ داد.');
                }
            });
        });
    });
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1>Welcome to Virgool Blog</h1>

    <?php
    session_start();
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        echo "<h2>Admin Panel</h2>";
        $result = $conn->query("SELECT * FROM contact_requests");
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>Name: {$row['name']}, Message: {$row['message']}</li>";
        }
        echo "</ul>";
    } elseif (isset($_SESSION['user_phone'])) {
        echo "<p>Hello, {$_SESSION['user_phone']} (<a href='logout.php'>Logout</a>)</p>";
    } else {
        echo "<p><a href='login.php'>Login</a> | <a href='register.php'>Register</a></p>";
    }
    ?>
    <h1>پست‌های وبلاگ</h1>
    <!-- در صفحه اصلی -->
    <a href="add_post.php" class="btn btn-primary">پست جدید</a>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <h2><?php echo $row['title']; ?></h2>
            <p><strong>نویسنده:</strong> <?php echo $row['author']; ?></p>
            <p><?php echo $row['content']; ?></p>
            <p><strong>تاریخ انتشار:</strong> <?php echo $row['created_at']; ?></p>
            <p><strong>تعداد لایک‌ها:</strong> <?php echo $row['likes']; ?></p>
            <form method="post" action="like.php">
                <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                <button type="submit">لایک</button>
            </form>
            <a href="post.php?id=<?php echo $row['id']; ?>">مشاهده نظرات</a>
        </div>
    <?php endwhile; ?>


    <h2>Contact Us</h2>
    <form action="contact.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="message">Message:</label>
        <textarea name="message" required></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>
    <!-- فوتر -->
    <footer>
        <h2>درباره‌ی ما</h2>
        <p><strong>نام سایت:</strong> ویرگول</p>
        <p><strong>شماره تماس مدیر سایت:</strong> 09123456789</p>
        <p><strong>آدرس شرکت:</strong> خیابان آزادی، تهران</p>
        <p><strong>سوابق درخشان:</strong> ارائه بهترین خدمات وبلاگ‌نویسی در ایران با بیش از 5 سال تجربه.</p>
    </footer>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>

<?php
$conn->close();
?>
