<?php namespace App\Transaction\Model;

class TranInfoModel extends \Core\Base\Model
{
	protected $table = 'tran_info';

	protected $primary_key = 'tran_id';

	protected $timestamps = false;

	protected $casts = [
		'payment_tran' => 'array',
	];

	protected $defaults = [
		'payment_tran' => [],
	];

}