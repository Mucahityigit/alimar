<?php
require "baglan.php"; // bağlan.php dosyamızı buraya aktarıyoruz.

//  Aşağıdaki işlemlerde site ayarlarımızı yapmak için kodlar yazdık
if (isset($_POST['ayarkaydet'])) {

    $ayarguncelle = $db->prepare("UPDATE ayarlar SET 
            site_baslik=:site_baslik,
            site_aciklama=:site_aciklama,
            site_link=:site_link,
            site_sahip_mail=:site_sahip_mail,
            site_mail_host=:site_mail_host,
            site_mail_mail=:site_mail_mail,
            site_mail_port=:site_mail_port,
            site_mail_sifre=:site_mail_sifre WHERE id=1
        ");

    $guncelle = $ayarguncelle->execute(array(
        'site_baslik' => $_POST['site_baslik'],
        'site_aciklama' => $_POST['site_aciklama'],
        'site_link' => $_POST['site_link'],
        'site_sahip_mail' => $_POST['site_sahip_mail'],
        'site_mail_host' => $_POST['site_mail_host'],
        'site_mail_mail' => $_POST['site_mail_mail'],
        'site_mail_port' => $_POST['site_mail_port'],
        'site_mail_sifre' => $_POST['site_mail_sifre']
    ));

    if ($_FILES['site_logo']['error'] == "0") {
        $gecici_ismi = $_FILES['site_logo']['tmp_name'];
        $dosya_ismi = rand(100000, 999999) . $_FILES['site_logo']['name'];
        move_uploaded_file($gecici_ismi, "../dosyalar/$dosya_ismi");

        $ayarguncelle = $db->prepare("UPDATE ayarlar SET 
                site_logo=:site_logo 
        ");

        $guncelle = $ayarguncelle->execute(array(
            'site_logo' => $dosya_ismi
        ));
    }

    if ($guncelle) {
        header("Location:../ayarlar.php?durum=ok");
    } else {
        header("Location:../ayarlar.php?durum=no");
    }
    exit;
}
/***************************************************************** */ 

//  Aşağıdaki işlemlerde admin panele giriş için kullanıcı mail ve şifresinin karşılaştırılmasını yaptık ve eşleşme halinde giriş işlemi yapıldı

if (isset($_POST['oturumacma'])) {
    $gelen_mail = $_POST['kul_mail'];
    $gelen_sifre = md5($_POST['kul_sifre']);
    $kullanicisor = $db->prepare("SELECT * FROM kullanicilar WHERE kul_mail = ?  AND kul_sifre = ?   ");
    $kullanicisor->execute([$gelen_mail, $gelen_sifre]);
    $say = $kullanicisor->rowCount();
    $kullanicicek = $kullanicisor->fetch(PDO::FETCH_ASSOC);

    if ($say == 0) {
        header("Location:../login.php?durum=no");
    } else {

        $_SESSION['kul_isim'] = $kullanicicek['kul_isim'];
        $_SESSION['kul_mail'] = $kullanicicek['kul_mail'];
        $_SESSION['kul_id'] = $kullanicicek['kul_id'];
        $_SESSION['kul_yetki'] = $kullanicicek['kul_yetki'];
        header("Location:../index.php?durum=ok"); 
    }
    exit;
}
/***************************************************************** */ 

//  Aşağıdaki işlemlerde kullanıcının profil sayfasında bilgilerini güncellemesini sağladık. Sayfadan gelen değerleri veratabanına sorgu yazarak güncelledik
if (isset($_POST['profilkaydet'])) {

    $profilguncelle = $db->prepare("UPDATE kullanicilar SET 
            kul_isim=:kul_isim,
            kul_mail=:kul_mail,
            kul_tel=:kul_tel WHERE kul_id=:kul_id       
        ");

    $guncelle = $profilguncelle->execute(array(
        'kul_isim' => $_POST['kul_isim'],
        'kul_mail' => $_POST['kul_mail'],
        'kul_tel' => $_POST['kul_tel'],
        'kul_id' => $_SESSION['kul_id']
    ));

    if (strlen($_POST['kul_sifre']) > 0) {
        $profilguncelle = $db->prepare("UPDATE kullanicilar SET 
            kul_sifre=:kul_sifre
             WHERE kul_id=:kul_id       
        ");

        $guncelle = $profilguncelle->execute(array(
            'kul_sifre' => md5($_POST['kul_sifre']),
            'kul_id' => $_SESSION['kul_id']
        ));
    }

    if ($guncelle) {
        header("Location:../profil.php?durum=ok");
    } else {
        header("Location:../profil.php?durum=no");
    }
}

/* Aşağıdaki kodlar ile satış temsilcisinin anasayfada var olan değerlerde seçim yaparak siparişi 
tamamlamasının ardından bu sayfaya gelen değerleri işleyerek veritabanına kayıt işlemini yapıyoruz */
if (isset($_POST['siparistamamla'])) { // sipariş gönderme işlemi gerçekleşmiş mi diye kontrol ettik

    //Aşağıda ise anasayfadan gelen değerleri tek tek değişkenlere atıyoruz.
    $jeneratorid = $_POST["jeneratorid"];
    $motorid = $_POST["motorid"];
    
    $motorsorgu = $db->prepare("SELECT * FROM motor WHERE motor_id=$motorid");
    $motorsorgu->execute();
    $motorcek = $motorsorgu->fetch(PDO::FETCH_ASSOC);

    $motor_marka = $motorcek["motor_marka"];
    
    $jeneratorsorgu = $db->prepare("SELECT * FROM jeneratortip WHERE jeneratorTip_id=$jeneratorid");
    $jeneratorsorgu->execute();
    $jeneratorcek = $jeneratorsorgu->fetch(PDO::FETCH_ASSOC);

    $jeneratortip_ad = $jeneratorcek["jeneratorTip_ad"];
    $guc    = $_POST["guc"];
    $fan    = $_POST["fan"];    
    $hortum = $_POST["hortum"];
    $kullanici_adi = $_POST["kullanici_adi"];   
    if($fan==""){
        $fan = "Standart Kalite";
    }
    if($hortum==""){
        $hortum = "Standart Kalite";
    }
    // Rastgele müşteri numarası oluşturuyoruz.
    $musteri_referans_numarasi = rand(1000,2000);

    // Değişkenlere atadığımız değerleri veritabanı sorgusu yazarak veritabanına kaydediyoruz
   $siparisekle = $db->prepare("INSERT INTO siparisler SET
        motor_id=:motor_id,
        kullanici_adi=:kullanici_adi,
        musteri_referans_numarasi=:musteri_referans_numarasi,
        jeneratorTip_ad=:jeneratorTip_ad,
        motor_marka=:motor_marka,
        guc=:guc,
        fan=:fan,
        hortum=:hortum
   ");

   $kaydet = $siparisekle->execute(array(
        'motor_id'                  => $motorid,
        'kullanici_adi'             => $kullanici_adi,
        'musteri_referans_numarasi' => $musteri_referans_numarasi,  
        'jeneratorTip_ad'           => $jeneratortip_ad,
        'motor_marka'               => $motor_marka,
        'guc'                       => $guc,
        'fan'                       => $fan,
        'hortum'                    => $hortum
   ));
   if ($kaydet) {
        header("Location:../index.php?durum=ok");
    } else {
        header("Location:../index.php?durum=no");
    }
}

if(isset($_GET["sil"])){
    $siparis_id = $_GET["siparis_id"];

    $silsorgusu = $db->prepare("UPDATE siparisler SET 
        siparis_durumu=:siparis_durumu
     WHERE siparis_id = $siparis_id");
    $sil = $silsorgusu->execute(array(
        'siparis_durumu' => "PASIF"
    ));

    if($sil){
        header("Location:../index.php?durum=ok");
    }else{
        header("Location:../index.php?durum=no");
    }
}

if(isset($_POST["kullaniciekle"])){
    $kullaniciekle = $db->prepare("INSERT INTO kullanicilar SET
        kul_isim=:kul_isim,
        kul_mail=:kul_mail,
        kul_tel=:kul_tel,
        kul_yetki=:kul_yetki
    ");
    $ekle = $kullaniciekle->execute(array(
        'kul_isim' => $_POST["kul_isim"],
        'kul_mail' => $_POST["kul_mail"],
        'kul_tel' => $_POST["kul_tel"],
        'kul_yetki' => $_POST["kul_yetki"],
    ));

    if ($ekle) {
        header("Location:../kullanicilar.php?ekle=ok");
        exit;
    } else {
        header("Location:../kullanicilar.php?ekle=no");
        exit;
    }

}

if (isset($_POST['kullaniciguncelle'])) {
    $gelenid = $_POST['kul_id'];
    $kullanicisor = $db->prepare("UPDATE kullanicilar SET 
            kul_isim=:kul_isim,
            kul_mail=:kul_mail,
            kul_tel=:kul_tel,
            kul_yetki=:kul_yetki
            WHERE kul_id=:kul_id
        ");
    $guncelle = $kullanicisor->execute(array(
        'kul_isim' => $_POST['kul_isim'],
        'kul_mail' => $_POST['kul_mail'],
        'kul_tel' => $_POST['kul_tel'],
        'kul_yetki' => $_POST['kul_yetki'],
        'kul_id' => $gelenid
    ));

    if ($guncelle) {
        header("Location:../kullanicilar.php?durum=ok");
        exit;
    } else {
        header("Location:../kullanicilar.php?durum=no");
        exit;
    }
}

if(isset($_POST["kullanicisil"])){
    $gelenid = $_POST["kul_id"];
    $kullanicisor = $db->prepare("DELETE FROM kullanicilar WHERE kul_id = $gelenid");
    $sil = $kullanicisor->execute();

    if ($sil) {
        header("Location:../kullanicilar.php?sil=ok");
        exit;
    } else {
        header("Location:../kullanicilar.php?sil=no");
        exit;
    }
}

if(isset($_GET["durum"])){
    $siparisid = $_GET["siparis_id"];
    $siparissorgu = $db->prepare("UPDATE siparisler SET 
        siparis_durumu=:siparis_durumu
        WHERE siparis_id = $siparisid
    ");
    if($_GET["durum"]=="AKTIF"){
        $guncelle = $siparissorgu->execute(array(
            'siparis_durumu' => "PASIF"
        ));
    }else if($_GET["durum"]=="PASIF"){
        $guncelle = $siparissorgu->execute(array(
            'siparis_durumu' => "AKTIF"
        ));
    }
    if ($guncelle) {
        header("Location:../siparisler.php?durum=ok");
        exit;
    } else {
        header("Location:../siparisler.php?durum=no");
        exit;
    }
    
}

