<?php include "header.php"; 
$siparissorgusu = $db->prepare("SELECT * FROM siparisler ORDER BY siparis_tarih DESC");
$siparissorgusu->execute();
$siparisler = $siparissorgusu->fetchAll(PDO::FETCH_ASSOC);
$say = 1;
?>

<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Sıra Numarası</th>
            <th scope="col">Satış Uzmanı</th>
            <th scope="col">Müşteri Numarası</th>
            <th scope="col">Jeneratör Tipi</th>
            <th scope="col">Motor Markası</th>
            <th scope="col">Güç</th>
            <th scope="col">Fan</th>
            <th scope="col">Hortum</th>
            <th scope="col">Siparis Tarihi</th>
            <th scope="col">Siparis Durumu</th>
            <th scope="col">İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($siparisler as $siparis){?>
        <tr>
            <th><?php echo $say ?></th>
            <td><?php echo $siparis["kullanici_adi"]?></td>
            <td><?php echo $siparis["musteri_referans_numarasi"]?></td>
            <td><?php echo $siparis["jeneratorTip_ad"]?></td>
            <td><?php echo $siparis["motor_marka"]?></td>
            <td><?php echo $siparis["guc"]?></td>
            <td><?php echo $siparis["fan"]?></td>
            <td><?php echo $siparis["hortum"]?></td>
            <td><?php echo $siparis["siparis_tarih"]?></td>
            <td><?php echo $siparis["siparis_durumu"]?></td>
            <td>
                <?php if($siparis["siparis_durumu"]=="AKTIF"){?><a href="islemler/islem.php?siparis_id=<?php echo $siparis["siparis_id"] ?>&durum=AKTIF" class="btn btn-secondary">Pasif</a><?php } ?>
                <?php if($siparis["siparis_durumu"]=="PASIF"){?><a href="islemler/islem.php?siparis_id=<?php echo $siparis["siparis_id"] ?>&durum=PASIF" class="btn btn-success">Aktif</a><?php } ?>
            </td>                
        </tr>
        <?php $say++; } ?>    
    </tbody>
</table>

<?php include "footer.php"; ?>