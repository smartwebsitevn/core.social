<?php namespace App\Product\Model;

use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Product\Library\ProductType;
use App\Product\Model\ProductModel as ProductModel;

class OrderModel extends \Core\Base\Model
{
	protected $table = 'product_order';

	protected $casts = [
		'price'          => 'float',
		'price_original' => 'float',
		'price_par'      => 'float',
		'quantity'       => 'float',
		'amount'         => 'float',
		'profit'         => 'float',
		'amount_par'     => 'float',
	];

	protected $formats = [
		'price'          => 'amount',
		'price_original' => 'amount',
		'price_par'      => 'amount',
		'amount'         => 'amount',
		'profit'         => 'amount',
		'amount_par'     => 'amount',
		'created'        => 'date',
	];


	/**
	 * Gan product
	 *
	 * @param ProductModel $product
	 */
	protected function setProductAttribute(ProductModel $product)
	{
		$this->additional['product'] = $product;
	}

	/**
	 * Lay thong tin san pham
	 *
	 * @return ProductModel|null
	 */
	protected function getProductAttribute()
	{
		if ( ! array_key_exists('product', $this->additional))
		{
			$product_id = $this->getAttribute('product_id');

			$this->additional['product'] = ProductModel::find($product_id);
		}

		return $this->additional['product'];
	}

	/**
	 * Gan invoice
	 *
	 * @param InvoiceModel $invoice
	 */
	protected function setInvoiceAttribute(InvoiceModel $invoice)
	{
		$this->additional['invoice'] = $invoice;
	}

	/**
	 * Lay invoice
	 *
	 * @return InvoiceModel|null
	 */
	protected function getInvoiceAttribute()
	{
		if ( ! array_key_exists('invoice', $this->additional))
		{
			$invoice_id = $this->getAttribute('invoice_id');

			$this->additional['invoice'] = InvoiceModel::find($invoice_id);
		}

		return $this->additional['invoice'];
	}

	/**
	 * Gan invoice_order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	protected function setInvoiceOrderAttribute(InvoiceOrderModel $invoice_order)
	{
		$this->additional['invoice_order'] = $invoice_order;
	}

	/**
	 * Lay invoice_order
	 *
	 * @return InvoiceOrderModel|null
	 */
	protected function getInvoiceOrderAttribute()
	{
		if ( ! array_key_exists('invoice_order', $this->additional))
		{
			$invoice_order_id = $this->getAttribute('invoice_order_id');

			$this->additional['invoice_order'] = InvoiceOrderModel::find($invoice_order_id);
		}

		return $this->additional['invoice_order'];
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		switch ($action)
		{
			case 'active':
			case 'complete':
			case 'cancel':
			{
				$status = $this->getAttribute('status');

				return in_array($status, [OrderStatus::PENDING, OrderStatus::PROCESSING]);
			}

			case 'retake_cards':
			{
				$type = $this->getAttribute('type');

				$last_request_id = $this->getAttribute('last_provider_request_id');

				return ($type == ProductType::CARD && $last_request_id);
			}
		}

		return parent::can($action);
	}

	/**
	 * Cap nhat trang thai
	 *
	 * @param string $status
	 */
	public function updateStatus($status)
	{
		$this->update(compact('status'));

		$this->getAttribute('invoice_order')->update(['order_status' => $status]);
	}

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