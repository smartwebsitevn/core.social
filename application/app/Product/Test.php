<?php namespace App\Product;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Model\ProductModel as ProductModel;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\Library\OrderStatus;

class Test
{
	public static function index()
	{
		$product = ProductModel::find(121);

		$v = ProductFactory::order()->makeOrderAmount([
			'product' => ProductModel::find(121),
			'quantity' => 20000,
		]);

		pr($v, 0);

//		return static::shopping();
//		return static::order_topup_game();
	}

	public static function shopping()
	{
		$cart = ProductFactory::shopping()->cart();

		pr($cart, 0);
	}

	public static function order_card()
	{
		$invoice = \App\Invoice\InvoiceFactory::invoice()->create(new CreateInvoiceOptions([
			'amount' => 150000,
			'amounts_currency' => [
				8 => 7.5,
			],
		]));

		$invoice_id = $invoice->id;

		foreach ([61, 60] as $product_id)
		{
			$product = \App\Product\Model\ProductModel::find($product_id);

			$invoice_order = \App\Invoice\Model\InvoiceOrderModel::create([
				'invoice_id'  => $invoice_id,
				'service_key' => 'ProductOrderCard',
				'amount'      => $product->price,
				'title'       => 'Mua mã thẻ',
				'desc'        => "1 {$product->name}",
			]);

			$order = \App\Product\Model\OrderModel::create([
				'invoice_id' => $invoice_id,
				'invoice_order_id' => $invoice_order->id,
				'type' => $product->type,
				'type_type' => $product->type_type,
				'type_value' => $product->type_value,
				'product_id' => $product->id,
				'price' => $product->price,
				'quantity' => 1,
				'amount' => $product->price*1,
				'status' => OrderStatus::PENDING,
				'user_id' => 1,
			]);
		}

		pr($invoice);
	}

	public static function order_topup_mobile()
	{
		static::createOrderOfProductIds([4, 5]);
	}

	public static function order_topup_game()
	{
		static::createOrderOfProductIds([125, 126]);
	}

	protected static function createOrderOfProductIds(array $product_ids)
	{
		$invoice = \App\Invoice\InvoiceFactory::invoice()->create(new CreateInvoiceOptions([
			'amount' => 150000,
			'amounts_currency' => [
				8 => 7.5,
			],
		]));

		$invoice_id = $invoice->id;

		foreach ($product_ids as $product_id)
		{
			$product = \App\Product\Model\ProductModel::find($product_id);

			static::createOrder($invoice_id, $product);
		}

		pr($invoice);
	}

	protected static function createOrder($invoice_id, $product)
	{
		$invoice_order = \App\Invoice\Model\InvoiceOrderModel::create([
			'invoice_id'  => $invoice_id,
			'service_key' => 'ProductOrder'.studly_case($product->type),
			'amount'      => $product->price,
			'profit'      => $product->price * 0.15,
			'desc'        => "{$product->name}",
			'order_status' => OrderStatus::PENDING,
		]);

		$order = \App\Product\Model\OrderModel::create([
			'invoice_id' => $invoice_id,
			'invoice_order_id' => $invoice_order->id,
			'type' => $product->type,
			'type_type' => $product->type_type,
			'type_value' => $product->type_value,
			'product_id' => $product->id,
			'price' => $product->price,
			'quantity' => 1,
			'amount' => $product->price*1,
			'status' => OrderStatus::PENDING,
			'user_id' => 1,
		]);
	}
}