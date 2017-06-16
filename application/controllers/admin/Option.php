<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Option extends MY_Controller {
	public $params = array(
		'name' => 'required|trim|xss_clean',
		'type' => 'required|trim|xss_clean',
		'sort_order' => 'trim|xss_clean',
		'status' => 'trim|xss_clean'
	);
	public $filter = array('id', 'group_id', 'name', 'status');
	public $actions = array('edit', 'del', 'translate');
	public $_toolbar;
	public $_type;

	function __construct()
	{
		parent::__construct();
		$this->lang->load('common');
		$this->lang->load('admin/option');

		$this->_type = array(
			'select' => 'select',
			'checkbox' => 'checkbox',
			'radio' => 'radio',
			'text' => 'text',
			'textarea' => 'textarea'
		);
		$this->_toolbar = array(
		);
	}
	
	
	
/*
 * ------------------------------------------------------
 *  Actions
 * ------------------------------------------------------
 */
	function initial($_autocheck)
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');

		// Tu dong kiem tra gia tri cua 1 bien
		$param = $_autocheck;
		if ($param)
			$this->_autocheck($param);
	}



	/**
	 * Them moi
	 */
	function add()
	{
		$this->initial($this->input->post('_autocheck'));
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{

			// Gan dieu kien cho cac bien
			$this->_set_rules(array_keys($this->params));
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{

				// Them du lieu vao data
				$data = array();	
				foreach ($this->params as $key => $value) {
					$data[ $key ] = $this->input->post( $key );
				}
				
				$option_id = 0;
				$this->_model()->create( $data, $option_id );

				$this->_mod()->to_option_value( $option_id, $this->input->post('option_value[]') );

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('option');
				set_message(lang('notice_add_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($this->params as $key => $value)
				{
					$result[$key] = form_error($key);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}

		// Luu bien gui den view
		$this->data['action'] = current_url(true);

		// Hien thi view
		$this->_display('form');
	}



	
	/**
	 * Chinh sua
	 */
	function _edit($info)
	{
		$this->initial($this->input->post('_autocheck'));
		

		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$this->_set_rules( array_keys($this->params) );
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{

				// Cap nhat du lieu vao data
				$data = array();	
				foreach ($this->params as $key => $value) {
					$data[ $key ] = $this->input->post( $key );
				}

				$this->_model()->update($info->id, $data);


				$this->_mod()->to_option_value( $info->id, $this->input->post('option_value[]') );
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('option');
				set_message(lang('notice_update_success'));

			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($this->params as $key => $value)
				{
					$result[$key] = form_error($key);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}

		// Xu ly thong tin
		$info = $this->_mod()->add_info($info);
		$this->data['info'] = $info;

		$this->data['option_values'] = model('option_value')->get_list_rule( array( 'option_id' => $info->id ) );

		// Luu cac bien gui den view
		$this->data['action'] = current_url(true);
		// Hien thi view
		$this->_display('form');
	}
	






	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->_model()->del($info->id);

		// Xóa values
		if( model('option_value')->check_exits('option_id =' . $info->id) )
			model('option_value')->del_rule('option_id =' . $info->id);

		// Gui thong bao
		set_message(lang('notice_del_success'));
	}
	








	/**
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;
			
			// Kiem tra id
			$info = $this->_model()->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_mod()->can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			if (in_array($action, array()))
			{
				$this->_action_option($info, $action);
			}
			else 
			{
				$this->{'_'.$action}($info);
			}
		}
	}
	





	/**
	 * Xu ly hanh dong voi cac thuoc tinh
	 */
	function _action_option($info, $action)
	{
		// Xu ly voi cac option
		$data = array();
		switch ($action)
		{
		}
		
		// Cap nhat data
		if (count($data))
		{
			$this->_model()->update($info->id, $data);
			
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
	}
	
	

	public function loadUpload()
	{
		if( ltrim($_GET['option_value_id'],'n') != $_GET['option_value_id'] )
			$fake_id = fake_id_get('option_value'. '_' . $_GET['option_value_id']) ;
		else
			$fake_id = $_GET['option_value_id'];
		$arrs = array(
			'mod' => 'single',
			'file_type' => 'image',
			'status' => config('file_public', 'main'),
			'table' => 'option_value',
			'table_id' => $fake_id,
			'table_field' => 'image',
			'resize' => TRUE,
			'thumb' => TRUE
		);
		t('widget')->admin->upload($arrs);
		echo '<input type="hidden" value="'.$fake_id.'" name="option_value['.$_GET['option_value_id'].'][image_id]" />';
	}




	
	/*
	 * ------------------------------------------------------
	 *  Danh sach
	 * ------------------------------------------------------
	 */
	function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('site');
		$this->load->helper('form');
		
		// Tao filter
		$filter_input 	= array();
		$filter = $this->_model()->filter_create($this->filter, $filter_input);
		$this->data['filter'] = $filter_input;
		
		// Lay tong so
		$total = $this->_model()->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->_model()->filter_get_list($filter, $input);
		
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $this->actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);

			$row->_url_translate = admin_url("translate/table/option/{$row->id}");
			
			foreach ($this->actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
			
			foreach (array('edit') as $action)
			{
				$row->{'_url_'.$action} = url_add_return($row->{'_url_'.$action});
			}
		}
		$this->data['list'] = $list;
		
		// Tao chia trang
		$this->data['pages_config'] = array(
			'base_url' => current_url().'?'.url_build_query($filter_input),
			'page_query_string' => TRUE,
			'total_rows' => $total,
			'per_page' => $page_size,
			'cur_page' => $limit
		);
		
		// Tao action list
		$actions = array();
		foreach (array('del') as $v)
		{
			$url = admin_url(strtolower(__CLASS__).'/'.$v);
			if ( ! admin_permission_url($url)) continue;
			
			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;


		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		
		// Hien thi view
		$this->_display();
	}

	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('edit', 'del', 'hide')))
		{
			$this->_action($method);
		}
		elseif (method_exists($this, $method))
		{
			$this->{$method}();
		}
		else
		{
			show_404('', FALSE);
		}
	}
	
	





	/**
	 * Lay image
	 */
	function _get_image($option_value_id)
	{
		$this->load->model('file_model');
		$image = $this->file_model->get_info_of_mod( 'option_value', $option_value_id, 'image', 'id, file_name' );
		
		return $image;
	}
	
	/**
	 * Cap nhat image
	 */
	function _update_image( $option_value_id )
	{
		// Lay thong tin cua file
		$file = $this->_get_avatar( $option_value_id );
		if ( ! $file)
		{
			$file = new stdClass();
			$file->id = 0;
			$file->file_name = '';
		}
		
		// Cap nhat du lieu vao data
		$data = array();	
		$data['image_id']	= $file->id;
		$data['image_name']	= $file->file_name;
		model('option_value')->update( $option_value_id, $data );
	}

	
	/*
	 * ------------------------------------------------------
	 *  Rule handle
	 * ------------------------------------------------------
	 */
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		$rules = array();
		foreach ($params as $key) {
			$rules[ $key ] = array( $key, $this->params[ $key ] );
		}
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	function _autocheck($param)
	{
		$this->_set_rules($param);
		
		$result = array();
		$result['accept'] = $this->form_validation->run();
		$result['error'] = form_error($param);
		
		$output = json_encode($result);
		set_output('json', $output);
	}
	
}