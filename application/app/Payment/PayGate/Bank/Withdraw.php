<?php namespace App\Payment\PayGate\Bank;

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
        if(!isset($input['bank']))
        {
            $error = lang('notice_value_invalid', lang('bank'));
            return false;
        }
        
        //lay id user_bank
        $user_bank_id = $input['bank'];
        $user_bank = model('user_bank')->get_info($user_bank_id);
        if(!$user_bank)
        {
            $error = lang('notice_value_invalid', lang('bank'));
            return false;
        }
        
        if($user_bank->status != mod('order')->status('completed'))
        {
            $error = lang('notice_value_invalid', lang('bank'));
            return false;
        }
 
        return true;
    }
    
    public function formConfirm(array $data)
    {
        if(!isset($data['bank']))
        {
            return $data;
        }
        
        //lay id user_bank
        $user_bank_id = $data['bank'];
        $user_bank = model('user_bank')->get_info($user_bank_id);
        if(!$user_bank)
        {
             return $data;
        }
        $bank = model('bank')->get_info($user_bank->bank_id);
        if(!$bank)
        {
             return $data;
        }
        
        t('lang')->load('site/withdraw');
        
        return [
            lang('bank')         => $bank->name,
            lang('acc_id')       => $user_bank->bank_account,
            lang('acc_name')     => $user_bank->bank_account_name,
            lang('branch')       => $user_bank->bank_branch,
        ];
    }
    
    /**
     * Xu ly gia tri
     *
     * @param array $data
     * @return array
     */
    public function value(array $data)
    {
        if(!isset($data['bank']))
        {
            return $data;
        }
        
        //lay id user_bank
        $user_bank_id = $data['bank'];
        $user_bank = model('user_bank')->get_info($user_bank_id);
        if(!$user_bank)
        {
            return $data;
        }
        $bank = model('bank')->get_info($user_bank->bank_id);
        if(!$bank)
        {
            return $data;
        }
        
        $data['user_bank_id']   = $user_bank->id;
        $data['bank']        = $bank->name;
        $data['acc_id']      = $user_bank->bank_account;
        $data['acc_name']    = $user_bank->bank_account_name;
        $data['branch']      = $user_bank->bank_branch;
 
        return $data;
    }
    
    /**
     * Xu ly view thong tin
     *
     * @param array $data
     * @return array|string
     */
    public function view(array $data)
    {
        t('lang')->load('site/withdraw');
        $data_show = array();
        foreach ($data as $key => $val)
        {
            if($key == 'user_bank_id') continue;
            $data_show[lang($key)] = $val;
        }
        return $data_show;
    }
    
	/**
	 * Xu ly config
	 *
	 * @param array $config
	 * @return array
	 */
	protected function handleConfig(array $config)
	{
	    t('lang')->load('site/withdraw');
	    $user = user_get_account_info();
	    $filter = array();
	    $filter['user'] = $user->id;
	    $filter['status'] = mod('order')->status('completed');
	    $banks = model('user_bank')->filter_get_list($filter);
	    $bank_blank = array();
	    $bank_blank[0] = new \stdClass();
	    $bank_blank[0]->id   = '';
	    $bank_blank[0]->name = lang('select_bank');
	    $banks = array_merge($bank_blank, $banks);
	    
		$config['bank']['values'] = array_pluck($banks, 'name', 'id');

		return $config;
	}
}

