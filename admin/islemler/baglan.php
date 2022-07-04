<?php
session_start();

try {
     // Aşağıda veritabanına bağlanma işlemini yapıyoruz
    $db = new PDO("mysql:host=localhost;dbname=alimar;charset=utf8","root","");
} catch (PDOException $hata) {
    echo $hata->getMessage();
}

// Site ayarlarını çekmek için yazdığımız veritabanı kodları
$ayarsor = $db->prepare("SELECT * FROM ayarlar");
$ayarsor->execute();
$ayarcek = $ayarsor->fetch(PDO::FETCH_ASSOC);
