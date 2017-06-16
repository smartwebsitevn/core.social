<?php

class Verify_user_balance_library
{
	/**
	 * Test
	 */
	public function _t()
	{
//		$v = $this->handleVerifyTran(1, 191, 1234976);
//		$v = $this->handleVerifyHistory(1);
//		$v = $this->verifyTran(190, 1244976);
//		$v = $this->getUserHistory(1, 50);
//		$v = $this->verifyHistory(1, 100);
//		$v = $this->getListUserIdTranNewest(100);
//		$v = $this->matchBalance(1, 6.5);
//		$this->cron();

//		$trans = $this->getUserHistory(1, 10);
//		$v = $this->getTotalTranInTime($trans, 1444188915, 1444217248);
//		$v = $this->checkHistoryLimitTime($trans, 5);

//		dd($v);
//		return pr($v);


//		$trans = t('db')->select('user_id')->group_by('user_id')->get('tran')->result();
//		$user_ids = array_pluck($trans, 'user_id');
		$user_ids = $this->getListUserIdTranNewest(100);

		foreach ($user_ids as $user_id)
		{
			$verify = $this->verifyHistory($user_id);

			pr([$user_id, $verify], 0);
		}

	}

	/**
	 * Xu ly cron
	 *
	 * @param int $user_limit So luong user xu ly
	 */
	public function cron($user_limit = 5)
	{
		$user_ids = $this->getListUserIdTranNewest($user_limit);

		foreach ($user_ids as $user_id)
		{
			$this->handleVerifyHistory($user_id);
		}
	}

	/**
	 * Thuc hien block user
	 *
	 * @param int    $user_id
	 * @param string $error
	 */
	public function blockUser($user_id, $error = null)
	{
		foreach ((array) $user_id as $id)
		{
			$user = model('user')->get_info($id);

			if ( ! $user || $user->blocked)
			{
			    continue;
			}

			model('user')->update($user->id, array('blocked' => '1'));

			model('notification')->push("Block user: $user->id. Error: {$error}");
		}
	}

	/**
	 * Lay danh sach user id co giao dich moi nhat
	 *
	 * @param int $limit
	 * @return array
	 */
	protected function getListUserIdTranNewest($limit)
	{
		$trans = $this->listTran(array(
			'user !=' => 0,
			'status'  => array(
				$this->tranStatus('completed'),
				$this->tranStatus('refund')
			),
		), array(
			'select' => 'id, user_id',
			'order'  => array('id', 'desc'),
			'limit'  => array(0, 50),
		));

		$user_ids = array();
		foreach ($trans as $tran)
		{
			$user_ids[] = $tran->user_id;
		}

		$user_ids = array_unique($user_ids);
		$user_ids = array_slice($user_ids, 0, $limit);

		return $user_ids;
	}

	/**
	 * Xu ly xac thuc history.
	 * Neu khong thanh cong thi block user
	 *
	 * @param int $user_id Ma thanh vien
	 * @param int $limit   So luong giao dich xu ly
	 * @return bool
	 */
	public function handleVerifyHistory($user_id, $limit = 20)
	{
		$verify = $this->verifyHistory($user_id, $limit);

		if ( ! $verify['status'])
		{
			$this->blockUser($user_id, $verify['result']);
		}

		return $verify['status'];
	}

