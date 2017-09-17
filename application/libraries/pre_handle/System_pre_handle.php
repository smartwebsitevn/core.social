<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_pre_handle extends MY_Pre_handle
{
	private $_settings=array();
	/**
	 * Goi cac ham xu ly
	 */
	public function boot()
	{
		// Xu ly cac thiet lap cau hinh cua he thong
		$this->init();

		$this->log_access();
		// Xu ly theo area
		if (get_area() == 'admin')
		{
			$this->pre_handle->admin->boot();
		}
		else 
		{
			$this->pre_handle->site->boot();
		}
	}
	/**
	 * Cai dat he thong
	 */
	protected function init()
	{
		$this->_settings = setting_get_group('config');
		//pr($this->_settings);
		// === Server
		//$this->config->set_item('base_url',$this->_settings['base_url']);
		$this->config->set_item('server_ip',$this->_settings['server_ip']);
		// he thong chay sau proxy
		if($this->_settings['proxy_ips'])
			$this->config->set_item('proxy_ips', $_SERVER['REMOTE_ADDR']);

		//loc Xss
		if($this->_settings['xss_protect'])
			$this->config->set_item('global_xss_filtering', TRUE);
		else
			$this->config->set_item('global_xss_filtering', FALSE);
		//Su dung Seo Url

		if($this->_settings['use_seo_url'])
			$this->config->config['main']['seo_url'] = TRUE;
		else
			$this->config->config['main']['seo_url'] = FALSE;

		if($this->_settings['use_ssl'])
			$this->config->config['main']['use_ssl'] = TRUE;
		else
			$this->config->config['main']['use_ssl'] = FALSE;

		// Log
		if($this->_settings['log_error'])
			$this->config->set_item('log_threshold', TRUE);
		else
			$this->config->set_item('log_threshold', FALSE);

		if($this->_settings['log_access'])
			$this->config->config['main']['log_access'] = TRUE;
		else
			$this->config->config['main']['log_access'] = FALSE;

		if($this->_settings['log_activity'])
			$this->config->config['main']['log_activity'] = TRUE;
		else
			$this->config->config['main']['log_activity'] = FALSE;

		if($this->_settings['log_user_balance'])
			$this->config->config['main']['log_user_balance'] = TRUE;
		else
			$this->config->config['main']['log_user_balance'] = FALSE;

		// Upload
		// limit type upload
		if($this->_settings['upload_allowed_types'])
			$this->config->config['main']['upload']['allowed_types'] = implode('|',$this->_settings['upload_allowed_types']);
		else
			$this->config->config['main']['upload']['allowed_types']="*";
		// limit upload site
		if($this->_settings['upload_max_size'])
			$this->config->config['main']['upload']['max_size'] = $this->_settings['upload_max_size']*1024;
		// limit upload admin
		if($this->_settings['upload_max_size_admin'])
			$this->config->config['main']['upload']['max_size_admin'] = $this->_settings['upload_max_size_admin']*1024;

		//Captcha
		$this->config->config['main']['captcha_type'] = $this->_settings['captcha_type'];
		if($this->_settings['captcha_type'] == 'google'){
			$this->config->config['main']['captcha_google_api_url'] = $this->_settings['captcha_google_api_url'];
			$this->config->config['main']['captcha_google_secret_key'] = $this->_settings['captcha_google_secret_key'];
			$this->config->config['main']['captcha_google_site_key'] = $this->_settings['captcha_google_site_key'];
		}
		// === Security
		mod('user')->set_setting('register_allow',$this->_settings['user_register_allow']);
		mod('user')->set_setting('register_require_activation',$this->_settings['user_register_require_activation']);
		mod('user')->set_setting('register_banned_countries',$this->_settings['user_register_banned_countries']);

		mod('user')->set_setting('login_allow',$this->_settings['user_login_allow']);
		//mod('user')->set_setting('login_auth_allow',$this->_settings['user_login_auth_allow']);
		mod('user')->set_setting('login_fail_count_max',$this->_settings['user_login_fail_count_max']);
		mod('user')->set_setting('login_fail_block_timeout',$this->_settings['user_login_fail_block_timeout']);
		mod('user')->set_setting('login_check_ip',$this->_settings['user_login_check_ip']);
		mod('user')->set_setting('balance_block',$this->_settings['user_balance_block']);
		mod('user')->set_setting('balance_timeout_from_register',$this->_settings['user_balance_timeout_from_register']);


		// === Image
		if(isset($this->_settings['upload_img_max_width']))
			$this->config->config['main']['upload']['img']['max_width'] = $this->_settings['upload_img_max_width'];
		if(isset($this->_settings['upload_img_max_height']))
			$this->config->config['main']['upload']['img']['max_height'] = $this->_settings['upload_img_max_height'];
		/*if(isset($this->_settings['upload_img_resize_width']))
			$this->config->config['main']['upload']['img']['resize_width'] = $this->_settings['upload_img_resize_width'];
		if(isset($this->_settings['upload_img_resize_height']))
			$this->config->config['main']['upload']['img']['resize_height'] = $this->_settings['upload_img_resize_height'];*/

		if(isset($this->_settings['upload_img_thumb_width']))
			$this->config->config['main']['upload']['img']['thumb_width'] = $this->_settings['upload_img_thumb_width'];
		if(isset($this->_settings['upload_img_thumb_height']))
			$this->config->config['main']['upload']['img']['thumb_height'] = $this->_settings['upload_img_thumb_height'];

		for($i=1;$i<=5 ;$i++){
				if(isset($this->_settings['upload_img_thumb'.$i.'width']))
					$this->config->config['main']['upload']['img']['thumb'.$i.'_width'] = $this->_settings['upload_img_thumb'.$i.'_width'];
				if(isset($this->_settings['upload_img_thumb'.$i.'_height']))
					$this->config->config['main']['upload']['img']['thumb'.$i.'_height'] = $this->_settings['upload_img_thumb'.$i.'_height'];

			}
		$this->config->config['main']['upload']['server']['status'] = false;
		if(isset($this->_settings['upload_server_status'])){
			$this->config->config['main']['upload']['server']['status'] = $this->_settings['upload_server_status'];
			$this->config->config['main']['upload']['server']['save_on_local'] = $this->_settings['upload_server_status'];
			$this->config->config['main']['upload']['server']['url'] = $this->_settings['upload_server_url'];
			$this->config->config['main']['upload']['server']['hostname'] = $this->_settings['upload_server_hostname'];
			$this->config->config['main']['upload']['server']['username'] = $this->_settings['upload_server_username'];
			$this->config->config['main']['upload']['server']['password'] = $this->_settings['upload_server_password'];

		}
		// lay thiet lap he thong
	   //pr(get_config());


		//== Ban dia hoa
		$this->local();


		// Cap nhat ngon ngu hien tai
		if (config('language_multi', 'main'))
		{
			$this->lang();
		}
		else{
			\Carbon\Carbon::setLocale('vi');
		}

		// Load file ngon ngu chinh
		$this->lang->load('common');
		// load file do admin tu them
		$this->lang->load('additional');

		// Cap nhat currency hien tai
		if (config('currency_multi', 'main'))
		{
			$this->currency();
		}
	}
	/**
 *Su ly van de local (ban dia hoa moi truong ung dung)
 */
	protected function local()
	{

		// kiem tra xem co thiet lap ban ip
		if (get_area() == 'admin') {
			// su ly banned ip
			if ($this->_settings['banned_ips']){
				// lay ip hien tai
				$ip = $this->input->ip_address ();
				$banned_ips =  explode ( "\n", $this->_settings['banned_ips'] );
				if($banned_ips && in_array($ip,$banned_ips))
					die('Banned IP');
			}

			// su ly  banned country
			if ($this->_settings['banned_countries']){
				// lay coutry hien tai
				$ip = $this->input->ip_address ();

				$api = lib('Geoip')->country($ip);
				// neu lay duoc quoc gia thi moi check
				if($api){
					$country_code = (isset($api->country->isoCode)) ? $api->country->isoCode : '';
					if($this->_settings['banned_countries'] && in_array($country_code,$this->_settings['banned_countries']))
						die('Banned Country');
				}
			}
		}
		// Su ly timezone
		if (function_exists('date_default_timezone_set'))
		{
			date_default_timezone_set($this->_settings['timezone']);//'Asia/Ho_Chi_Minh'
		}

		// su ly dinh dang ngay thang
		if($this->_settings['date_format'])
		{
			$this->config->config['main']['date_format_display'] = $this->_settings['date_format'];
			$this->config->config['main']['date_format_display_time'] = $this->_settings['date_format'].' - %H:%i';
			$this->config->config['main']['date_format_display_full'] = $this->_settings['date_format'].' - %H:%i:%s';
		}

		/*$config['date_format']			= '%d-%m-%Y';
		$config['date_format_time']		= '%d-%m-%Y - %H:%i';
		$config['date_format_full']		= '%d-%m-%Y - %H:%i:%s';*/


	}
	/**
	 * Cap nhat ngon ngu cua he thong
	 */
	protected function lang()
	{
		//======= Su ly ngon ngu trong admin
		if (get_area() == 'admin'){
			// kiem tra xem phien lam viec co change ngon ngu khong
			//$lang_id = $this->session->userdata('_admin_language');
			$lang_id = get_cookie('admin_lang');
			$lang_id = ( ! is_numeric($lang_id)) ? 0 : $lang_id;
			if(!$lang_id){
				// neu ko co luu trong phien thi lay trong cau hinh da thiet lap
				$lang_id =$this->_settings['admin_language'];
			}

		}
		//======= Su ly ngon ngu phia Site
		else
		{
			// Lay lang_id trong cookie
			$lang_id = lang_get_cur()->id;//get_cookie('lang_id');
			$lang_id = ( ! is_numeric($lang_id)) ? 0 : $lang_id;
			if(!$lang_id){
				// neu ko co luu trong phien thi lay trong cau hinh da thiet lap
				$lang_id =$this->_settings['site_language'];
			}

		}

		// Kiem tra lang_id nay co ton tai hay khong
		$lang = model('lang')->get_info_active($lang_id, 'id, directory');
		// Neu khong ton tai thi lay ngon ngu khac
		if ( ! $lang)
		{
			$lang = model('lang')->get_list_active('id, directory');
			if(!$lang)
				return;
			$lang = $lang[0];
		}
		$this->config->set_item('language', $lang->directory);
		\Carbon\Carbon::setLocale($lang->directory);

	}
	
	/**
	 * Cap nhat tien te hien tai
	 */
	protected function currency()
	{
		// Lay currency_id trong cookie
		$currency_id = get_cookie('currency_id');
		$currency_id = ( ! is_numeric($currency_id)) ? 0 : $currency_id;
		
		// Kiem tra currency_id nay co ton tai hay khong
		$currency = model('currency')->get_info_active_show($currency_id, 'id');
		
		// Neu khong ton tai thi gan bang currency mac dinh
		if ( ! $currency)
		{
			$currency = currency_get_default();
			
			// Cap nhat cookie
			set_cookie('currency_id', $currency->id, config('cookie_expire', 'main'));
		}
	}
	/**
	 * Cap nhat tien te hien tai
	 */
	protected function log_access()
	{
		return;
		if(config('log_access'))
		{
			//lib('log_access')->log();
			mod('log_access')->write_log();
		}
	}

}