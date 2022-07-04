<?php // Admin panelden kullanıcı çıkışı yapıldığında aşağıdaki işlemler devreye giriyor
    session_start();
    session_destroy();
    header("Location:../login.php");
?>