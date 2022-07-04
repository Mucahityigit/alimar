<?php  include "header.php"; // header.php dosyasını buraya aktardık.

// Jenerator tip sorgusu için veritabanına sorgu yazıp dönen değerleri çekiyoruz.
$jeneratortipsorgusu = $db->prepare("SELECT * FROM jeneratorTip");
$jeneratortipsorgusu->execute();
$jeneratortipler = $jeneratortipsorgusu->fetchAll(PDO::FETCH_ASSOC);

$siparissorgusu = $db->prepare("SELECT * FROM siparisler WHERE siparis_durumu='AKTIF' ORDER BY siparis_tarih DESC");
$siparissorgusu->execute();
$siparisler = $siparissorgusu->fetchAll(PDO::FETCH_ASSOC);
$say = 1;
?>
<div id="Anasayfa">
        <?php  // Aşağıda if yapısı ile kullanıcı yetkisini sorguluyoruz ve yetki durumuna göre kullanıcının karşısına ekran çıkartıyoruz
            if($_SESSION["kul_yetki"]==3 || $_SESSION["kul_yetki"]==2){?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="font-weight-bold text-primary">Satış Bölümü</h5>
                                </div>
                                <div class="card-body">
                                    <form action="islemler/islem.php" method="POST">
                                        <div class="form-row">
                                            <div class="col-md-3 form-group">
                                                <label>Lütfen Jeneratör Tipini Seçiniz</label>
                                                <!-- Seçenekler arasında geçiş yaptıkça seçilen seçeneğe göre diğer seçenekleri "motor markası vb." getirmek için 
                                                jeneratortipsecimi() adında bir fonksiyon tanımladık ve sayfanın en altında bu fonksiyonu yazacağız -->
                                                <select id="jeneratortip" class="form-select" aria-label="Default select example" onchange="jeneratortipsecimi(this.value)" name="jeneratorid">
                                                    <option>Seçenekler</option>
                                                    <!-- Yukarıda yaptığımız sorgudan dönen değerleri foreach ile döngü kullanarak aşağıda yazdırıyoruz -->
                                                    <?php foreach($jeneratortipler as $jeneratortip){?>
                                                        <option value="<?php echo $jeneratortip["jeneratorTip_id"] ?>"><?php echo $jeneratortip["jeneratorTip_ad"] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label>Lütfen Motor Markasını Seçiniz</label>
                                                <!-- Seçenekler arasında geçiş yaptıkça seçilen seçeneğe göre diğer seçenekleri "güç aralığı vb." getirmek için 
                                                motormarka() adında bir fonksiyon tanımladık ve sayfanın en altında bu fonksiyonu yazacağız -->
                                                <select id="motor" class="form-select" aria-label="Default select example" onchange="motormarka(this.value)" name="motorid">
                                                        <option value="BOS">Seçenekler</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label>Lütfen Güç Değerini Seçiniz</label>
                                                <select id="guc" class="form-select" aria-label="Default select example" name="guc">
                                                        <option value="BOS">Seçenekler</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label>Lütfen Frekans Değerini Seçiniz</label>
                                                <select id="guc" class="form-select" aria-label="Default select example" name="frekans">
                                                        <option value="BOS">Seçenekler</option>
                                                        <option value="50">50</option>
                                                        <option value="60">60</option>
                                                </select>
                                            </div>
                                        </div>   
                                        <div id="siparisdetayalani">
                                            <div class="form-row">
                                                <div class="col-md-3 form-group">
                                                    <label>Lütfen Fan Tipini Seçiniz</label>
                                                    <select id="guc" class="form-select" aria-label="Default select example" name="fan">
                                                            <option value="Standart Kalite">Standart Kalite</option>
                                                            <option value="Yüksek Kalite">Yüksek Kalite</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>Lütfen Hortum Tipini Seçiniz</label>
                                                    <select id="guc" class="form-select" aria-label="Default select example" name="hortum">
                                                            <option value="Standart Kalite">Standart Kalite</option>
                                                            <option value="Yüksek Kalite">Yüksek Kalite</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="kullanici_adi" value="<?php echo $_SESSION["kul_isim"]?>">
                                            </div>   
                                        </div>
                                        <!-- Siparişi tamamla butonuna tıklayarak formda aldığımız değerleri işlem.php ye yolluyoruz 
                                        e orada sipariş ekleme işlemlerini yapıyoruz -->                                       
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Siparişi Tamamla</button>
                                        
                                        <!-- Sipariş detay butonu ile detaylandırmak istediğimiz sipariş için yeni bir seçenek alanı açılıyor. --> 
                                        <button id="siparisdetay" type="button" class="btn btn-warning">Siparişi Detaylandır</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">UYARI !</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Siparişiniz tamamlanacak. Emin misiniz?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">İptal Et</button>
                                                <button id="siparistamamla" type="submit" name="siparistamamla" class="btn btn-primary">Siparişi Kaydet</button>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#silModal">Sil</button></td>
                        <div class="modal fade" id="silModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">UYARI !</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Siparişiniz silinecektir. Emin misiniz?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">İptal Et</button>
                                    <a href="islemler/islem.php?sil&siparis_id=<?php echo $siparis["siparis_id"] ?>" class="btn btn-danger">Sil</a>
                                </div>
                            </div>
                        </div>
                    </tr>
                    <?php $say++; } ?>    
                </tbody>
            </table>

                <!-- Aşağıda if yapısı ile kullanıcı yetkisini sorguluyoruz ve yetki durumuna göre kullanıcının karşısına ekran çıkartıyoruz  -->                                             
        <?php }else if($_SESSION["kul_yetki"]==1){?>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Sıra Numarası</th>
                        <th scope="col">Müşteri Numarası</th>
                        <th scope="col">Jeneratör Tipi</th>
                        <th scope="col">Motor Markası</th>
                        <th scope="col">Güç</th>
                        <th scope="col">Fan</th>
                        <th scope="col">Hortum</th>
                        <th scope="col">Siparis Tarihi</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($siparisler as $siparis){ ?>
                            <tr>
                                <th><?php echo $say ?></th>
                                <td><?php echo $siparis["musteri_referans_numarasi"]; ?></td>
                                <td><?php echo $siparis["jeneratorTip_ad"]; ?></td>
                                <td><?php echo $siparis["motor_marka"]; ?></td>
                                <td><?php echo $siparis["guc"]; ?></td>
                                <td><?php echo $siparis["fan"]; ?></td>
                                <td><?php echo $siparis["hortum"]; ?></td>
                                <td><?php echo $siparis["siparis_tarih"]; ?></td>
                                <td><a href="fpdf/recete.php?motor_id=<?php echo $siparis["motor_id"] ?>&guc=<?php echo $siparis["guc"]?>&siparis_id=<?php echo $siparis["siparis_id"] ?>" target="_blank" class="btn btn-success">PDF</a></td>
                            </tr>
                    <?php } ?> 
                </tbody>
            </table>
        <?php } ?>    
