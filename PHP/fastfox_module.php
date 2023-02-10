<?php 
class FastFox_Foxdim_Module {
    private $KEY_ARR=[
        "PRIVATE_KEY"=>"",
        "PUBLIC_KEY"=>"",
        "API_URL"=>""
    ];
   
    function __construct($arr) {
        
       $this->KEY_ARR["PRIVATE_KEY"]=$arr["PRIVATE_KEY"];
       $this->KEY_ARR["PUBLIC_KEY"]=$arr["PUBLIC_KEY"];
       $this->KEY_ARR["API_URL"]=$arr["API_URL"];
    }
    
    function test_connection()
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FJWT"=>["REQUEST"=>"test_connection"]];
        $FJWT=$this->FJWT_encode($PAYLOAD_ARR);
        $response_FJWT=$this->Api_Request($FJWT);
        $FJWT_decode=$this->FJWT_decode($response_FJWT);
        return $FJWT_decode;
    }
    
    
    function show_packages($barcodes) //barcode1,barcode2,barcode3 vs vs.
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FJWT"=>["REQUEST"=>"show_packages","BARCODES"=>$barcodes]];
        $FJWT=$this->FJWT_encode($PAYLOAD_ARR);
        $response_FJWT=$this->Api_Request($FJWT);
        $FJWT_decode=$this->FJWT_decode($response_FJWT);
        return $FJWT_decode;
    }
    
    function show_last_packages($limit=5) //max 1000 packages
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FJWT"=>["REQUEST"=>"show_last_packages","LIMIT"=>$limit]];
        $FJWT=$this->FJWT_encode($PAYLOAD_ARR);
        $response_FJWT=$this->Api_Request($FJWT);
        $FJWT_decode=$this->FJWT_decode($response_FJWT);
        return $FJWT_decode;
    }
    function create_package($package_arr)
    {
        
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FJWT"=>["REQUEST"=>"create_package","PACKAGE"=>$package_arr]];
        $FJWT=$this->FJWT_encode($PAYLOAD_ARR);
        $response_FJWT=$this->Api_Request($FJWT);
        $FJWT_decode=$this->FJWT_decode($response_FJWT);
        return $FJWT_decode;
        
    }
    function delete_packages($barcodes)
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FJWT"=>["REQUEST"=>"delete_packages","BARCODES"=>$barcodes]];
        $FJWT=$this->FJWT_encode($PAYLOAD_ARR);
        $response_FJWT=$this->Api_Request($FJWT);
        $FJWT_decode=$this->FJWT_decode($response_FJWT);
        return $FJWT_decode;
    }

    
  
    function get_utc($format='Y-m-d H:i:s')
    {
    $dt = new DateTime('now', new DateTimeZone('UTC'));
    return $dt->format($format);
    }
    
    function Api_Request($PostParams=null)
    {
    $headers = [
        'User-Agent: FASTFOX-API',
        'Content-Type: application/json;charset=utf-8'
    ];
    
    $ch = curl_init($this->KEY_ARR["API_URL"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt($ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );

    if($PostParams!=null){
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $PostParams);//http_build_query
    }
    $output = curl_exec($ch)??"";
    $httpcode = curl_getINFO($ch, CURLINFO_HTTP_CODE)??"500";
    $contentType = curl_getINFO($ch, CURLINFO_CONTENT_TYPE)??"text/html; charset=UTF-8";
    curl_close($ch); 
   
    if($httpcode=="200")
    {
        return $output;
    }
    $return_arr=["STATUS"=>"FAIL"];
    return $return_arr;

    }
    
     function b64Encode($text)
    {
        return base64_encode($text);
    }
    function b64Decode($text)
    {
        return base64_decode($text);
    }
    function jsonDecode($text)
    {
        return json_decode($text,true);
    }
    function jsonEncode($text)
    {
        return json_encode($text,JSON_UNESCAPED_UNICODE);
    }
    
    function FJWT_encode($PAYLOAD_ARR)
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR["FJWT"]=$this->encode($this->get_utc()).".".$KEY_ARR["PUBLIC_KEY"].".".$this->encode($this->jsonEncode($PAYLOAD_ARR["FJWT"]));
        $PAYLOAD_ARR_JSON=$this->jsonEncode($PAYLOAD_ARR);
        return $PAYLOAD_ARR_JSON;
    }
    function FJWT_decode($FJWT_JSON)
    {
        $KEY_ARR=$this->KEY_ARR;
        $FJWT=$FJWT_JSON;
        if(!is_array($FJWT))$FJWT=$this->jsonDecode($FJWT_JSON);
        $FJWT_ARR=explode(".",$FJWT["FJWT"]);
        $PAYLOAD=$this->jsonDecode($this->decode($FJWT_ARR[2]));
        $FJWT["FJWT"]=$PAYLOAD;
        return $FJWT;
    }
    private $ciphering = "AES-128-CBC";
    function encode($text)
    {
        $KEY_ARR=$this->KEY_ARR;
    	$ciphering=$this->ciphering;
        $PUBLIC_KEY=$KEY_ARR["PUBLIC_KEY"];
        $PRIVATE_KEY=$KEY_ARR["PRIVATE_KEY"];
        $iv_length = openssl_cipher_iv_length($ciphering);
        $encryption = openssl_encrypt($text, $ciphering,
        $PUBLIC_KEY, 0, $PRIVATE_KEY);
        return $encryption??null;
    }
    
    function decode($text)
    {
        $KEY_ARR=$this->KEY_ARR;
        $ciphering=$this->ciphering;
        $PUBLIC_KEY=$KEY_ARR["PUBLIC_KEY"];
        $PRIVATE_KEY=$KEY_ARR["PRIVATE_KEY"];
        $iv_length = openssl_cipher_iv_length($ciphering);
        $decryption=openssl_decrypt ($text, $ciphering, 
        $PUBLIC_KEY, $options, $PRIVATE_KEY);
        return $decryption??null;
    }
    
     function fox_toUpper($string)
    {
        $string=trim($string);
        $degis 	= array("I","Ğ","Ü","Ş","İ","Ö","Ç");
		$bul  = array("ı","ğ","ü","ş","i","ö","ç");
		$newstring	= str_replace($bul, $degis, $string);
		//$newstring	= strtoupper($newstring);
        $newstring = mb_convert_case($newstring, MB_CASE_UPPER, "UTF-8");
        return $newstring;
    }
    function fox_toLower($string)
    {
        $bul 	= array("I","Ğ","Ü","Ş","İ","Ö","Ç");
		$degis  = array("ı","ğ","ü","ş","i","ö","ç");
		$newstring	= str_replace($bul, $degis, $string);
		//$newstring	= strtoupper($newstring);
        $newstring = mb_convert_case($newstring, MB_CASE_LOWER, "UTF-8");
        return $newstring;
    }
    
}
?>
