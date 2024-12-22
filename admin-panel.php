<?php
// Check if admin
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header('Location: index1.php');
    exit();
}

// Fetch contacts
$result = $conn->query("SELECT * FROM contacts");
?>
<h1>Admin Panel</h1>
<table>
    <tr>
        <th>نام</th>
        <th>پیام</th>
        <th>تاریخ</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['message']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>
