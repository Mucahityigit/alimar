<?php include "header.php";?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="font-weight-bold text-primary">Kitap Ekle</h5>
                </div>
                <div class="card-body">
                    <form action="islemler/islem.php" method="POST">
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı İsim Soyisim</label>
                                <input type="text" name="kul_isim" class="form-control">
                            </div>
                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı Mail</label>
                                <input type="text" name="kul_mail" class="form-control">
                            </div>
                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı Telefon</label>
                                <input type="text" name="kul_tel" class="form-control">
                            </div>
                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-6 form-group">
                                <label>Kullanıcı Yetki</label>
                                <select class="form-select" aria-label="Default select example" name="kul_yetki">
                                    <option selected>Yetki Seçiniz</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>                       
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="kullaniciekle">Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>