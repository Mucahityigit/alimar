<?php 
    // baglan.php dosyamızı aşağıda dosyamıza dahil ediyoruz.
    include "islemler/baglan.php";
    // anasayfadan jeneratorid diye bir değerin gelip gelmediğini kontrol ediyoruz 
    if($_GET["jeneratorid"]){
        $jeneratorid = $_GET["jeneratorid"];
        // Gelen değeri yukarıda değişkene aktardık ve aşağıda bir veritabanı sorgusu ile gelen değeri eşleştirdik
        $motorsorgu = $db->prepare("SELECT * FROM motor WHERE jeneratorTip_id = $jeneratorid");
        $motorsorgu->execute();
        $motorlar = $motorsorgu->fetchAll(PDO::FETCH_ASSOC);
        // Sorgu sonunda dönen değerleri aşağıda foreach kullanarak anasayfada yazdırıyoruz.
            echo "<option>Seçenekler</option>";
        foreach($motorlar as $motor){
            $motorid = $motor["motor_id"];
            $motormarka = $motor["motor_marka"];
            echo "<option value='$motorid'>$motormarka</option>";
        };
    }
        // anasayfadan jeneratorid diye bir değerin gelip gelmediğini kontrol ediyoruz 
    
        if($_GET["motorid"]){
            $motorid = $_GET["motorid"];
            $gucsorgu = $db->prepare("SELECT * FROM motor WHERE motor_id = $motorid");
            $gucsorgu->execute();
            $gucler = $gucsorgu->fetch(PDO::FETCH_ASSOC); 
            $gucdizisi =  explode(",",$gucler["guc"]);
            foreach($gucdizisi as $guc){
              echo  "<option value='$guc'>$guc</option>";
            }   
        }
?>