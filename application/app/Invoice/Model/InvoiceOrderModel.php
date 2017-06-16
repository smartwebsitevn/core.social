<?php namespace App\Invoice\Model;

use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\InvoiceService\Order as ServiceOrder;
use App\User\Model\UserModel as UserModel;
use App\User\Model\UserRelationTrait;
use Core\Model\TokenMakerTrait;

class InvoiceOrderModel extends \Core\Base\Model
{
	use InvoiceRelationTrait;
	use UserRelationTrait;
	use TokenMakerTrait;

	protected $table = 'invoice_order';

	protected $microtime = true;

	protected $casts = [
		'fee_tax'       => 'float',
		'amount'        => 'float',
		'profit'        => 'float',
		'amount_par'    => 'float',
		'order_options' => 'array',
	];

	protected $defaults = [
		'order_options' => [],
	];

	protected $formats = [
		'fee_tax'    => 'amount',
		'amount'     => 'amount',
		'profit'     => 'amount',
		'amount_par' => 'amount',
		'created'    => 'date',
	];


	/**
	 * Tao doi tuong moi va gan $attributes
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function newWithAttributes(array $attributes)
	{
		$relations = static::pullRelationsFromAttributes($attributes, [
			'invoice' => 'App\Invoice\Model\InvoiceModel',
			'user'    => 'App\User\Model\UserModel',
		]);

		return parent::newWithAttributes($attributes)->fill($relations);
	}

	/**
	 * Gan thong tin user
	 *
	 * @param UserModel|null $user
	 */
	protected function setUserAttribute($user)
	{
		$this->additional['user'] = $user;
	}

	/**
	 * Gan service_order
	 *
	 * @param ServiceOrder $service_order
	 */
	protected function setServiceOrderAttribute($service_order)
	{
		$this->additional['service_order'] = $service_order;
	}

	/**
	 * Lay service_order
	 *
	 * @return ServiceOrder|null
	 */
	protected function getServiceOrderAttribute()
	{
		if ( ! array_key_exists('service_order', $this->additional))
		{
			$this->additional['service_order'] = $this->invoiceServiceInstance()->findOrder($this->getKey());
		}

		return $this->additional['service_order'];
	}

	/**
	 * Lay service_name
	 *
	 * @return string
	 */
	protected function getServiceNameAttribute()
	{
		$service = $this->invoiceServiceInstance();

		return array_get($service->info(), 'name', $service->key());
	}

	/**
	 * Lay service_type
	 *
	 * @return string
	 */
	protected function getServiceTypeAttribute()
	{
		return $this->invoiceServiceInstance()->type();
	}

	/**
	 * Lay order_status_name
	 *
	 * @return string
	 */
	protected function getOrderStatusNameAttribute()
	{
		$status = $this->getAttribute('order_status');

		return $this->invoiceServiceInstance()->getOrderStatusName($status);
	}

	/**
	 * Lay order_desc
	 *
	 * @return mixed
	 */
	protected function getOrderDescAttribute()
	{
		$order_desc = $this->invoiceServiceInstance()->getOrderDesc($this);

		return $order_desc ?: $this->getAttribute('desc');
	}

	/**
	 * lay user_country_code
	 *
	 * @return string
	 */
	protected function getUserCountryCodeAttribute()
	{
		$ip = (string) $this->getAttribute('user_ip');

		return lib('geoip')->country_code($ip) ?: 'VN';
	}

	/**
	 * Lay doi tuong InvoiceService
	 *
	 * @return InvoiceService
	 */
	public function invoiceServiceInstance()
	{
		$service_key = $this->getAttribute('service_key');

		return InvoiceFactory::invoiceService($service_key);
	}

	/**
	 * Tao url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function url($action, array $opt = [])
	{
		switch ($action)
		{
			case 'view':
			{
				return parent::url($action, $opt).'?'.http_build_query([
					'token' => $this->token($action),
				]);
			}
		}

		return parent::url($action, $opt);
	}

}