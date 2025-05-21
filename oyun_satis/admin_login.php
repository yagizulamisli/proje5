<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Admin giriş bilgileri
$admin_username = 'admin';
$admin_password = 'admin123';

// Veritabanı bağlantısı
$conn = new mysqli("localhost", "root", "", "oyun_satis");
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// Giriş kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
    if ($_POST['admin_username'] === $admin_username && $_POST['admin_password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Geçersiz giriş bilgileri!";
    }
}

// Çıkış işlemi
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

// Durum güncelleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['update_status'], $_POST['order_id'], $_POST['new_status'])) {
        $order_id = intval($_POST['order_id']);
        $new_status = $_POST['new_status'];
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['delete_order'], $_POST['order_id'])) {
        $order_id = intval($_POST['order_id']);
        $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Siparişleri çek
$query = "SELECT o.*, u.email FROM orders o 
          LEFT JOIN users u ON o.kullanici = u.username 
          ORDER BY o.kullanici, o.order_date DESC";
$result = $conn->query($query);

$orders = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[$row['kullanici']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli - Sipariş Yönetimi</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body { font-family: Arial; margin: 20px; }
        .admin-container { max-width: 1000px; margin: auto; }
        .login-form, .admin-header, .orders-table-container { margin-bottom: 20px; }
       
        .logout-btn { float: right; padding: 8px 12px; background: #e74c3c; color: white; border: none; cursor: pointer; border-radius: 4px; }
        .delete-btn { background-color: #e74c3c; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; }
        .status-select { padding: 5px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="admin-container">
    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
        <div class="login-form">
            <h2>Admin Girişi</h2>
            <?php if (!empty($error)): ?>
                <div style="color: red;"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="text" name="admin_username" placeholder="Kullanıcı Adı" required>
                <input type="password" name="admin_password" placeholder="Şifre" required>
                <button type="submit" name="admin_login">Giriş Yap</button>
            </form>
        </div>
    <?php else: ?>
        <div class="admin-header">
            <h1>Kullanıcı Sipariş Tablosu</h1>
            <form method="post">
                <button type="submit" name="logout" class="logout-btn">Çıkış Yap</button>
            </form>
        </div>

        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                <tr>
                    <th>Kullanıcı</th>
                    <th>E-posta</th>
                    <th>Oyun</th>
                    <th>Fiyat</th>
                    <th>Sipariş Tarihi</th>
                    <th>Durumu</th>
                    <th>Durum Değiştir</th>
                    <th>Sil</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $username => $user_orders): ?>
                    <?php foreach ($user_orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($username); ?></td>
                            <td><?php echo htmlspecialchars($order['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo $order['price']; ?>₺</td>
                            <td><?php echo date('d.m.Y H:i', strtotime($order['order_date'])); ?></td>
                            <td><?php echo htmlspecialchars($order['status'] ?? 'Hazırlanıyor'); ?></td>
                            <td>
                                <form method="post" style="margin:0;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="new_status" class="status-select" onchange="this.form.submit()">
                                        <option value="Hazırlanıyor" <?php if ($order['status'] == 'Hazırlanıyor') echo 'selected'; ?>>Hazırlanıyor</option>
                                        <option value="Teslim Edildi" <?php if ($order['status'] == 'Teslim Edildi') echo 'selected'; ?>>Teslim Edildi</option>
                                        <option value="İade Edildi" <?php if ($order['status'] == 'İade Edildi') echo 'selected'; ?>>İade Edildi</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <form method="post" onsubmit="return confirm('Siparişi silmek istediğinize emin misiniz?')">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" name="delete_order" class="delete-btn">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
