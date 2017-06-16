<?php namespace App\StockCard\Service;

use App\Product\Model\ProductModel;
use App\StockCard\Model\StockCardModel;
use TF\Support\Collection;

class StockCardService
{
	/**
	 * Gan trang thai card da duoc ban
	 *
	 * @param StockCardModel $card
	 * @param int            $invoice_order_id
	 */
	public function sold(StockCardModel $card, $invoice_order_id = 0)
	{
		$card->update([
			'sold' => true,
			'sold_at' => now(),
			'invoice_order_id' => $invoice_order_id,
		]);
	}

	/**
	 * Gan trang thai card chua duoc ban
	 *
	 * @param StockCardModel $card
	 */
	public function unsold(StockCardModel $card)
	{
		$card->update([
			'sold' => false,
			'sold_at' => 0,
			'invoice_order_id' => 0,
		]);
	}

	/**
	 * Lay cards chua ban cua san pham
	 *
	 * @param ProductModel $product
	 * @param int             $quantity
	 * @return Collection
	 */
	public function getCards(ProductModel $product, $quantity)
	{
		// Lay cards
		$cards = model('stock_card')->get_list([
			'where' => [
				'product_id' => $product->id,
				'sold' => 0,
			],
			'order' => ['created', 'asc'],
			'limit' => [0, $quantity],
		]);

		$cards = StockCardModel::makeCollection($cards);

		// Neu so luong cards khong du
		if ($cards->count() != $quantity)
		{
		    return collect([]);
		}

		return $cards;
	}

	/**
	 * Xoa
	 *
	 * @param StockCardModel $card
	 */
	public function delete(StockCardModel $card)
	{
		$card->delete();
	}

	/**
	 * Lay so luong ton kho cua danh sach san pham
	 *
	 * @param array $products
	 * @return array [product_id => available, ...]
	 */
	public function getAvailables(array $products)
	{
		$product_ids = array_pluck($products, 'id');

		$list = t('db')->select('product_id, COUNT(id) as available')
					   ->where_in('product_id', $product_ids)
					   ->where('sold', 0)
					   ->group_by('product_id')
					   ->get('stock_card')->result();

		$list = array_pluck($list, 'available', 'product_id');

		$result = [];

		foreach ($products as $product)
		{
			$result[$product->id] = array_get($list, $product->id, 0);
		}

		return $result;
	}

	/**
	 * Lay tong so tien ton trong kho
	 *
	 * @return float
	 */
	public function getBalance()
	{
		$list = t('db')->select('SUM(product.price_original) as balance')
					   ->where('stock_card.sold', 0)
					   ->join('product', 'stock_card.product_id = product.id')
					   ->get('stock_card')->result();

		return ($row = head($list)) ? (float) $row->balance : 0;
	}

}