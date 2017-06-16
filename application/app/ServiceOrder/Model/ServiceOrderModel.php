<?php namespace App\ServiceOrder\Model;

use App\Currency\Model\CurrencyRelationTrait;
use Core\Model\TokenMakerTrait;

class ServiceOrderModel extends \Core\Base\Model
{
	use CurrencyRelationTrait;
	use TokenMakerTrait;
	
	protected $table = 'service_order';

	protected $casts = [
		'amount' => 'float',
	];

	protected $formats = [
	    'amount'       => 'amount',
	    'created'      => 'date',
	    'expire_to'    => 'date',
	    'expire_from'  => 'date',
	];
	
	/**
	 * Lay thong tin tu $invoice_order_id
	 *
	 * @param int $invoice_order_id
	 * @return static|null
	 */
	public static function findByInvoiceOrder($invoice_order_id)
	{
	    return static::findWhere(compact('invoice_order_id'));
	}
	
}