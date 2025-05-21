<?php
// Sepetteki ürünleri tutan bir örnek dizi
// Gerçek uygulamada bu bilgiler veritabanından alınır
$sepet = [
    ['id' => 1, 'isim' => 'Subway Surfer', 'fiyat' => 100],
    ['id' => 2, 'isim' => 'Fortnite', 'fiyat' => 150],
    ['id' => 3, 'isim' => 'Minecraft', 'fiyat' => 80]
];

// Sepetten ürün silme işlemi
if (isset($_GET['sil'])) {
    $silId = $_GET['sil'];
    // Silme işlemi: array_filter ile o id'yi diziye dahil etmiyoruz
    $sepet = array_filter($sepet, function($urun) use ($silId) {
        return $urun['id'] != $silId;
    });
    // Yeniden indexleme
    $sepet = array_values($sepet);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="nav container">
            <a href="index.html" class="logo">Wuthering<span>Games</span></a>
        </div>
    </header>

    <div class="sepet-container">
        <h1>Sepetim</h1>

        <?php if(count($sepet) > 0): ?>
            <ul class="sepet-list">
                <?php foreach ($sepet as $urun): ?>
                    <li class="sepet-item">
                        <span class="urun-isim"><?php echo $urun['isim']; ?></span>
                        <span class="urun-fiyat"><?php echo $urun['fiyat']; ?>₺</span>
                        <a href="sepetim.php?sil=<?php echo $urun['id']; ?>" class="sil-butonu">Sil</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Sepetiniz boş.</p>
        <?php endif; ?>
    </div>

</body>
</html>
