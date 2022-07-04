<?php include "header.php";
$gelenid = $_POST['kul_id'];
$kullanicisorgusu = $db->prepare("SELECT * FROM kullanicilar WHERE kul_id=$gelenid");
$kullanicisorgusu->execute();
$kullanici = $kullanicisorgusu->fetch(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="font-weight-bold text-primary">Kitap Güncelle</h5>
                </div>
                <div class="card-body">
                    <form action="islemler/islem.php" method="POST">
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı Adı</label>
                                <input type="text" name="kul_isim" class="form-control" value="<?php echo $kullanici['kul_isim'] ?>">
                            </div>
                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı Mail</label>
                                <input type="text" name="kul_mail" class="form-control" value="<?php echo $kullanici['kul_mail'] ?>">
                            </div>
                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı Telefon</label>
                                <input type="text" name="kul_tel" class="form-control" value="<?php echo $kullanici['kul_tel'] ?>">
                            </div>
                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı Yetki</label>
                                <select class="form-select" aria-label="Default select example" name="kul_yetki">
                                    <option selected>Yetki Seçiniz</option>
                                    <option value="1" <?php if($kullanici["kul_yetki"]==1){echo "selected";} ?>>1</option>
                                    <option value="2" <?php if($kullanici["kul_yetki"]==2){echo "selected";} ?>>2</option>
                                    <option value="3" <?php if($kullanici["kul_yetki"]==3){echo "selected";} ?>>3</option>
                                </select>                       
                            </div>
                        </div>
                        <input type="hidden" name="kul_id" value="<?php echo $gelenid ?>">
                        <button type="submit" class="btn btn-primary" name="kullaniciguncelle">Güncelle</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>