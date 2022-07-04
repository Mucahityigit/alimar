<?php 
/* Bu dosyada admin panele direkt ulaşımı engellemek için bir kontrol yapıyoruz. Böylece kullanıcının girişi 
sistem üzerinde aktif değilse kullanıcıyı direkt login sayfasına yönlendiriyor.*/
    function oturumkontrol(){
        if(!isset($_SESSION['kul_mail']) OR !isset($_SESSION['kul_isim']) OR !isset($_SESSION['kul_id'])){
            session_destroy();
            header("Location:login.php");
            exit;
        }
    }
?>