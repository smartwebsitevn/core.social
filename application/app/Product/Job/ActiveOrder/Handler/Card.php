<?php namespace App\Product\Job\ActiveOrder\Handler;

use App\Product\Job\ActiveOrder\Command;
use App\Product\Job\ActiveOrder\Handler;
use App\Product\Library\Provider\TranResponse;
use App\Product\Model\OrderCardsModel as OrderCardsModel;
use App\Product\ProductFactory as ProductFactory;

class Card extends Handler
{
	/**
	 * Thuc hien request den provider
	 *
	 * @return TranResponse
	 */
	public function request()
	{
		return $this->dispatch(Command::BUY_CARD, [
			'key_connection' => $this->getProduct()->provider_key_connection,
			'quantity'       => $this->getOrder()->quantity,
			'order'          => $this->getOrder(),
		]);
	}

	/**
	 * Xu ly response success
	 *
	 * @param TranResponse $response
	 */
	public function success(TranResponse $response)
	{
		$this->saveCards($response->cards);
	}

	/**
	 * Xu ly du lieu khi order da hoan thanh truoc do
	 */
	public function completed()
	{
		ProductFactory::order()->retakeCards($this->getOrder());
	}

	/**
	 * Luu ma the
	 *
	 * @param array $cards
	 */
	protected function saveCards(array $cards)
	{
		foreach ($cards as $card)
		{
			OrderCardsModel::create([
				'product_order_id' => $this->getOrder()->id,
				'code'   => $card->code,
				'serial' => $card->serial,
				'expire' => $card->expire,
			]);
		}
	}

}