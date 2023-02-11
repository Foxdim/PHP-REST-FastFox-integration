<?php 
class FPJA {
    private $KEY_ARR=[
        "PRIVATE_KEY"=>"",
        "PUBLIC_KEY"=>"",
        "API_URL"=>"",
        "EXPIRED_SEC"=>300,
    ];
   
    function __construct($arr) {
        
       $this->KEY_ARR["PRIVATE_KEY"]=$arr["PRIVATE_KEY"];
       $this->KEY_ARR["PUBLIC_KEY"]=$arr["PUBLIC_KEY"];
       $this->KEY_ARR["API_URL"]=$arr["API_URL"];
    }
    
    function test_connection()
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"test_connection"]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
        echo($this->jsonEncode($PAYLOAD_ARR));
        echo "<hr>";
        echo($this->jsonEncode($this->FPJA_decode($FPJA))); //şifresiz
        echo "<hr>";
        echo($FPJA);//şifreli
        echo "<hr><hr><hr>";
        echo($response_FPJA);
        echo "<hr>";
        echo($this->jsonEncode($FPJA_decode));
        echo "<hr>";die;
        
        return $FPJA_decode;
    }
    
    
    function show_packages($barcodes) //barcode1,barcode2,barcode3 vs vs.
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"show_packages","BARCODES"=>$barcodes]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
        return $FPJA_decode;
    }
    
    function show_last_packages($limit=5) //max 1000 packages
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"show_last_packages","LIMIT"=>$limit]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
        return $FPJA_decode;
    }
    function create_package($package_arr)
    {
        
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"create_package","PACKAGE"=>$package_arr]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
        return $FPJA_decode;
        
    }
    function delete_packages($barcodes)
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"delete_packages","BARCODES"=>$barcodes]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
        return $FPJA_decode;
    }

    
  
    function get_utc($format='Y-m-d H:i:s')
    {
    $dt = new DateTime('now', new DateTimeZone('UTC'));
    return $dt->format($format);
    }
    
    function Api_Request($url,$PostParams=null,$header=null)
    {
    $headers = [
        'User-Agent: FASTFOX-API',
        'Content-Type: application/json;charset=utf-8'
    ];
    if($header!=null)
    {
        $headers=$header;
    }
    
     $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    
    if($PostParams!=null){
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $PostParams);
    }
    
    $output = curl_exec($ch)??"";
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE)??"500";
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE)??"text/html; charset=UTF-8";
    curl_close($ch); 
       
        
     if($httpcode=="200")
    {
        return $output;
    }
    $return_arr=["STATUS"=>"FAIL"];
    return $return_arr;
    }
   
   function HOOK_FPJA_POST()
    {
      $POST_ARG_ORG=file_get_contents('php://input');
      $POST_ARG=$this->jsonDecode($POST_ARG_ORG);
      return $POST_ARG;
    }
    function update_keys($arr) {
       $this->KEY_ARR["PRIVATE_KEY"]=$arr["PRIVATE_KEY"];
       $this->KEY_ARR["PUBLIC_KEY"]=$arr["PUBLIC_KEY"];
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
    
    function FPJA_encode($PAYLOAD_ARR)
    {
        $KEY_ARR=$this->KEY_ARR;
        $RETURN_ARR=$PAYLOAD_ARR;
        if(!is_array($PAYLOAD_ARR["FPJA"]))$PAYLOAD_ARR["FPJA"]["PAYLOAD"]=$RETURN_ARR;
        $RETURN_ARR["FPJA"]["TOKEN"]=$this->encode($this->get_utc());
        $RETURN_ARR["FPJA"]["KEY"]=$KEY_ARR["PUBLIC_KEY"];
        $RETURN_ARR["FPJA"]["PAYLOAD"]=$this->encode($this->jsonEncode($PAYLOAD_ARR["FPJA"]["PAYLOAD"]));
        $PAYLOAD_ARR_JSON=$this->jsonEncode($RETURN_ARR);
        return $PAYLOAD_ARR_JSON;
    }
    function FPJA_decode($FPJA_ARR)
    {
        $KEY_ARR=$this->KEY_ARR;
        $FPJA=$FPJA_ARR;
        if(!is_array($FPJA))$FPJA=$this->jsonDecode($FPJA_ARR);
        $PAYLOAD=$this->jsonDecode($this->decode($FPJA["FPJA"]["PAYLOAD"]));
        $FPJA["FPJA"]["PAYLOAD"]=$PAYLOAD;
        
        $TOKEN_DATE=strtotime($this->decode($FPJA["FPJA"]["TOKEN"])??0);
        $CURRENT_DATE=strtotime($this->get_utc());
        $EXPIRED_time=(($TOKEN_DATE+$KEY_ARR["EXPIRED_SEC"])-$CURRENT_DATE);
        
        if($EXPIRED_time<=0)$FPJA["FPJA"]["EXPIRED"]="1";
        else $FPJA["FPJA"]["EXPIRED"]="0";
        return $FPJA;
    }
    
    private $ciphering = "AES-128-CBC";
    function encode($text)
    {
        $KEY_ARR=$this->KEY_ARR;
    	$ciphering=$this->ciphering;
        $PUBLIC_KEY=$KEY_ARR["PUBLIC_KEY"];
        $PRIVATE_KEY=$KEY_ARR["PRIVATE_KEY"];
        return openssl_encrypt($text, $ciphering, $PRIVATE_KEY, 0, $PUBLIC_KEY)??null;
    }
    
    function decode($text)
    {
        $KEY_ARR=$this->KEY_ARR;
        $ciphering=$this->ciphering;
        $PUBLIC_KEY=$KEY_ARR["PUBLIC_KEY"];
        $PRIVATE_KEY=$KEY_ARR["PRIVATE_KEY"];
        return openssl_decrypt($text, 'aes-128-cbc', $PRIVATE_KEY, 0, $PUBLIC_KEY)??null;
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
