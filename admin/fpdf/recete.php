<?php         
    include "../islemler/baglan.php";
    require('fpdf.php');


    function turkce($k){
        return iconv('utf-8','iso-8859-9',$k);
    }
    if(isset($_GET["siparis_id"])){
        $siparisid = $_GET["siparis_id"];
        $siparissorgu = $db->prepare("SELECT * FROM siparisler WHERE siparis_id=$siparisid");
        $siparissorgu->execute();
        $siparis = $siparissorgu->fetch(PDO::FETCH_ASSOC);
    }
    class PDF extends FPDF
    {
        // Sayfa ba�l���
        function Header()
        {      
            // Logo ayarlan�r
            $this->Image('../logo.jpg',10,5,30);
            
            $this->AddFont('arial_tr',"",'arial_tr.php');
            $this->SetFont('Arial_tr','',11); 

            // Yaz� rengi ayarlan�r
            // X koordinat�
            $x = $this->GetX();
            // Y Koordinat� 
            $y = $this->GetY();
            // D�z �izgi �izilir
            
            // 5 pixel a�a��da yeni sat�ra ge� 
            $this->Ln(10);
        }

        // Sayfa Alt�
        function Footer()
        {
            // 15 p�xel sayfa alt�ndan yukar�da ba�la
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Sayfa Numaras�
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
        
        // Renkli tablo
        function FancyTable($header, $data)
        {
            // Renkler ve y�kseklikler ayarlan�r
            $this->SetFillColor(51,153,255);
            $this->SetTextColor(255);
            $this->SetLineWidth(.3);

            // Sat�r�n X ve Y koordinatlar� al�n�r
            $posX = $this->GetX();
            $posY = $this->GetY();
            
            // Yaz� fontu ayarlan�r
            $this->AddFont('arial_tr',"",'arial_tr.php');
            $this->SetFont('Arial_tr','',12); 

            // Tablonun en �st sat�r� (ba�l�k k�sm�)
            // Her kolonun pixel boyutu ayarlan�r
            $w = array(31, 100, 30, 30);
            for($i=0;$i<count($header);$i++)
            {          
                $this->MultiCell($w[$i],6,$header[$i],1,'C',true);
                // Bir sonraki h�crenin X koordinat� bir �nceki kolonun pixel say�s� eklenerek hesaplan�r
                $posX +=  $w[$i];
                
                $this->SetXY($posX, $posY);
            }
                
            $this->Ln(12);
            
            $this->SetFillColor(224,235,255);
            $this->SetTextColor(0);

            // Bilgiler
            $fill = false;
            foreach($data as $row)
            {
                $this->Cell($w[0],6,$row[0],'LR',0,'C',$fill);
                $this->Cell($w[1],6,$row[1],'LR',0,'C',$fill);
                $this->Cell($w[2],6,$row[2],'LR',0,'C',$fill);
                $this->Cell($w[3],6,$row[3],'LR',0,'C',$fill);
                $this->Ln();
                $fill = !$fill;
            }
            // Sat�r kapat�l�r
            $this->Cell(array_sum($w),0,'','T');
        }  
    }
    
    // Pdf nesnesi olu�turulur
    $pdf = new PDF();
    
    // Sayfa alt�nda numaralar� g�stermek i�in kullan�l�r
    $pdf->AliasNbPages();
   
    // font ayarlan�r
    $pdf->SetFont('Arial','',12);
    
    
    // Ba�l�k i�in array olusturulur
    $header = array('Malzeme Kodu', turkce('Malzeme Açıklaması'), 'Miktar','Birim');
    
    // Bilgiler eklenir
    $data = array();
    if(isset($_GET["motor_id"])){
        $motorid = $_GET["motor_id"];
        $guc = $_GET["guc"];       
        $gucsorgu = $db->prepare("SELECT * FROM motor WHERE motor_id = $motorid");
        $gucsorgu->execute();
        $gucler = $gucsorgu->fetch(PDO::FETCH_ASSOC); 
        $gucdizisi =  explode(",",$gucler["guc"]);
        $indexdegeri = array_search($guc,$gucdizisi);
        $receteid = $indexdegeri % 5;
        $recetesorgusu = $db->prepare("SELECT * FROM recete WHERE motor_id=$motorid AND recete_id=$receteid");
        $recetesorgusu->execute();
        $receteler = $recetesorgusu->fetchAll(PDO::FETCH_ASSOC);
            // Ba�l�k i�in array olusturulur
        foreach($receteler as $recete){ 
            // Bilgiler eklenir
            $MK = $recete['malzeme_kodu'];
            $MA = $recete['malzeme_aciklama'];
            $MT = $recete['miktar'];
            $BR = $recete['birim'];
            array_push($data, array(turkce($MK), turkce($MA), turkce($MT), turkce($BR)));
        }
    }
    // sayfa eklenir
    $pdf->AddPage();
    // Ba�l�klar ve bilgiler tabloya yollan�r
    $pdf->AddFont('arial_tr',"",'arial_tr.php');
    $pdf->SetFont('Arial_tr','',11); 
    $pdf->Cell(30,5,turkce("Müşteri Referans Kodu : ") . turkce($siparis["musteri_referans_numarasi"]));
    $pdf->Cell(70);
    $pdf->Cell(30,5,turkce("Sipariş Tarihi : ") . turkce($siparis["siparis_tarih"]));
    
    $pdf->Ln(6);
    $pdf->Cell(30,5,turkce("Satış Temsilcisi : ") . turkce($siparis["kullanici_adi"]));
    $pdf->Cell(70);
    $pdf->Cell(30,5,turkce("Jeneratör tipi : "). turkce($siparis["jeneratorTip_ad"]));

    $pdf->Ln(6);
    $pdf->Cell(30,5,turkce("Motor Markası : ") . turkce($siparis["motor_marka"]));
    $pdf->Cell(70);
    $pdf->Cell(30,5,turkce("Motor Güç Değeri : "). turkce($siparis["guc"]));
    $pdf->Ln(10);


    $pdf->FancyTable($header,$data);
    
    $pdf->Ln();    
    
    $pdf->Output();     
?> 
 