</div>

<?php include "footer.php"; ?>

<script type="text/javascript">
    var jeneratoriddegeri;
    // Yukarıda jenerator seçenekleri kısmında oluşturduğumuz jeneratortipsecimi() fonksiyonunu yazıyoruz. 
    // bu fonksiyon ile seçilen seçenekten aldığımız değeri deneme.php sayfasına yolluyoruz ve orada yaptığımız 
    // sorgu ile dönen değeri buraya çekip sayfaya yazdırıyoruz.
    function jeneratortipsecimi(data){
        jeneratoriddegeri = data;
        let motor = document.getElementById("motor");
        let istek = new XMLHttpRequest();
        istek.open('GET','http://localhost/alimar/admin/deneme.php?jeneratorid='+data,"TRUE");      
        istek.send();

        istek.onreadystatechange = function(){
            if(istek.readyState == 4 && istek.status == 200){
                motor.innerHTML = istek.responseText;
            }
        }
    }
    // Yukarıda jenerator seçenekleri kısmında oluşturduğumuz motormarka() fonksiyonunu yazıyoruz. 
    // bu fonksiyon ile seçilen seçenekten aldığımız değeri deneme.php sayfasına yolluyoruz ve orada yaptığımız 
    // sorgu ile dönen değeri buraya çekip sayfaya yazdırıyoruz.
    function motormarka(data){
        let guc = document.getElementById("guc");
        let istek = new XMLHttpRequest();
        istek.open('GET','http://localhost/alimar/admin/deneme.php?motorid='+data,"TRUE");      
        istek.send();

        istek.onreadystatechange = function(){
            if(istek.readyState == 4 && istek.status == 200){
                 guc.innerHTML = istek.responseText;
            }
        }
    }

    let siparisdetaybtn = document.getElementById("siparisdetay");
    let siparisdetayalani = document.getElementById("siparisdetayalani");
    // Aşağıda sipariş detay butonuna tıkladığımızda detay seçeneklerinin açılması için gereken kodları yazdık
    siparisdetaybtn.addEventListener("click",function(){
        siparisdetayalani.style.display = "block";
    });
</script>

