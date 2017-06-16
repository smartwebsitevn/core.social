<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ads_banner extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('ads_banner_model');
		$this->lang->load('admin/ads_banner');
	}
	
	/**
	 * Remap method
	 */
	public function _remap($method, $params = array())
	{
		return $this->_remap_action($method, $params, array('edit', 'del'));
	}
	
	
/*
 * ------------------------------------------------------
 *  Rules params
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		$rules = array();
		$rules['name'] 		= array('name', 'required');
		$rules['ads_location'] 		= array('location', 'required|callback__check_ads_location');
		$rules['image_id'] 			= array('banner', 'callback__check_image');
		//$rules['url'] 				= array('banner_url', 'required|trim|xss_clean');
		$rules['sort_order'] 		= array('sort_order', 'is_natural');
		$rules['end'] 				= array('date_expire', 'trim|callback__check_date');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra vi tri banner
	 */
	function _check_ads_location($value)
	{
		// Kiem tra su ton tai
		$this->load->model('ads_location_model');
		$ads_location_id = (!is_numeric($value)) ? 0 : $value;
		$location = $this->ads_location_model->get_info($ads_location_id, 'banner_quantity');
		if (!$location)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
			return FALSE;
		}
		
		// Neu vi tri nay gioi han so luong banner thi kiem tra so luong banner da tao
		if ($location->banner_quantity)
		{
			$banner_id = $this->uri->rsegment(3);
			$banner_id = (!is_numeric($banner_id)) ? 0 : $banner_id;
			$banner = $this->ads_banner_model->get_info($banner_id, 'ads_location_id');
			
			if (!$banner || $banner->ads_location_id != $ads_location_id)
			{
				$where = array();
				$where['ads_location_id'] = $ads_location_id;
				$total = $this->ads_banner_model->get_total($where);
				if ($total >= $location->banner_quantity)
				{
					$this->form_validation->set_message(__FUNCTION__, lang('notice_can_not_add'));
					return FALSE;
				}
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Kiem tra ngay thang
	 */
	function _check_date($value)
	{
		if ($value && ! get_time_from_date($value))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
		
		return TRUE;
	}

	/**
	 * Kiem tra image
	 */
	public function _check_image()
	{
		if ( ! $this->_get_image())
		{
			$this->form_validation->set_message(__FUNCTION__, lang('required'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Lay image
	 */
	protected function _get_image($id = NULL)
	{
		if (is_null($id))
		{
			$id = $this->_get_id_cur();
		}
		
		$image = model('file')->get_info_of_mod('ads_banner', $id, 'image', 'id, file_name');
		
		return $image;
	}
	
	/**
	 * Cap nhat image
	 */
	protected function _update_image($id)
	{
		// Lay thong tin cua file
		$file = $this->_get_image($id);
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
		$this->_model()->update($id, $data);
	}
	
	/**
	 * Lay id xu ly hien tai
	 * 
	 * @return int
	 */
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get('ads_banner') 
			: $this->uri->rsegment(3);
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
	
	
/*
 * ------------------------------------------------------
 *  Actions
 * ------------------------------------------------------
 */
	/**
	 * Them moi
	 */
	function add()
	{
		// Tao fake id tam thoi de cap nhat cho file dinh kem
		$fake_id = fake_id_get('ads_banner');
		
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('name','ads_location', 'url', 'image_id', 'sort_order', 'end');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay thong tin
				$image = $this->_get_image();
				
				// Them du lieu vao data
				$data = array();
				$data['name'] 	= $this->input->post('name');
				$data['content']				= handle_content($this->input->post('content'),"input");
				$data['ads_location_id'] 	= $this->input->post('ads_location');
				$data['image_id']			= $image->id;
				$data['image_name']			= $image->file_name;
				$data['url']				= $this->input->post('url');
				$data['sort_order']			= $this->input->post('sort_order');
				$data['status']			= $this->input->post('status');
				$end = $this->input->post('end');
				if($end)
					$data['end']				= get_time_from_date($end);
				else
					$data['end'] = 0;
				$data['created']			= now();
				
				// Lay ads_banner_id vua them
				$id = 0;
				$this->ads_banner_model->create($data, $id);

				// Cap nhat lai table_id table file
				model('file')->update_table_id_of_mod('ads_banner', $fake_id, $id);
				fake_id_del('ads_banner');
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('ads_banner');
				set_message(lang('notice_add_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Lay danh sach ads location
		$this->load->model('ads_location_model');
		$this->data['locations'] = $this->ads_location_model->get_list();
		
		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 		= 'single';
		$widget_upload['file_type'] = 'image';
		$widget_upload['status'] 	= config('file_public', 'main');
		$widget_upload['table'] 	= 'ads_banner';
		$widget_upload['table_id'] 	= $fake_id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 	= FALSE;
		$this->data['widget_upload']= $widget_upload;
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('ads_banner'), lang('mod_ads_banner'));
		$breadcrumbs[] = array('', lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display('form');
	}
	
	/**
	 * Chinh sua
	 */
	function _edit($info)
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Cap nhat thong tin
		if ($this->input->get('act') == 'update_image')
		{
			$this->_update_image($info->id);
			exit();
		}
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('name','ads_location', 'url', 'image_id', 'sort_order', 'end');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao data
				$data = array();
				$data['name'] 	= $this->input->post('name');
				$data['content']				= handle_content($this->input->post('content'),"input");
				$data['ads_location_id'] 	= $this->input->post('ads_location');
				$data['url']				= $this->input->post('url');
				$data['sort_order']			= $this->input->post('sort_order');
				$data['status']			= $this->input->post('status');
				$end = $this->input->post('end');
				if($end)
					$data['end']				= get_time_from_date($end);
				else
					$data['end'] = 0;
				$this->ads_banner_model->update($info->id, $data);
				
				// Cap nhat image
				$this->_update_image($info->id);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('ads_banner');
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Xuu ly info
		$info->_end = get_date($info->end);
		
		// Lay danh sach ads location
		$this->load->model('ads_location_model');
		$this->data['locations'] = $this->ads_location_model->get_list();
		
		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 		= 'single';
		$widget_upload['file_type'] = 'image';
		$widget_upload['status'] 	= config('file_public', 'main');
		$widget_upload['table'] 	= 'ads_banner';
		$widget_upload['table_id'] 	= $info->id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 	= FALSE;
		$widget_upload['url_update']= current_url().'?act=update_image';
		$this->data['widget_upload']= $widget_upload;
		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		$this->data['info'] = $info;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('ads_banner'), lang('mod_ads_banner'));
		$breadcrumbs[] = array('', lang('edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display('form');
	}
	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->ads_banner_model->del($info->id);

		$this->load->helper('file');
		file_del_table('ads_banner', $info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
		return TRUE;
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
			$info = $this->model->{$mod}->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			//if ( ! $this->mod->{$mod}->can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
		}
	}
	
	
	/**
	 * Danh sach
	 */
	function index()
	{
		// Cap nhat sort_order
		if ($this->input->get('act') == 'sort_update')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);
			foreach ($items as $i => $id)
			{
				$data = array();
				$data['sort_order']	= $i;
				$this->_model()->update($id, $data);
			}

			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		// Tai cac file thanh phan
		$this->load->helper('file');


		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('ads_location_id','status');
		$filter = $this->_mod()->create_filter($filter_fields, $filter_input);


		$this->data['filter'] = $filter_input;

		// Lay danh sach
		$list = $this->_model()->filter_get_list($filter,$filter_input);

		$actions = array('edit', 'del');
		$list = admin_url_create_option($list, __CLASS__, 'id',$actions);
		foreach ($list as $row)
		{
			$row->_location_name='';
			if($row->ads_location_id){
				$location =model("ads_location")->get_info($row->ads_location_id,'name');
				if($location){
					$row->_location_name=$location->name;
				}
			}
			$row->image 		= file_get_image_from_name($row->image_name)->url;
			$row->_created 		= get_date($row->created);
			$row->_end 			= get_date($row->end);
			$row->_days_left	= days_left($row->end);
			$row->_is_expire 	= ($row->end < now()) ? TRUE : FALSE;
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		//pr($list);
		$this->data['list'] = $list;
		$this->data['sort_url_update'] = current_url() . '?act=sort_update';
		$this->data['locations']	 	=  model('ads_location')->get_list();
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('ads_banner'), lang('mod_ads_banner'));
		$breadcrumbs[] = array('', lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display();
	}

	
}