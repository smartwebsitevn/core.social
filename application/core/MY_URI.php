<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_URI extends CI_URI
{

	/*
	 *  current language
	 */
	public $langcur = '';
	public $langcur_id = 0;

	protected function _parse_request_uri()
	{
		// lay chuoi uri tu xu ly cor
		$parent = parent::_parse_request_uri();

		// Remove the URL suffix, if present
		if (($suffix = (string) $this->config->item('url_suffix')) !== '')
		{
			$slen = strlen($suffix);

			if (substr($parent, -$slen) === $suffix)
			{
				$parent = substr($parent, 0, -$slen);
			}
		}

		$uri = explode('/', $parent);
		// neu lanh admin
		if(isset($uri[0]) && $uri[0] == $this->config->item('admin_folder'))
			return $parent;

		// lay danh sach lang
		$path = $this->config->item('cache_path');
		$_cache_path = (($path == '') ? APPPATH.'cache'.DS : $path).'lang';
		if(!file_exists($_cache_path))
			return $parent;

		$lang = file_get_contents($_cache_path);
		if(!$lang)
			return $parent;

		$lang = @unserialize($lang);
		//var_dump($lang);die();
		if(!is_array($lang))
			return $parent;

		//echo $uri[0];echo '<pre>';print_r($lang);echo '</pre>';
		// kiem tra xem lang co o trong uri 0 ko?		
		if(isset($uri[0]) && isset($lang[$uri[0]]))
		{
			// neu co thuc hien thay doi va luu lang
			$parent = substr(trim($parent), strlen($uri[0]));
			$parent = $this->_remove_relative_directory($parent);
			$this->langcur_id = $lang[$uri[0]]['id'];
			// neu la mac dinh thi de site_url co tien to lang = ''
//			if(!$lang[$uri[0]]['is_default'])
			$this->langcur = $uri[0];
		} else {
			foreach($lang as $ky => $ro) {
				if($ro['is_default']) {
					$this->langcur_id = $ro['id'];
					$this->langcur = '';
					break;
				}
			}
		}
		return $parent;
	}
}