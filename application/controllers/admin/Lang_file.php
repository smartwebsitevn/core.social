<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lang_file extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('lang_file_model');
		$this->lang->load('admin/lang');
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('phrase', 'del', 'sync')))
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
	
	
/*
 * ------------------------------------------------------
 *  Rules params
 * ------------------------------------------------------
 */




	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */

	/**
	 * Import du lieu
	 */
	function import()
	{
		$lang_id=$this->input->get('lang');
		$lang =model('lang')->get_info ( $lang_id);

		if ( ! $lang) return;
		/*$len = strlen(APPPATH.'language/'.$language->directory.'/');
		$file = substr($info->file, $len);*/
		$this->load->helper('directory');
		$path = APPPATH.'language/'.$lang->directory;
		$path_fixed = $path.'/';

		// sua o day
		$path_fixed =str_replace('\\','/',$path_fixed);
		$this->_import_process($lang,$path,$path_fixed);

		// Gui thong bao
		set_message(lang('notice_import_success'));
	}

	function _import_process($language,$source_dir,$path_fixed, $directory_depth = 5, $hidden = FALSE)
	{
		//echo '<br>==$source_dir:' . $source_dir;
		if ($fp = @opendir($source_dir)) {
			$filedata = array();
			$new_depth = $directory_depth - 1;
			$source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

			while (FALSE !== ($file = readdir($fp))) {

				// Remove '.', '..', and hidden files [optional]
				if ($file === '.' OR $file === '..' OR ($hidden === FALSE && $file[0] === '.')) {
					continue;
				}

				is_dir($source_dir . $file) && $file .= DIRECTORY_SEPARATOR;

				if (($directory_depth < 1 OR $new_depth > 0) && is_dir($source_dir . $file)) {
					//   echo '<br>==$source_dir con:'.$source_dir.$file;
					$filedata[$file] = $this->_import_process($language,$source_dir . $file,$path_fixed, $new_depth, $hidden);
					// echo '<br>--';pr($filedata[$file],false);
				} else {
					//echo '<br>-' . $source_dir  . $file;
					// neu la file kiem tra xem co phai la file lang
					if (strpos($file,'_lang.php') !== false)
					{
						//echo '<br>---lang';
						// import vao CSDL
						$file = $source_dir  . $file;
						$file =str_replace('\\','/',$file);
						$file =str_replace($path_fixed,'',$file);

						// them vao bang file lang
						$info= $this->lang_file_model->import($file);
						// them vao bang phrase
						$this->_phrase_import($info,$language);


					}
					else{
						//echo '<br>---ko phai lang';
					}

					//rename($source_dir . '/' . $file, $source_dir . '/' . ucfirst($file));

				}
			}

			closedir($fp);
			return $filedata;
		}
		else{

			// neu khong co thu muc ngon ngu thi lay ngon ngu tieng anh lam mac dinh
			// lay danh sach phrase english va nhan ban ra cho ngon ngu moi
			$files = model('lang_file')->get_list();
			foreach($files as $file){
				// lay cac phrase cua file ngon ngu
				$list = model('lang_phrase')->filter_get_list(array('lang'=>1,'file'=>$file->id));

				if($list) {
					$lang_phrases=array();
					foreach ($list as $info) {
						// luu vao csdl key ,value va ban dich cho ngon ngu
						model('lang_phrase')->set($language->id, $file->id, $info->key, $info->value, $info->value);
						$lang_phrases[$info->key]=$info->value;
					}
					// tao cache cho file lang
					//// neu co thay doi thi luu lai vao cache
					lang_set_cache($language->directory,$file->file,$lang_phrases);
				}
			}


		}

	}

	/**
	 * Chinh sua
	 */
	function _phrase($info)
	{
		$lang_id=$this->input->get('lang');
		$lang =model('lang')->get_info ( $lang_id);
		if(!$lang) return;
		redirect(admin_url('lang_phrase').'?lang='.$lang_id.'&file='.$info->id);
	}
	function _phrase_import($info,$language)
	{

		// check lang
		if (!$language  ) {
			set_message(lang('notice_value_invalid'));
			return;
		}
		// check file
		if (! $this->lang->check_exits ( $info->file, $language->directory )) {
			set_message(lang('notice_value_invalid'));
			return;
		}

		$lang_phrase = $this->lang->get ( $info->file, $language->directory );
		//pr($lang_phrase);
		if($lang_phrase){
			foreach ($lang_phrase as $k=>$v){
				// luu vao csdl key ,value va ban d?ch neu chua co
				model('lang_phrase')->set($language->directory,$info->id,$k,$v,$v);
			}
			// neu co thay doi thi luu lai vao cache
			lang_set_cache($language->directory,$info->file,$lang_phrase);
		}

	}
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Xoa
		$this->lang_file_model->del($info->id);
		// Xoa ban dich
		model("lang_phrase")->del_rule(array('file_id'=>$info->id));
		// Gui thong bao
		set_message(lang('notice_del_success'));
	}
	/**
	 * Sync du lieu
	 */
	function _sync($info){

		$lang_id=$this->input->get('lang');
		$language =model('lang')->get_info ( $lang_id);
		if(!$language) return;
		$this->_phrase_import($info,$language);
		// Gui thong bao
		set_message(lang('notice_reset_success',$info->file));
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
			$info = $this->lang_file_model->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
		}
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _can_do($row, $action)
	{
		switch ($action)
		{
			case 'del':
			{
				//pr($row,false);
				if(isset($row->protected) && $row->protected)
					return false;

				return true;
			}
			case 'sync':case 'edit':case 'phrase':
			{
				return TRUE;
			}

		}
		
		return FALSE;
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
		$this->load->helper('site');
		$this->load->helper('form');

		// Lay ngon ngu mac dinh



		// Tao filter
		$filter_input 	= array();
		$filter_fields 	=  array('lang', 'file', 'created', 'created_to');
		$filter = $this->lang_file_model->filter_create($filter_fields, $filter_input);


		$lang_id  =$filter_input['lang'];
		if ( empty($lang_id ))
		{
			$lang = lang_get_default();
			$lang_id = (isset($lang->id)) ? $lang->id : 0;
			$filter['lang']=$filter_input['lang']=$lang_id;
		}
		else{
			$lang =lang_get_info($lang_id );
		}
		$this->data['filter'] = $filter_input;
		// Lay tong so
		$total = $this->lang_file_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));

		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->lang_file_model->filter_get_list($filter, $input);

		$actions = array('phrase', 'del', 'sync');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions,'?lang='.$lang_id);
		foreach ($list as $row)
		{

			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;

			}
		}
		$this->data['list'] = $list;

		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= current_url().'?'.url_build_query($filter_input);
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;


		// Tao action list
		$actions = array();
		foreach (array('del') as $v)
		{
			$url = admin_url(strtolower(__CLASS__).'/'.$v);
			//if ( ! admin_permission_url($url)) continue;

			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;

		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['lang'] = $lang;
		$this->data['langs'] = model('lang')->get_list_active();
		$this->data['url_import'] = admin_url('lang_file/import').'?lang='.$lang_id;

		// Breadcrumbs
		/*$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('page'), lang('mod_page'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;*/

		// Hien thi view
		$this->_display();
	}
	
}