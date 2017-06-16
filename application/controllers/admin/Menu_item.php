<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_item extends MY_Controller {

	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();

		// Tai cac file thanh phan
		$this->load->model('menu_model');
		$this->load->model('menu_item_model');
		$this->lang->load('admin/menu');
	}

	/**
	 * Remap method
	 */
	function _remap($method)
	{

		if (in_array($method, array('edit', 'del','on','off')))
		{
			$this->_action($method);
		}
		elseif (in_array($method, array('add')))
		{
			$this->_action_add($method);
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

	/*
     * ------------------------------------------------------
     *  Menu handle
     * ------------------------------------------------------
     */



	// --------------------------------------------------------------------

	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		$rules = array();
		$rules['title'] 		= array('title', 'required|trim|xss_clean');
		$rules['url'] 			= array('url', 'trim|xss_clean|callback__check_url');
		$rules['sort_order'] 	= array('sort_order', 'is_natural');

		$this->form_validation->set_rules_params($params, $rules);
	}
	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	function _autocheck($param)
	{
		$this->_set_rules($param);

		$result = array();
		$result['accept'] 	= $this->form_validation->run();
		$result['error'] 	= form_error($param);

		$output = json_encode($result);
		set_output('json', $output);
	}


	/**
	 * Kiem tra Url
	 */
	function _check_url($value)
	{
		$url = $this->_get_url();

		if ( ! $url)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('required'));
			return FALSE;
		}

		/* if ( ! filter_var($url, FILTER_VALIDATE_URL))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		} */

		return TRUE;
	}

	/**
	 * Lay gia tri cua Url
	 */
	function _get_url()
	{
		$url = $this->input->post('url');

		return $url;
	}


	/**
	 * Lay danh sach module
	 *
	 * @return array
	 */
	public function _get_list_module()
	{
		$modules=array();
		$list =model('module')->get_list();
		foreach ($list as $module)
		{
			$key=$module->key;
			$menu	= $this->module->$key->config->item('menu_methods');
			if($menu)
				$modules[$key]['url']=$menu;
			$menu_holder	= $this->module->$key->config->item('menu_holder_methods');
			if($menu_holder)
				$modules[$key]['holder']=$menu_holder;
		}


		return $modules;
	}


	// --------------------------------------------------------------------

	/**
	 * Them moi
	 */
	function _add($menu)
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');



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
			$params = array('title', 'url', 'sort_order');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Them du lieu vao data
				$data = array();
				$data['parent_id']			= $this->input->post('parent_id');
				if($data['parent_id'] == 0){
					$data['level']=0 ;
				}
				else{
					$parent=$this->menu_item_model->get_info($data['parent_id']);
					$data['level']=$parent->level + 1;
				}
				$data['menu'] 			= $menu->key;
				$data['title']			= $this->input->post('title');
				$data['url'] 			= handle_content($this->_get_url(), 'input');
				$data['holder'] 		= $this->input->post('holder');
				$data['sort_order']		= $this->input->post('sort_order');
				$data['target']			= $this->input->post('target');
				$data['icon']			= $this->input->post('icon');
				$data['nofollow'] 		= config( ($this->input->post('nofollow')) ? 'verify_yes' : 'verify_no' , 'main');
				$data['status'] 		= config( ($this->input->post('status')) ? 'verify_yes' : 'verify_no' , 'main');
				$this->menu_item_model->create($data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('menu_item').'?menu='.$menu->key;
				set_message(lang('notice_add_success'));
			}

			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}

			// Form output
			$this->_form_submit_output($result);
		}


		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();

		$this->data['menu'] 	= $menu->key;
		$this->data['parents']	 	= $this->menu_item_model->get_list_hierarchy(array('where'=>array('menu'=>$menu->key)));
		$this->data['modules'] 	= $this->_get_list_module();
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('admin'), lang('mod_menu'));
		$breadcrumbs[] = array(admin_url('menu').'?menu='.$menu->key, $menu->name);
		$breadcrumbs[] = array(current_url(), lang('add_item'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display('form');
	}



	/**
	 * Chinh sua
	 */
	function _edit($item)
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');

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
			$params = array('title', 'url', 'sort_order');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao data
				$data = array();
				$data['parent_id']			= $this->input->post('parent_id');
				if($data['parent_id'] == 0){
					$data['level']=0 ;
				}
				else{
					$parent=$this->menu_item_model->get_info($data['parent_id']);
					$data['level']=$parent->level + 1;
				}
				$data['title']			= $this->input->post('title');
				$data['url'] 			= handle_content($this->_get_url(), 'input');
				$data['holder'] 		= $this->input->post('holder');
				$data['sort_order']		= $this->input->post('sort_order');
				$data['target']			= $this->input->post('target');
				$data['icon']			= $this->input->post('icon');
				$data['nofollow'] 		= config( ($this->input->post('nofollow')) ? 'verify_yes' : 'verify_no' , 'main');
				$data['status'] 		= config( ($this->input->post('status')) ? 'verify_yes' : 'verify_no' , 'main');

				$this->menu_item_model->update($item->id, $data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('menu_item').'?menu='.$item->menu;
				set_message(lang('notice_update_success'));
			}

			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}

			// Form output
			$this->_form_submit_output($result);
		}

		// Xu ly info
		$item->url = handle_content($item->url, 'output');
		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $item;
		$this->data['parents']	 	= $this->menu_item_model->get_list_hierarchy(array('where'=>array('menu'=>$item->menu,'id <>'=>$item->id)));
		$this->data['menu'] 	= $item->menu;
		$this->data['modules'] 	= $this->_get_list_module();

		// Hien thi view
		$this->_display('form');
	}

	// --------------------------------------------------------------------

	/**
	 * Xoa du lieu
	 */
	function _del($item)
	{
		// Thuc hien xoa
		$this->menu_item_model->del($item->id);

		$this->menu_item_model->cache_update($item->menu);

		// Gui thong bao
		set_message(lang('notice_del_success'));
	}

	// --------------------------------------------------------------------

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
			$info = $this->menu_item_model->get_info($id);
			if ( ! $info) continue;

			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_mod()->can_do($info, $action)) continue;

			// Chuyen den ham duoc yeu cau
			if (in_array($action, array('on', 'off')))
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
			case 'on':
			{
				$data['status'] = '1';
				break;
			}
			case 'off':
			{
				$data['status'] = '0';
				break;
			}
		}

		// Cap nhat data
		if (count($data))
		{
			$this->_model()->update($info->id, $data);
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
	}
	function _action_add($action)
	{
		// Lay input
		$menu = $this->uri->rsegment(3);

		// Kiem tra id
		$info = $this->menu_model->get_info_rule(array('key'=>$menu));
		if ( ! $info) return;

		// Kiem tra co the thuc hien hanh dong nay khong
		if ( ! $this->_mod()->can_do($info, $action)) return;

		// Chuyen den ham duoc yeu cau
		$this->{'_'.$action}($info);
	}



	/*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
	/**
	 * Danh sach
	 */
	function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		// Cap nhat sort_order
		if ($this->input->get('act') == 'update_order')
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

		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('menu');
		$filter = $this->_model()->filter_create($filter_fields, $filter_input);


		// Lay danh sach menu
		$menus = $this->menu_model->get_list();

		if( !isset( $filter['menu']) && count($menus)>0){
			$menu =$menus[0]->key;
			$filter['menu']=$filter_input['menu']=$menu;
		}
		else
			$menu = $filter['menu'];


		$this->data['filter'] = $filter_input;
		if(empty($menu))
			$this->_redirect(admin_url('menu'));

		$input['where']['menu']   = $menu;
		$list = $this->menu_item_model->get_list_hierarchy($input);
		$actions = array('edit', 'del', 'on', 'off', 'translate');

		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			// Lay danh sach item
			$row->url = handle_content($row->url, 'output');
			$row->_url_translate = admin_url("translate/table/menu_item/".$row->id);
			// Menu action
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		// Luu cac bien gui den view
		$this->data['list'] = $list;
		$this->data['menu']	 	= $menu;
		$this->data['menus']	 	= $menus;
		$this->data['url_update_order'] = current_url().'?act=update_order';

		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('menu_item'), lang('mod_menu_item'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display();
	}

}