<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_mod extends MY_Mod
{
	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row = parent::add_info($row);
		$row->cost = floatval($row->cost);
		$row->cost = (!$row->cost) ? '' : $row->cost;
		$row->_cost    = currency_format_amount($row->cost);
		$row->cost_new = $row->cost;

		if($row->discount > 0)
		{
			$discount = $row->discount;
			$row->discount_percent = $discount;

			if($row->discount_type ==  mod("product")->config('discount_type_percent'))
			{
				$cost_new  = $row->cost - (($row->cost*$discount)/100);
			}else{
				//neu chiet khau theo so tien
				$cost_new = $row->cost - $discount;
				$row->discount_percent = $discount*100/$row->cost;
			}
			$row->discount_percent = round($row->discount_percent,0);

			$row->cost_new = $cost_new;
		}

		$row->_cost_new = currency_format_amount($row->cost_new);
		return $row;
	}


}