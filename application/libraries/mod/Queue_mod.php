<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Queue_mod extends MY_Mod
{
	/**
	 * Test
	 */
	public function _t()
	{
// 		$this->push('email', [1, [2]]);
// 		$v = $this->get();
// 		$this->handle($v);
// 		$v = $this->work();

		//pr($v);
	}
	
	/**
	 * Them cong viec vao hang doi
	 * 
	 * @param string 	$key
	 * @param array 	$args
	 */
	public function push($key, array $args)
	{
		$this->_model()->create([
			'key' 		=> $key,
			'args' 		=> serialize($args),
			'status' 	=> 'pending',
		]);
	}
	
	/**
	 * Thuc hien xu ly cong viec dau tien
	 * 
	 * @param string $key
	 * @return object|false
	 */
	public function work($key = null)
	{
		$queue = $this->get_queue_pending($key);
		
		if ($queue)
		{
			$this->handle_queue($queue);
		}
		
		return $queue;
	}
	
	/**
	 * Lay cong viec can xu ly
	 * 
	 * @param string $key
	 * @return object|false
	 */
	protected function get_queue_pending($key = null)
	{
		$filter = is_null($key) ? [] : compact('key');
		$filter['status'] = 'pending';
		
		$input = [
			'select' 	=> 'queue.*',
			'order' 	=> ['id', 'asc'],
			'limit' 	=> [0, 1],
		];
		
		return head($this->get_list($filter, $input));
	}
	
	/**
	 * Xu ly cong viec
	 * 
	 * @param object $queue
	 */
	protected function handle_queue($queue)
	{
		$this->_model()->set_handling($queue->id);
		
		call_user_func_array(
			[t('lib')->driver('queue_handler', $queue->key), 'handle'],
			$queue->args
		);
		
		$this->_model()->del($queue->id);
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
	
		if (isset($row->args) && ! is_array($row->args))
		{
			$row->args = @unserialize($row->args);
		}
		
		foreach (array('handled') as $p)
		{
			if (isset($row->$p))
			{
				$row->{'_'.$p} = ($row->$p) ? get_date($row->$p) : '';
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
			case 'view':
			{
				return true;
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
			$v = security_handle_input($v, in_array($f, []));

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
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
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
	
}