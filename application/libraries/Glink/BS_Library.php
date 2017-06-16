<?php
//error_reporting(E_ERROR | E_PARSE);
include_once 'BS_Getlink.php';
class BS_Library extends BS_Getlink
{

    public function get_link($link) {

        // neu link bi ma hoa
        if(!preg_match('/http(?:s)?:\/\/(?:[\w-]+\.)*([\w-]{1,63})(?:\.(?:\w{3}|\w{2}))(?:$|\/)/i', $link))
            $link = $this->decrypte($link, 'doitoicodon');
        else $link = $link;

        $fileCache = md5($link).'.cache';
       /*  echo '<br>--Getlink';
        echo '<br>link:'.$link;
         echo '<br>filecache:'.$fileCache;pr(1);*/
        $list = $this->cache($fileCache);
        if(!$list)
        {
            preg_match('/http(?:s)?:\/\/(?:[\w-]+\.)*([\w-]{1,63})(?:\.(?:\w{3}|\w{2}))(?:$|\/)/i', $link, $match);
            $server = str_replace(array('http:', 'https:' ,'/', 'www.', '.'), array('', '', '', '', '_'), $match[0]);
            //echo '<br>server:'.$server;
            if(!method_exists($this,$server))
                return;


            if(!mod("product")->setting("movie_private_status")){
                $dataCache  = $this->$server($link);//, $sub, $title
            }
            else{
                // APPPATH.'libraries\Glink\
                include_once'BS_Getlink_API.php';
                //include_once 'BS_Getlink_API.php';
                $BS_Library_API = new BS_Getlink_API;
                // $link=$this->encrypte($link);
               /* pr($link,0);
                $link=$this->decrypte($link);
                pr($link);*/
                $dataCache  = $BS_Library_API->$server($link);//, $sub, $title

            }
            $list = $this->cache($fileCache,$dataCache);
        }
        $list = (array)json_decode($list);
        return $list;
    }

    private function cache($fileCache,$dataCache=null) {
       // $path = 'application/cache/glink/';
        $folder = APPPATH .'cache/glink/';

        if(!file_exists($folder))
        mkdir($folder, 0777, true);

        if($dataCache) {
            $this->writeFile($fileCache, $dataCache);
            return $dataCache;
        }
        if(!file_exists($folder.$fileCache))
            return '';
        date_default_timezone_set("Asia/Saigon");
        $timeCache = '7200';
        $timeNow    = time();
        $fileTime   = filemtime($folder.$fileCache);
        $timeOut    = $timeNow - $timeCache;
        if($fileTime > $timeOut)
        {
            header("X-Cache: True");
            header("X-Cache-Author: BSplugin");
            header("X-Cache-Expires: ".$this->timeExpires($fileTime-$timeOut));
            header("X-Cache-Version: 1.0");
            return file_get_contents($folder.$fileCache);
        }
        unlink($folder.$fileCache);
    }
    
    private function timeExpires($time)
    {
        $condition = array(
           12 * 30 * 24 * 60 * 60 => ' year',
           30 * 24 * 60 * 60 => ' month',
           24 * 60 * 60 => ' days',
           60 * 60 => ' hours',
           60 => ' minutes',
           1 => 's'
        );
        foreach($condition as $secs => $str)
        {
            $d = $time/$secs;
            if($d >= 1)
            return round($d).$str;
        }
    }
    public function csrfToken($tokenPost='') {

        $nameToken = 'T'.md5($_SERVER['REMOTE_ADDR'].'O'.$_SERVER['HTTP_USER_AGENT'].'K!@_@!-(o^0)E').'N';

        if($tokenPost) {

            if(!isset($_COOKIE[$nameToken]) || $nameToken != $tokenPost)
                return false;
            else
                return true;

        } else {

            if(!isset($_COOKIE[$nameToken]) || !isset($_SESSION['csrfToken'])) {

                $token = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

                $_SESSION['csrfToken'] = $token;

                setcookie($nameToken, $token, time() + 7);

                return $nameToken;

            } elseif(isset($_SESSION['csrfToken'])) {

                $token = $_COOKIE[$nameToken];

                setcookie($nameToken, $token, time() + 7);

                $_SESSION['csrfToken'] = $token;

                return $nameToken;
            }
        }
    }

