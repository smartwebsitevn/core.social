<?php
function get_curl_x($url){
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}
function docs_direct($link){
		    $get = get_curl_x($link);
	        $data = explode('url\u003d', $get);
            for($i=count($data);$i>0;$i--){
                $url = explode('\u0026type\u003d', $data[$i]);
                $url = explode('google.com', $url[0]);				
                $link = 'https://redirector.googlevideo.com'.urldecode($url[1]);
				
				if(strpos($link , 'itag=59') !== false || strpos($link , 'itag=18') !== false || strpos($link , 'itag=22') !== false || strpos($link , 'itag=37') !== false )
				$html .= $link.'<br>';
			}
	return $html;	
}
$link = 'https://drive.google.com/file/d/0Bzxx_cRi9CFTZ1NiRGlWMFYzRW8/edit';
echo docs_direct($link);