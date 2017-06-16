<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_api_model extends MY_Model
{
	public $table = 'log_api';
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
	
		foreach (array('id', 'key', 'ip', 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		if (isset($filter['value']))
		{
			$v = $this->db->escape_like_str($filter['value']);
				
			$this->db->where("(
				( input LIKE '%{$v}%' ) OR
				( output LIKE '%{$v}%' )
			)");
		}
		
		return $where;
	}
	
	/**
	 * Them moi
	 */
	public function create(array $data, &$insert_id = null)
	{
		$this->cleanup();
		
		$data = $this->_make_data($data);
		
		$data = array_add($data, 'ip', t('input')->ip_address());
		
		$data['created'] = now();
		
		return parent::create($data, $insert_id);
	}
	
	/**
	 * Update
	 */
	public function update_rule($where, array $data)
	{
		$data = $this->_make_data($data);
		
		return parent::update_rule($where, $data);
	}
	
	/**
	 * Xu ly data
	 * 
	 * @param array $data
	 * @return array
	 */
	protected function _make_data(array $data)
	{
		foreach (['input', 'output'] as $p)
		{
			if (isset($data[$p]) && (is_object($data[$p]) || is_array($data[$p])))
			{
				$data[$p] = json_encode($data[$p]);
			}
		}
		
		return $data;
	}
	
	/**
	 * Luu log
	 * 
	 * @param string $key
	 * @param mixed $input
	 * @param mixed $output
	 * @param array $data
	 * @return int
	 */
	public function log($key, $input, $output, array $data = [])
	{
		$data = array_merge($data, compact('key', 'input', 'output'));
		
		$id = 0;
		
		$this->create($data, $id);
		
		return $id;
	}
	
	/**
	 * Luu log input
	 * 
	 * @param string $key
	 * @param mixed $input
	 * @param array $data
	 * @return int
	 */
	public function log_input($key, $input, array $data = [])
	{
		return $this->log($key, $input, '', $data);
	}
	
	/**
	 * Luu log output
	 * 
	 * @param int $id
	 * @param mixed $output
	 * @return boolean
	 */
	public function log_output($id, $output)
	{
		return $this->update($id, compact('output'));
	}
	
	/**
	 * Don dep du lieu
	 */
	public function cleanup($timeout = 0)
	{
		$timeout = max(6*30*24*60*60, $timeout);
		
		$where = array();
		$where['created <'] = now() - $timeout;
		
		$this->del_rule($where);
	}
	
}