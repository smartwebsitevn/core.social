<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_widget extends MY_Widget
{
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		// Tai cac file thanh phan
		//$this->lang->load('widget/admin');
	}
	
	/**
	 * Xu ly head
	 */
	function head()
	{
		$config=setting_get_group('config');
		$data['title']=$config['name'];
		$data['icon'] =public_url('site/images/icon.png') ;
		if(isset($config['favicon']) ){

			$favicon=file_get_image_from_name($config['favicon']);
			if($favicon)
				$data['icon'] =$favicon->url;
		}
		$this->data = $data;
		// Hien thi view
		$this->load->view('tpl::_widget/head',$this->data);
	}
	
	/**
	 * Menu dieu khien chinh
	 */
	function menu($group_cur = '', $item_cur = '', $temp = '')
	{
		$menu = config('menu', 'widget/admin');
		
		$menu_lang = config('menu_lang', 'widget/admin');
		$menu_icon_group = config('menu_icon_group', 'widget/admin');
		
		$items_url = config('menu_url', 'widget/admin');
		
		
		// Them danh sach module vao group module
		if (isset($menu['module']))
		{
			$this->load->model('module_model');
			
			$input = array();
			$input['select'] = 'key, name';
			$modules = $this->module_model->get_list($input);
			foreach ($modules as $row)
			{
				$_setting = module($row->key)->setting_get_config();
				$_table = module($row->key)->table_get_config();
				
				if (empty($_setting) && empty($_table)) continue;
				
				$k = 'module_'.$row->key;
				
				$menu['module'][] = $k;
				$menu_lang['module'][$k] = $row->name;
				$items_url['module'][$k] = module_url( 'admin',$row->key,'setting');
			}
		}

		if(!config('language_multi', 'main') && isset($menu['lang']))
			unset($menu['lang']);

		// Tao url cho menu
		$menu_url = array();
		foreach ($menu as $group => $items)
		{
			foreach ($items as $k => $item)
			{
				if($item == '-') continue;
				$url = (isset($items_url[$group][$item])) ? $items_url[$group][$item] : admin_url($item);
				if ( ! module_can_access(url_parse($url)->controller))
				{
					unset($menu[$group][$k]);
				}
				elseif ( ! admin_permission_url($url))
				{
					unset($menu[$group][$k]);
				}
				else 
				{
					$menu_url[$group][$item] = $url;
				}
			}
			
			// Neu group khong con item nao thi remove group
			if ($group != 'home' && ! count($menu[$group]))
			{
				unset($menu[$group]);
			}
		}


		// Lay group, item hien tai
		if ( ! $group_cur && ! $item_cur)
		{
			// Lay ra cac item co url la cha cua url hien tai
			$items_cur 	= array();
			$url_cur	= url_remove_suffix(current_url());
			foreach ($menu_url as $group => $items)
			{
				foreach ($items as $item => $url)
				{
					$url = url_remove_suffix($url);
					if (preg_match('#^'.preg_quote($url).'#i', $url_cur))
					{
						$items_cur[$group][$item] = $url;
					}
				}
			}
			
			// Item hien tai la item co url gan url hien tai nhat (co chieu dai lon nhat)
			$url_cur_len = 0;
			foreach ($items_cur as $group => $items)
			{
				foreach ($items as $item => $url)
				{
					$url_len = strlen($url);
					if ($url_len >= $url_cur_len)
					{
						$group_cur 	= $group;
						$item_cur 	= $item;
						$url_cur_len = $url_len;
					}
				}
			}
		}
		
		
		// Luu bien gui den view
		$this->data['menu'] 		= $menu;
		$this->data['menu_url'] 	= $menu_url;
		$this->data['menu_icon_group'] 	= $menu_icon_group;
		$this->data['menu_lang'] 	= $menu_lang;
		$this->data['group_cur'] 	= $group_cur;
		$this->data['item_cur'] 	= $item_cur;
		
		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/menu' : $temp;
		$this->load->view($temp, $this->data);
	}
	
	/**
	 * Bang dieu khien cua account
	 */
	function account_panel($temp = '')
	{
		// Lay thong tin cua account hien tai
		$admin = admin_get_account_info();
		$admin = admin_add_info($admin);
		$this->data['acount'] = $admin;
		
		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/account_panel' : $temp;
		$this->load->view($temp, $this->data);
	}
	
	/**
	 * Tao cay thu muc den trang hien tai
	 */
	function breadcrumbs($input)
	{
		$items = array();
		$items[] = array(admin_url(), lang('mod_home'));
		foreach ($input as $value)
		{
			$items[] = $value;
		}
		
		$i = 1;
		$count = count($items);
		$data = array();
		foreach ($items as $item)
		{
			$data_item = array();
			$data_item['url'] = $item[0];
			$data_item['name'] = $item[1];
			$data_item['current'] = ($i == $count) ? TRUE : FALSE;
			$data[] = $data_item;
			
			$i++;
		}
		
		$this->data['items'] = $data;
		$this->load->view('tpl::_widget/breadcrumbs', $this->data);
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
		$this->data['message_display'] = get_message_display('text');
		$this->load->view('tpl::_widget/message', $this->data);
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
		
		$this->load->view('tpl::_widget/'.$temp, $this->data);
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
		$upload_config['max_size'] = $config_main['max_size_admin'];
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
		$upload_url['upload'] 	= admin_url('file/upload').'?'.security_create_query($upload_query, 'upload');
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
					$upload_url['get'] = admin_url('file/get').'?'.http_build_query($query);
					break;
				}
				
				case 'multi':
				{
					$query['file_type'] = $config['file_type'];
					$query['sort'] = array_get($config, 'sort', true);
					$upload_url['get'] = admin_url('file').'?'.http_build_query($query);
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
	function upload_adv($config, $config_html = array())
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
		$upload_config['max_size'] = $config_main['max_size_admin'];
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
		$upload_url['upload'] 	= admin_url('file_adv/upload').'?'.security_create_query($upload_query, 'upload');
		$upload_url['update'] 	= (isset($config['url_update'])) ? $config['url_update'] : '';
		//pr($upload_url);
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
					$upload_url['get'] = admin_url('file/upload_adv').'?'.http_build_query($query);
					break;
				}

				case 'multi':
				{
					$query['file_type'] = $config['file_type'];
					$query['sort'] = array_get($config, 'sort', true);
					$upload_url['get'] = admin_url('file/upload_adv').'?'.http_build_query($query);
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
			$temp = 'tpl::_widget/upload_adv/'.$temp;
		}

		// Hien thi view
		$this->load->view($temp, $this->data);
	}

	function upload_adv_js()
	{
		//$this->load->model('filefolder_model');
		//$this->data['folder_id'] = 0;
		/*$folder = $this->input->get('fol');
		if($folder)
		{
			$where['where']['id'] = $folder;
			$where['where']['userid'] = user_is_login();
			$folder = $this->filefolder_model->total($where);
			if($folder)
				$this->data['folder_id'] = $where['where']['id'];
		}*/

		$this->load->view('admin/_widget/upload_adv/_js',$this->data);
	}

}