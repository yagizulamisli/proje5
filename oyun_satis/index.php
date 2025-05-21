<?php
session_start();
$conn = new mysqli("localhost", "root", "", "oyun_satis");

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'] ?? "";
    $product_price = $_POST['product_price'] ?? 0;

    if (!empty($product_name) && is_numeric($product_price)) {
        $product_price = floatval($product_price);

        if (!isset($_SESSION['sepet'])) {
            $_SESSION['sepet'] = [];
        }

        $_SESSION['sepet'][] = [
            'product_name' => $product_name,
            'product_price' => $product_price
        ];

        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $stmt = $conn->prepare("INSERT INTO orders (kullanici, product_name, price) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $username, $product_name, $product_price);

            if ($stmt->execute()) {
                $mesaj = "Ürün sepete eklendi!";
            } else {
                $mesaj = "Veritabanı hatası!";
            }
            $stmt->close();
        }
    } else {
        $mesaj = "Eksik veya geçersiz ürün bilgisi!";
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_from_cart'])) {
    $index = $_POST['remove_index'];
    if (isset($_SESSION['sepet'][$index])) {
        unset($_SESSION['sepet'][$index]);
        $_SESSION['sepet'] = array_values($_SESSION['sepet']); // index'leri sıfırla
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wuthering Games</title>
    <link rel="shortcut icon" href="img/fav.png" type="image/x-icon">
    <link rel="stylesheet" href="css/styleyagiz.css">
    <link href='https://unpkg.com/boxicons@2.1.0/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/swiper-bundle.min.css">
</head>
<body>
<div class="progress">
    <div class="progress-bar" id="scroll-bar"></div>
</div>
<header>
    <div class="nav container">
        <a href="index.html" class="logo">Wuthering<span>Games</span></a>
        <div class="nav-icons">
            <!-- Bildirim noktasi sadece aktifse görünmeli -->
            <i class='bx bx-bell bx-tada' id="bell-icon"></i>
            <i class='bx bxs-download'></i>
<i class="bx bx-cart" id="cart-icon"></i>


<!-- Bu panel ikonun hemen yanına eklenecek -->




            <div class="menu-icon">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </div>
        <div class="menu">
            <img src="img/menu.png" alt="">
            <div class="navbar">
                <li><a href="index.html">Home</a></li>
                <li><a href="#trending">Trending</a></li>
                <li><a href="#new">New Games</a></li>
                <li><a href="#action">Action Games</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </div>
        </div>
        <div class="notification">
            <div class="notification-box">
                <i class='bx bxs-check-circle'></i>
                <p>Congratulation, your game download successfully</p>
            </div>
            <div class="notification-box box-color">
                <i class='bx bxs-x-circle'></i>
                <p>Could not apply changes</p>
            </div>
        </div>
    </div>
</header>
  </header>

<div class="cart-panel" id="cart-panel">
    <h3>Sepetim</h3>
    <?php if (!empty($_SESSION['sepet'])): ?>
        <ul>
            <?php 
            $toplam = 0;
            foreach ($_SESSION['sepet'] as $key => $urun): 
                $toplam += $urun['product_price'];
            ?>
                <li style="margin-bottom: 10px;">
                    <?php echo htmlspecialchars($urun['product_name']); ?> - <?php echo $urun['product_price']; ?>₺
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="remove_index" value="<?php echo $key; ?>">
                        <button type="submit" name="remove_from_cart" class="delete-btn" style="margin-left:10px;">Sil</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <strong>Toplam: <?php echo $toplam; ?>₺</strong>
    <?php else: ?>
        <p>Sepetiniz boş.</p>
    <?php endif; ?>
</div>

<section class="home container" id="home">
    <img src="img/home.png" alt="">
    <div class="home-text">
        <h1>CITY OF THE <br> FUTURE</h1>
        <a href="#" class="btn">Available Now</a>
    </div>
</section>


<section class="trending container" id="trending">
<div class="heading">
     <i class='bx bxs-flame'></i>
     <h2>Trending Games</h2>

    
</div>
 <div class="trending-content swiper">
  <div class="swiper-wrapper">
    <!-- Slide 1 -->
    <div class="swiper-slide">
      <div class="box">
        <img src="img/trending1.webp" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Cyberpunk 2077</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>
     <!-- Slide 2 -->
        <div class="swiper-slide">
      <div class="box">
        <img src="img/trending2.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Battlefield 2042</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>
    <!-- Slide 3 -->
     <div class="swiper-slide">
      <div class="box">
        <img src="img/trending3.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Assasins Creed Valhalla</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>
    <!-- Slide 4 -->
     <div class="swiper-slide">
      <div class="box">
        <img src="img/trending4.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Ghost of Tsushima</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
<a href="https://www.playstation.com/en-tr/games/ghost-of-tsushima/" class="box-btn" target="_blank">
    <i class='bx bx-down-arrow-alt'></i>
</a>

          </div>
        </div>
      </div>
    </div>
    <!-- Slide 5 -->
     <div class="swiper-slide">
      <div class="box">
        <img src="img/trending5.png" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>GTA V</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>
       <!-- Slide 6 -->
     <div class="swiper-slide">
      <div class="box">
        <img src="img/trending6.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Dying Light</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>
       <!-- Slide 7 -->
     <div class="swiper-slide">
      <div class="box">
        <img src="img/trending7.png" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Halo İnfinite</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>
       <!-- Slide 8 -->
     <div class="swiper-slide">
      <div class="box">
        <img src="img/trending8.png" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Resident Evil Village</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              <i class='bx bxs-star'></i>
              <span>4.7</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>
    <!-- Diğer slide'lar buraya -->
  </div>
  <div class="swiper-pagination"></div>
</div>

</section>

<section class="new container" id="new">

<div class="heading">
     <i class='bx bxs-flame'></i>
     <h2>New Games</h2>
      
    
</div>
<div class="new-content">
    <!-- box 1-->
   <div class="box">
    <img src="img/new1.jpg" alt="Subway Surfers">
    <div class="box-text">
        <h2>Subway Surfers</h2>
        <h3>Action</h3>
        <div class="rating-download">
            <div class="rating">
               
                <span>ücretsiz</span>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="product_name" value="Subway Surfers">
                <input type="hidden" name="product_price" value="100">
               <button type="submit" class="box-btn" style="background:none; border:none; padding:0; cursor:pointer;">
                    <i class='bx bx-down-arrow-alt'></i> <!-- Sepet ikonu -->
                </button>
            </form>
        </div>
    </div>
</div>
       <!-- box 2-->
    <div class="box">
        <img src="img/new2.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Call of Duty Mobile</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              
              <span>250₺</span>
            </div>
                   <form method="POST" action="" style="display:inline;">
    <input type="hidden" name="add_to_cart" value="1">
    <input type="hidden" name="product_name" value="Call of Duty Mobile">
    <input type="hidden" name="product_price" value="250">
    <button type="submit" class="box-btn" style="background:none; border:none; padding:0; cursor:pointer;">
        <i class='bx bx-down-arrow-alt'></i>
    </button>
</form>
          </div>
        </div>
      </div>
       <!-- box 3-->
    <div class="box">
        <img src="img/new3.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Free Guy</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              
              <span>150₺</span>
            </div>
   <form method="POST" action="" style="display:inline;">
    <input type="hidden" name="add_to_cart" value="1">
    <input type="hidden" name="product_name" value="Free Guy">
    <input type="hidden" name="product_price" value="150">
    <button type="submit" class="box-btn" style="background:none; border:none; padding:0; cursor:pointer;">
        <i class='bx bx-down-arrow-alt'></i>
    </button>
</form>
          </div>
        </div>
      </div>
       <!-- box 4-->
    <div class="box">
        <img src="img/new4.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Clash Royale</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
             
              <span>ÜCRETSİZ</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
       <!-- box 5-->
    <div class="box">
        <img src="img/new5.png" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Minecraft</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
             
              <span>450₺</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
       <!-- box 6-->
    <div class="box">
        <img src="img/new6.png" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>PUBG</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
             
              <span>275₺</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
       <!-- box 7-->
    <div class="box">
        <img src="img/new7.png" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Fortnite</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
             
              <span>ÜCRETSİZ</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
       <!-- box 8-->
    <div class="box">
        <img src="img/new8.jpg" alt="Cyberpunk 2077">
        <div class="box-text">
          <h2>Marvel of Champions</h2>
          <h3>Action</h3>
          <div class="rating-download">
            <div class="rating">
              
              <span>ÜCRETSİZ</span>
            </div>
            <a href="#" class="box-btn"><i class='bx bx-down-arrow-alt'></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="next-page">
      <a href="#">Next Page</a>
    </div>

</section>

  <div class="copyright container">
    <a href="#" class="logo">Wuthering<span>Games</span></a>
    <p>&#169; Buğrahan Bayram - Yağız Ulamışlı</p>
    <a href="login.php" class="logout-link">Çıkış Yap</a>
</div>


<script src="js/swiper-bundle.min.js"></script>

    
    <script src="js/main.js"></script>
</body>
</html>