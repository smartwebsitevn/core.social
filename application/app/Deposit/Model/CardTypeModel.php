<?php namespace App\Deposit\Model;

class CardTypeModel extends \Core\Base\Model
{
	protected $table = 'card_type';

	protected $casts = [
		'profit'         => 'float',
		'fee'            => 'float',
		'fee_sub'        => 'float',
		'fee_user_group' => 'array',
	];

	protected $defaults = [
		'fee_user_group' => [],
	];

	protected $formats = [
		'profit'  => 'amount',
		'fee'     => 'amount',
		'fee_sub' => 'amount',
		'created' => 'date',
	];

}