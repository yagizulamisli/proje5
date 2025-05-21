<?php
session_start();
$conn = new mysqli("localhost", "root", "", "oyun_satis");

// Çıkış işlemi
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Giriş işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $_SESSION['user_logged_in'] = true;
    $_SESSION['username'] = $username;
    
    header("Location: index.php");
    exit(); // Yönlendirmeden sonra scriptin devam etmemesi için
} else {
        $login_error = "Geçersiz kullanıcı adı veya şifre!";
    }
}

// Kayıt işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($password !== $_POST['confirm_password']) {
        $register_error = "Şifreler eşleşmiyor!";
    } else {
        $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if ($conn->query($query)) {
            $register_success = "Kayıt başarılı! Giriş yapabilirsiniz.";
        } else {
            $register_error = "Bu kullanıcı adı veya e-posta zaten kayıtlı!";
        }
    }
}

// Sepete ürün ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_logged_in'])) {
        $cart_error = "Sepete ürün eklemek için giriş yapmalısınız!";
    } else {
        $product_name = "";
        $price = 0;
        
        if ($_POST['product'] == 'product1') {
            $product_name = "Cyberpunk 2077";
            $price = 150;
        } elseif ($_POST['product'] == 'product2') {
            $product_name = "The Witcher 3";
            $price = 100;
        }
        
        $username = $_SESSION['username'];
        $query = "INSERT INTO orders (kullanici, product_name, price) VALUES ('$username', '$product_name', $price)";
        
        if ($conn->query($query)) {
            $cart_success = "Ürün sepete eklendi!";
        } else {
            $cart_error = "Sipariş kaydedilirken hata oluştu!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wuthering Games - Giriş</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <style>
      body {
    font-family: 'Roboto', sans-serif;
    background-color: #000;
    color: white;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding-left: 10%;
    background-size: cover;
    background-position: center;
    background-image: url('images/bg1.jpg');
    animation: backgroundTransition 6s infinite alternate; /* Geçiş animasyonu */
}

@keyframes backgroundTransition {
    0% {
        background-image: url('images/bg1.jpg');
    }
    100% {
        background-image: url('images/bg2.jpg');
    }
}

        
        .form-container {
            width: 350px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #1cb495;
        }
        
        .form-tabs {
            display: flex;
            margin-bottom: 20px;
        }
        
        .tab-btn {
            flex: 1;
            padding: 10px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            border-bottom: 2px solid #1cb495;
            color: #1cb495;
        }
        
        .form-content {
            display: none;
        }
        
        .form-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #1cb495;
        }
        
        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #1cb495;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #199e83;
        }
        
        .error-message {
            color: #ff4444;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .success-message {
            color: #1cb495;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .admin-link {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
        
        .admin-link a {
            background-color: #333;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Wuthering Games</h1>
            <p>Oyun dünyasına hoş geldiniz</p>
        </div>
        
        <div class="form-tabs">
            <button class="tab-btn active" onclick="showTab('login')">Giriş Yap</button>
            <button class="tab-btn" onclick="showTab('register')">Kayıt Ol</button>
        </div>
        
        <!-- Giriş Formu -->
        <div id="login-form" class="form-content active">
            <form method="post">
                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php if (isset($login_error)): ?>
                    <div class="error-message"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <button type="submit" name="login" class="submit-btn">Giriş Yap</button>
            </form>
        </div>
        
        <!-- Kayıt Formu -->
        <div id="register-form" class="form-content">
            <form method="post">
                <div class="form-group">
                    <label for="reg-username">Kullanıcı Adı</label>
                    <input type="text" id="reg-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="reg-email">E-posta</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="reg-password">Şifre</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="reg-confirm-password">Şifreyi Onayla</label>
                    <input type="password" id="reg-confirm-password" name="confirm_password" required>
                </div>
                <?php if (isset($register_error)): ?>
                    <div class="error-message"><?php echo $register_error; ?></div>
                <?php endif; ?>
                <?php if (isset($register_success)): ?>
                    <div class="success-message"><?php echo $register_success; ?></div>
                <?php endif; ?>
                <button type="submit" name="register" class="submit-btn">Kayıt Ol</button>
            </form>
        </div>
    </div>
    
    <div class="admin-link">
        <a href="admin_login.php" style="background-color: #1cb495;">Admin Girişi</a>

    </div>
    
    <script>
        function showTab(tabName) {
            // Tab butonlarını güncelle
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.tab-btn[onclick="showTab('${tabName}')"]`).classList.add('active');
            
            // Form içeriklerini güncelle
            document.querySelectorAll('.form-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(`${tabName}-form`).classList.add('active');
        }
    </script>
</body>
</html>