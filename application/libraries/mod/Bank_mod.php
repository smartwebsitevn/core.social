<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		foreach (array('fee_constant', 'fee_percent', 'fee_min', 'fee_max', 'amount_min', 'amount_max') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = (float) $row->$p;
			}
		}
		
		if (isset($row->image_name))
		{
			t('load')->helper('file');
			$row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
		}
		
		if (isset($row->status))
		{
			$row->_status = ($row->status) ? 'on' : 'off';
		}
		
		return $row;
	}
	
	/**
	 * Lay fee qua bank
	 * 
	 * @param int $bank_id
	 * @param float $amount
	 * @return float
	 */
	public function get_fee($bank_id, $amount)
	{
	    $amount = (float) $amount;
	    
        if ($amount <= 0) return 0;
        
        $bank = $this->get_info($bank_id);
        
        if ( ! $bank) return 0;
        
        $fee = $bank->fee_constant + ($amount * $bank->fee_percent * 0.01);
        
        $fee = max($bank->fee_min, $fee);
        
        if ($bank->fee_max)
        {
            $fee = min($fee, $bank->fee_max);
        }
        
        return $fee;
	}
}