	/**
	 * Xac thuc history
	 *
	 * @param int $user_id Ma thanh vien
	 * @param int $limit   So luong giao dich xu ly
	 * @return array
	 */
	public function verifyHistory($user_id, $limit = 20)
	{
		// Day la user admin phia ngoai site phuc vu cho viec nhan tien
		// len co the phat sinh nhieu giao dich 1 luc len ta khong check user nay
		//if($user_id ==2)
		if ( ! $user_id ||$user_id ==2)
		{
			return $this->result(true);
		}

		$balance_cur = $this->getUserBalance($user_id);

		$history = $this->getUserHistory($user_id, $limit);		
		
		
		/*if ($balance_cur && empty($history))
		{
			return $this->resultError("
				Ton tai so du nhung khong ton tai giao dich nao.
				user_id: {$user_id}, balance: {$balance_cur}, history:{$history_st}
			");
		}

		$balance_last = reset($history)->user_balance;
		if ( ! $this->matchBalance($balance_cur, $balance_last))
		{
			return $this->resultError("
				So du hien tai khac so du sau giao dich cuoi.
				user_id: {$user_id}, {$balance_cur} != {$balance_last}, history:{$history_st}
			");
		}*/

		if ( ! $this->checkHistoryBalanceChange($history, $error))
		{
			$history_st= json_encode($history);
			return $this->resultError("
				Qua trinh thay doi so du trong history khong hop le.
				user_id: {$user_id}, tran_id_error: {$error['tran']->id}, tran_user_balance: {$error['tran']->user_balance}, balance: {$error['balance']}, history:{$history_st}
			");
		}
		
		if ( ! $this->checkHistoryLimitTime($history,3))
		{
			$history_st= json_encode($history);
			return $this->resultError("
				History ton tai nhieu hon 1 giao dich trong vong 3s. history:{$history_st}
			");
		}

		return $this->result(true);
	}

	
	/**
	 * Kiem tra gioi han so luong giao dich theo thoi gian
	 *
	 * @param array $history
	 * @param int   $time_limit
	 * @return bool
	 */
	protected function checkHistoryLimitTime(array $history, $time_limit = 5)
	{
		foreach ($history as $tran)
		{
			$start = $tran->created - $time_limit;

			$end = $tran->created;

			if ($this->getTotalTranInTime($history, $start, $end) > 1)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Lay tong so giao dich trong khoang thoi gian
	 *
	 * @param array $trans
	 * @param int   $start
	 * @param int   $end
	 * @return int
	 */
	protected function getTotalTranInTime(array $trans, $start, $end)
	{
		$total = 0;

		foreach ($trans as $tran)
		{
			if ($start <= $tran->created && $tran->created <= $end)
			{
				$total++;
			}
		}

		return $total;
	}

	/**
	 * Kiem tra qua trinh thay doi so du trong history
	 *
	 * @param array $history
	 * @param array $error
	 * @return bool
	 */
	protected function checkHistoryBalanceChange(array $history, &$error = null)
	{
		 return true;
		if ( ! count($history))
		{
		    return true;
		}

		$balance = reset($history)->user_balance;

		foreach ($history as $tran)
		{
			 // neu la giao dich mua hang bang cong thanh toan khong phai la so du thi bo qua
			 // vi thanh toan qua cong thanh toan ko lam thay doi so du
		 	 if (in_array($tran->_type, array('order')) && $tran->payment != 'balance')
			 {
			  	continue;
			 }
			if ( ! $this->matchBalance($balance, $tran->user_balance))
			{
				$error['tran'] = $tran;
				$error['balance'] = $balance;
				
			    return false;
			}

			$balance -= $this->getAmountChange($tran->_type, $tran->amount);
		}

		return true;
	}

	/**
	 * Lay lich su giao dich gan nhat cua user
	 *
	 * @param int $user_id
	 * @param int $limit
	 * @return array
	 */
	protected function getUserHistory($user_id, $limit)
	{
		return $this->listTran(array(
			'user'   => $user_id,
			'status' => array(
				$this->tranStatus('completed'),
				//$this->tranStatus('refund')
			),
			'created' => array(now()-24*60*60-10, now()+10),
		), array(
			'order' => array('id', 'desc'),
			'limit' => array(0, $limit),
		));
	}

	/**
	 * Xu ly xac thuc so du truoc va sau giao dich.
	 * Neu khong thanh cong thi block user
	 *
	 * @param int   $user_id
	 * @param int   $tran_id
	 * @param float $balance_pre
	 * @return bool
	 */
	public function handleVerifyTran($user_id, $tran_id, $balance_pre)
	{
		$verify = $this->verifyTran($tran_id, $balance_pre);

		if ( ! $verify['status'])
		{
			$this->blockUser($user_id, $verify['result']);
		}

		return $verify['status'];
	}

	/**
	 * Xac thuc so du truoc va sau giao dich
	 *
	 * @param int   $tran_id     Ma so giao dich
	 * @param float $balance_pre So du cua user truoc khi thuc hien giao dich
	 * @return array
	 */
	public function verifyTran($tran_id, $balance_pre)
	{
		$tran = $this->getTran($tran_id);

		$user_id = $tran->user_id;
		$balance_cur = $this->getUserBalance($tran->user_id);

		$balance_post = $this->makeTranUserBalance($tran->_type, $tran->amount, $balance_pre);

		if ($balance_cur < 0)
		{
			return $this->resultError("
				So du khong hop le.
				user_id: {$user_id}, balance: {$balance_cur}
			");
		}

		if ( ! $this->matchBalance($balance_cur, $tran->user_balance))
		{
		    return $this->resultError("
		    	So du hien tai khac so du sau giao dich.
		    	user_id: {$user_id}, {$balance_cur} != {$tran->user_balance}
		    ");
		}

		if ( ! $this->matchBalance($balance_cur, $balance_post))
		{
		    return $this->resultError("
		    	So du hien tai khac so du tinh duoc sau giao dich.
		    	user_id: {$user_id}, {$balance_cur} != {$balance_post}
		    ");
		}

		return $this->result(true);
	}

	/**
	 * Tinh so du sau giao dich
	 *
	 * @param string $tran_type
	 * @param float  $tran_amount
	 * @param float  $balance_pre
	 * @return float
	 */
	protected function makeTranUserBalance($tran_type, $tran_amount, $balance_pre)
	{
		$amount_change = $this->getAmountChange($tran_type, $tran_amount);

		return $balance_pre + $amount_change;
	}

	/**
	 * Lay amount change
	 *
	 * @param string $tran_type
	 * @param float  $amount
	 * @return float
	 */
	protected function getAmountChange($tran_type, $amount)
	{
		return $this->getAmountStatus($tran_type) == '-' ? -$amount : $amount;
	}

	/**
	 * Lay trang thai cong tru tien cua tran
	 *
	 * @param string $tran_type
	 * @return string
	 */
	protected function getAmountStatus($tran_type)
	{
		return in_array(
			$tran_type,
			array('admin_deposit', 'deposit', 'deposit_card', 'receive', 'refund')
		) ? '+' : '-';
	}

	/**
	 * Lay so du cua user
	 *
	 * @param int $user_id
	 * @return float
	 */
	protected function getUserBalance($user_id)
	{
		return model('user')->balance_get($user_id);
	}

	/**
	 * Lay thong tin tran
	 *
	 * @param int $tran_id
	 * @return object
	 */
	protected function getTran($tran_id)
	{
		$tran = model('tran')->get_info($tran_id);

		return $this->addTranInfo($tran);
	}

	/**
	 * Lay list trans
	 *
	 * @param array $filter
	 * @param array $input
	 * @return array
	 */
	protected function listTran(array $filter, array $input = array())
	{
		$list = model('tran')->filter_get_list($filter, $input);

		return array_map(array($this, 'addTranInfo'), $list);
	}

	/**
	 * Xu ly thong tin tran
	 *
	 * @param object $tran
	 * @return object
	 */
	protected function addTranInfo($tran)
	{
		$types = config('tran_types', 'main');

		if (isset($tran->type))
		{
			$tran->_type = isset($types[$tran->type]) ? $types[$tran->type] : '';
		}

		if (isset($tran->user_balance))
		{
			$tran->user_balance = (float) security_encrypt($tran->user_balance, 'decode');
		}

		if (isset($tran->amount))
		{
			$tran->amount = (float) $tran->amount;
		}

		return $tran;
	}

	/**
	 * Lay tran status id
	 *
	 * @param string $name
	 * @return int|false
	 */
	protected function tranStatus($name)
	{
		return config("tran_status_{$name}", 'main');
	}

	/**
	 * So sanh gia tri balance
	 *
	 * @param float $a
	 * @param float $b
	 * @return bool
	 */
	protected function matchBalance($a, $b)
	{
		$left = abs(round($a) - round($b));

		return (0 <= $left && $left <= 5);
	}

	/**
	 * Tao ket qua tra ve
	 *
	 * @param bool  $status
	 * @param mixed $result
	 * @return array
	 */
	protected function result($status, $result = null)
	{
		return compact('status', 'result');
	}

	/**
	 * Tao ket qua tra ve false
	 *
	 * @param string $error
	 * @return array
	 */
	protected function resultError($error)
	{
		return $this->result(false, $error);
	}

}