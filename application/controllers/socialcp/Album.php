<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		//$this->lang->load('admin/'.$this->_get_mod());
		//$this->lang->load('admin/album');
	}
	
	/**
	 * Remap method
	 */

	function _remap($method)
	{

		if (in_array($method, array('edit', 'del')))
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
 *  Rules params
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params)
	{
		if (!is_array($params))
		{
			$params = array($params);
		}

		$rules = array();
		$rules['status'] 			= array('status', 'trim|is_natural');
		$rules['feature'] 			= array('feature', 'trim|is_natural');
		//$rules['image'] 		= array('image', 'callback__check_image');
		$this->form_validation->set_rules_params($params, $rules);

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
	 * Lay image
	 */
	protected function _get_image($id = NULL)
	{
		if (is_null($id))
		{
			$id = $this->_get_id_cur();
		}

		$image = model('file')->get_info_of_mod($this->_get_mod(), $id, 'image', 'id, file_name');

		return $image;
	}
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get($this->_get_mod())
			: $this->uri->rsegment(3);
	}

	private function getUrl($url, $id = 0){
		$where['where']['lang_id'] = $this->input->post('lang_id');
		if($id)
			$where['where']['id !='] = $id;
		$where['where']['url'] = $url;
		if($this->_model()->total($where)){
			return $this->getUrl($url.strtolower(random_string('alpha',3)), $id);
		}
		return $url;
	}
	/*
       * ------------------------------------------------------
       *  Prepare data handle
       * ------------------------------------------------------
       */
	protected function _fields()
	{
		return  array('name', /*'cats_id', 'summary',*/ 'content',
			/*'order',*/'feature','status','lang_id',
			'url','description', 'keywords','titleweb',
			'nofollow','comment_status','public');
	}

	protected function _get_params()
	{
		$params = $this->_fields();
		$params[] = 'image';

		return $params;
	}

	/**
	 * Lay input
	 */

	protected function _get_inputs($param = '')
	{
		$data = array();
		$fields = $this->_fields();
		foreach ($fields as $f) {
			$v = $this->input->post($f);
			if (!$v) $v = '';

			/*if (in_array($f, array(	'cat_j_type_id'	))) {
				$v = json_encode($v);
			}*/
			//$v =$this->_get_user_id($v);
			//	$v= currency_handle_input($v);
			$data[$f] = $v;
		}

		$data['content'] = handle_content($data['content'], 'input');
		if(!$data['url'])
			$data['url'] = $data['name'];

		$data['url'] =convert_vi_to_en($data['url']);
		//$data['created'] = get_time_from_date($data['created']);
		$data['admin_id'] = admin_get_account_info()->id;
		$data['updated'] = now();
		/*if($data['cats_id']) {
			$data['cat_id'] = $data['cats_id'][0];
			$data['cats_id'] = implode(',', $data['cats_id']);
		} else {
			$data['cats_id'] = '';
			$data['cat_id'] = 0;
		}*/


		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');

		$image = $this->_get_image();
        if ($image)
        {
            $data['image_id']	= $image->id;
            $data['image_name']	= $image->file_name;
        }
		//pr($data);
		return ($param) ? $data[$param] : $data;
	}


	/**
	 * Tao data gui den view
	 *
	 * @param int $id
	 */
	protected function _create_view_data($id)
	{
		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 			= 'single';
		$widget_upload['file_type'] 	= 'image';
		$widget_upload['status'] 		= config('file_public', 'main');
		$widget_upload['resize'] 		= TRUE;
		$widget_upload['thumb'] 		= TRUE;
		$widget_upload['url_update']	= ($id > 0) ? current_url().'?act=update_image' : null;

		$widget_upload['table'] 		= $this->_get_mod();
		$widget_upload['table_id'] 		= $id;
		// up anh cua cat
		$widget_upload['table_field'] 	= 'image';
		$this->data['widget_upload'] 	= $widget_upload;
		// up anh cua tac gia
		//$widget_upload['table_field'] 	= 'author';
		//$this->data['upload_author'] 	= $widget_upload;

		// Other
		$this->data['action'] = current_url();
	}

	/*
     * ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
     */
	function _action_add($action)
	{
		// Lay input
		$cat_id = $this->uri->rsegment(3);

		// Kiem tra id
		$info = model('album_cat')->get_info($cat_id);
		if ( ! $info) return;

		// Kiem tra co the thuc hien hanh dong nay khong
		//if ( ! $this->_mod()->can_do($info, $action)) return;

		// Chuyen den ham duoc yeu cau
		$this->{'_'.$action}($info);
	}
	/**
	 * Them moi
	 */
	public function _add($cat)
	{
		$fake_id = $this->_get_id_cur();
		$form = array();
		$form['view'] = 'form';
		$form['validation']['params'] = $this->_get_params();
		$form['submit'] = function()use ($fake_id,$cat)
		{
			$data = $this->_get_inputs();
			$data['cat_id'] =$cat->id;
			$data['sort_order'] = $this->_model()->get_total() + 1;
			$id = 0;
			$this->_model()->create($data,$id);
			// Cap nhat lai table_id table file
			model('file')->update_table_id_of_mod($this->_get_mod(), $fake_id, $id);
			fake_id_del($this->_get_mod());

			set_message(lang('notice_add_success'));
			return admin_url($this->_get_mod().'?cat_id='.$cat->id);
		};
		$form['form'] = function() use ($fake_id,$cat)
		{
			$this->_create_view_data($fake_id,$cat);
			$this->data['cat']	 	= $cat->id;
			$this->_display('form');
		};
		$this->_form($form);
	}
	
	/**
	 * Chinh sua
	 */
	protected function _edit($info)
	{
		$info  =$this->_mod()->add_info($info);
		$this->data['info'] = $info;
		// Cap nhat thong tin
		if ($this->input->get('act') == 'update_image')
		{
			$this->_update_image($info->id);
			return;
		}
		$form = array();
		$form['view'] = 'form';
		$form['validation']['params'] =$this->_get_params();
		$form['submit'] = function() use ($info)
		{
			$data = $this->_get_inputs();
			$this->_model()->update($info->id, $data);
			set_message(lang('notice_update_success'));

			return admin_url($this->_get_mod().'?cat_id='.$info->cat_id);
		};
		$form['form'] = function() use ($info)
		{
			$this->_create_view_data($info->id);
			$this->data['cat']	 	= $info->cat_id;
			$this->_display('form');
		};
		$this->_form($form);
	}
	
	/**
	 * Xoa
	 */
	protected function _del($info)
	{
		$this->_model()->del($info->id);

		$this->load->helper('file');
		file_del_table($this->_get_mod(), $info->id);
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
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
		$filter_fields 	= array('cat_id','status');
		$filter = $this->_mod()->create_filter($filter_fields, $filter_input);

		// Lay danh sach cat
		$cats = model('album_cat')->get_list();
		if( !isset( $filter['cat_id']) && count($cats)>0){

			$cat =array_values($cats)[0]->id;
			$filter['cat_id']=$filter_input['cat_id']=$cat;
		}
		else if(isset( $filter['cat_id']))
			$cat = $filter['cat_id'];


		$this->data['filter'] = $filter_input;
		if(empty($cat))
			redirect_admin('album_cat');

		//$input['where']['cat_id']   = $cat;
		$list = $this->_model()->filter_get_list($filter);
		$actions = array('edit', 'del'/*, 'translate'*/);
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
			// Lay danh sach item
			//$row->_url_translate = admin_url("translate/table/menu_item/".$row->id);
			// Menu action
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		// Luu cac bien gui den view
		$this->data['list'] = $list;
		$this->data['cat']	 	= $cat;
		$this->data['cats']	 	= $cats;
		$this->data['sort_url_update'] = current_url().'?act=update_order';

		// Breadcrumbs
		/*$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('menu_item'), lang('mod_menu_item'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;*/

		// Hien thi view
		$this->_display();
	}

}