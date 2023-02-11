<?php 
include_once("FPJA_Module.php");
$API_INFORMATION_ARR=[ //DEFAULT VALUES=DEMO
    "PRIVATE_KEY"=>"1111111111111111",
    "PUBLIC_KEY"=>"2222222222222222",
    "API_URL"=>"https://fastfoxdemo.foxdim.com/Apps/1/PublicApi/Firma/Rest/Api.php",
    ];
$FPJA_Module = new FPJA($API_INFORMATION_ARR);

$process=$_GET["process"]??"test_connection";
//$process="show_packages";
//$process="show_last_packages";
//$process="create_package";
//$process="delete_packages";

switch ($process) {
    case 'test_connection':
        echo "<h3>Connection Test</h3>";
        $testconnection=$FPJA_Module->test_connection();
        print_r($testconnection);die;
        break;
        
        case 'show_packages':
        echo "<h3>List Packages for barcodes</h3>";
        $get_packages=$FPJA_Module->show_packages("924819810384");
        print_r($get_packages);die;
        break;
        
        case 'show_last_packages':
        echo "<h3>List Last Packages </h3>"; 
        $get_last_packages=$FPJA_Module->show_last_packages(1);
        print_r($get_last_packages);die;
        break;
        
        case 'create_package':
        echo "<h3>Create Package </h3>";
        /*
        Required Parameters
        ---------------------
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
            "product_name"=>"test product",
            "product_price"=>"100,00", // 100,00 or 100.00 or 10000 | (For 100TL)
            "recipient_fname"=>"lorem ipsum",
            "recipient_identification"=>"00000000000", //11 Length or null default=00000000000
            "recipient_phone"=>"0000000000",//5xxxxxxxxx 10 Length
            "recipient_mail"=>"info.foxdim@gmail.com",
            "recipient_country"=>"TR",
            "recipient_province"=>"İSTANBUL",//The system does not allow typos.
            "recipient_district"=>"MALTEPE",
            "recipient_address"=>"lorem ipsum cad. lorem ipsum sk. no:1 d:1",
            "recipient_address_description"=>"",//detailed address description
            "package_weight"=>"0",
            "package_deci"=>"0",//default =0
            "payment_type"=>"1",//1=Credit card | 2=Cash payment | 3=Without payment
            "cargo_type"=>"1",  //1=Sender pay  | 2=Buyer pay
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
?>
