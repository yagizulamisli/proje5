<?php
session_start();
if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

// Değerler
$kullanici = $_SESSION['kullanici_adi'];
$product_name = "Subway Surfer";
$price = 0; // Ücretsizse 0, ücretliyse fiyat
$order_date = date("Y-m-d H:i:s");

$sql = "INSERT INTO orders (kullanici, product_name, price, order_date) 
        VALUES (:kullanici, :product_name, :price, :order_date)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':kullanici' => $kullanici,
    ':product_name' => $product_name,
    ':price' => $price,
    ':order_date' => $order_date
]);

// Başarıyla eklenince yönlendir
header("Location: index.html?status=success");
exit;
?>
