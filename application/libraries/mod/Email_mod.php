<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_mod extends MY_Mod
{
	public $total_email = 0;
	public $total_email_send = 0;
	public $error_code = 0;

	/**
	 * error_code : ma loi
	 * 0 : lỗi không xác định
	 * 1 : lỗi không gửi được mail
	 * 2 : lỗi không có email smtp nào được thiết lập
	 * 3 : lỗi không gửi email nào
	 * 		toàn bộ các email smtp đều đang bận hoặc không kết nối được
	 */
	/**
	 * Lay setting
	 * 
	 * @param string 	$key
	 * @param mixed 	$default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting($this->_get_mod());
		
		return array_get($setting, $key, $default);
	}
	
	/**
	 * Gui mail
	 * 
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param array  $opt
	 * 		cc 		= '' 	:	CC
	 * 		bcc 	= '' 	:	BCC
	 * 		attach 	= array :	File dinh kem
	 * 		debug 	= false :	Bat debug
	 * @return boolean
	 */
	public function to($to, $subject, $message, array $opt = array())
	{
//		if ($this->is_local())
//		{
//			//return false;
//		}
		// xu ly thong tin email gui va email reply
		$config = setting_get_group('config');
		// get cac thong tin mac dinh
		$config = $this->setDefault($config);
		// tinh tong so email can gui
		$to = $this->_str_to_array($to);
		$this->total_email_send = $this->total_email = count($to);

		$mailconfig = array();
		$return = false;
		t('load')->library('email');
		switch($config['email_protocol'])
		{
			case 'phpmail':
				$mailconfig['protocol'] = 'mail';
				$return = $this->doSend($mailconfig, $config, $to, $subject, $message, $opt);
				break;
			case 'sendmail':
				$mailconfig['protocol'] = 'sendmail';
				$return = $this->doSend($mailconfig, $config, $to, $subject, $message, $opt);
				break;
			case 'smtp':
			{
				$mailconfig['protocol'] = 'smtp';
				$return = $this->doSendSMTP($mailconfig, $config, $to, $subject, $message, $opt);
				break;
			}
			case 'nencer_mail_api':
			{
				$return = $this->doSendNencerAPI($mailconfig, $config, $to, $subject, $message, $opt);
				break;
			}
		}
		return $return;


	}
	
	/**
	 * Gui mail theo email template
	 * 
	 * @param string $key
	 * @param string $to
	 * @param array  $params
	 * @param array  $opt
	 * @return boolean
	 */
	public function send($key, $to, $params = array(), array $opt = array())
	{
		$email = model('email')->get($key);
		if ( ! $email)
		{
			return false;
		}
		
		foreach (array('title', 'content') as $p)
		{
			$email->$p = $this->bind($email->$p, $params);
		}
		// neu la gui theo thu tu thi luu tru lai va gui sau sendnow= false
		if(isset($opt['sendnow']) && !$opt['sendnow'])
		{
			$data = array();
			$data['title'] = $email->title;
			$data['content'] = $email->content;
			$data['updated'] = $data['created'] = now();
			$this->load->model(array('emailsend_model','emailsend_to_model'));
			$id = 0;
			// them thong tin email vao csdl
			$this->emailsend_model->create($data, $id);
			$to = $this->_str_to_array($to);
			foreach($to as $row)
			{
				$data = array();
				$data['emailsend_id'] = $id;
				$data['email'] = $row;
				$this->emailsend_to_model->create($data);
			}
			return true;
		}
		return $this->to($to, $email->title, $email->content, $opt);
	}
	
	/**
	 * Tao config gui mail
	 * 
	 * @return array
	 */
	public function make_config()
	{
		$config = array();
		$config['protocol'] = $this->setting('protocol');
		$config['newline'] 	= "\r\n";
		$config['mailtype'] = 'html';
		$config['wordwrap'] = true;
		
		if ($config['protocol'] == 'smtp')
		{
			$config = array_merge($config, array_only($this->setting(), array(
				'smtp_host', 'smtp_user', 'smtp_pass', 'smtp_port', 'smtp_timeout',
			)));
		}
		
		return $config;
	}
	
	/**
	 * Kiem tra moi truong hien tai
	 * 
	 * @return boolean
	 */
	protected function is_local()
	{
		$host = t('input')->server('HTTP_HOST');
		
		return (
			in_array($host, array('localhost', '127.0.0.1'))
			|| starts_with($host, '192.168.1.')
		);
	}
	
	/**
	 * Tao message
	 * 
	 * @param string $message
	 * @return string
	 */
	protected function make_message($message)
	{
		$message .= $this->setting('signature');
		
		return $this->bind($message);
	}
	
	/**
	 * Gan bien vao noi dung
	 * 
	 * @param string $content
	 * @param array  $params
	 * @return string
	 */
	protected function bind($content, array $params = array())
	{
		$params = $this->make_replacement($params);
		
		return strtr($content, $params);
	}
	
	/**
	 * Tao replacement
	 * 
	 * @param array $params
	 * @return array
	 */
	protected function make_replacement(array $params)
	{
		$params['site_name'] 	= module_get_setting('site', 'name');
		$params['site_email'] 	= module_get_setting('site', 'email');
		
		$result = array();
		foreach ($params as $k => $v)
		{
			$k = '{'.$k.'}';
			
			$result[$k] = $v;
		}
		
		return $result;
	}

	function _str_to_array($email)
	{
		if ( ! is_array($email))
		{
			return (strpos($email, ',') !== FALSE)
					? preg_split('/[\s,]/', $email, -1, PREG_SPLIT_NO_EMPTY)
					: (array) trim($email);
		}

		return $email;
	}

	/*
	 * tao cac thong tin mac dinh cho email
	 */
	private function setDefault($config)
	{
		if(!isset($config['email_from_email']) || !$config['email_from_email'])
		{
			$config['email_from_email'] = $config['email'];
		}
		if(!isset($config['email_reply_email']) || !$config['email_reply_email'])
		{
			$config['email_reply_email'] = $config['email'];
		}
		if(!isset($config['email_from_name']) || !$config['email_from_name'])
		{
			$config['email_from_name'] = $config['name'];
		}
		if(!isset($config['email_reply_name']) || !$config['email_reply_name'])
		{
			$config['email_reply_name'] = $config['name'];
		}
		foreach(array('email_reply_name', 'email_from_name') as $row){
			if(strlen($config[$row]) > 30)
				$config[$row] = substr($config[$row], 0, 30).' ...';
		}
		return $config;
	}

	/*
	 * gui email
	 */
	private function doSend($mailconfig, $config, $bcc, $subject, $message,array $opt = array())
	{
		// lay email dau tien
		$to = '';
		$to = array_shift($bcc);
		$mailconfig['mailtype'] = 'html';
		$mailconfig['newline'] = "\r\n";
		$mailconfig['crlf'] = "\r\n";

		$mailconfig['charset']    = 'utf-8';
		t('email')->clear();

 		//$this->email->set_crlf( "\r\n" );
		$this->email->initialize($mailconfig);

		$this->email->from($config['email_from_email'], $config['email_from_name']);
		$this->email->reply_to($config['email_reply_email'], $config['email_reply_name']);
		$this->email->to($to);
		if(count($bcc))
			$this->email->bcc($bcc);
		$this->email->subject($subject);
		$this->email->message($message);
		if ($attach = array_get($opt, 'attach'))
		{
			foreach ((array) $attach as $file)
			{
				if (file_exists($file))
				{
					$this->email->attach($file);
				}
			}
		}
		if($this->email->send()) {
			if (array_get($opt, 'debug'))
			{
				echo t('email')->print_debugger();
			}
			return true;
		}
		return false;
	}

	/*
	 * gui email dang SMTP
	 */
	private function doSendSMTP($mailconfig, $config, $to, $subject, $message, array $opt = array())
	{
		// chuyen email sang array
		$maillist = $email_to = $to;

		$email_total = count($email_to);

		$this->load->model(array('emailsmtp_model'));
		$this->load->library('encrypt');

		// lay danh sach email smtp
		$where['order'] = array('default'=>'desc', 'sendtotal' => 'asc');
		$where['where']['active'] = 1;
		$lists = $this->emailsmtp_model->select($where);
		// tra ve neu khong co email nao trong danh sach;
		if(!$lists){
			$this->error_code = 2;
			return false;
		}
		$total_has_send = 0;

		foreach($lists as  $list)
		{

			$time = date('d-M-Y',now());
			$current =	strtotime($time);
			$sendtotal = $list->sendtotal;

			if($list->currenttime != $current)
			{
				$currenttime['currenttime'] = $current;
				$sendtotal = $currenttime['sendtotal'] = 0;

				$this->emailsmtp_model->update($list->id, $currenttime);
			}
			// kiem tra xem email nay da vuot qua so lan send hay chua
			if(($list->limit_per_day > 0) && ($list->limit_per_send + $sendtotal > $list->limit_per_day))
				continue;

			// kiem tra thoi gian nghi cua email giua 2 lan gui
			if(($list->limit_delay > 0) && ($list->limit_delay + $list->delay > now()))
				continue;


			// lay danh sach email duoc gui cho lan gui lan
			if($list->limit_per_send > 0)
				$mail_list_send = array_slice($maillist, $total_has_send, $list->limit_per_send);
			else
				$mail_list_send = $maillist;
			// thiet lap thong smtp
			$mailconfig['smtp_host'] = $list->host;
			$mailconfig['smtp_user'] = $list->email;
			$mailconfig['smtp_pass'] = $this->encrypt->decode($list->password);
			if($list->port)
				$mailconfig['smtp_port'] = $list->port;
			if($list->timeout)
				$mailconfig['smtp_timeout'] = $list->timeout;

			if(!$this->doSend($mailconfig, $config, $mail_list_send, $subject, $message, $opt)) {
				$this->error_code = 1;
				continue;
			}
			$this->error_code = 0;

			$total_has_send += count($mail_list_send);
			// cap nhap cho email nay da gui
			$_data['sendtotal'] = $sendtotal + count($mail_list_send);
			$_data['delay'] = now();
			$this->emailsmtp_model->update($list->id, $_data);

			// neu da gui het roi thi thoi, neu con thi email smtp khac tiep tuc gui
			if($total_has_send >= $email_total) break;
		}
		if($total_has_send > 0)
		{
			$this->total_email = $email_total;
			$this->total_email_send = $total_has_send;
			return true;
		}
		$this->error_code = 3;
		return false;
	}

	/**
	 * gui email bang nencer api
	 */
	private function doSendNencerAPI($mailconfig, $config, $to, $subject, $message, array $opt = array())
	{

		$mailconfig = array();
		$mailconfig['subject'] = $subject;
		$mailconfig['message'] = $message;
		$mailconfig['user'] = $config['nencer_mail_api_user'];
		$mailconfig['pass'] = $config['nencer_mail_api_pass'];
		$mailconfig['to'] = implode(',',$to);
		$mailconfig['from'] = $config['email_from_email'];
		$mailconfig['name_from'] = $config['email_from_name'];
		$mailconfig['reply'] = $config['email_reply_email'];
		$mailconfig['name_reply'] = $config['email_reply_name'];

		$this->load->library('Curl_library',null,'curl');
		$server_mail = "https://www.nencer.com/api/email_api";
		$return = $this->curl->post($server_mail,$mailconfig);
		$return = json_decode($return);
		//if(isset($return['status']) && $return['status'])
		//return true;
		if(isset($return->status) && $return->status)
			return true;
		$this->error_code =0;
		return false;
	}
}