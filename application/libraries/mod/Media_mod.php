<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media_mod extends MY_Mod {


	/*===============================================================
        * Get LINK
     *=================================================================*/


	function get_link_multi($input,$opts=[])
	{
		$result = array();
		if (!$input)
			return $result;
		// Lay link phim
		$i=1;
		foreach ($input as $link) {
			$rs= $this->get_link($link,$opts);
			if(isset($rs['link_main']) && $rs['link_main'])
				// $result[$rs['episode']] =$rs;
				$result[$i] =$rs;
			$i++;
		}

		//pr($result);
		return $result;
	}
	function get_link($link,$opts=[])
	{
		$link_data= trim($link);
		$link_data= trim($link_data,';');
		$link_data =explode("\n",$link_data);

		$d=count($link_data);
		if(count($d)<=0)
			return null;

		//=== Su ly link phim chinh
		// pr($link_data);
		$link_main=$this->_get_link_parse_st2($link_data[0]);

		$result=$link_main; // set mac dinh link chinh ra ngoai de su dung

		//== kiem tra co su ly multi server
		$link_subs=array();

		$multi_server =array_get($opts,'multi_server',false);
		if($multi_server){
			$link_subs[]=$link_main;
			if($d>1){
				//echo '<br>=='. $d;
				for($i=1;$i<=($d);$i++){
					if(isset($link_data[$i]) && $link_data[$i]){
						//pr($link_data[$i],0);
						$rs = $this->_get_link_parse_st2($link_data[$i]);
						if($rs)
							$link_subs[] =$rs;
					}
				}
			}
			$result['servers']=$link_subs;

		}
		//pr($result);
		return $result;

	}

	/**
	 * Lay gia tri cua link
	 */
	function _get_link_parse_st2($link)
	{
		if (!$link)
			return null;

		$link = trim($link);
		$link = trim($link, ';');
		if (empty($link))
			return null;

		$txt = explode(';', $link);
		$d = count($txt);
		if ($d < 2) {
			$server = mod("server")->get_default();
			$server_id = $server->id;
			array_unshift($txt, $server_id);
		} else {
			$server_id = trim($txt[0], 'S');
		}
		$link_main = array();
		$link_main['server'] = $server_id;
		// $link_main['link']=$this->_get_link_parse_st3($txt[1]);
		$this->_get_link_parse_st3($txt[1], $link_main);
		$link_main['sub'] = array();
		if ($d > 2) {
			//echo '<br>=='. $d;
			for ($i = 2; $i <= ($d - 1); $i++) {
				if (isset($txt[$i]) && $txt[$i]) {
					//echo '<br>=='. $txt[$i];
					$link_main['sub'][] = $txt[$i];
				}
			}
		}

		return $link_main;

	}

	// Su ly link chinh
	function _get_link_parse_st3($link, &$link_main)
	{
		$link = trim($link);
		if (empty($link))
			return null;
		$txt = explode('|', $link);
		if (count($txt) < 1)
			return null;

		// su ly link chinh
		$link_main['link'] = $this->_get_link_parse_st4($txt[0]);
		//neu co link thuyet minh
		if (isset($txt[1]))
			$link_main['link_interpret'] = $this->_get_link_parse_st4($txt[1]);


	}

	// Su ly link thuoc loai chuyen biet
	function _get_link_parse_st4($link)
	{
		// neu la link google drive
		if (preg_match('#https://drive.google.com/file/d/(.*)|https://drive.google.com/file/d/(.*)/(.+?)#', $link, $id)) {
			if (isset($id[1]))
				return $id[1];
		}
		// neu la link youtube
		if (preg_match("|^http(s)?://(www.)?youtube.([a-z]+)/(.*)?$|i", $link)) {
			return $this->_get_link_youtube($link);
		}
		return $link;
	}

	// kiem tra xem url co phai link youtube
	function _get_link_youtube($url)
	{
		if (empty($url))
			return false;

		$parse_url = parse_url($url);
		if (!isset($parse_url ['query']))
			return false;
		$array = explode("&", $parse_url ['query']);
		$param = explode("=", $array [0]);
		//pr($param);
		if ($parse_url ['path'] != '/watch' || $param [0] != "v") {
			return false;
		}
		$key = $param [1];
		/*if (!$this->checkVideoExist($key)) {
            return false;
        }*/
		return $key;

	}

	/*===============================================================
    * PROCESS LINK
   *=================================================================*/

	public
	function process_link_main($product, $episode)
	{
		//pr($product->link->{$episode});
		if ($product->link) {
			foreach ($product->link_data as $epi => $row) {
				$row->episode_key = $epi;
				if ($epi == $episode) {
					$row->link_main = $this->process_link_detail($row->link_main);
				}
			}
		}
		return $product;
	}

	public
	function process_link_demo($product)
	{
		// su ly link demo
		if ($product->demo) {
			$product->demo->link_main = $this->process_link_detail($product->demo->link_main);
		}
		return $product;
	}

	public
	function process_link_detail($ob_link)
	{
		if (!is_object($ob_link)) {

			$ob_link = json_decode($ob_link);
		}
		if (!$ob_link)
			return $ob_link;

		$server_id = trim($ob_link->server, 's');
		//===============
		static $tmp_servers = [];
		if (!isset($tmp_servers[$server_id]))
			$server_cur = model('server')->get_info($server_id);
		else
			$server_cur = $tmp_servers[$server_id];

		if (!$server_cur)
			return $ob_link;
		// == Su ly Player
		/* if ($server_cur->player == 'jw6') {
             $player = 'jw6';
         } elseif ($server_cur->player == 'html5') {
             $player = 'html5';
         } elseif ($server_cur->player == 'flowplayer') {
             // if (isset($server_cur->streaming_protocol) && ($server_cur->streaming_protocol == 'hls' || $server_cur->streaming_protocol == 'hlss'))
             $player = 'flowplayer_hls';
             //  else
             // $player = 'flowplayer_hds';
         } elseif ($server_cur->player == 'youtube') {
             $player = 'youtube';
         } elseif ($server_cur->player == 'torrent') {
             $player = 'torrent';
         } elseif ($server_cur->player == 'videojs_hls') {
             $player = 'videojs_hls';
         } else {
             $player = 'videojs';
         }*/
		$player = $server_cur->player;
		if ($server_cur->player_mobile) {
			static $is_mobile = null;
			if (is_null($is_mobile))
				$is_mobile = t('input')->is_mobile();
			if ($is_mobile)
				$player = $server_cur->player_mobile;
		}
		//== Su ly link chinh
		$link = $ob_link->link;
		$link = $this->process_link_by_server($link, $server_cur);
		// ma hoa link
		//$link = $this->encode_link($link);
		//== Su ly link co thuyet minh
		$link_interpret = null;// cac link co cac quality (youtuby,picasa)
		if (isset($ob_link->link_interpret) && $ob_link->link_interpret) {
			$link_interpret = $ob_link->link_interpret;
			$link_interpret = $this->process_link_by_server($link_interpret, $server_cur);
			// ma hoa link
			//$link_interpret = $this->encode_link($link_interpret);
		}
		//== Su ly Sub
		$subs = [];
		if ($ob_link->sub) {
			static $sub_types = null;
			static $path_sub = null;
			if (!$sub_types) {
				$sub_types = $this->config('sub_types');
			}
			if (!$path_sub) {
				$path_sub = $this->setting('path_sub');
				$path_sub = handle_content($path_sub, 'output');
				$path_sub = trim($path_sub, '/');
			}
			foreach ($ob_link->sub as &$sub) {
				// $sub = $path_sub.'/'.$sub;

				$file_name = file_get_name($sub);
				$file_name = preg_replace('#\.[^.]*$#', '', $file_name);//cat bo phan mo rong
				// lay phan ngon ngu sub
				$lang = right($file_name, 2);
				if (in_array($lang, $sub_types)) {
					$subs[$lang] = $path_sub . '/' . $sub;
				}
			}
		}

		//====================
		$ob_link->_link = $link;
		$ob_link->_link_interpret = $link_interpret;
		$ob_link->_sub = $subs;
		$ob_link->_player = $player;
		return $ob_link;
	}

	function process_link_by_server($link, $server_cur, &$links = [])
	{

		// echo '<br>===process_link_by_server'. $link.' s='.$server_cur->key;
		if ($server_cur->key == 'wowza') {
			$link = $this->process_link_wowza($link, $server_cur);
		} else if ($server_cur->key == 'nc') {
			$link = $this->process_link_nc($link, $server_cur);
		} else if ($server_cur->key == 'dedicated') {
			$link = $this->process_link_dedicated($link, $server_cur, $links);
		} else if ($server_cur->key == 'youtube') {
			$link = $this->process_link_youtube($link, $server_cur, $links);

		} else if ($server_cur->key == 'picasa') {
			$link = $this->process_link_picasa($link, $server_cur, $links);
		} else if ($server_cur->key == 'google_drive') {
			$link = $this->process_link_google_drive($link, $server_cur, $links);
		} else {
			$link = $this->process_link_other($link, $server_cur, $links);
		}
		return $link;
	}

	//https://drive.google.com/file/d/0B1xQLLJtrzJoaWUxUHdqY01mRGM/view
	function process_link_google_drive($id, $server, &$links = [])
	{
		$link = $server->url . '/file/d/' . $id . '/view';
		//- lay cac chat luong]
		// pr($link);
		$link_processed = lib('glink')->get_link($link);
		if ($link_processed) {
			$link = $link_processed;
		}
		return $link;
	}

	function process_link_google_drive_($id, $server, &$links = [])
	{
		//server url  	https://docs.google.com/file/d
		$video_id = $id;

		$link = $server->url . '/file/d/' . $video_id;
		$get = file_get_contents($link);
		$cat = explode(',["fmt_stream_map","', $get);
		$cat = explode('"]', $cat[1]);
		$cat = explode(',', $cat[0]);
		/*if(admin_is_login()){
             pr($cat);
        }*/

		foreach ($cat as $link) {
			$cat = explode('|', $link);
			$links = str_replace(array('\u003d', '\u0026'), array('=', '&'), $cat[1]);
			if ($cat[0] == 37) {
				$f1080p = $links;
			}
			if ($cat[0] == 22) {
				$f720p = $links;
			}
			if ($cat[0] == 18) {
				$f580p = $links;
			}

			if ($cat[0] == 59) {
				$f480p = $links;
			}
			if ($cat[0] == 43) {
				$f360p = $links;
			}
		}
		$links = array();
		if (isset($f1080p)) {
			$links['1080p'] = $f1080p;
			/*$links['720p'] = $f720p;
            $links['480p'] = $f480p;
            $links['360p'] = $f360p;*/

		} elseif (isset($f720p)) {
			$links['720p'] = $f720p;
			/* $links['480p'] = $f480p;
             $links['360p'] = $f360p;*/
		} elseif (isset($f580p)) {
			$links['580p'] = $f580p;
			/* $links['360p'] = $f360p;*/
		} elseif (isset($f480p)) {
			$links['480p'] = $f480p;
			/* $links['360p'] = $f360p;*/
		} else {
			$links = $f360p;
		}
		$url = '';
		if ($links) {
			$url = array_values($links)[0];
		}
		// pr($links);
		return $url;
	}


	// chu y : voi file tren, duung luong nho khi get tu google khong can confime len no play dc
	//https://drive.google.com/uc?export=download&id=0B0JMGMGgxp9WMEdWb1hyQUhlOWs

	// con link duoi dung luong lon khi paste vao trinh duyet thi hien confirm, len co the ly do do ko play dc
	//https://drive.google.com/uc?export=download&id=0B1xQLLJtrzJoaWUxUHdqY01mRGM
	// chu y dang linh nay chi play dc tren site( local khong play dc)
	/*function process_link_google_drive($id, $server, &$links = [])
     {
         $url = $server->url . '/uc?export=download&id=' . $id;
         // $url = $server->url . '/file/d/' . $id;
         return $url;
     }*/
	// =================== Picasa =====================
	function process_link_picasa($id, $server, &$links = [])
	{
		$link = $server->url . '/' . $id;
		//- lay cac chat luong
		$link_processed = lib('glink')->get_link($link);
		if ($link_processed) {
			$link = $link_processed;
		}
		return $link;
	}

	function process_link_picasa_($id, $server, &$links = [])
	{
		$url = $server->url . '/' . $id;
		//- lay cac chat luong
		$list_link = lib('Picasa')->getLink($url);
		//pr($list_link);

		if ($list_link && count($list_link) > 0) {
			$i = true;
			foreach ($list_link as $video) {
				$links[$video['q']] = $video['link'];
				if ($i) {
					$url = $video['link'];
					$i = false;
				}
			}
		}
		//pr($links);
		return $url;
	}

	/* Link dang 1: https://picasaweb.google.com/lh/photo/oSbE7LmJg7_RD0sEQ7jyW_hOCqZ-rWSgN0iX_85P3x4?feat=directlink
    */
	/* function process_link_picasa1($link)
     {
         //get picasa page by default file_get_contents function
         $data = file_get_contents($link);
         $a = explode('"media":{"content":[', $data);
         if (!isset($a[1])) {
             $source = '<source src="' . ($link) . '" type="video/mp4" data-res="420px" ></source>';

             return $source;
         }
         $a = explode('],"', $a[1]);
         $datar = explode('},', $a[0]);
         foreach ($datar as $key => $value) {
             $value = str_replace("}}", "}", $value . "}");
             $mp4s[] = json_decode($value, true);
         }
         $js = $bt = '';
         for ($i = 1; $i < count($mp4s); $i++) {
             $mp4 = $mp4s[$i];
             //$js .= $mp4['height'] . '<br>' . urlencode($mp4['url']) . '</br>';
             $js .= $mp4['height'] . '<source src="' . ($mp4['url']) . '" type="video/mp4" data-res="' . $mp4['height'] . 'px" ></source>';
         }
         return $js;
     }*/
	/* Linh dang 2:
        https://picasaweb.google.com/111504043269160975788/2015?authkey=Gv1sRgCL6C34T88NnycQ#6154583810546157330
        https://picasaweb.google.com/107381407253905053463/DungNhanTroChoiMaQuai?authkey=Gv1sRgCPWZvt73l8u8jwE#6220341408683541154
     */

	/*
     }*/

	/*function process_link_picasa3($video_ids, $server, &$subs = array(), &$product_file_id) {
        $video_id = '';
        product_get_data($video_ids, $video_id, $subs);
        $product_file_id = $video_id;

        $link = $server->url.'/'.$video_id;

        //get picasa page by default file_get_contents function
        $data = file_get_contents($link);
        $a = explode('"media":{"content":[', $data);
        $a = explode('],"', $a[1]);
        $datar = explode('},', $a[0]);
        foreach ($datar as $key => $value) {
            $value = str_replace("}}", "}", $value . "}");
            $mp4s[] = json_decode($value, true);
        }
        $links = array();
        for ($i = 1; $i < count($mp4s); $i++) {
            $mp4 = $mp4s[$i];
            $links[$mp4['height']] = $mp4['url'];
        }

        return $links;
    }*/
	// =================== Youtube =====================
	function process_link_youtube($id, $server, &$links = [])
	{
		//- lay cac chat luong
		//  $link = $server->url . '/watch?v=' . $id;
		// $list_link = lib('Youtube')->getLink($link);
		$link = " https://www.youtube.com/watch?v=" . $id;
		$link_processed = lib('glink')->get_link($link);
		if ($link_processed) {
			$link = $link_processed;
		}
		return $link;

	}

	function process_link_youtube_($id, $server, &$links = [])
	{
		// neu la link youtube
		if (preg_match("|^http(s)?://(www.)?youtube.([a-z]+)/(.*)?$|i", $id)) {
			$id = $this->getIdYouTube($id);
		}
		$url = $server->url . '/embed/' . $id;

		//- lay cac chat luong
		$link = $server->url . '/watch?v=' . $id;
		$list_link = lib('Youtube')->getLink($link);
		// pr($list_link);
		if ($list_link && count($list_link) > 0) {
			$i = true;
			foreach ($list_link as $video) {
				$links[$video['q']] = $video['link'];
				if ($i) {
					$url = $video['link'];
					$i = false;
				}
			}
			//$url= array_values($list_link)[0];
		}
		// pr($links);
		return $url;

	}

	// =================== Wowza =====================
	function process_link_wowza($url, $server)
	{
		$setting = json_decode($server->setting);
		$link = '';
		$port = ':1953';
		if (!empty($setting->port))
			$port = ':' . $setting->port;

		$wowzaContentPath = $setting->application . '/_definst_/mp4:' . $url;
		if ($setting->streaming == 'hls' || $setting->streaming == 'hlss') {
			if ($setting->streaming == 'hls')
				$link = 'http://' . $server->url . $port . '/' . $wowzaContentPath . '/playlist.m3u8';
			else
				$link = 'https://' . $server->url . $port . '/' . $wowzaContentPath . '/playlist.m3u8';
		} else
			$link = $setting->streaming . '://' . $server->url . $port . '/' . $wowzaContentPath;
		//pr($setting);

		if (!empty($setting->token)) {
			//  $ip = t('input')->ip_address ();
			$wowzaTokenPrefix = $setting->query_prefix;
			// $wowzaSecureTokenStartTime = $wowzaTokenPrefix . "starttime=" . time();
			//$wowzaSecureTokenEndTime = $wowzaTokenPrefix . "endtime=" . (time() + 86400);
			// $hashstr = $wowzaContentPath . "?" . $setting->token . "&" . $wowzaSecureTokenEndTime . "&" . $wowzaSecureTokenStartTime;
			$hashstr = $wowzaContentPath . "?" . $setting->token;
			$hash = hash('sha256', $hashstr, 1);
			$usableHash = strtr(base64_encode($hash), '+/', '-_');
			// $link = $link . "?" . $wowzaSecureTokenStartTime . "&" . $wowzaSecureTokenEndTime . "&" . $wowzaTokenPrefix . "hash=$usableHash";
			$link = $link . "?" . $wowzaTokenPrefix . "hash=$usableHash";
		}

		/*echo  '<br>$link1='.$link;
        $string = "hdfilme/sample.mp4?52b57a98e0dc0203";
        $hash = hash('sha256', $string, 1);
        $hash =base64_encode($hash);
        $link='http://151.80.99.22:1935/hdfilme/sample.mp4/playlist.m3u8?wowzatokenhash='.$hash;
        echo  '<br>$link2='.$link;*/

		//  http://http://http://118.70.187.232/:1935/:1935/vod/_definst_//_definst_/mp4:sample.mp4/playlist.m3u8
		//echo $link;       pr($setting);
		return $link;

	}

	// =================== NC =====================

	function process_link_nc($url, $server)
	{
		$setting = json_decode($server->setting);
		// pr($setting);
		$today = gmdate("n/j/Y g:i:s A");
		$initial_url = trim($server->url, '/') . "/" . $url;
		if ($setting->streaming == 'hls') {
			$initial_url .= '/playlist.m3u8';
		}
		$ip = $_SERVER['REMOTE_ADDR'];
		$key = $setting->secret; //this is also set up in WMSPanel rule
		$validminutes = $setting->time;

		$str2hash = $ip . $key . $today . $validminutes;
		$md5raw = md5($str2hash, true);
		$base64hash = base64_encode($md5raw);
		$urlsignature = "server_time=" . $today . "&hash_value=" . $base64hash . "&validminutes=$validminutes";
		$base64urlsignature = base64_encode($urlsignature);
		$signedurlwithvalidinterval = "$initial_url?wmsAuthSign=$base64urlsignature";

		return $signedurlwithvalidinterval;
	}

	// =================== Dedicated =====================

	function  process_link_dedicated($link, $server)
	{
		$url = $server->url . '/' . $server->target_folder . '/' . $link;
		$url = ltrim($url, '/');

		if ($server->secret_status) {
			$url = $server->url;

			if ($server->secret_type == 'lighttpd') {
				//lighhtp
				$secret = $server->secret;
				$uri_prefix = $server->uri_prefix;
				# set filename
				$f = '/' . $link;

				# set current timestamp
				$t = time();
				$t_hex = sprintf("%08x", $t);
				$m = md5($secret . $f . $t_hex);
				if ($server->port == '443') {
					$url = str_replace('http://', 'https://', $url);
				} elseif
				($server->port != '80'
				) {
					$url = $url . ':' . $server->port;
				}
				$url = $url . $uri_prefix . $m . "/" . $t_hex . $f;

			} elseif ($server->secret_type == 'nginx') {
				/*
                 * //ngix
                 * */
				$path = '/' . $link;//duong dan toi file tren server

				$expire = time() + $server->expire * 60; // At which point in time the file should expire. time() + x; would be the usual usage.

				$md5 = base64_encode(md5($server->secret . $path . $expire, true)); // Using binary hashing.
				$md5 = strtr($md5, '+/', '-_'); // + and / are considered special characters in URLs, see the wikipedia page linked in references.
				$md5 = str_replace('=', '', $md5); // When used in query parameters the base64 padding character is considered special.

				$url = $url . "?st=$md5&e=$expire";

			}

		}
		return $url;

	}


	// =================== Dailymotion =====================

	function process_link_dailymotion($url, $server)
	{
		/*  $video_id = '';
          product_get_data($video_ids, $video_id, $subs);
          $product_file_id = $video_id;

          $url = $server->url.'/'.$video_id.'?autoPlay=1';*/
		return $url;
	}

	// =================== OTHER =====================
	function process_link_other($id, $server, &$links = [])
	{
		$url = $server->url . '/' . $id;
		return $url;
	}

	//========================= Ma hoa link =======================
	// encode 1
	function encode_link($link)
	{
		//$link = urlencode($link);
		//return $link;
		$link = str_rot13($link);
		$link = base64_encode($link);
		return $link;
	}

	// encode 2
	function encode_link_($input)
	{
		//$input = urlencode($input);
		//return $input;
		$keyStr = "ABCDEFGHIJKLMNOP" . "QRSTUVWXYZabcdef" . "ghijklmnopqrstuv" . "wxyz0123456789";

		$output = "";
		$chr1 = $chr2 = $chr3 = "";
		$enc1 = $enc2 = $enc3 = $enc4 = "";
		$i = 0;

		do {
			$chr1 = ord(substr($input, $i++, 1));
			$chr2 = ord(substr($input, $i++, 1));
			$chr3 = ord(substr($input, $i++, 1));

			$enc1 = $chr1 >> 2;
			$enc2 = (($chr1 & 3) << 4) | ($chr2 >> 4);
			$enc3 = (($chr2 & 15) << 2) | ($chr3 >> 6);
			$enc4 = $chr3 & 63;

			if (empty ($chr2)) {
				$enc3 = $enc4 = 64;
			} else if (empty ($chr3)) {
				$enc4 = 64;
			}
			$output = $output . substr($keyStr, $enc1, 1) . substr($keyStr, $enc2, 1) . substr($keyStr, $enc3, 1) . substr($keyStr, $enc4, 1);

			$chr1 = $chr2 = $chr3 = "";
			$enc1 = $enc2 = $enc3 = $enc4 = "";
		} while ($i < strlen($input));

		return $output;
	}

	/*function _decode64($input){
      $keyStr = "ABCDEFGHIJKLMNOP". "QRSTUVWXYZabcdef". "ghijklmnopqrstuv" .  "wxyz0123456789";
      $output = "";
      $chr1 = $chr2 =$chr3 = "";
      $enc1 = $enc2 =$enc3 = $enc4 = "";
      $i = 0;


      $input = preg_replace("#[^A-Za-z0-9\+\/\=]#", "",$input);

      do {

         $enc1 = (int)strrpos($keyStr, substr($input,$i++,1));
         $enc2 = (int)strrpos($keyStr, substr($input,$i++,1));
         $enc3 = (int)strrpos($keyStr, substr($input,$i++,1));
         $enc4 = (int)strrpos($keyStr, substr($input,$i++,1));
         //echo $enc1.'-'.$enc2 .'-'.$enc3 .'-'.$enc4 ;
         $chr1 = ($enc1 << 2) | ($enc2 >> 4);
         $chr2 = (($enc2 & 15) << 4) | ($enc3 >> 2);
         $chr3 = (($enc3 & 3) << 6) | $enc4;

         $output = $output + chr($chr1);
        // echo '<br>chr='.chr($chr1);
         if ($enc3 != 64) {
            $output = $output + chr($chr2);
         }
         if ($enc4 != 64) {
            $output = $output + chr($chr3);
         }
         //echo '<br>output='.$output;
         $chr1 = $chr2 = $chr3 = "";
         $enc1 = $enc2 = $enc3 = $enc4 = "";

      } while ($i < strlen($input));

      return $output;
   }*/


	/*===============================================================
      * Other
     *===============================================================*/

	function _fb_url_get_shares($url)
	{

		//echo urlencode ( $url ) ;
		$url = get_data("https://graph.facebook.com/fql?q=SELECT+share_count+FROM+link_stat+WHERE+url='" . urlencode($url) . "'");
		//$url = file_get_contents ( "https://graph.facebook.com/fql?q=SELECT+like_count+FROM+link_stat+WHERE+url='" . urlencode ( $url ) . "'" );
		$rs = json_decode($url, true);

		if (isset($rs['data']) && isset($rs['data'][0]) && isset($rs["data"][0]["share_count"])) {
			return $rs["data"][0]["share_count"];
		} else {
			return 0;
		}


	}

	/*
     * Lay id youtube
    */
	function getIdYouTube($url)
	{
		$links = explode('?v=', $url);
		if (!isset($links['1'])) {
			return false;
		}
		$images = explode('&', $links['1']);
		if (!isset($images['0'])) {
			return false;
		}
		$videoId = $images['0'];
		return $videoId;
	}
}