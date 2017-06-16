<?php namespace App\Product\Provider\StockCard;

use App\Product\Library\Provider\BuyCardRequest;
use App\Product\Library\Provider\BuyCardResponse;
use App\Product\Library\Provider\FindTranRequest;
use App\Product\Library\Provider\FindTranResponse;
use App\Product\Library\Provider\GetBalanceResponse;
use App\Product\Library\Provider\GetCardRequest;
use App\Product\Library\Provider\GetCardResponse;
use App\Product\Library\Provider\TestResponse;
use App\Product\Library\Provider\TopupGameRequest;
use App\Product\Library\Provider\TopupGameResponse;
use App\Product\Library\Provider\TopupMobilePostRequest;
use App\Product\Library\Provider\TopupMobilePostResponse;
use App\Product\Library\Provider\TopupMobileRequest;
use App\Product\Library\Provider\TopupMobileResponse;
use App\Product\Library\Provider\Card;
use App\Product\Library\ProviderService;
use App\StockCard\StockCardFactory;

class Service extends ProviderService
{
	/**
	 * Lay cac dich vu ho tro
	 *
	 * @return array
	 */
	public function getServices()
	{
		return ['card'];
	}

	/**
	 * Nha cung cap co su dung kho the hay khong
	 *
	 * @return bool
	 */
	public function useStockCard()
	{
		return true;
	}

	/**
	 * Test ket noi
	 *
	 * @return TestResponse
	 */
	public function test()
	{
		return TestResponse::success();
	}

	/**
	 * Lay balance
	 *
	 * @return GetBalanceResponse
	 */
	public function getBalance()
	{
		$balance = StockCardFactory::card()->getBalance();

		return GetBalanceResponse::success($balance);
	}

	/**
	 * Mua ma the
	 *
	 * @param BuyCardRequest $request
	 * @return BuyCardResponse
	 */
	public function buyCard(BuyCardRequest $request)
	{
		$product = $request->order->product;

		$quantity = $request->quantity;

		$stock_cards = StockCardFactory::card()->getCards($product, $quantity);

		if ( ! $stock_cards->count())
		{
		    return BuyCardResponse::error('Stock card not enough');
		}

		$cards = [];

		foreach ($stock_cards as $card)
		{
			StockCardFactory::card()->sold($card, $request->order->invoice_order_id);

			$cards[] = new Card($card->onlyAttributes(['code', 'serial', 'expire']));
		}

		return BuyCardResponse::success($cards);
	}

	/**
	 * Lay so luong ton kho cua danh sach san pham
	 *
	 * @param array $products
	 * @return array array(product_id => available, ...)
	 */
	public function getAvailables(array $products)
	{
		return StockCardFactory::card()->getAvailables($products);
	}

}