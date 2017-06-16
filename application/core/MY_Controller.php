<?php

use Core\FormHandler\FormHandlerInterface;
use Core\RequestHandler\Action as ActionRequestHandler;

/**
 * Controller Core Class
 * 
 * Class xay dung cho cac controller
 *
 * @author		***
 * @version		2015-08-08
 */
class MY_Controller extends CI_Controller
{
	/**
	 * Bien luu thong tin gui den view
	 * 
	 * @var array
	 */
	public $data = array();

	/**
	 * Doi tuong App
	 *
	 * @var MY_App
	 */
	public $app;


	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		// App
		$this->app = load_class('App', 'core');
		$this->app->app_boot();

		//$this->output->enable_profiler(true);
	}
	
	/**
	 * Goi cac lop xu ly cua he thong
	 * 
	 * @param string $key
	 * return mixed
	 */
	public function __get($key)
	{
		if ($this->app->bound($key))
		{
			return $this->app->make($key);
		}
		
		//throw new InvalidArgumentException("Argument {$key} does not exist.");
	}
	
	// --------------------------------------------------------------------
	/**
	 * Phan hoi du lieu ve cho client
	 *
	 * @param array $result 	Ket qua tra ve sau khi xu ly form
	 */
	protected function _response($result=array(),$status=true)
	{
		//$result['location'] = url_get_return($result['location']);
		$result['complete'] = $status;
		set_output('json', json_encode($result));

	}
	/**
	 * Hien thi view
	 * 
	 * @param string $view		File view ('' => Lay theo method hien tai || NULL => Khong su dung view)
	 * @param string $layout	File layout ('' => Lay theo config || NULL => Khong su dung layout)
	 */
	protected function _display($view = '', $layout = '')
	{
		if ( ! is_null($view))
		{
			$controller = t('uri')->rsegment(1);
			$method 	= t('uri')->rsegment(2);
			$view = ($view == '') ? $method : $view;
			$view = (substr($view, 0, 1) == '/') ? ltrim($view, '/') : 'tpl::'.$controller.'/'.$view; // Xu ly load view tu mod khac

		}

		return t('tpl')->display($view, $layout, $this->data);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tao form xu ly
	 * 
	 * @param array $form	Thong tin form
	 * 	Bao gom cac bien:
	 * 		autocheck 	= TRUE		: Xu ly autocheck (option)
	 * 			$form['autocheck'] = TRUE;
	 * 			$form['autocheck'] = FALSE;
	 * 			$form['autocheck'] = function($param){};
	 * 		
	 * 		validation 	= Function	: Validation form (required)
	 * 			$form['validation'] = function(){return (array)$params};
	 * 			$form['validation']['params'] = $params;
	 * 			$form['validation']['method'] = '_set_rules'; (Bien di kem voi $form['validation']['params'])
	 * 		
	 * 		submit 		= Function	: Xu ly du lieu khi validation thanh cong (required)
	 * 			$form['submit'] = function($param){return (array)$result || (string)$location};
	 * 		
	 * 		error		= Function 	: Lay danh sach loi cua cac bien (option)
	 * 			$form['error'] = function($param){return (array)$params_error};
	 * 		
	 * 		form		= Function 	: Xu ly hien thi form (option)
	 * 			$form['form'] = function(){};
	 */
	protected function _form(array $form)
	{
		// Xu ly input
		$form = set_default_value($form, 'autocheck', TRUE);
		$form = set_default_value($form, 'error');
		$form = set_default_value($form, 'form');
		
		// Tai file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Autocheck
		if ( ! empty($form['autocheck']))
		{
			$param = $this->input->post('_autocheck');
			if ($param)
			{
				if (is_callable($form['autocheck'])) // Goi callback autocheck
				{
					call_user_func_array($form['autocheck'], array($param));
				}
				else // Autocheck mac dinh
				{
					$this->_form_autocheck($param);
				}
			}
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Validation
			$params = array();
			if (isset($form['validation']) && $form['validation'] instanceof Closure)
			{
				$params = call_user_func_array($form['validation'], array());
			}
			else 
			{
				$params = (array)$form['validation']['params'];
				$method = (isset($form['validation']['method'])) ? $form['validation']['method'] : '_set_rules';
				
				$this->{$method}($params);
			}
			
			
			// Submit
			$result = array();
			if ($this->form_validation->run())
			{
				$result['complete'] = TRUE;
				
				$submit_result = call_user_func_array($form['submit'], array($params)); // Goi callback submit
				if (is_array($submit_result))
				{
					$result = array_merge($result, $submit_result); // Gop result voi result cua callback submit
				}
				else 
				{
					$result['location'] = $submit_result ?: $this->_url(); // Callback submit tra ve location
				}
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				if (is_callable($form['error'])) // Goi callback error
				{
					$result = call_user_func_array($form['error'], array($params));
				}
				else 
				{
					$errors = [];

					foreach ($params as $param)
					{
						$errors[$param] = form_error($param);
					}

					$result = array_merge($errors, $result);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		// Form
		$this->data['action'] = current_url(TRUE);
		
		if (is_callable($form['form'])) // Goi callback form
		{
			call_user_func_array($form['form'], array());
		}
		else 
		{
			$this->_display();
		}
	}
	
	/**
	 * Xu ly form submit output
	 * 
	 * @param array $result 	Ket qua tra ve sau khi xu ly form
	 */
	protected function _form_submit_output(array $result)
	{
		// Lay input
		$complete = (isset($result['complete']) && $result['complete'] === TRUE) ? TRUE : FALSE;
		
		// Xu ly location
		if ($complete && isset($result['location']))
		{
			$result['location'] = url_get_return($result['location']);
		}
		
		// Neu su dung ajax load
		if ($this->input->post('_submit') != '1')
		{
			set_output('json', json_encode($result));
		}
		// Neu load form
		elseif ($complete)
		{
			redirect($result['location']);
		}
	}
	
	/**
	 * Xu ly form autocheck
	 * 
	 * @param string 	$param				Ten bien can kiem tra
	 * @param string 	$method_set_rules	Ten method gan dieu kien
	 */
	protected function _form_autocheck($param, $method_set_rules = '_set_rules')
	{
		$this->{$method_set_rules}($param);
		
		$result = array();
		$result['accept'] 	= $this->form_validation->run();
		$result['error'] 	= form_error($param);
		
		set_output('json', json_encode($result));
	}
	protected function _form_create_view($id, $info = null)
	{

		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] = 'single';
		$widget_upload['file_type'] = 'image';
		$widget_upload['status'] = config('file_public', 'main');
		$widget_upload['resize'] = TRUE;
		$widget_upload['thumb'] = TRUE;
		$widget_upload['table'] = $this->_get_mod();
		$widget_upload['table_id'] = $id;
		// up anh avatar
		$widget_upload['url_update'] = ($id > 0) ? current_url() . '?act=update_image&field=avatar' : null;
		$widget_upload['table_field'] = 'avatar';
		$this->data['widget_upload_avatar'] = $widget_upload;

		// up anh image
		$widget_upload['url_update'] = ($id > 0) ? current_url() . '?act=update_image&field=image' : null;
		$widget_upload['table_field'] = 'image';
		$this->data['widget_upload_image'] = $widget_upload;

		// up anh banner
		$widget_upload['url_update'] = ($id > 0) ? current_url() . '?act=update_image&field=banner' : null;
		$widget_upload['table_field'] = 'banner';
		$this->data['widget_upload_banner'] = $widget_upload;
		// up anh icon
		$widget_upload['url_update'] = ($id > 0) ? current_url() . '?act=update_image&field=icon' : null;
		$widget_upload['table_field'] = 'icon';
		$this->data['widget_upload_icon'] = $widget_upload;
		// up anh multi
		$widget_upload['mod'] = 'multi';
		$widget_upload['table_field'] = 'images';
		//$widget_upload['resize'] = FALSE;
		//$widget_upload['thumb'] = FALSE;
		$this->data['widget_upload_images'] = $widget_upload;

		// up files
		$widget_upload['mod'] = 'multi';
		$widget_upload['file_type'] = 'file';
		$widget_upload['table_field'] = 'files';
		$widget_upload['resize'] = FALSE;
		$widget_upload['thumb'] = FALSE;
		$this->data['widget_upload_files'] = $widget_upload;


		// Luu cac bien gui den view
		$this->data['action'] = current_url(true);
		// Hien thi view
		$this->data['url_search_user'] = admin_url('user/ac');
		$this->data['url_search_admin'] = admin_url('admin/ac');
		$this->data['url_tag'] = admin_url('tag/getinfor');



	}

	protected function _form_get_inputs($id=null,$fake_id=null)
	{
		$data = array();
		$fields = $this->_model()->fields;
		foreach ($fields as $f) {
			$v= $this->input->post($f,true);
			if(is_null($v)) $v='';
			$data[$f] =$v;
		}
		// Su ly du lieu theo cac loai cua model
		$data =$this->_model()->handle_data_input($data);

		// Video
		if (isset($data['video']) && $data['video']) {
			$link = mod("media")->get_link($data['video']/*,['multi_server'=>1]*/);
			$data['video_data'] = json_encode($link);
		}

		// SEO url
		if (isset($data['seo_url']) && $data['seo_url']) {
			$data['seo_url'] = convert_vi_to_en($data['seo_url']);
		} elseif (isset($data['seo_url']) && isset($data['name']) && $data['name']) {
			$data['seo_url'] = convert_vi_to_en($data['name']);
		} elseif (isset($data['seo_url']) && isset($data['title']) && $data['title']) {
			$data['seo_url'] = convert_vi_to_en($data['title']);
		}


		// Lay thong tin image
		foreach ($this->_model()->fields_type_image as $i) {
			$image = $this->_get_image($fake_id, $i);
			if ($image) {
				$data[$i . '_id'] = $image->id;
				$data[$i . '_name'] = $image->file_name;
			}
		}
		foreach (array('updated') as $key) {
			if(isset($data[$key]))
				$data[$key] = now();
		}
		//pr($data);
		return $data;
	}
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _form_set_rules($params = [])
	{
		$rules =[];
		$fields_rule = $this->_model()->fields_rule;
		// pr($fields_rule);
		if ($fields_rule){
			foreach ($params as $key) {
				$fields_rule_default = "trim|xss_clean" ;
				if (isset($fields_rule[$key]) && $fields_rule[$key]){
					if(!is_array($fields_rule[$key]))
						$rules[$key] = array($key,$fields_rule_default.'|'. $fields_rule[$key]);
					else
						$rules[$key] = array($fields_rule[$key][0],$fields_rule_default.'|'. $fields_rule[$key][1]);
				}
			}
		}
		//pr($rules);

		return $rules;
	}
	// --------------------------------------------------------------------
	
	/**
	 * Tao trang list
	 * 
	 * @param array $list
	 */
	protected function _list(array $args = array())
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		
		// Xu ly args
		$args = $this->_list_make_args($args);
		
		// Sap xep row
		$this->_list_make_sort($args);
		
		// Tao danh sach
		$this->_list_make_list($args);
		
		// Luu bien gui den view
		$this->data['action'] = current_url();

		// Hien thi view
		if ($args['display'])
		{
			$this->_display();
		}
	}

	/**
	 * Xu ly args list
	 *
	 * @param array $args
	 * @return array
	 */
	protected function _list_make_args(array $args)
	{
		$args = set_default_value($args, 'mod', $this->uri->rsegment(1)); // Ten mod
		$args = set_default_value($args, 'obj_mod', $args['mod']); // Ten hoac doi tuong cua mod
		$args = set_default_value($args, 'obj_model', $args['mod']); // Ten hoac doi tuong cua model
		
		$args = set_default_value($args, 'filter', FALSE); // Bat tat filter
		$args = set_default_value($args, 'filter_fields', array()); // Cac bien cho phep filter
		$args = set_default_value($args, 'filter_value', array()); // Filter mac dinh
		
		$args = set_default_value($args, 'order', false); // Bat tat order
		$args = set_default_value($args, 'order_fields', array()); // Cac bien cho phep order
		$args = set_default_value($args, 'order_value', array()); // Gia tri mac dinh cua order
		
		$args = set_default_value($args, 'input', array()); // Input lay list row
		
		$args = set_default_value($args, 'page', TRUE); // Su dung phan trang hay khong
		$args = set_default_value($args, 'page_size', 0); // So rows tren 1 page
		
		$args = set_default_value($args, 'actions', array('edit', 'del', 'translate')); // Cac action gan cho row
		$args = set_default_value($args, 'actions_list', array('del')); // Cac action xu ly list
		$args = set_default_value($args, 'actions_return', array('edit')); // Cac action se quay ve trang truoc sau khi xu ly xong
		
		$args = set_default_value($args, 'sort', FALSE); // Bat tat sap sep row
		$args = set_default_value($args, 'sort_field', 'sort_order'); // Ten field sort
		
		$args = set_default_value($args, 'display', TRUE); // Bat tat hien thi view
		
		if (is_string($args['obj_mod']))
		{
			$args['obj_mod'] = $this->mod->{$args['obj_mod']};
		}
	
		if (is_string($args['obj_model']))
		{
			$args['obj_model'] = $this->model->{$args['obj_model']};
		}
	
		return $args;
	}
	
	/**
	 * Xu ly sap xep list
	 * 
	 * @param array $args
	 */
	protected function _list_make_sort(array $args)
	{
		if ( ! $args['sort']) return;
		
		if ($this->input->get('act') == 'sort_update')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);
			foreach ($items as $i => $id)
			{
				$args['obj_model']->update_field($id, $args['sort_field'], $i + 1);
			}
			
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		
		$this->data['sort_url_update'] = current_url() . '?act=sort_update';
	}
	
	/**
	 * Lay danh sach rows
	 * 
	 * @param array $args
	 * @return array
	 */
	protected function _list_get_rows(array $args, &$total = 0)
	{
		// Khoi tao base_url
		$base_url = current_url().'?';
		
		
		// Su dung filter
		if ($args['filter'])
		{
			// Tao filter
			$filter_input 	= array();
			$filter_fields 	= (array) $args['filter_fields'];
				
			$filter = $args['obj_mod']->create_filter($filter_fields, $filter_input);
			$filter = array_merge($filter, $args['filter_value']);
			$this->data['filter'] = $filter_input;
			
			// Cap nhat base_url
			$base_url .= url_build_query($filter_input);
			
			// Tao order
			$this->_list_make_order($args, $base_url);
			
			// Lay danh sach
			$input = $args['input'];
			
			if ($args['page'])
			{
				$total = $args['obj_model']->filter_get_total($filter);
				$input['limit'] = make_limit_page($total, $args['page_size']);
				list($limit, $page_size) = $input['limit'];
			}
			$list = $this->_list_get_rows_do($args, $input,$filter);
		}
		
		// Khong su dung filter
		else
		{
			// Tao order
			$this->_list_make_order($args, $base_url);
			
			// Lay danh sach
			$input = $args['input'];
			$input = set_default_value($input, 'where', array());
				
			if ($args['page'])
			{
				$total = $args['obj_model']->get_total($input['where']);
				$input['limit'] = make_limit_page($total, $args['page_size']);
				list($limit, $page_size) = $input['limit'];
			}
			
			$list = $this->_list_get_rows_do($args, $input);

		}
		
		
		// Tao chia trang
		if ($args['page'])
		{
			$pages_config = array();
			$pages_config['base_url'] 	= $base_url;
			$pages_config['total_rows'] = $total;
			$pages_config['per_page'] 	= $page_size;
			$pages_config['cur_page'] 	= $limit;
			$this->data['pages_config'] = $pages_config;
		}
		
		// Xu ly total
		if ( ! $total)
		{
			$total = count($list);
		}
		
		return $list;
	}
	protected function _list_get_rows_do($args=[],$input=[], $filter=[])
	{
		$list = $args['obj_model']->filter_get_list($filter, $input);
		return $list;
	}
	/**
	 * Xi lu tao orders
	 * 
	 * @param array $args
	 * @param string $base_url
	 */
	protected function _list_make_order(array &$args, &$base_url)
	{
		// Neu khong su dung order
		if ( ! $args['order']) return;
		
		// Tao orders
		$order_fields = (array) $args['order_fields'];
		
		$orders = set_default_value($args['order_value'], $order_fields, array());
		
		if (isset($orders['id']) && ! isset($orders['id']['status_default']))
		{
			$orders['id']['status_default'] = 'desc';
		}
		
		// Tao order
		$order = url_get_order($orders, $base_url);

		// Gan input order
		$args['input']['order'] = array($order[0], $order[1]);
		$args['input']['order'][0] = array_get($orders, "{$order[0]}.column", $args['obj_model']->table.'.'.$order[0]);
		
		// Cap nhat base_url
		$base_url .= '&'.$order[2];

		// View data
		$this->data['orders'] = $orders;
	}
	
	/**
	 * Tao danh sach rows
	 * 
	 * @param array $args
	 */
	protected function _list_make_list(array $args)
	{
		// Lay danh sach
		$total = 0;
		$list = $this->_list_get_rows($args, $total);
		
		// Xu ly danh sach
		$actions = (array) $args['actions'];
		if (get_area() == 'admin')
		{
			$list = admin_url_create_option($list, $args['mod'], $args['obj_model']->key, $actions);
		}
		
		foreach ($list as $row)
		{
			$row = $args['obj_mod']->add_info($row);
			
			if (get_area() == 'admin')
			{
				$row->_url_translate = admin_url("translate/table/{$args['mod']}/{$row->{$args['obj_model']->key}}");
			}
			
			foreach ($actions as $action)
			{
				if (
					in_array($action, $args['actions_return'])
					&& isset($row->{'_url_'.$action})
				)
				{
					$row->{'_url_'.$action} = url_add_return($row->{'_url_'.$action});
				}
				
				if (get_area() == 'admin')
				{
					$row->{'_can_'.$action} = (
						$args['obj_mod']->can_do($row, $action) 
						&& admin_permission_url($row->{'_url_'.$action})
					) ? TRUE : FALSE;
				}
			}
		}
		
		$this->data['list'] = $list;
		$this->data['total'] = $total;
		
		// Tao action list
		if (get_area() == 'admin')
		{
			$actions = array();
			foreach ((array) $args['actions_list'] as $v)
			{
				$url = admin_url($args['mod'] . '/' . $v);
				if ( ! admin_permission_url($url)) continue;
		
				$actions[$v] = $url;
			}
			
			$this->data['actions'] = $actions;
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xu ly remap cho action
	 * 
	 * @param string 	$action			Action can goi
	 * @param array 	$params			Bien chuyen vao
	 * @param array 	$actions		Cac action cho phep
	 * @param string 	$action_method	Ten method xu ly
	 */
	protected function _remap_action($action, array $params, array $actions, $action_method = '_action')
	{
		if (in_array($action, $actions))
		{
			return call_user_func_array(array($this, $action_method), array($action));
		}
		elseif (method_exists($this, $action))
		{
			return call_user_func_array(array($this, $action), $params);
		}
		else
		{
			show_404('', FALSE);
		}
	}


	/**
	 * Thuc hien tuy chinh
	 */
	protected function _action($action)
	{
		// Lay input
		$mod = $this->uri->rsegment(1);
		
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		
		// Thuc hien action
		foreach ((array) $ids as $id)
		{
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;
			
			// Kiem tra id
			$info = model($mod)->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! mod($mod)->can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
		}
	}

	// --------------------------------------------------------------------
	
	/**
	 * Chuyen huong den cac page trong controller hien tai
	 * 
	 * @param string $uri
	 * @param string $method
	 * @param number $http_response_code
	 */
	protected function _redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		redirect($this->_uri($uri), $method, $http_response_code);
	}
	protected function _url_create_option($list, $key,  $options,$params=null)
	{
		$is_array = TRUE;
		if ( ! is_array($list))
		{
			$is_array = FALSE;
			$list = array($list);
		}
		foreach ($list as $row)
		{
			foreach ($options as $option)
			{
				$row->{'_url_'.$option} =  $this->_url($option.'/'.$row->{$key}.$params);
			}
		}
		return ($is_array) ? $list : $list[0];
	}

	/**
	 * Tao url den cac page trong controller hien tai
	 * 
	 * @param string $uri
	 * @param array $opt
	 * @return string
	 */
	protected function _url($uri = '', array $opt = array())
	{
		return site_url($this->_uri($uri), $opt);
	}
	
	/**
	 * Tao uri cho controller hien tai
	 * 
	 * @param string $uri
	 * @return string
	 */
	protected function _uri($uri = '')
	{
		$controller = $this->uri->rsegment(1);
		
		$uri = (starts_with($uri, '/')) ? ltrim($uri, '/') : $controller.'/'.$uri;
		
		if (get_area() == 'admin')
		{
			$uri = config('admin_folder', 'main').'/'.$uri;
		}
		
		return $uri;
	}
	
	/**
	 * Lay doi tuong cua mod hien tai
	 * 
	 * @return MY_Mod
	 */
	protected function _mod()
	{
		return mod($this->_get_mod());
	}
	
	/**
	 * Lay doi tuong cua model hien tai
	 * 
	 * @return MY_Model
	 */
	protected function _model()
	{
		return model($this->_get_mod());
	}
	
	/**
	 * Lay key cua mod hien tai
	 *
	 * @return string
	 */
	protected function _get_mod()
	{
		return $this->uri->rsegment(1);
	}
	/**
	 * Lay action
	 *
	 * @return string
	 */
	protected function _get_act()
	{
		return $this->uri->rsegment(2);
	}

	// --------------------------------------------------------------------

	/**
	 * Thuc thi FormHandler
	 *
	 * @param FormHandlerInterface $form
	 * @param string               $view
	 * @param array                $options
	 */
	protected function _run_form_handler(FormHandlerInterface $form, $view = null, array $options = [])
	{
		$view = $view ?: 'form';

		$options['validation'] = function() use ($form)
		{
			return $form->validation();
		};

		$options['submit'] = function() use ($form)
		{
			return $form->submit();
		};

		$options['form'] = function() use ($form, $view)
		{
			$this->data = array_merge($this->data, $form->form());

			$this->data['action'] = current_url(true);

			$this->_display($view);
		};

		$this->_form($options);
	}


	/**
	 * Action handler
	 *
	 * @param \Closure|array $args
	 * @return mixed
	 */
	protected function _action_handler($args)
	{
		if ($args instanceof \Closure)
		{
			$args = ['handle' => $args];
		}

		$input 		= array_get($args, 'input', 3);
		$handle 	= array_get($args, 'handle');
		$location 	= array_get($args, 'location', $this->_url());

		if ( ! $input instanceof \Closure)
		{
			$ruri_index = $input;

			$input = function() use ($ruri_index)
			{
				return t('uri')->rsegment($ruri_index) ?: t('input')->post_get('id');
			};
		}

		$args = compact('input', 'handle', 'location');

		return ActionRequestHandler::make($args)->run();
	}

	/**
	 * Xu ly thuc hien hanh dong voi model
	 *
	 * @param array $args
	 */
	protected function _action_model_handler(array $args)
	{
		$model	= $args['model'];
		$handle	= $args['handle'];
		$action	= array_get($args, 'action', snake_case(t('uri')->rsegment(2)));

		$args['handle'] = function($ids) use ($model, $action, $handle)
		{
			$updated = false;

			foreach ((array) $ids as $id)
			{
				$instance = forward_static_call([$model, 'find'], $id);

				if ( ! $instance || ! $instance->can($action)) continue;

				$result = call_user_func($handle, $instance);

				$result = is_null($result) ? true : $result;

				if ($result)
				{
					$updated = true;
				}
			}

			if ($updated)
			{
				set_message(lang('notice_update_success'));
			}
		};

		$this->_action_handler($args);
	}

	/* Su ly hinh anh*/

	/*=============================*/

	/**
	 * Lay id xu ly hien tai
	 *
	 * @return int
	 */
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get($this->_get_mod())
			: $this->uri->rsegment(3);
	}

	/**
	 * Lay image
	 */
	protected function _get_image($id = NULL, $field = 'avatar')
	{
		if (is_null($id)) {
			$id = $this->_get_id_cur();
		}
		$image = $this->model->file->get_info_of_mod($this->_get_mod(), $id, $field, 'id, file_name');
		return $image;
	}

	/**
	 * Kiem tra image
	 */
	public function _check_image()
	{
		if (!$this->_get_image()) {
			$this->form_validation->set_message(__FUNCTION__, lang('required'));
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Cap nhat image
	 */
	protected function _update_image($id,$field=null)
	{
		if(!$field)
			$field = $this->input->get('field');
		$field = $field ? $field : 'avatar';
		// Lay thong tin cua file
		$file = $this->_get_image($id, $field);
		if (!$file) {
			$file = new stdClass();
			$file->id = 0;
			$file->file_name = '';
		}

		// Cap nhat du lieu vao data
		$data = array();
		$data[$field . '_id'] = $file->id;
		$data[$field . '_name'] = $file->file_name;
		$this->_model()->update($id, $data);
	}


}
