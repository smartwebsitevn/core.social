<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		redirect();
	}
	public function click($id)
	{
		// Lay thong tin
		$banner = model('ads_banner')->get_info_rule(array('id' => $id, 'status' => 1));
		if (!$banner) {
			redirect();
		}
		if($banner->url){
			model('ads_banner')->update_field($id,'count_click',$banner->count_click+1);
			$this->_response(array('location'=>$banner->url));
		}

	}
}
