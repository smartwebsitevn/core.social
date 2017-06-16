<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cards_mod extends MY_Mod
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('config')->load('mod/'.$this->_get_mod(), true, true);
	}
	
	/**
	 * Lay config
	 * 
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function config($key = null, $default = null)
	{
		$config = config('mod/'.$this->_get_mod(), '');
		
		return array_get($config, $key, $default);
	}
	
	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row = parent::add_info($row);
		
		if (isset($row->code) && empty($row->__decode_code))
		{
			$row->code = security_encrypt($row->code, 'decode');
			$row->__decode_code = true;
		}
		
		foreach (array('amount') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = (float) $row->$p;
			}
		}
		
		foreach (['expire', 'created', 'used_at', 'bought_at'] as $p)
		{
			if (isset($row->$p))
			{
				$row->{'_'.$p} = ($row->$p) ? get_date($row->$p) : '';
				$row->{'_'.$p.'_time'} = ($row->$p) ? get_date($row->$p, 'time') : '';
				$row->{'_'.$p.'_full'} = ($row->$p) ? get_date($row->$p, 'full') : '';
			}
		}
		
		return $row;
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 * 
	 * @param object $row
	 * @param string $action
	 * @return boolean
	 */
	public function can_do($row, $action)
	{
		if ( ! $row) return false;
		
		switch ($action)
		{
			case 'used':
			{
				return ! $row->used;
			}

			case 'bought':
			{
				return ! $row->bought;
			}
		}
		
		return parent::can_do($row, $action);
	}
	
	/**
	 * Tao filter tu input
	 * 
	 * @param array $fields
	 * @param array $input
	 * @return array
	 */
	public function create_filter(array $fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			if (in_array($f, ['used', 'bought']) && ! in_array($v, ['no', 'yes']))
			{
				$v = '';
			}
			
			$input[$f] = $v;
		}
		
		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'used':
				case 'bought':
				{
					$v = ($v == 'yes') ? 1 : 0;
					break;
				}
				
				case 'expire':
				{
					$_to = $input['expire_to'];
					$v = (strlen($_to)) ? array($v, $_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
				
				case 'created':
				{
					$_to = $input['created_to'];
					$v = (strlen($_to)) ? array($v, $_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}
			
			if (is_null($v)) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}
	
	/**
	 * Lay danh sach code_encode
	 * 
	 * @return array
	 */
	public function list_code_encode()
	{
		static $list;
		
		if (is_null($list))
		{
			$list = $this->_model()->get_list(array('select' => 'id, code_encode'));
			
			$list = array_pluck($list, 'code_encode', 'id');
		}
		
		return $list;
	}
	
	/**
	 * Kiem tra su ton tai cua code
	 * 
	 * @param string $code
	 * @return boolean
	 */
	public function has_code($code)
	{
		$code = md5($code);
		
		return in_array($code, $this->list_code_encode());
	}
	
	/**
	 * Random code
	 * 
	 * @return string
	 */
	public function random_code()
	{
		return random_string('numeric', $this->config('code_length'));
	}
	
	/**
	 * Tao code moi
	 * 
	 * @return string|null
	 */
	public function make_code()
	{
		for ($i = 1; $i <= 100; $i++)
		{
			$code = $this->random_code();
			
			if ( ! $this->has_code($code))
			{
				return $code;
			}
		}
	}
	
	/**
	 * Tao code theo so luong
	 * 
	 * @param int $quantity
	 * @return array
	 */
	public function make_codes($quantity)
	{
		$list = array();
		
		for ($i = 1; $i <= $quantity; $i++)
		{
			$code = $this->make_code();
			
			if ($code && ! in_array($code, $list))
			{
				$list[] = $code;
			}
		}
		
		return $list;
	}
	
	/**
	 * Lay danh sach serial
	 * 
	 * @return array
	 */
	public function list_serial()
	{
		static $list;
		
		if (is_null($list))
		{
			$list = $this->_model()->get_list(array('select' => 'id, serial'));
			
			$list = array_pluck($list, 'serial', 'id');
		}
		
		return $list;
	}
	
	/**
	 * Kiem tra su ton tai cua serial
	 * 
	 * @param string $serial
	 * @return boolean
	 */
	public function has_serial($serial)
	{
		return in_array($serial, $this->list_serial());
	}
	
	/**
	 * Random serial
	 * 
	 * @return string
	 */
	public function random_serial()
	{
		return random_string('numeric', $this->config('serial_length'));
	}
	
	/**
	 * Tao serial moi
	 * 
	 * @return string|null
	 */
	public function make_serial()
	{
		for ($i = 1; $i <= 100; $i++)
		{
			$serial = $this->random_serial();
			
			if ( ! $this->has_serial($serial))
			{
				return $serial;
			}
		}
	}
	
	/**
	 * Tao serial theo so luong
	 * 
	 * @param int $quantity
	 * @return array
	 */
	public function make_serials($quantity)
	{
		$list = array();
		
		for ($i = 1; $i <= $quantity; $i++)
		{
			$serial = $this->make_serial();
			
			if ($serial && ! in_array($serial, $list))
			{
				$list[] = $serial;
			}
		}
		
		return $list;
	}
	
	/**
	 * Tao card theo so luong
	 * 
	 * @param int $quantity
	 * @return array
	 */
	public function make_cards($quantity)
	{
		$codes = $this->make_codes($quantity);
		
		$serials = $this->make_serials($quantity);
		
		$total = min(count($codes), count($serials));

		$cards = array();
		
		for ($i = 0; $i < $total; $i++)
		{
			$cards[] = array(
				'code' 		=> $codes[$i],
				'serial' 	=> $serials[$i],
			);
		}
		
		return $cards;
	}
	
	/**
	 * Tao card va them vao data
	 * 
	 * @param int $quantity
	 * @param array $data
	 * @return array
	 */
	public function create($quantity, array $data)
	{
		$cards = $this->make_cards($quantity);
		
		foreach ($cards as $card)
		{
			$row = array_merge($data, array(
				'code' 			=> security_encrypt($card['code'], 'encode'),
				'code_encode' 	=> md5($card['code']),
				'serial' 		=> $card['serial'],
			));
			
			$this->_model()->create($row);
		}
		
		return $cards;
	}
	
	/**
	 * Lay danh sach menh gia the
	 * 
	 * @return array
	 */
	public function amounts()
	{
		$list = t('db')->select('amount')
			->group_by('amount')
			->order_by('amount', 'asc')
			->get('cards')->result();
		
		$list = array_pluck($list, 'amount');
		
		return array_map('floatval', $list);
	}
	
	/**
	 * Lay so luong the co the su dung cua menh gia
	 * 
	 * @param float $amount
	 * @return number
	 */
	public function available($amount)
	{
		return $this->_model()->filter_get_total([
			'amount' => $amount,
			'usable' => true,
		]);
	}
	
	/**
	 * Lay thong tin card
	 * 
	 * @param string $code
	 * @param string $serial
	 * @return array array(status, result)
	 */
	public function find_card($code, $serial)
	{
		if (strlen($code) != $this->config('code_length'))
		{
			return [false, 'code_invalid'];
		}
		
		if (strlen($serial) != $this->config('serial_length'))
		{
			return [false, 'serial_invalid'];
		}
		
		$card = $this->_model()->get_info_rule([
			'code_encode'	=> md5($code),
			'serial'		=> $serial,
		]);
		
		if ( ! $card)
		{
			return [false, 'card_not_exist'];
		}
		
		if ( ! $card->status)
		{
			return [false, 'card_used'];
		}
		
		if ($card->expire < now())
		{
			return [false, 'card_expired'];
		}
		
		$card = $this->add_info($card);
		
		return [true, compact('card')];
	}
	
	/**
	 * Doi the
	 * 
	 * @param string $code
	 * @param string $serial
	 * @return array array(status, result)
	 */
	public function charge_card($code, $serial)
	{
		list($status, $result) = $this->find_card($code, $serial);
		
		if ($status)
		{
			$this->set_used($result['card']->id);
		}
		
		return [$status, $result];
	}
	
	/**
	 * Lay the co the su su dung theo menh gia
	 * 
	 * @param float $amount
	 * @param int $quantity
	 * @return array
	 */
	public function get_cards($amount, $quantity, $set_used = true)
	{
		// Kiem tra input
		if ( ! $amount || ! $quantity)
		{
			return [];
		}
		
		// Lay card trong data
		$list = $this->get_list([
			'amount' => $amount,
			'usable' => true,
		], [
			'limit' => [0, $quantity],
			'order' => ['id', 'asc'],
		]);
		
		// Neu so luong card khong du $quantity
		if (count($list) != $quantity)
		{
			return [];
		}
		
		// Gan trang thai da su dung
		if ($set_used)
		{
			$ids = array_pluck($list, 'id');
			
			$this->set_used($ids);
		}
		
		return $list;
	}
	
	/**
	 * Gan card da duoc su dung
	 * 
	 * @param int|array $ids
	 */
	public function set_used($ids)
	{
		if (count($ids = (array) $ids))
		{
			t('db')->where_in('id', $ids)->update('cards', ['status' => 0]);
		}
	}
	
}