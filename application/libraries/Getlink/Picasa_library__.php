<?php

class Picasa_library
{
	private $link;
	private $type;
	private $obj_array;



	public function get_links($link)
	{
		$this->link	  = $link;
		if (preg_match('/directlink/', $this->link)) {
			$this->type = 1;
		} else {
			$this->type =  2;
		}
		$this->obj_array = $this->get_json($this->get_xml_link());

		$this->get_480p_mp4();
	}

	public function view_source()
	{
		$timeout = 15;
		$ch	  = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->link);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return false;
		} else {
			return $result;
		}
	}

	public function get_xml_link()
	{
		$source = $this->view_source($this->link);

		if (!$source) {
			echo 'Link die';
			exit();
		}
		$xml_link = '';
		switch ($this->type) {
			case 1:
				$xml_link = explode('"application/atom+xml","href":"', $source)[1];
				$xml_link = explode('"}', $xml_link)[0];
				break;
			case 2:
				$start	= strpos($source, 'https://picasaweb.google.com/data/feed/base/user/');
				$end	  = strpos($source, '?alt=');
				$xml_link = substr($source, $start, $end - $start);
				$photoid = trim(explode('#', $this->link)[1], ' ');
				$xml_link .= '/photoid/' . $photoid . '?alt=jsonm&authkey=';
				$xml_link .= explode('#', explode('authkey=', $this->link)[1])[0];
				$xml_link = str_replace('base', 'tiny', $xml_link);
				break;
		}

		return $xml_link;
	}

	public function get_json($xml_link)
	{
		$sourceJson = file_get_contents($xml_link);
		$decodeJson = json_decode($sourceJson);
		return $decodeJson->feed->media->content;
	}

	public function get_720p_mp4()
	{
		for ($i = count($this->obj_array) - 1; $i >= 0; $i--) {
			if ($this->obj_array[$i]->type == 'video/mpeg4') {
				return $this->obj_array[$i]->url;
			}
		}
	}

	public function get_480p_mp4()
	{
		for ($i = 0; $i < count($this->obj_array); $i++) {
			if ($this->obj_array[$i]->type == 'video/mpeg4') {
				return $this->obj_array[$i]->url;
			}
		}
	}
}
/*
 // su dung
include 'ClassPicasa.php';
$Url_1 = 'https://picasaweb.google.com/lh/photo/8ZZnCemRJfb4QjJsJtwQXNOydrU-8nQfVWbvDyT43k8?feat=directlink';
$Url_2 = 'https://picasaweb.google.com/103219276718020854069/Op?authkey=Gv1sRgCPih7_WYnbGKtAE#6038015163887814978';
$Url_3 = 'https://picasaweb.google.com/106600393574771987734/FT?authkey=Gv1sRgCKnRhK_ti4LgnQE#6170462589846807330';
// Bạn có thể ngẫu nhiên chọn một trong ba đường dẫn trên để gán vào biến $Picasa
$Picasa = new Picasa($Url_1);
Echo $Picasa->get_480p_mp4() . '<br />';
Echo $Picasa->get_720p_mp4() . '<br />';
*/
?>
