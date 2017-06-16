<?php namespace App\Withdraw\Service;

use Core\Support\Number;
use App\Currency\Model\CurrencyModel;
use App\Invoice\Library\OrderStatus;
use App\LogActivity\LogActivityFactory as LogActivityFactory;
use App\LogActivity\Library\ActivityLogger as ActivityLogger;
use App\LogActivity\Library\ActivityOwner as ActivityOwner;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Purse\Model\PurseModel as PurseModel;
use App\Withdraw\Command\CreateWithdrawCommand as CreateWithdrawCommand;
use App\Withdraw\Model\WithdrawModel as WithdrawModel;

class WithdrawService
{
	public static function _t()
	{
		$me = new static;

		$currency = CurrencyModel::find(1);
		$purse = PurseModel::find(2);
		$payment = PaymentModel::find(1);

//		$v = $me->settingForCurrency($currency);
//		$v = $me->getFee($purse, 100);
		$v = $me->getAmounts($purse, 10, $payment);

		pr($v, 0);
	}

	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting('withdraw');

		return array_get($setting, $key, $default);
	}

	/**
	 * Lay setting tuong ung voi currency
	 *
	 * @param CurrencyModel $currency
	 * @param string        $key
	 * @param mixed         $default
	 * @return mixed
	 */
	public function settingForCurrency(CurrencyModel $currency, $key = null, $default = null)
	{
		$params = ['amount_min', 'amount_max', 'fee_constant', 'fee_min', 'fee_max'];

		$setting = $this->setting();

		foreach ($setting as $param => &$value)
		{
			if ( ! in_array($param, $params)) continue;

			$value = currency_convert_amount($value, $currency->id);
		}

		return array_get($setting, $key, $default);
	}


	/**
	 * Lay setting tuong ung voi currency
	 *
	 * @param CurrencyModel $currency
	 * @param string        $key
	 * @param mixed         $default
	 * @return mixed
	 */
	public function settingBankForCurrency(CurrencyModel $currency, $bank_id = 0)
	{
	    $params  = ['fee_constant', 'fee_min', 'fee_max'];
	    $setting = array();
	    
	    if($bank_id)
	    {
	        $user_bank = model('user_bank')->get_info($bank_id, 'bank_id');
	        if($user_bank)
	        {
	            $bank = model('bank')->get_info($user_bank->bank_id);
	            foreach ($params as $param)
	            {
	                $setting[$param] = isset($bank->{$param}) ? $bank->{$param} : 0;
	            }
	             
	            foreach ($setting as $param => &$value)
	            {
	                if ( ! in_array($param, $params)) continue;
	                 
	                $value = currency_convert_amount($value, $currency->id);
	            }  
	            $setting['fee_percent'] = isset($bank->fee_percent) ? $bank->fee_percent : 0;
	        }  
	    }
	    
	    return $setting;
	}
	
	
	/**
	 * Lay fee setting
	 *
	 * @param CurrencyModel $currency
	 * @param string        $key
	 * @param mixed         $default
	 * @return mixed
	 */
	public function getFeeSetting(CurrencyModel $currency, $key = null, $default = null)
	{
		$setting = $this->settingForCurrency($currency);

		$options = [];

		foreach (['constant', 'percent', 'min', 'max'] as $param)
		{
			$options[$param] = array_get($setting, 'fee_'.$param);
		}

		return array_get($options, $key, $default);
	}


	/**
	 * Lay fee setting
	 *
	 * @param CurrencyModel $currency
	 * @param string        $key
	 * @param mixed         $default
	 * @return mixed
	 */
	public function getBankFeeSetting(CurrencyModel $currency, $bank_id = 0)
	{
	    $setting = $this->settingBankForCurrency($currency, $bank_id);
	
	    $options = [];
	
	    foreach (['constant', 'percent', 'min', 'max'] as $param)
	    {
	        $options[$param] = array_get($setting, 'fee_'.$param, 0);
	    }
	
	    return $options;
	}
	
	/**
	 * Tinh phi chung
	 *
	 * @param PurseModel   $purse
	 * @param float        $amount	So tien can rut (tinh theo tien te cua vi)
	 * @return float
	 */
	public function getFee(PurseModel $purse, $amount, $bank_id)
	{
	    
		$currency = $purse->currency;

		$setting = $this->getFeeSetting($currency);
        //phi chung
		$fee = Number::getFee($amount, $setting);
		
		
		//phi theo ngan hang
		if(!$bank_id)
		{
		    $bank_id = $this->get_bank_id($bank_id);
		}
		$fee_bank = $this->getFeebank($purse, $amount, $bank_id);
		$fee = $fee + $fee_bank;

		return $fee;
	}


	/**
	 * Tinh phi ngan hàng
	 *
	 * @param PurseModel   $purse
	 * @param float        $amount	So tien can rut (tinh theo tien te cua vi)
	 * @return float
	 */
	public function getFeeBank(PurseModel $purse, $amount, $bank_id = 0)
	{
	    $currency = $purse->currency;
	
	    $setting = $this->getBankFeeSetting($currency, $bank_id);
	    
	    return Number::getFee($amount, $setting);
	}
	
	/**
	 * Xu ly withdraw amounts
	 *
	 * @param PurseModel   $purse
	 * @param              $amount
	 * @param PaymentModel $payment
	 * @return array
	 */
	public function getAmounts(PurseModel $purse, $amount, PaymentModel $payment, $receiver)
	{
	    $bank_id = $this->get_bank_id($receiver);
	    
		$fee = $this->getFee($purse, $amount, $bank_id);
		
		$amount_remain = $amount - $fee;

		$receive_amount = currency_convert_amount_other(
			$amount_remain, $purse->currency_id, $payment->currency_id
		);
		
		return compact('amount', 'fee', 'receive_amount');
	}
	
	/**
	 * Lay id cua bank
	 */
	private function get_bank_id($receiver)
	{
	    $bank_id = isset($receiver['bank']) ? intval($receiver['bank']) : 0;
	    if(!$bank_id && isset($receiver['user_bank_id']))
	    {
	        $bank_id = $receiver['user_bank_id'];
	    }
	    if(!$bank_id && is_array($receiver))
	    {
	        foreach ($receiver as $payment_id => $bank)
	        {
	            $bank_id = isset($bank['bank']) ? $bank['bank'] : 0;
	        }
	    }
	    return $bank_id;
	}
	
	/**
	 * Tao withdraw
	 *
	 * @param CreateWithdrawCommand $command
	 * @return WithdrawModel
	 */
	public function create(CreateWithdrawCommand $command)
	{
		$data = $command->except(['invoice_order', 'purse']);
        
		return WithdrawModel::create(array_merge([
			'invoice_id'       => $command->invoice_order->invoice_id,
			'invoice_order_id' => $command->invoice_order->id,
			'purse_id'         => $command->purse->id,
			'user_id'          => $command->purse->user_id,
			'currency_id'      => $command->purse->currency_id,
		], $data));
	}

	/**
	 * Hoan thanh withdraw
	 *
	 * @param WithdrawModel $withdraw
	 * @param array      $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function complete(WithdrawModel $withdraw, array $options = [])
	{
		$withdraw->updateStatus(OrderStatus::COMPLETED);

		$this->logActivity('completed', $withdraw, array_get($options, 'owner'));

		$this->email($withdraw);
	}

	/**
	 * Huy bo withdraw
	 *
	 * @param WithdrawModel $withdraw
	 * @param array      $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function cancel(WithdrawModel $withdraw, array $options = [])
	{
		$withdraw->updateStatus(OrderStatus::CANCELED);

		$this->logActivity('canceled', $withdraw, array_get($options, 'owner'));

		$this->email($withdraw);
	}

	/**
	 * Gui email thong bao
	 *
	 * @param WithdrawModel $withdraw
	 * @param string        $email_key
	 * @return bool
	 */
	public function email(WithdrawModel $withdraw, $email_key = 'withdraw')
	{
		$to = $withdraw->user->email;

		return mod('email')->send($email_key, $to, [
			'order_id' => $withdraw->invoice_order_id,
		]);
	}

	/**
	 * Lay doi tuong ActivityLogger
	 *
	 * @return ActivityLogger
	 */
	public function activityLogger()
	{
		return LogActivityFactory::logger('Withdraw');
	}

	/**
	 * Log activity
	 *
	 * @param string        $action
	 * @param WithdrawModel $withdraw
	 * @param ActivityOwner $owner
	 * @param array         $context
	 * @return LogActivityModel
	 */
	public function logActivity($action, WithdrawModel $withdraw, ActivityOwner $owner = null, array $context = [])
	{
		$context['withdraw'] = $withdraw->getAttributes();

		return $this->activityLogger()->log($action, $withdraw->id, $owner, $context);
	}
}