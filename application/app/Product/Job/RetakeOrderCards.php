<?php namespace App\Product\Job;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Model\OrderModel as OrderModel;
use App\Product\Model\OrderCardsModel as OrderCardsModel;
use App\Product\Model\LogProviderRequestModel as LogProviderRequestModel;
use App\Product\Library\Provider\Card;
use TF\Support\Collection;

class RetakeOrderCards extends \Core\Base\Job
{
	/**
	 * Doi tuong OrderModel
	 *
	 * @var OrderModel
	 */
	protected $order;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param OrderModel $order
	 */
	public function __construct(OrderModel $order)
	{
		$this->order = $order;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		$result = [];

		foreach ($this->getRequests() as $request)
		{
			$cards = $this->getCards($request);

			$this->saveCards($cards);

			$result = array_merge($result, $cards);
		}

		return $result;
	}

	/**
	 * Lay danh sach requests
	 *
	 * @return Collection
	 */
	protected function getRequests()
	{
		return LogProviderRequestModel::listOfInvoiceOrder($this->order->invoice_order_id);
	}

	/**
	 * Lay cards tu nha cung cap
	 *
	 * @param LogProviderRequestModel $log
	 * @return array
	 */
	protected function getCards(LogProviderRequestModel $log)
	{
		$response = ProductFactory::logProviderRequest()->getCard($log);

		return $response->status ? $response->cards : [];
	}

	/**
	 * Luu danh sach cards
	 *
	 * @param array $cards
	 */
	protected function saveCards(array $cards)
	{
		foreach ($cards as $card)
		{
			if ( ! $this->cardSaved($card))
			{
			    $this->saveCard($card);
			}
		}
	}

	/**
	 * Kiem tra card da duoc luu hay chua
	 *
	 * @param Card $card
	 * @return bool
	 */
	protected function cardSaved(Card $card)
	{
		return OrderCardsModel::findCard($card->code, $card->serial) ? true : false;
	}

	/**
	 * Luu card
	 *
	 * @param Card $card
	 * @return OrderCardsModel
	 */
	protected function saveCard(Card $card)
	{
		return OrderCardsModel::create([
			'product_order_id' => $this->order->id,
			'code'   => $card->code,
			'serial' => $card->serial,
			'expire' => $card->expire,
		]);
	}

}