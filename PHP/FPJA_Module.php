<?php 
class FPJA_FF_API {
    private $KEY_ARR=[
        "PRIVATE_KEY"=>"1111111111111111",
        "PUBLIC_KEY"=> "2222222222222222",
        "CIPHERING"=>"AES-128-CBC",
        "EXPIRED_SEC"=>300,
	    "API_URL"=>"",
    ];
    public $FPJA_POST=null;
    function __construct() {

    }
    function HOOK_FPJA_POST()
    {

      $POST_ARG_ORG=file_get_contents('php://input');
      $POST_ARG=$this->jsonDecode($POST_ARG_ORG);
      $this->FPJA_POST=$POST_ARG;
      return $POST_ARG??null;
    }
    function HOOK_FPJA_GET()
    {
      return $_GET["FPJA"]??null;
    }
    function HOOK_FPJA()
    {
        $hook=null;
        $hook=$this->HOOK_FPJA_GET()??null;
        if($hook==null)
        {
            $hook=$this->HOOK_FPJA_POST()??null;
        }
        return $hook;
    }
     function test_connection()
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"test_connection"]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
       /* echo($this->jsonEncode($PAYLOAD_ARR));
        echo "<hr>";
        echo($this->jsonEncode($this->FPJA_decode($FPJA))); //şifresiz
        echo "<hr>";
        echo($FPJA);//şifreli
        echo "<hr><hr><hr>";
        echo($response_FPJA);
        echo "<hr>";
        echo($this->jsonEncode($FPJA_decode));
        echo "<hr>";die;*/
        
        return $response_FPJA;
    }
    function pk_show_packages($PKS) //pk numbers pk1,pk2,pk3
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"pk_show_packages","PKS"=>$PKS]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
        return $FPJA_decode;
    }
    function pk_delete_packages($PKS)//pk numbers pk1,pk2,pk3
    {
        $KEY_ARR=$this->KEY_ARR;
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>["REQUEST"=>"pk_delete_packages","PKS"=>$PKS]]];
        $FPJA=$this->FPJA_encode($PAYLOAD_ARR);
        $response_FPJA=$this->Api_Request($KEY_ARR["API_URL"],$FPJA);
        $FPJA_decode=$this->FPJA_decode($response_FPJA);
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
  
    function FPJA_exit($arr)
    {
             header('Content-Type: application/json;charset=utf-8');
             $FPJA=$this->FPJA_encode($arr,true);
             echo $FPJA;die;
    }
    function JSON_exit($arr)
    {
       header('Content-Type: application/json;charset=utf-8');
       $FPJA=json_encode($arr,JSON_UNESCAPED_UNICODE);
       echo $FPJA;die;
    }
    function sql_injection_protection($data)
    {
    	$datam=$data;
    		$datam=str_replace("\"","\"",$datam);
    		$datam=str_replace("'","\'",$datam);
    		$datam=str_replace("<","",$datam);
    		$datam=str_replace(">","",$datam);
    		$datam=str_replace("`","",$datam);
    		return $datam;
    }

    function SETUP_ARR($SETUP_ARR)
    {
        foreach ($SETUP_ARR as $key=>$value)
            {
                $this->KEY_ARR[$key]=$value;
            }
    }
    function get_utc($format='Y-m-d H:i:s')
    {
    $dt = new DateTime('now', new DateTimeZone('UTC'));
    return $dt->format($format);
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
    
    function FPJA_encode_b64($PAYLOAD_ARR,$Hide_key=false,$Hide_token=false)
    {
        $PAYLOAD_ARR=["FPJA"=>["PAYLOAD"=>$PAYLOAD_ARR]];
        $ENCODED_DATA=$this->FPJA_encode($PAYLOAD_ARR,$Hide_key,$Hide_token);
        $xdata=$this->b64Encode($ENCODED_DATA);
        return $xdata;
    }
    function FPJA_decode_b64($FPJA_b64)
    {
        $FPJA_GET=$FPJA_b64;
        $FPJA_GET=$this->b64Decode($FPJA_GET);
        return $this->FPJA_decode($FPJA_GET);
    }
    
    function FPJA_encode($PAYLOAD_ARR,$Hide_key=false,$Hide_token=false)
    {
        $KEY_ARR=$this->KEY_ARR;
        $RETURN_ARR=$PAYLOAD_ARR;
        if(!is_array($PAYLOAD_ARR["FPJA"]))$PAYLOAD_ARR["FPJA"]["PAYLOAD"]=$RETURN_ARR;
        if($Hide_token==false)$RETURN_ARR["FPJA"]["TOKEN"]=$this->encode($this->get_utc());
        if($Hide_key==false)$RETURN_ARR["FPJA"]["KEY"]=$KEY_ARR["PUBLIC_KEY"];
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
    
    function FPJA_Basic_Post_Hook()
    {
       $fpja_data= $this->HOOK_FPJA();
       $fpja_data2= $this->FPJA_Basic_Decode($fpja_data);
       if($fpja_data2["FPJA"]["PAYLOAD"]!=null)return $fpja_data2["FPJA"];
       else return null;
    }
    
    function FPJA_Basic_Encode($PAYLOAD_ARR)
    {
       $KEY_ARR=$this->KEY_ARR;
        $RETURN_ARR["FPJA"]["PAYLOAD"]=$this->encode($this->jsonEncode($PAYLOAD_ARR));
        $RETURN_ARR["FPJA"]["TOKEN"]=$this->encode($this->get_utc());
        $RETURN_ARR["FPJA"]["KEY"]=$KEY_ARR["PUBLIC_KEY"];
        $PAYLOAD_ARR_JSON=$this->jsonEncode($RETURN_ARR);
        return $PAYLOAD_ARR_JSON;
    }
    function FPJA_Basic_Decode($FPJA_ARR)
    {
        return $this->FPJA_decode($FPJA_ARR);
    }

    function encode($text,$pubkey=null,$prikey=null)
    {
        $KEY_ARR=$this->KEY_ARR;
    	$ciphering=$KEY_ARR["CIPHERING"];
        $PUBLIC_KEY=$pubkey??$KEY_ARR["PUBLIC_KEY"];
        $PRIVATE_KEY=$prikey??$KEY_ARR["PRIVATE_KEY"];
        return openssl_encrypt($text, $ciphering, $PRIVATE_KEY, 0, $PUBLIC_KEY)??null;
    }
    
    function decode($text,$pubkey=null,$prikey=null)
    {
        $KEY_ARR=$this->KEY_ARR;
        $ciphering=$KEY_ARR["CIPHERING"];
        $PUBLIC_KEY=$pubkey??$KEY_ARR["PUBLIC_KEY"];
        $PRIVATE_KEY=$prikey??$KEY_ARR["PRIVATE_KEY"];
        return openssl_decrypt($text, $ciphering, $PRIVATE_KEY, 0, $PUBLIC_KEY)??null;
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
    function Api_Request($URL,$POST_STRING=null,$ASYNC=false,$HEADER_ARR=null,$IP=null)
    {
        if($ASYNC==true)
        return $tihs->Api_Request_ASYNC($URL,$POST_STRING,$ASYNC,$HEADER_ARR,$IP);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        $PACKAGE_HEADERS = [
        'User-Agent: FASTFOX-API',
        'Content-Type: application/json;charset=utf-8',
        'Connection: Close',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $PACKAGE_HEADERS);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_TIMEOUT,60);
        curl_setopt($ch, CURLOPT_HEADER, true);

        if(strlen($POST_STRING)>=1){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    $POST_STRING);
        }
        
        // In real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS, 
        //          http_build_query(array('postvar1' => 'value1')));
        
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        list($headers, $body) = explode("\r\n\r\n", $server_output, 2);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
       // var_dump($server_output);
        if($httpcode=="200") return $body;
        return ["STATUS"=>"FAIL"];
        
        
    }
    
    function Api_Request_ASYNC($URL,$POST_STRING=null,$ASYNC=true,$HEADER_ARR=null,$IP=null)
    {
    $PACKAGE_STRING="";
    $parts=parse_url($URL); $xhost=null; $xip=$IP; $xport=80;
    if(!in_array($parts["scheme"],["http","https"]))return ["STATUS" => "FAIL"];
    
    $PACKAGE_HEADERS = [
        'User-Agent: FASTFOX-API',
        'Content-Type: application/json;charset=utf-8',
        'Connection: Close',
    ];
    if($HEADER_ARR!=null)
    {
        $PACKAGE_HEADERS=$HEADER_ARR;
    }
    
    if($xip==null&&$parts["scheme"]!="https")$xip = gethostbyname($parts['host']);
    else {$xip=$IP;}
    
    
    $query="";if(isset($parts["query"])){$query="?".$parts["query"];}
    if($parts["scheme"]=="https"){$xport=443; $xhost="ssl://".$parts['host'];}
    else if($parts["scheme"]=="http"){$xport=80; $xhost="tcp://".$xip;}
	else $xhost=$xip;

	$Request_type="GET";
	if($POST_STRING!=null){$Request_type="POST";
	    if(!is_string($POST_STRING))$POST_STRING=$this->jsonEncode($POST_STRING);
	}
	
    $fp = fsockopen($xhost,$xport,$errno, $errstr, 30);
    $PACKAGE_STRING = $Request_type." ".$parts['path'].$query." HTTP/1.1\r\n";
    $PACKAGE_STRING.= "Host: ".$parts['host']."\r\n";
    foreach($PACKAGE_HEADERS as $header)
    {
        $PACKAGE_STRING.= $header."\r\n";
    }
	if($POST_STRING!=null){$PACKAGE_STRING.= "Content-Length: ".mb_strlen($POST_STRING, '8bit')."\r\n";}
    $PACKAGE_STRING.= "\r\n";
	$PACKAGE_STRING.= $POST_STRING;
    fwrite($fp, $PACKAGE_STRING);
    

	if($ASYNC==true) {fclose($fp); return ["STATUS" => "SUCCESS"];}
	$response = '';
    while (!feof($fp)) {
		$response .= fgets($fp, 8192);
	}
	fclose($fp);
    var_dump($response);die;
	list($headers, $body) = explode("\r\n\r\n", $response, 2);
    $header_lines = explode("\r\n", $headers);

    preg_match('/HTTP\/[\d\.]+\s(\d+)/', $header_lines[0], $matches);
    $status_code = $matches[1];
	if($status_code=="200") return $body;
	
    return ["STATUS"=>"FAIL"];
    }
    
}
?>
