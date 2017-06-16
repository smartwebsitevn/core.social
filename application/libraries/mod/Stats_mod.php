<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats_mod extends MY_Mod
{
	/**
	 * Lay date thong ke mac dinh
	 *
	 * @return array
	 */
	public function get_date_default()
	{
		$time = get_time_info();
		
		$start = add_time(now(), array('d' => -$time['d']+1));
		$start = get_date($start);
	
		$end = get_date();
	
		return compact('start', 'end');
	}
	
	/**
	 * Lay report cua table
	 * 
	 * @param string $table
	 * @param string $field
	 * @param int $start
	 * @param int $end
	 * @return float
	 */
	public function get_report_table($table, $field, $start, $end, $where = null)
	{
		$table_key = model($table)->key;
		
		$query = $this->db
					->select_sum("{$table}.{$field}", 'sum')
					->from($table)
					->where('tran.created >=', $start)
					->where('tran.created <=', $end)
					->where('tran.status', mod('tran')->status('completed'));
		
		if ($table != 'tran')
		{
			$query->join('tran', "{$table}.{$table_key} = tran.id");
		}
		
		if ( ! is_null($where))
		{
			$query->where($where);
		}
		
		$result = $query->get()->row();
		
		return (float) data_get($result, 'sum');
	}
	
}