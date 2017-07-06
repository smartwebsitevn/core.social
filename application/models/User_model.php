<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
	public $table = 'user';

	public $select = 'user.*';

	public $timestamps = true;

	public $join_sql = [
		'user_group' => 'user.user_group_id = user_group.id',
		'purse' => 'user.id = purse.user_id',
	];

	public $relations = [
		'user_group' => 'one',
		'purse' => 'many',
	];

	public $_password_lenght = 6;

	public $_info_key = array('name','username','phone','email',);
	public $_info_genneral = array(
		'title','first_name',/*'last_name',*/'birthday','gender',
		/*'phone2',*/ 'fax','yahoo','skype','website','address','desc',
		//'profession','languages','passport',/*'tax_number',*/
		//'country','city','district','state','postcode',
	);
	public $_info_social = array('facebook','twitter','googleplus','linkedin','youtube','instagram');
	public $_info_id = [];//array('id_number','id_place','id_date'/*,'id_image_front','id_image_back'*/);
	public $_info_card = [];//array(/*'card_bank_id',*/'card_bank_name','card_bank_branch',		'card_account_name','card_account_number','card_atm_number');






/*
 * ------------------------------------------------------
 *  Main Handle
 * ------------------------------------------------------
 */
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'phone', 'verify', 'user_group','gender',
					 'city','country','birthday_year','subject_id',
				  'user_affiliate_id') as $p)
		{
			$f = (in_array($p, array('user_group'))) ? $p.'_id' : $p;
			$f = 'user.'.$f;
			$this->_filter_set_where($filter, $p, $f, $where);
		}
		// -=Modified=-
		if (isset($filter['!id']))
		{
			if( is_array($filter['!id']) )
			{
				$this->db->where_not_in( 'id', $filter['!id'] );
			}
			else
			{
				$this->db->where( "id !=", $filter['!id'] );
			}
		}

		if (isset($filter['key']))
		{
			$v = $this->db->escape_like_str($filter['key']);
			
			$this->db->where("(
				( user.email LIKE '%{$v}%' ) OR 
				( user.username LIKE '%{$v}%' ) OR 
				( user.phone LIKE '%{$v}%' )
			)");
		}
		
		if (isset($filter['email']))
		{
			$this->search('user', 'email', $filter['email']);
		}
		
		if (isset($filter['name']))
		{
			$this->search('user', 'name', $filter['name']);
		}
		
		if (isset($filter['blocked']))
		{
			$status = ($filter['blocked']) ? 'yes' : 'no';
			$where['user.blocked'] = config('verify_'.$status, 'main');
		}
		

		
		if (isset($filter['balance']))
		{
			if ($filter['balance'])
			{
				$where['user.balance_decode >'] = 0;
			}
			else 
			{
				$where['user.balance_decode <='] = 0;
			}
		}

		//=== Su ly loc theo ngay tao
		//  1: tu ngay  - den ngay
		if (isset($filter['created']) && isset($filter['created_to'])) {
			$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		} //2: tu ngay
		elseif (isset($filter['created'])) {
			if (is_array($filter['created']))
			{
				$where[$this->table .'.created >='] = $filter['created'][0];
				$where[$this->table .'.created <'] = $filter['created'][1];
			}
			else
			$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
		} //3: den ngay
		elseif (isset($filter['created_to'])) {
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		}
		return $where;
	}
	
	/**
	 * Tim kiem
	 */
	public function _search($field, $key)
	{
		switch ($field)
		{
			case 'email':
			{
				$this->db->like('user.email', $key);
				break;
			}
			case 'name':
			{
				$this->db->like('user.name', $key);
				break;
			}
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  Balance handle
 * ------------------------------------------------------
 */

	/**
	 * Lay balance cua user
	 */
	public function balance_get($id)
	{
		$user = $this->get_info($id, 'balance');
		
		if (empty($user->balance))
		{
			return 0;
		}
		
		$balance = $this->balance_encrypt('decode', $id, $user->balance);
		
		return $balance;
	}


	/**
	 *  Tang so du
	 *
	 * @param int 	$id
	 * @param float $amount
	 * @return float	So du cua user sau khi thuc hien thay doi
	 */
	public function balance_plus($id, $amount)
	{
		$balance_bf = $this->balance_get($id);

		$amount = (float) $amount;
		$balance_af=$balance_bf;
		if ($amount)
		{
			$balance_af = $balance_bf + $amount;

			$this->_balance_set($id, $balance_bf,$balance_af,$amount,'+');
		}

		return $balance_af;
	}
	/**
	 * Giam  so du
	 *
	 * @param int 	$id
	 * @param float $amount
	 * @return float	So du cua user sau khi thuc hien thay doi
	 */
	public function balance_minus($id, $amount)
	{
		$balance_bf = $this->balance_get($id);

		$amount = (float) $amount;

		$balance_af=$balance_bf;
		if ($amount)
		{
			$balance_af = $balance_bf - $amount;

			$this->_balance_set($id, $balance_bf,$balance_af,$amount,'-');
		}

		return $balance_af;
	}
	/**
	 * Cap nhat balance cua user
	 */
	private function _balance_set($id, $balance_bf,$balance_af,$amount,$change)
	{
		$data = array();
		$data['balance'] = $this->balance_encrypt('encode', $id, $balance_af);
		$data['balance_decode'] = $balance_af;
		$this->update($id, $data);
		if(config('log_user_balance')){
			model('log_user_balance')->log($id, $balance_bf,$balance_af,$amount,$change);
		}
	}

	/**
	 * Xu ly ma hoa balance cua user
	 * 
	 * @param string 	$act
	 * @param int 		$id
	 * @param float 	$balance
	 * @return float
	 */
	 function balance_encrypt($act, $id, $balance)
	{
		$this->load->library('encrypt');
		
		// Tao key ma hoa
		$key = config('encryption_key', '').$id;
		
		// Ma hoa
		if ($act == 'encode')
		{
			$balance = floatval($balance);
			$balance = $this->encrypt->encode($balance, $key);
		}
		
		// Giai ma
		elseif ($act == 'decode')
		{
			$balance = $this->encrypt->decode($balance, $key);
			
			// Neu balance sau khi giai ma khong phai la dang float
			/* if ( ! preg_match('/^-?[0-9]+\.?[0-9]*$/', $balance))
			{
				$balance = 0;
			} */
			
			$balance = floatval($balance);
		}
		
		return $balance;
	}
	
	
/*
 * ------------------------------------------------------
 *  Other fun
 * ------------------------------------------------------
 */
	/**
	 * Lay cac cong thanh toan user duoc phep su dung (kem theo amount duoc thanh toan trong 1 ngay)
	 */
	public function get_payments($id)
	{
		// Tai file thanh phan
		$this->load->model('user_group_model');
		
		// Lay user_group_id
		$user_group_id = 0;
		$user = $this->get_info($id, 'user_group_id, payments');
		if ($user)
		{
			$user_group_id = $user->user_group_id;
		}
		else 
		{
			$user_group_client = $this->user_group_model->get_type('client', 'id');
			$user_group_id = $user_group_client->id;
		}
		
		// Lay payments cua user_group_id
		$payments = $this->user_group_model->get_payments($user_group_id);
		
		// Cap nhat amount cua payment duoc set rieng cho user
		if ($user)
		{
			$user->payments = @unserialize($user->payments);
			foreach ($payments as $payment => $amount)
			{
				if (isset($user->payments[$payment]))
				{
					$payments[$payment] = floatval($user->payments[$payment]);
				}
			}
		}
		
		return $payments;
	}
	
	/**
	 * Lay ten cua user
	 */
	public function get_name($id)
	{
		if ( ! $id)
		{
			return lang('customer');
		}
		
		$user = $this->get_info($id, 'name');
		
		return ($user) ? $user->name : '';
	}
	
	/**
	 * Lay tong so thanh vien theo trang thai xac thuc
	 */
	public function get_total_verify($status)
	{
		$filter['verify'] = config('user_verify_'.$status, 'main');
		
		return $this->filter_get_total($filter);
	}
	
	/**
	 * Tim user tu key
	 * 
	 * @param string $key
	 * @return false|object
	 */
	public function find_user($key)
	{
		if ( ! $key) return false;
		
		$query = $this->db->where('id', $key)
					->or_where('email', $key)
					->or_where('username', $key)
					->or_where('phone', $key)
					->limit(1)
					->get('user');
		
		return $query->num_rows() ? $query->row() : false;
	}
	
	/**
	 * Kiem tra co ton tai user tuong ung voi key hay khong
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has_user($key)
	{
		return $this->find_user($key, 'id') ? true : false;
	}
	
	/**
	 * Gan action
	 * 
	 * @param int $id
	 * @param string $action
	 */
	public function set_action($id, $action)
	{
		$this->update($id, array(
			'action' => $action,
			'action_time' => now(),
		));
	}
	
}