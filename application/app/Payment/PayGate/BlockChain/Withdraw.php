<?php namespace App\Payment\PayGate\BlockChain;

use App\Payment\Library\PayGateServiceWithdraw;

class Withdraw extends PayGateServiceWithdraw
{

    /**
     * Validate du lieu
     *
     * @param array  $input
     * @param string $error
     * @return bool
     */
    public function validate(array $input, &$error = null)
    {
      
        if(!isset($input['btc_address']))
        {
            $error = lang('notice_value_invalid', lang('btc_address'));
            return false;
        }
        
        if(!lib('btc_address_valid')->valid($input['btc_address']))
        {
            $error = lang('notice_value_invalid', lang('btc_address'));
            return false;
        }
        return true;
    }
}