    public function encrypte($string,$key){
        $returnString = "";
        $charsArray = str_split("e7NjchMCEGgTpsx3mKXbVPiAqn8DLzWo_6.tvwJQ-R0OUrSak954fd2FYyuH~1lIBZ");
        $charsLength = count($charsArray);
        $stringArray = str_split($string);
        $keyArray = str_split(hash('sha256',$key));
        $randomKeyArray = array();
        while(count($randomKeyArray) < $charsLength){
            $randomKeyArray[] = $charsArray[rand(0, $charsLength-1)];
        }
        for ($a = 0; $a < count($stringArray); $a++){
            $numeric = ord($stringArray[$a]) + ord($randomKeyArray[$a%$charsLength]);
            $returnString .= $charsArray[floor($numeric/$charsLength)];
            $returnString .= $charsArray[$numeric%$charsLength];
        }
        $randomKeyEnc = '';
        for ($a = 0; $a < $charsLength; $a++){
            $numeric = ord($randomKeyArray[$a]) + ord($keyArray[$a%count($keyArray)]);
            $randomKeyEnc .= $charsArray[floor($numeric/$charsLength)];
            $randomKeyEnc .= $charsArray[$numeric%$charsLength];
        }
        return $randomKeyEnc.hash('sha256',$string).$returnString;
    }
    
    private function decrypte($string,$key) {
        $returnString = "";
        $charsArray = str_split("e7NjchMCEGgTpsx3mKXbVPiAqn8DLzWo_6.tvwJQ-R0OUrSak954fd2FYyuH~1lIBZ");
        $charsLength = count($charsArray);
        $keyArray = str_split(hash('sha256',$key));
        $stringArray = str_split(substr($string,($charsLength*2)+64));
        $sha256 = substr($string,($charsLength*2),64);
        $randomKeyArray = str_split(substr($string,0,$charsLength*2));
        $randomKeyDec = array();
    	if(count($randomKeyArray) < 132) return false;
        for ($a = 0; $a < $charsLength*2; $a+=2){
            $numeric = array_search($randomKeyArray[$a],$charsArray) * $charsLength;
            $numeric += array_search($randomKeyArray[$a+1],$charsArray);
            $numeric -= ord($keyArray[floor($a/2)%count($keyArray)]);
            $randomKeyDec[] = chr($numeric);
        }
        for ($a = 0; $a < count($stringArray); $a+=2){
            $numeric = array_search($stringArray[$a],$charsArray) * $charsLength;
            $numeric += array_search($stringArray[$a+1],$charsArray);
            $numeric -= ord($randomKeyDec[floor($a/2)%$charsLength]);
            $returnString .= chr($numeric);
        }
        if(hash('sha256',$returnString) != $sha256){
            return false;
        }else{
            return $returnString;
        }
    }
    
    public function checkRef($domain) {
            $wildcard       = FALSE;
            $credentials    = FALSE;
            $allowedOrigins = array($domain);
            $http_refferer  = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:$_SERVER['HTTP_ORIGIN'];
            $http_refferer  = explode('/', $http_refferer);
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
                if(!isset($http_refferer[2]))
                exit('101');
                if(!in_array($http_refferer[2], $allowedOrigins) && !$wildcard)
                exit('208');
            } else {
                if(!isset($http_refferer[2]))
                exit('300');
                if(!in_array($http_refferer[2], $allowedOrigins) && !$wildcard)
                exit('404');
            }
            $origin = $wildcard && !$credentials ? '*' : $http_refferer[2];
            header("Access-Control-Allow-Origin: " . $origin);
            if($credentials)
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header("Access-Control-Allow-Headers: Origin");
            if($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
            exit('500');
    }

    public function writeFile($pathFile, $data) {
        //$path_cache = 'application/cache/glink/';
        $pathFile = APPPATH .'cache/glink/'.$pathFile;
        $fileOpen   = fopen($pathFile, 'w');
        fwrite($fileOpen, $data);
        fclose($fileOpen);
        return true;
    }

    public function readFile($pathFile) {
        $pathFile = APPPATH .'cache/glink/'.$pathFile;
        if(!file_exists($pathFile))
        return false;
        date_default_timezone_set("Asia/Saigon");
        $timeCache = '7200';
        $timeNow    = time();
        $fileTime   = filemtime($pathFile);
        $timeOut    = $timeNow - $timeCache;
        if($fileTime > $timeOut)
            return file_get_contents($pathFile);
        return '';
    }
}