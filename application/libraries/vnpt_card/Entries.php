<?php	
     Class login{
          Public $m_UserName;
          Public $m_Pass;
          Public $m_PartnerID;
          public $soapClient;
          function  login_(){
                   $Obj= new loginResponse();
                   $RSAClass = new ClsCryptor();
				   //Ham thuc hien lay public key cua CardGW tu file pem
                   $RSAClass->GetpublicKeyFrompemFile(APPPATH."libraries/vnpt_card/Key/CardGW_public_key.pem");
                   try{
                       $EncrypedPass = $RSAClass -> encrypt($this-> m_Pass);
                   }
                   catch(Exception  $ex){
                   }
                   $Pass = base64_encode($EncrypedPass);
                   //$soapClient = new Soapclient("http:192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
                   //$soapClient = new Soapclient("http://charging-test.megapay.net.vn:10001/CardChargingGW_V2.0/services/Services?wsdl");
                   try{
                       $result = $this->soapClient->login( $this->m_UserName, $Pass,$this->m_PartnerID); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
					   $this->soapClient->httpsocket = null;
                       //print_r($result);
                   }
                   catch(Exception $ex){
				   //echo "Xay ra loi khi thuc hien login: ".$ex;
				   }
                   $Obj->m_Sessage = $result->message;

                   //$Obj->m_SessionID = $result->sessionid;
                   $Obj->m_Status = $result->status;					
					
					//Ham thuc hien lay private key cua doi tac tu file pem
                   $RSAClass->GetPrivatekeyFrompemFile(APPPATH."libraries/vnpt_card/Key/private_key.pem");
                   try{
                       $Session_Decryped =   $RSAClass -> decrypt(base64_decode($result->sessionid));                       
                       $Obj->m_SessionID = $this->Hextobyte( $Session_Decryped) ;
                   }
                   catch(Exception $ex){
				    //echo"<br/><h1>Co loi khi thuc hien giai mai session:<h1/><br/>".$ex;
				   }				   
                   //print_r($Obj->m_SessionID) ;

                   $Obj->m_TransID = $result->transid;

                   return  $Obj;

          }
          function Hextobyte($strHex){
                    $string='';
                    for ($i=0; $i < strlen($strHex)-1; $i+=2)
                    {
                        $string .= chr(hexdec($strHex[$i].$strHex[$i+1]));
                    }
                    return  $string;
          }
          function ByteToHex($strHex){
                    return   bin2hex ($strHex);
          }
     }
     Class loginResponse{
          Public $m_Status;
          Public $m_Sessage;
          ///
          // Session do VNPT EPAY cung cap cho doi t�c d�ng de ma hoa du lieu va xac thuc thong tin.
          //SessinID gui ve tu VNPT EPAY duoc ma hoa bang public key cua merchant theo thuat toan RSA
          ///
          Public $m_SessionID;
          Public $m_TransID;
     }
     Class logout{
          Public $m_UserName;
          Public $m_PartnerID;
          Public $m_SessionID;
          public $soapClient;
          function   logout_(){
                    $Ojb = new LogoutResponse();
                    
                    //$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
                    try{
                       $result =  $this->soapClient->logout( $this->m_UserName, $this->m_PartnerID,md5($this->m_SessionID)); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
                    }
                    catch(Exception $ex){}
                    $Obj->m_Status = $result->status;
                    $Obj->m_Message = $result->message;
                    return  $Obj;
          }  
     }
     Class LogoutResponse{
          Public $m_Status;
          Public $m_Message;

     }
     Class ChangePassword{
          Public $m_TransID;
          Public $m_UserName;
          Public $m_PartnerID;
          Public $m_OLD_PASSWORD;
          Public $m_NEW_PASSWORD;
          Public $m_SessionID;
          public $soapClient;
          function   ChangePassword_(){
                    $Ojb = new ChangeResponse();
                    $ObjTriptDes = new TriptDes($this-> m_SessionID);
                    try {
                        $OldPass =  $ObjTriptDes->EncrypTriptDes(  $this->m_OLD_PASSWORD);
                        $NewPass = $ObjTriptDes->EncrypTriptDes(  $this->m_NEW_PASSWORD);
                    }catch(Exception $ex){}
                    //$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
                    try{
                       $result = $this->soapClient->changePassword( $this->m_TransID , $this->m_UserName, $this->m_PartnerID, $OldPass, $NewPass ,md5($this->m_SessionID)); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
                    }
                    catch(Exception $ex){}
                    $Obj->m_Status = $result->status;
                    $Obj->m_Message = $result->message;
                    return  $Obj;
          }
     }
     Class ChangeResponse{
          Public $m_Status;
          Public $m_Message;
     }
     Class ChangMPin{
          Public $m_TransID;
          Public $m_UserName;
          Public $m_PartnerID;
          Public $m_OLD_OLD_MPIN;
          Public $m_NEW_MPIN;
          Public $m_SessionID;
		  public $soapClient;
          function   ChangMPin_(){
                    $Ojb = new ChangeResponse();
                    $ObjTriptDes = new TriptDes($this-> m_SessionID);
                    try {
                        $OldMpin =  $ObjTriptDes->EncrypTriptDes(  $this->m_OLD_OLD_MPIN);
                        $NewMpin = $ObjTriptDes->EncrypTriptDes(  $this->m_NEW_MPIN);
                    }catch(Exception $ex){}


                    //$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
                    try{
                       $result = $this->soapClient->changeMPIN( $this->m_TransID , $this->m_UserName, $this->m_PartnerID, $OldMpin, $NewMpin ,md5($this->m_SessionID)); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
                    }
                    catch(Exception $ex){}
                    $Obj->m_Status = $result->status;
                    $Obj->m_Message = $result->message;
                    return  $Obj;
         }
     }
	 
     Class CardCharging{
		  Public $m_WebUser;
          Public $m_TransID;
          Public $m_UserName;
          Public $m_PartnerID;
          Public $m_MPIN;
          Public $m_Target;
          Public $m_Card_DATA;
		  public $m_Pass;
		  var $SessionID;
          var $soapClient;		  		 
          function  CardCharging_ (){					
					//echo"session get result: ".$this->SessionID;					
					//echo"login de lay session";					
					//if($this->SessionID == null || $this->SessionID == "")
					//{						
						$login = new login();						
						$login-> m_UserName = $this->m_UserName;
						$login-> m_Pass = $this->m_Pass;
						$login-> m_PartnerID = $this->m_PartnerID;
						$login-> soapClient = $this->soapClient ;
						//var_dump($login);
						$loginresponse = new loginResponse();
						$loginresponse =  $login-> login_();

						if($loginresponse->m_Status=="1"){ 
						//echo"<br/>----Login successfull-----<br/>";
						//print_r($loginresponse);
						//Nen luu lai bien SessionID de thuc hien cac ham charging tiep theo
						//Tranh viec moi giao dich charging lai login 1 lan nhu vay giao dich se rat cham.
						$this->SessionID = bin2hex($loginresponse->m_SessionID);						
						//session_start();     //start the session						
						//echo("<br/>Sessionid login clear text: ".$this->SessionID);
						//echo"<br/>----Login successfull-----<br/>";
						}
						else{
						//echo"<br/>----Login fail-----------<br/>";
						//echo"<br/>----Khong tiep tuc thuc hien charging-----------<br/>";
						//Login fail khong thuc hien charging
						//echo "Trang thai login: ".$loginresponse->m_Status;
						//echo "Message: ".$loginresponse->m_Sessage;
						//echo"<br/>----Login fail-----------<br/>";
						return;
						}
					//}						
					//echo "<br/>----------------Bat dau thuc hien charging-----------------------<br/>";					
					///Bat dau thuc hien charging
                    $Ojb = new CardChargingResponse();
                    $key = $this->Hextobyte($this->SessionID);
                    //print_r($this-> m_SessionID);
                    //print_r($this->m_MPIN);
                    //$keytesst = base64_encode($key);
                    //print_r($keytesst);
                    $ObjTriptDes = new TriptDes($key);
                    try {
                        $strEncreped =  $ObjTriptDes->encrypt(  $this->m_MPIN);
                        //print_r ($strEncreped);
                        //$decode =  $ObjTriptDes->decrypt(  $strEncreped);
                        //print_r ($decode);
                        $Mpin = $this->  ByteToHex($strEncreped);

                        $Card_DATA = $this->  ByteToHex( $ObjTriptDes->encrypt( $this->m_Card_DATA));
                        //print_r($Card_DATA);
                        
                    }catch(Exception $ex){
					//echo "Co loi xay ra khi ma hoa mpin: ".$ex;
					}
                    //$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
                    try{
						//print_r($this);
						
                       @$result = $this->soapClient->cardCharging( $this->m_TransID , $this->m_UserName, $this->m_PartnerID, $Mpin, $this->m_Target, $Card_DATA ,md5($this->SessionID)); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
					   $this->soapClient->httpsocket = null;
					   //print_r($result);
                    }
                    catch(Exception $ex){
					//echo "Co loi xay ra khi thuc hien charging: ".$ex;
					}
                    @$Obj->m_Status = $result->status;
                    $Obj->m_Message = $result->message;
                    $Obj->m_TRANSID = $result->transid;
                    $Obj->m_AMOUNT = $result->amount;
					$resAmount =  $ObjTriptDes->decrypt($this-> Hextobyte( $result->responseamount));
                    $Obj->m_RESPONSEAMOUNT = $resAmount ;//$result->responseamount;
					if($Obj->m_Status == 3 || $Obj->m_Status == 7)
					$this->SessionID = null;
					
					
                    //print_r($Obj                    return  $Obj;
					return $Obj;

          }
		  
         function Hextobyte($strHex){
                    $string='';
                    for ($i=0; $i < strlen($strHex)-1; $i+=2)
                    {
                        $string .= chr(hexdec($strHex[$i].$strHex[$i+1]));
                    }
                    return  $string;
          }
          function ByteToHex($strHex){
                  return   bin2hex ($strHex);
          }
          
     }

     Class CardChargingResponse{
          Public $m_Status;
          Public $m_Message;
          Public $m_TRANSID;
          Public $m_AMOUNT;
          Public $m_RESPONSEAMOUNT;

     }
     
     Class getTransactionStatus{
          Public $m_TransID;
          Public $m_UserName;
          Public $m_PartnerID;
          Public $m_SessionID;
          public $soapClient;
          function   getTransactionStatus_(){
                    $Ojb = new ChangeResponse();
                 
                    try{
                       $result = $this->soapClient->getTransactionStatus($this->m_TransID , $this->m_UserName, $this->m_PartnerID,md5($this->m_SessionID)); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
                    }
                    catch(Exception $ex){}
                    $Obj->m_Status = $result->status;
                    $Obj->m_Message = $result->message;
                    return  $Obj;
          }
     }

     class TriptDes{
           private $DessKey;
           public function TriptDes($key){
           $this->DessKey= $key;
           }
           public function decrypt($text) {
                  $key = $this->DessKey;
                  $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
                  $iv = mcrypt_create_iv($size, MCRYPT_RAND);
                  $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, $text, MCRYPT_MODE_ECB,$iv);
                  return rtrim($this->pkcs5_unpad($decrypted) );
           }

           public function encrypt($text) {
                  $key = $this->DessKey;
                  $text=$this->pkcs5_pad($text,8);  // AES?16????????
                  $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
                  $iv = mcrypt_create_iv($size, MCRYPT_RAND);
                  $bin = pack('H*', bin2hex($text) );
                  $encrypted = mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB,$iv);
                  return $encrypted;
           }

           function pkcs5_pad($text, $blocksize) {
                    $pad = $blocksize - (strlen($text) % $blocksize);
                    return $text . str_repeat(chr($pad), $pad);
           }

           function pkcs5_unpad($text) {
                    $pad = ord($text{strlen($text)-1});
                    if ($pad > strlen($text)) return false;
                    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
                    return substr($text, 0, -1 * $pad);
           }

      }
      class ClsCryptor{
        private  $RsaPublicKey;
        private  $RsaPrivateKey;
        private  $TripDesKey;
        public function GetpublicKeyFromCertFile($filePath)
        {
              $fp=fopen($filePath,"r");
              $pub_key=fread($fp,filesize($filePath));
              fclose($fp);
              openssl_get_publickey($pub_key);

              $this-> RsaPublicKey = $pub_key;
        }

        public function GetpublicKeyFrompemFile($filePath)
        {
              $fp=fopen($filePath,"r");
              $pub_key=fread($fp,filesize($filePath));
              fclose($fp);
              openssl_get_publickey($pub_key);
              //print_r($pub_key);
              $this-> RsaPublicKey = $pub_key;
              //print_r($this-> RsaPublicKey);
        }

        public function GetPrivatekeyFrompemFile($filePath)
        {
              $fp=fopen($filePath,"r");
              $pub_key=fread($fp,filesize($filePath));
              fclose($fp);
              $this-> RsaPrivateKey = $pub_key;


        }
       public function GetPrivate_Public_KeyFromPfxFile($filePath,$Passphase)
        {
               $p12cert = array();
               $fp=fopen($filePath,"r");
               $p12buf = fread($fp, filesize($filePath));
               fclose($fp);
               openssl_pkcs12_read($p12buf, $p12cert, $Passphase);
               $this-> RsaPrivateKey =  $p12cert['pkey'];
               $this-> RsaPublicKey = $p12cert['cert'];

        }

        function encrypt($source)
            {
                //path holds the certificate path present in the system
                $pub_key = $this -> RsaPublicKey;
                //$source="sumanth";
                $j=0;
                $x=strlen($source)/10;
                $y=floor($x);
                $crt='';
                //print_r($pub_key) ;
                for($i=0;$i<$y;$i++)
                {
                $crypttext='';

                openssl_public_encrypt(substr($source,$j,10),$crypttext,$pub_key);$j=$j+10;
                $crt.=$crypttext;
                $crt.=":::";
                }
                if((strlen($source)%10)>0)
                {
                openssl_public_encrypt(substr($source,$j),$crypttext,$pub_key);
                $crt.=$crypttext;
                }
                return($crt);

            }
            //Decryption with private key
            function decrypt($crypttext)
            {
                $priv_key = $this -> RsaPrivateKey;
                $tt=explode(":::",$crypttext);
                $cnt=count($tt);
                $i=0;
                $str='';
                while($i<$cnt)
                {
                openssl_private_decrypt($tt[$i],$str1,$priv_key);
                $str.=$str1;
                $i++;
                }
                return $str;
            }
  }

?>

