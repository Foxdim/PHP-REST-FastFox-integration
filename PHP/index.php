<?php 

include_once("FPJA_Module.php");
$API_INFORMATION_ARR=[ //DEFAULT VALUES=DEMO
    "PRIVATE_KEY"=>"1111111111111111",
    "PUBLIC_KEY"=>"2222222222222222",
    "API_URL"=>"https://demo.fastfoxapp.cc/Panel/Api/PublicApi/Firma/Rest/Api.php",
    "EXPIRED_SEC"=>300,
    ];
$FPJA_Module = new FPJA_FF_API();     
$FPJA_Module->SETUP_ARR($API_INFORMATION_ARR);


$process=$_GET["process"]??"test_connection";
//$process="pk_show_packages";
//$process="pk_delete_packages";
//$process="show_packages";
//$process="show_last_packages";
//$process="delete_packages";
//$process="create_package";


switch ($process) {
    case 'test_connection':
        echo "<h3>Connection Test</h3>";
        $testconnection=$FPJA_Module->test_connection();
        print_r($testconnection);die;
        break;

        case 'pk_show_packages':
        echo "<h3>List Packages for product keys</h3>";
        $get_packages=$FPJA_Module->pk_show_packages("1112,111"); //pk numbers pk1,pk2,pk3  pk your product keys. // 0 last package info
        print_r($get_packages);die;
        break;

        case 'pk_delete_packages':
        echo "<h3>Canceled Packages for product keys</h3>";
        $get_packages=$FPJA_Module->pk_delete_packages("1112"); //pk numbers pk1,pk2,pk3  pk your product keys. // 0 last package info
        print_r($get_packages);die;
        break;
        
        case 'show_packages':
        echo "<h3>List Packages for barcodes</h3>";
        $get_packages=$FPJA_Module->show_packages("924819810384"); //barcode1,barcode2,barcode3 vs vs.
        print_r($get_packages);die;
        break;

        case 'delete_packages':
        echo "<h3>Canceled Packages for barcodes</h3>";
        $get_packages=$FPJA_Module->show_packages("924819810384"); //barcode1,barcode2,barcode3 vs vs. Only package status:1 waiting (bekliyor)
        print_r($get_packages);die;
        break;
        
        case 'show_last_packages':
        echo "<h3>List Last Packages </h3>"; 
        $get_last_packages=$FPJA_Module->show_last_packages(1); //limit
        print_r($get_last_packages);die;
        break;
        
        case 'create_package':
        echo "<h3>Create Package </h3>";
        /*
        Required Parameters
        ---------------------
        product_key //product key your barcode or order number or a custom id 1 can be defined for each package.
        product_price
        recipient_fname
        recipient_phone
        recipient_country
        recipient_province
        recipient_district
        recipient_address
        payment_type
        cargo_type
        */
        $package_arr=[
            "product_key"=>"1112", //default:null | customer product key-barcode | varchar(50)
            "product_price"=>"100,00", // 100,00 or 100.00 or 10000 | (For 100TL)
            "product_name"=>"test product", //(varchar(255))
            "recipient_fname"=>"lorem ipsum", //(varchar(100))
            "recipient_identification"=>"00000000000", //11 Length or null default=00000000000 (varchar(30))
            "recipient_phone"=>"0000000000",//5xxxxxxxxx 10 Length //(varchar(15))
            "recipient_mail"=>"info.foxdim@gmail.com", //(varchar(255))
            "recipient_country"=>"TR", //varchar(2)
            "recipient_province"=>"İSTANBUL",//The system does not allow typos. (varchar(255))
            "recipient_district"=>"MALTEPE", //(varchar(255))
            "recipient_address"=>"lorem ipsum cad. lorem ipsum sk. no:1 d:1", //(varchar(255))
            "recipient_address_description"=>"",//detailed address description (text)
            "package_weight"=>"0", //(int)
            "package_deci"=>"0",//default =0 (tinyint)
            "payment_type"=>"1",//1=Credit card | 2=Cash payment | 3=Without payment (tinyint)
            "cargo_type"=>"1",  //1=Sender pay  | 2=Buyer pay (tinyint)
            "private_text_1"=>null, //It is used if the cargo company needs it. (varchar(150))
            "private_text_2"=>null, //It is used if the cargo company needs it. (varchar(150))
            "private_text_3"=>null, //It is used if the cargo company needs it. (varchar(150))
            "private_text_4"=>null  //It is used if the cargo company needs it. (varchar(150))
            ];
        $created_package=$FPJA_Module->create_package($package_arr);
        print_r($created_package);die;
        break;
        
        case 'delete_packages':
        echo "<h3>Delete Packages for barcodes</h3>";
        $delete_packages=$FPJA_Module->delete_packages("567256076322");
        print_r($delete_packages);die;
        break;
        
}
/*
// Package Status //
1:Bekliyor
2:Kargo Girişi Yapıldı
3:Kargo Kaydı Bekliyor
4:Kargo Girişi Yapıldı -API
5:Dağıtıma Çıkarıldı
6:Tekrar Dağıtıma Çıkarılacak
7:İade Talep Edildi
8:Değişim Talep Edildi
9:Yolda
10:Şubede
11:Teslimat Yapıldı
12:İade Edildi
13:Aktarmada
14:Dağıtım Şubesinde
15:Değişim-İade Edildi
16:Şubede Bekliyor
-1:İptal Edildi

// Cargo Types //
1:Gönderici Ödemeli
2:Alıcı Ödemeli

// Payment Types //
1:Kapıda Kredi Kartı
2:Kapıda Kredi Kartı
3:Tahsilatsız
4:Sanal Ödeme

// Shipment Types //
1:Normal Gönderi
2:Express Gönderi
3:Aynı Gün Teslimatlı Gönderi
4:Aracı Gönderi
*/
?>
