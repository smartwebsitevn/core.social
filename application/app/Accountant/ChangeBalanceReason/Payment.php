<?php namespace App\Accountant\ChangeBalanceReason;

use App\Accountant\Library\Reason;
use App\Transaction\Model\TranModel as TranModel;

class Payment extends Reason
{
	/**
	 * Tao reason
	 *
	 * @param TranModel $tran
	 * @return Reason
	 */
	public static function make(TranModel $tran)
	{
		return new static([
			'tran' => $tran->onlyAttributes([
				'id', 'invoice_id', 'secret_key'
			]),
		]);
	}

	/**
	 * Lay mo ta
	 *
	 * @return string
	 */
	public function desc()
	{
		$tran_id = $this->getOption('tran.id');

		return 'Thanh toán cho giao dịch #'.$tran_id;
	}
}