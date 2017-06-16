<?php

use App\Transaction\Library\TranStatus;
use App\Transaction\Model\TranModel;

class Tran_widget extends MY_Widget
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/transaction/tran');
	}

	/**
	 * Moi nhat
	 *
	 * @param array $opts
	 */
	public function newest(array $opts = [])
	{
		$total = array_get($opts, 'total', 15);

		$list = model('tran')->filter_get_list([
			'status !=' => TranStatus::PENDING,
		], [
			'relation' => 'user',
			'order'    => ['id', 'desc'],
			'limit'    => [0, $total],
		]);

		$this->data['list'] = TranModel::makeCollection($list);
		$this->data['url_all'] = admin_url('tran');

		$this->_display($this->_make_view(array_get($opts, 'view'), __FUNCTION__));
	}

	/*
     * Menu cho thành viên
     */
	public function menu($current=null,$data=[],$temp="deposit" )
	{

		$this->data=array_merge(['current'=>$current],$data);
		//$temp = (!$temp) ? 'account' : $temp;
		$temp = 'tpl::_widget/tran/menu/'.$temp;
		return $this->_display($this->_make_view($temp, __FUNCTION__),1);
	}
}