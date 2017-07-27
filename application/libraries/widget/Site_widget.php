<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_widget extends MY_Widget {

	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		// Tai cac file thanh phan
		//$this->lang->load('widget/site');
	}

	/**
	 * Xu ly head
	 */
	function head(array $separators_custom = array(), $temp = '')
	{
		$config=setting_get_group('config');
		//pr($config,false);
		// Ki tu phan cach giua cac gia tri
		$separators = array();
		$separators['title'] 		= ' - ';
		$separators['description'] 	= ', ';
		$separators['keywords'] 	= ', ';
		$separators['robots'] 		= ', ';
		$separators = extend($separators, $separators_custom);

		// Lay thong tin
		$data = array();
		foreach (array('title', 'description', 'keywords', 'robots') as $p)
		{
			$v = page_info($p);
			$v = ( ! is_array($v)) ? array($v) : $v;
			$v = array_filter($v);
			$v = implode($separators[$p], $v);

			$data[$p] = $v;
		}

		// Xu ly robots
		if (empty($data['robots']))
		{
			//$c = $this->uri->rsegment(1);
			//$v = (in_array($c, array())) ? array('noindex', 'nofollow') : array('index', 'follow');
			$v = ($config['no_index']) ? array('noindex', 'nofollow') : array('index', 'follow');
			$data['robots'] = implode($separators['robots'], $v);
		}


		// Xu ly thong tin
		$data = $this->mod->seo_word->handle_page_info($data);


		$data['icon'] =public_url('site/images/icon.png') ;
		if(isset($config['favicon']) ){

			$favicon=file_get_image_from_name($config['favicon']);
			if($favicon)
				$data['icon'] =$favicon->url;
		}

		$data['css']=[];
		if(isset($separators_custom['css'])){
			$data['css'] =$separators_custom['css'];
		}
		$data['js']=[];
		if(isset($separators_custom['js'])){
			$data['js'] =$separators_custom['js'];
		}


		$data['embed_js']='';
		if(isset($config['embed_js'])){
			$data['embed_js']=$config['embed_js'];
		}
		$data['meta_other']='';
		if(isset($config['meta_other'])){
			$data['meta_other'] = html_entity_decode($config['meta_other']) ;
		}
		// Luu bien gui den view
		$this->data = $data;
		//pr($data);
		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/site/head' : $temp;
		$this->load->view($temp, $this->data);
	}

	/**
	 * Hien thi thong bao
	 */
	function message($message = array())
	{
		// Neu khai bao theo kieu: message($type, $message)
		if (func_num_args() == 2)
		{
			$t = func_get_arg(0);
			$m = func_get_arg(1);
			$message = array($t => $m);
		}
		elseif (is_string($message))
		{
			$message = array('info' => $message);
		}

		$message = empty($message) ? get_message() : $message;

		if (empty($message)) return;

		foreach ($message as $t => $m)
		{
			$message[$t] = is_array($m) ? $m : array($m);
		}

		$this->data['message'] = $message;
		$this->data['message_display'] = get_message_display('modal');
		$this->load->view('tpl::_widget/common/message', $this->data);
	}

	/**
	 * Hien thi phan trang
	 */
	function pages(array $config = array(), $temp = 'pages')
	{
		$this->load->library('pagination');

		if ( ! isset($config['page_query_string']))
		{
			$config['page_query_string'] = TRUE;
		}

		$this->data['config'] = $config;

		$this->load->view('tpl::_widget/common/'.$temp, $this->data);
	}

	/**
	 * Tao cay thu muc den trang hien tai
	 */
	function breadcrumbs(array $items = array(), $temp = '')
	{
		// Lay input
		$items = empty($items) ? page_info('breadcrumbs') : $items;
		$items = ( ! is_array($items)) ? array() : $items;
		if (empty($items))
		{
			$title = (array) page_info('title');
			$items = array(array(current_url(), head($title)));
		}
		elseif ( ! is_array(reset($items)))
		{
			$items = [$items];
		}

		array_unshift($items, array(site_url(), lang('home_page')));


		// Xu ly items
		$i = 1;
		$count = count($items);
		$list = array();
		foreach ($items as $item)
		{
			$row = array();
			$row['url'] 	= $item[0];
			$row['name'] 	= $item[1];
			$row['title'] 	= (isset($item[2])) ? $item[2] : $item[1];
			$row['current'] = ($i == $count) ? TRUE : FALSE;
			$list[] = $row;

			$i++;
		}

		$this->data['items'] = $list;

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/common/breadcrumbs' : $temp;
		$this->load->view($temp, $this->data);
	}

	/**
	 * Hien thi form upload file
	 */
	function upload($config, $config_html = array())
	{
		// Tai cac file thanh phan
		$this->lang->load('admin/file');

		// Xu ly config dau vao
		$config['mod'] 			= ( ! isset($config['mod'])) ? 'single' : $config['mod'];
		$config['file_type'] 	= ( ! isset($config['file_type'])) ? 'file' : $config['file_type'];
		$config['server'] 		= ( ! isset($config['server'])) ? TRUE : $config['server']['status'];
		// Lay config
		$config_main = config('upload', 'main');
		$upload_config = array();
		$upload_config['max_size'] = $config_main['max_size'];
		$upload_config['allowed_types'] = ($config['file_type'] == 'image') ? $config_main['img']['allowed_types'] : $config_main['allowed_types'];
		if ( ! empty($config['allowed_types']))
		{
			$upload_config['allowed_types'] = $config['allowed_types'];
		}

		// Xay dung query upload
		$upload_query = array();
		foreach (array('mod', 'file_type', 'allowed_types', 'status', 'server', 'table', 'table_id', 'table_field',
					 'resize', 'resize_width', 'resize_height', 'thumb', 'thumb_width', 'thumb_height', 'field',
				 ) as $param)
		{
			$upload_query[$param] = (isset($config[$param])) ? $config[$param] : '';
		}
		// Lay duong dan
		$upload_url = array();
		$upload_url['upload'] 	= site_url('file/upload').'?'.security_create_query($upload_query, 'upload');
		$upload_url['update'] 	= (isset($config['url_update'])) ? $config['url_update'] : '';

		if (isset($config['url_get']))
		{
			$upload_url['get'] = $config['url_get'];
		}
		else
		{
			$query = array();
			$query['table'] 		= $config['table'];
			$query['table_id'] 		= $config['table_id'];
			$query['table_field'] 	= $config['table_field'];

			switch ($config['mod'])
			{
				case 'single':
				{
					//$upload_url['get'] = site_url('file/get').'?'.http_build_query($query);
					$upload_url['get'] = site_url('file/get').'?'.security_create_query($query);
					break;
				}

				case 'multi':
				{
					$query['file_type'] = $config['file_type'];
					$query['sort'] = array_get($config, 'sort', true);
					//$upload_url['get'] = site_url('file').'?'.http_build_query($query);
					$upload_url['get'] = site_url('file/index').'?'.security_create_query($query);
					break;
				}

				default:
				{
					return FALSE;
				}
			}
		}

		// Luu bien gui den view
		$this->data['upload_config'] 	= $upload_config;
		$this->data['upload_url'] 		= $upload_url;
		$this->data['config_html'] 		= $config_html;

		// Lay file temp
		$temp = (isset($config_html['temp'])) ? $config_html['temp'] : '';
		if ( ! $temp)
		{
			$temp = $config['mod'];
			$temp .= ($config['file_type'] == 'image') ? '_image' : '';
			$temp = 'tpl::_widget/upload/'.$temp;
		}

		// Hien thi view
		$this->load->view($temp, $this->data);
	}

	/**
	 * Danh sach tien te
	 */
	function currency($temp = '')
	{
		// Tai file thanh phan
		$this->load->model('currency_model');

		// Lay tien te hien tai
		$currency_cur = currency_get_cur();
		$currency_cur = site_create_url('currency', $currency_cur);
		$this->data['currency_cur'] = $currency_cur;

		// Lay danh sach tien te
		$currency_list = $this->currency_model->get_list_active_show('id, name, code');
		foreach ($currency_list as $row)
		{
			$row = site_create_url('currency', $row);
		}
		$this->data['currency_list'] = $currency_list;

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/common/currency' : $temp;
		$this->load->view($temp, $this->data);
	}

	/**
	 * Danh sach ngon ngu
	 */
	function lang_($temp = '')
	{
		// Tai file thanh phan
		$this->load->model('lang_model');

		// Lay ngon ngu hien tai
		$lang_cur = lang_get_cur();
		$lang_cur = lang_add_info($lang_cur);
		$lang_cur = site_create_url('lang', $lang_cur);
		$this->data['lang_cur'] = $lang_cur;

		// Lay danh sach ngon ngu
		$lang_list = $this->lang_model->get_list_active('id, name, directory');
		foreach ($lang_list as $row)
		{
			$row = lang_add_info($row);
			$row = site_create_url('lang', $row);
		}
		$this->data['lang_list'] = $lang_list;

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/common/lang' : $temp;
		$this->load->view($temp, $this->data);
	}
	function lang($temp = '')
	{
		// Tai file thanh phan
		$this->load->model('lang_model');
		$uri = $this->uri->uri_string();
		// Lay ngon ngu hien tai
		$lang_de = lang_get_default();
		$url_suffix	= config('url_suffix', '');
		// Lay danh sach ngon ngu
		$lang_list = $this->lang_model->get_list_active('id, name, directory');
		foreach ($lang_list as $row)
		{
			$row = lang_add_info($row);
			if($row->id == $lang_de->id)
				$row->_url = base_url($uri).($uri ? $url_suffix : '');
			else
				$row->_url = base_url($row->directory.'/'.$uri).$url_suffix;
		}
		$this->data['lang_list'] = $lang_list;

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/common/lang' : $temp;
		$this->load->view($temp, $this->data);
	}

	/**
	 * Danh sach the loai
	 */
	function cat($cat_cur = 0, $temp = '')
	{
		// Lay danh sach cat
		$this->load->model('cat_model');
		$cats = $this->cat_model->get_list_level();
		$cats = $this->_cat_add_info($cats);

		// Luu bien gui den view
		$this->data['cats'] 	= $cats;
		$this->data['cat_cur'] 	= $cat_cur;

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/cat' : $temp;
		$this->load->view($temp, $this->data);
	}

	function _cat_add_info($list)
	{
		foreach ($list as $row)
		{
			$row = site_create_url('product_cat', $row);
			$row->_sub = $this->_cat_add_info($row->_sub);
		}

		return $list;
	}


	/**
	 * Quang cao
	 */
	function ads($location, $temp = '')
	{
		// Tai file than phan
		$this->load->helper('file');
		$this->load->model('ads_location_model');
		$this->load->model('ads_banner_model');

		// Lay thong tin location
		$where = array();
		$where['code'] = $location;
		$location = $this->ads_location_model->get_info_rule($where);
		if ( ! $location)
		{
			return;
		}

		// Lay danh sach banner
		$input = array();
		$input['where']['ads_location_id'] = $location->id;
		$input['where']['end >='] = now();
		$input['order'] = array('sort_order', 'asc');
		if ($location->banner_quantity)
		{
			$input['limit'] = array(0, $location->banner_quantity);
		}

		$banners = $this->ads_banner_model->get_list($input);
		foreach ($banners as $row)
		{
			$row->image = file_get_image_from_name($row->image_name, 'ads_banner')->url;
		}

		// Luu cac bien
		$this->data['location'] = $location;
		$this->data['banners'] 	= $banners;

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/ads' : $temp;
		$this->load->view($temp, $this->data);
	}

	/**
	 * Hien thi menu tai cac vi tri
	 */
	function menu($menu, $temp = '')
	{
		// Lay danh sach item
		$this->load->model('menu_item_model');
		$this->data['menu'] = $this->menu_item_model->get($menu);

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/menu' : $temp;
		$this->load->view($temp, $this->data);
	}



	/**
	 * Tim kiem
	 */
	function search($type = '', $key = '', $temp = '')
	{
		// Lay type
		$type = ( ! $type) ? $this->uri->rsegment(1) : $type;
		$type = ( ! in_array($type, array('news'))) ? 'news' : $type;

		// Tao url search
		$urls = array();
		$urls['news'] 		= site_create_url('news_page', 'search');

		// Luu bien gui den view
		$this->data['type'] = $type;
		$this->data['key'] 	= $key;
		$this->data['urls'] = $urls;

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/search' : $temp;
		$this->load->view($temp, $this->data);
	}



	function setting($key)
	{
		static $settings=null;
		if(!$settings)
			$settings = setting_get_group('config');
		return isset($settings[$key])?$settings[$key]:'';
	}
	function setting_image($key="logo")
	{
		$logo = public_url('site/theme/images/logo.png');
		$logo_uploaded = $this->setting($key);
		if ($logo_uploaded) {
			$logo_uploaded = file_get_image_from_name($logo_uploaded);
			if ($logo_uploaded)
				$logo = $logo_uploaded->url;
		}
		return $logo;
	}

	/**
	 * Sayus
	 */
	function sayus($size = 4, $temp = '')
	{
		// Lay tin tieu diem
		$this->load->model('Msayus');
		$this->load->helper("sayus");
		$input['limit'] = array('limit'=> $size);
		$list=$this->Msayus->getList($input);
		for ($i=0;$i<count($list);$i++)
		{
			$list[$i]=sayusAddInfo($list[$i]);
		}
		$this->data['list']=$list;
		$temp = (!$temp) ? 'site/_widget/site/sayus' : $temp;
		$this->load->view($temp, $this->data);
	}

	function social()
	{
		$this->load->view('site/_widget/site/social');
	}
	/**
	 * Support
	 */
	function support_($op=array(), $temp = '')
	{
		// Tai cac file thanh phan
		$this->load->model('Msupport');
		$this->load->helper("support");
		$input = array();
		if(isset($op['group']))
			$input['where']['group_id']=$op['group'];
		if(isset($op['limit']))
			$input['limit'] = array('limit'=>$op['limit']);
		$input['order'] = array('ordering', 'asc');
		$list=$this->Msupport->getList($input);
		for ($i=0;$i<count($list);$i++)
		{
			$list[$i]=supportAddInfo($list[$i]);
		}
		$this->data['list'] = $list;

		$temp = (!$temp) ? 'site/_widget/site/support' : $temp;
		$this->load->view($temp, $this->data);
	}





	/**
	 * Hien thi h? tr? tr?c tuy?n
	 */
	function support($temp = '')
	{
		// Tai cac file thanh phan
		$this->load->model('support_model');
		$this->load->model('support_group_model');

		// Lay danh sach
		$input = array();
		$input['order'] = array('sort_order', 'asc');

		$list = $this->support_group_model->get_list($input);

		foreach ($list as $row)
		{
			$input['where'] = array('group_id' => $row->id);
			$row->support   = $this->support_model->get_list($input);
		}

		$this->data['support'] = $list;

		// Hien thi view
		$temp = (!$temp) ? 'site/widget/support' : $temp;
		$this->load->view($temp, $this->data);

	}
	function follow($temp = ''){
		$_data['settings'] = setting_get_group('config');
		$temp = ( ! $temp) ? 'tpl::_widget/site/follow' : $temp;
		$this->load->view($temp, $_data);
	}

	/**
	 * C?u hình giao di?n
	 */
	function template_config()
	{
		$this->load->model('template_config_model');
		$configs = array();
		$temp_configs = $this->template_config_model->get();
		foreach ($temp_configs as $row)
		{
			$row->value = handle_content($row->value, 'output');
			$configs[$row->location] = $row;
		}
		$this->data['configs'] 	= $configs;
		if(!empty($configs))
		{
			// Hien thi view
			$this->load->view('tpl::_widget/common/template_config', $this->data);
		}
	}



}