<?php include "header.php";
$kullanicisorgusu = $db->prepare("SELECT * FROM kullanicilar ORDER BY kul_yetki DESC");
$kullanicisorgusu->execute();
$kullanicilar = $kullanicisorgusu->fetchAll(PDO::FETCH_ASSOC);
$say = 1;
?>
<link rel="stylesheet" type="text/css" href="vendor/datatables/dataTables.bootstrap4.min.css">

<div class="container">
    <div class="card">
        <a href="kullanicilarekle.php" style="text-decoration: none; color:white"><button class="btn btn-primary mt-3">Ekle</button></a>
        <div class="card-header">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table id="musteritablosu" class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>İsim-Soyisim</th>
                                <th>Mail</th>
                                <th>Telefon</th>
                                <th>Yetki</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kullanicilar as $kullanici) { ?>
                                <tr>
                                    <td><?php echo $say ?></td>
                                    <td><?php echo $kullanici['kul_isim'] ?></td>
                                    <td><?php echo $kullanici['kul_mail'] ?></td>
                                    <td><?php echo $kullanici['kul_tel'] ?></td>
                                    <td><?php echo $kullanici['kul_yetki'] ?></td>
                                    <td>
                                        <form action="kullanicilarguncelle.php" method="POST">
                                            <input type="hidden" name="kul_id" value="<?php echo $kullanici['kul_id'] ?>">
                                            <button type="submit" name="guncelle" class="btn btn-success">
                                                <span class="icon text-white-60">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                            </button>
                                        </form>
                                        <form class="mx-1" action="islemler/islem.php" method="POST">
                                            <input type="hidden" name="kul_id" value="<?php echo $kullanici['kul_id'] ?>">
                                            <button type="submit" name="kullanicisil" class="btn btn-danger">
                                                <span class="icon text-white-60">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php $say++;
                            }  ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    $("#musteritablosu").DataTable();
</script>

<?php
if (isset($_GET['durum'])) {
    if ($_GET['durum'] == "ok") { ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'İşlem Başarılı',
                text: 'İşleminiz başarıyla gerçekleştirilmiştir.',
                confirmButtonText: "Tamam"
            })
        </script>

    <?php } else { ?>

        <script>
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'İşleminiz başarısız. Lütfen tekrar deneyin.',
                confirmButtonText: "Tamam"
            })
        </script>

<?php }
} ?>