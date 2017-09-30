<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		// Kiem tra ip
		$ip = $this->input->ip_address();
		$server_ip = config('server_ip', 'main');
		//write_file_log('a.txt','co crom');
		if ($ip != $server_ip)
		{
			write_file_log('cronjob.txt', "IP {$ip} is not allowed to access");
			//log_message('error', "IP {$ip} is not allowed to access");
			//exit('This IP is not allowed to access');

		}
	}

	/**
	 * Caller
	 */
	public function _remap($method, $params = array())
	{
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}
		else
		{
			call_user_func_array(
				[t('lib')->driver('cronjob', $method), 'handle'],
				$params
			);
		}
	}


	/*
     * ------------------------------------------------------
     *  Main handle
     * ------------------------------------------------------
     */
	/**
	 * Chay cac cronjob da dang ky (1 phut chay 1 lan)
	 */
	function auto()
	{
		write_file_log('auto.txt','run auto');
		static $list=array();
		if(!$list){
			$list = model('cronjob')->get_list_rule(array('status'=>1));

			foreach($list as $cronjob){
				$setting =unserialize($cronjob->setting);
				//pr($setting);
				//	pr($cronjob);
				$setting['day']    = isset($setting['day']) ?  $setting['day'] : 0;
				$setting['minute'] = isset($setting['minute']) ?  $setting['minute'] : 0;
				$setting['hour'  ] = isset($setting['hour']) ?  $setting['hour'] : 0;

				//l?y th?i gian hi?n t?i v� ki?m tra xem l�c n�y c� ch?y cronjob hay khon
				$run = false;
				$now = get_time_info(now());
				if($setting['day'] == 1 || (is_array($setting['day']) && in_array($now['d'], $setting['day']))){
					if($setting['hour'] == 1 || (is_array($setting['hour']) && in_array($now['h'], $setting['hour']))){
						if($setting['minute'] == 1 || (is_array($setting['minute']) && in_array($now['mi'], $setting['minute']))){
							$run = true;
						}
					}
				}
				if($run)
				{
					$url =unserialize($cronjob->url);
					try{
						echo '<br>'.file_get_contents($url);
						write_file_log('auto.txt','-> run cronjob:'.$url);
					}
					catch(Exception $e){
						write_file_log('auto.txt','-> run cronjob error:'.$url .json_encode( $e->getMessage()));
					}


				}
			}
		}
	}

	function process_social_point()
	{
		write_file_log('auto.txt','-> run process_social_point:'.mod('product')->setting('product_delta_point'));
		$x=mod('product')->setting('product_delta_point'); // so phan tram thanh vien
		$user_total=model('user')->filter_get_total(['show'=>1]); // so thanh vien
		//pr_db($user_total);

		$delta= -((abs($x)*$user_total) /100);
		$list =model('product')->filter_get_list(['point_total_lte'=>$delta,'show'=>1],['select'=>'id,user_id,point_total,name,seo_url']);
		//pr($delta,0);
		//pr_db($list,0);

		if($list){
			foreach($list as $row){
				$row =mod('product')->add_info_url($row);
				//-- Khoa bai viet
				model('product')->update_field($row->id,'is_lock',1);
				//-- Gui thong bao cho chu bai viet
				mod('user_notice')->send($row->user_id, 'Bài viết <b>' . $row->name . '</b> đã bị khóa vì bị <b>'.$row->point_total.'point</b>', ['url' => $row->_url_view]);
			}
		}

	}
	/**
	 * gui email
	 */
	function send_mail()
	{
		// lay 1 email chua gui
		$where = array();
		$where['where']['status'] = 'pending';
		$where['order'] = array('id' => 'asc');
		$where['limit'] = array(1);
		$emailsends = model('emailsend')->select($where);
		if(!$emailsends)
			return true;
		foreach($emailsends as $emailsend) {
			// lay danh sach email can gui
			$where = array();
			$where['where']['emailsend_id'] = $emailsend->id;
			$where['where']['status'] = 0;
			$where['limit'] = array(1000);
			$where['order'] = array('id' => 'asc');
			$emailsend_to = model('emailsend_to')->select($where);
			// neu khong co email nao thi xoa
			if (!$emailsend_to) {
				model('emailsend')->del($emailsend->id);
				continue;
			}

			$emails = array();
			foreach ($emailsend_to as $row) {
				$emails[] = $row->email;
			}

			// neu khong gui duoc email thi dung lai qua trinh xu ly
			// cap nhap trang thai thu nay khong gui duoc
			$status = mod('email')->to($emails, $emailsend->title, $emailsend->content);
			$error_code = mod('email')->error_code;
			// neu khong smtp nao gui duoc thi dung xu ly
			if ($error_code === 2 || $error_code === 3) {
				exit;
			}
			if (!$status) {
				// loi khong xac dinh hoac loi khong gui duoc email
				if ($error_code === 0 || $error_code === 1) {
					$data = array();
					$data['status'] = 'failed';
					$data['updated'] = now();
					$data['error'] = $emailsend->error + count($emails);
					model('emailsend')->update($emailsend->id, $data);
				}
				exit;
			}
			// neu gui email thanh cong, kiem tra so email gui co het khong?
			$total_email = mod('email')->total_email;
			$total_email_send = mod('email')->total_email_send;

			// neu da gui het thi xoa noi dung email va danh sach email trong hang cho
			if ($total_email_send >= $total_email) {
				//model('emailsend')->del($emailsend->id);
				$where = array();
				$where['emailsend_id'] = $emailsend->id;
				model('emailsend_to')->del_rule($where);

				// cap cap trang thai email
				$data = array();
				$data['status'] = 'completed';
				$data['updated'] = now();
				$data['success'] = $emailsend->success + $total_email_send;
				model('emailsend')->update($emailsend->id, $data);
			} // neu chua gui het duoc email trong danh sach thi xoa cac email da gui di
			else {
				$i = 0;
				foreach ($emailsend_to as $row) {
					if($i >= $total_email_send)
						break;
					model('emailsend_to')->del($row->id);
					$i ++;
				}
				// cap cap trang thai email
				$data = array();
				$data['status'] = 'processing';
				$data['updated'] = now();
				$data['success'] = $emailsend->success + $total_email_send;
				model('emailsend')->update($emailsend->id, $data);
			}
		}
		return true;
	}

	/**
	 * Backup data va gui den email admin
	 */
	function backup()
	{
		// Lay du lieu tu data
		$this->load->dbutil();
		$prefs = array();
		$prefs['format'] = 'gzip';
		$backup =& $this->dbutil->backup($prefs);

		// Tao url file backup
		$config = config('upload', 'main');
		$file 	= $config['path'].$config['folder'].'/private/_db_'.mdate('%Y_%m_%d_%H_%i_%s', now()).'.'.$prefs['format'];

		// Tao file backup data tren server
		$this->load->helper('file');
		write_file($file, $backup);


		// Gui email kem theo file backup
		$admin_email = config('admin_email', 'main');
		if ($admin_email)
		{
			mod('email')->to(
				$admin_email,
				$config_site['name'].' - '.get_date(now(), 'full'),
				$config_site['name'].' - '.get_date(now(), 'full'),
				array('attach' => $file)
			);
		}

		echo 'TRUE';
	}

	/**
	 * Xu ly data index
	 */
	function tran_call_module()
	{
		// Kiem tra ma bao mat
		if (!security_check_query(array('id', 'act')))
		{
			exit('Request failure');
		}

		// Tai cac file thanh phan
		$this->load->helper('tran');
		$this->load->model('tran_model');

		// Lay thong tin tran
		$id = $this->input->get('id');
		$id = (!is_numeric($id)) ? 0 : $id;
		$tran = $this->tran_model->get_info($id);
		if (!$tran)
		{
			exit('Transaction not exist');
		}

		// Goi ham xu ly cua module tuong ung
		$act = $this->input->get('act');
		$result = tran_call_module($tran, $act, FALSE);

		echo serialize($result);
	}

	/**
	 * Xu ly invoice
	 */
	function invoice_call_module()
	{
		// Kiem tra ma bao mat
		if (!security_check_query(array('id', 'act')))
		{
			exit('Request failure');
		}

		// Tai cac file thanh phan
		$this->load->helper('invoice');
		$this->load->model('invoice_model');

		// Lay thong tin tran
		$id = $this->input->get('id');
		$id = (!is_numeric($id)) ? 0 : $id;
		$invoice = $this->invoice_model->get_info($id);
		if (!$invoice)
		{
			exit('Invoice not exist');
		}

		// Goi ham xu ly cua module tuong ung
		$act = $this->input->get('act');
		$result = invoice_call_module($invoice, $act, FALSE);

		echo serialize($result);
	}



	/*
     * ------------------------------------------------------
     *  Data index handle
     * ------------------------------------------------------
     */

	/**
	 * Import bang index bang tay
	 */

	function import_index() {
		$models=array ('movie');
		foreach ( $models as $m ) {
			model($m)->index_import();
		}

		pr('thanhcong');
	}

	/** Nua tieng chay 1 lan
	 * Xu ly data index
	 */
	function data_index()
	{
		write_file_log('a.txt','1');
		// Tai file thanh phan
		$this->load->model('data_index_model');

		// Khoi phuc cac index xu ly bi loi
		$this->data_index_model->restore_index_error();

		// Lay index can xu ly
		$data = $this->_data_index_get();
		if (!$data)
		{
			return FALSE;
		}
		write_file_log('a.txt','2');
		// Xu ly index
		$model = $data['table'].'_model';
		$this->load->model($model);
		$this->$model->index_update_list($data['index']);

		// Xoa index can xu ly
		foreach ($data['index'] as $row)
		{
			$this->data_index_model->del($row->id);
		}
		echo 'DONE!';
		file_put_contents ( '../cronjob/data_index.txt', 'Last run: ' . date ( 'h:m:s d/m/Y' ) );

	}

	/**
	 * Lay cac index can xu ly
	 */
	private function _data_index_get()
	{
		$tables = array('movie');
		foreach ($tables as $v)
		{
			// Lay table can xu ly
			$table_id = $this->setting_model->get('data_index_table_id');
			$table_id += 1;
			$table_id = (!isset($tables[$table_id])) ? 0 : $table_id;
			$this->setting_model->set('data_index_table_id', $table_id);
			$table = $tables[$table_id];

			// Lay cac index can xu ly cua table
			$index = $this->data_index_model->get($table);
			if (count($index))
			{
				// Cap nhat trang thai dang xu ly cho list
				$this->data_index_model->set_status($index);

				// Khai bao du lieu tra ve
				$result = array();
				$result['table'] = $table;
				$result['index'] = $index;

				return $result;
			}
		}

		return FALSE;
	}
	/*
     * ------------------------------------------------------
     *  Order handle
     * ------------------------------------------------------
     */
	/**
	 * Login vao acc cua payment
	 */
	function login() {
		// Tai cac file thanh phan
		$this->load->helper ( 'payment' );

		// Kiem tra payment
		$payment = $this->uri->rsegment ( 3 );

		if (! payment_active ( $payment )) {
			exit ( 'Payment not exists' );
		}
		echo '<br>Chay login.';
		file_put_contents('../cronjob/log_'.$payment.'_login.txt', 'Login Last run: ' . date('h:m:s d/m/Y'));

		// Chuyen den ham login cua payment
		$this->payment->{$payment}->login ();
	}

	/**
	 * Xu ly order theo lich su giao dich cua payment
	 */
	function history()
	{
		//echo '<br> History.';
		// Tai cac file thanh phan
		$this->load->helper('payment');
		$this->load->helper('order');
		$this->load->helper('tran');
		$this->load->model('tran_model');
		// Kiem tra payment

		$payment = $this->uri->rsegment(3);
		//echo '<br> $payment:'.$payment;
		if (!payment_active($payment)) {
			exit ('Payment not exists');
		}

		// Check Tran pendding
		$trans_pendding = $this->tran_model->filter_get_list(array('payment' => $payment, 'status' => config('tran_status_pending', 'main')));
		$num_trans_pendding = count($trans_pendding);
		if ($num_trans_pendding <= 0)
			return false;
		//echo '<br>Don hang '.$payment.' cho su ly:';print_r($trans_pendding);
		// huy bo don hang qua 1 ngay ma ko thanh toan
		$now = now();
		$num_trans_cancel = 0;
		foreach ($trans_pendding as $tran) {
			$left = $now - $tran->created;
			$days_left = $left / (24 * 60 * 60);    // tinh so ngay
			//echo '<br>date left='. $days_left;
			if ($days_left >= 2) // neu qua 2 ngay thi huy bo don hang (Tranh de lau phai log vao BANK nhieu va dong thoi giai phong don hang)
			{
				$num_trans_cancel++;
				tran_action($tran, 'cancel');

			}
		}

		if ($num_trans_cancel == $num_trans_pendding)
			return false;


		//$this->log->write_log('error','History'.$payment);
		echo '<br>Chay history.';
		file_put_contents('../cronjob/log_' . $payment . '_history.txt', 'History Last run: ' . date('h:m:s d/m/Y'));

		// Lay danh sach trans tu payment
		$trans = $this->payment->{$payment}->history();
		//$trans= $this->payment->{$payment}->history ('1/03/2014', '10/03/2014');
		//echo '<br>Giao dich tu '.$payment.':';print_r($trans);
		foreach ($trans as $tran) {
			// Lay order_id tu content_transfer
			$tran_id = $this->payment->{$payment}->bank_get_tranid_from_content_transfer($tran->content_transfer);
			if (!$tran_id) {
				continue;
			}
			// Lay thong tin Tran
			$tran_db = $this->tran_model->get_info($tran_id);
			if (!$tran_db) {
				continue;
			}
			// Kiem tra tran
			if ($tran_db->status != config('tran_status_pending', 'main') || $tran_db->amount > ( float )$tran->amount || $tran->status != '+') {
				continue;
			}
			// Xu ly Tran
			if ($tran_db->payment == $payment) {
				// Xac nhan khach da da thanh toan
				tran_action($tran_db, 'active');
			}

		}
	}

	function _change_file()
	{
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$dir = './public/';
		directory_map($dir);
	}
	function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
	{
		if ($fp = @opendir($source_dir))
		{
			$filedata	= array();
			$new_depth	= $directory_depth - 1;
			$source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (FALSE !== ($file = readdir($fp)))
			{
				// Remove '.', '..', and hidden files [optional]
				if ($file === '.' OR $file === '..' OR ($hidden === FALSE && $file[0] === '.'))
				{
					continue;
				}
				is_dir($source_dir.$file) && $file .= DIRECTORY_SEPARATOR;

				if (($directory_depth < 1 OR $new_depth > 0) && is_dir($source_dir.$file))
				{
					$filedata[$file] = directory_map($source_dir.$file, $new_depth, $hidden);
				}
				else
				{
					$link_file = $source_dir.'/'.$file;
					$time  = filemtime($source_dir.'/'.$file);
					$date  = date("d/m/Y H:s", $time);
					if($time > mktime(0,0,0,11,1,2015))
					{
						echo $link_file;
						die();
						//copy($link_file, 'file_modified/'.$file);
					}
				}
			}
			closedir($fp);
			return $filedata;
		}

		return FALSE;
	}

	/**
	 * AUTO SET FEATURE
	 */
	function auto_feature()
	{
		$this->load->model('movie_model');
		$this->movie_model->update_rule(array('view_in_week >'=>10),array('feature'=>1));
		echo 'DONE!';
	}


	/*
     * ------------------------------------------------------
     *  User Subscribe
     * ------------------------------------------------------
     */
	/** Nua tieng chay 1 lan
	 * Lay  movie thay doi, lay user theo doi movie do va day vao hang doi gui email
	 */
	function user_subscribe()
	{
		$this->load->model('email_queue_model');
		$this->load->model('movie_updated_model');
		$this->load->model('movie_subscribe_model');

		//kiem tra hang doi co trong khong , trong moi day vao hang doi

		// lay phim da cap nhap
		$input=array();
		$input['select'] ='movie_id';
		$input['limit'] =array(0,1);
		$input['order'] =array('created','asc');
		$list = $this->movie_updated_model->get_list($input);
		if($list){
			$movie_id =$list[0]->movie_id;
			// lay danh sach subscribe
			$input=array();
			$input['where'] =array('movie_id'=>$movie_id);
			$input['order'] =array('created','asc');
			$list = $this->movie_subscribe_model->get_list($input);
			$email_key='movie_updated';
			if($list){
				// khi lay dc danh sach ta them vao bang hang doi email
				//pr($list);
				$data=array();

				$data['email_key']=$email_key;
				$data['table']='movie';
				foreach($list as $it){
					$data['email']=$it->email;
					$data['table_id']=$it->movie_id;
					$list = $this->email_queue_model->add($data);
				}
				// sau khi add cac email theo doi movie vao hang doi, ta tien hanh xoa movie trong bang update
				$this->movie_updated_model->del($movie_id);

			}

		}

		echo 'DONE!';
		file_put_contents ( '../cronjob/user_subscribe.txt', 'Last run: ' . date ( 'h:m:s d/m/Y' ) );
	}


}