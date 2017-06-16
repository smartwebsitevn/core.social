<?php namespace App\StockCard\Service;

use App\StockCard\Model\StockCardModel;

class AuthCardService
{
	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Key luu tru
	 */
	const STORAGE_KEY = 'stockcard_auth';


	/**
	 * Kiem tra auth
	 *
	 * @param StockCardModel $card
	 * @return bool
	 */
	public function check(StockCardModel $card)
	{
		return $card->id === $this->getCardSession();
	}

	/**
	 * Gan card auth
	 *
	 * @param StockCardModel $card
	 */
	public function set(StockCardModel $card)
	{
		$this->setCardSession($card->id);
	}

	/**
	 * Lay thong tin card
	 *
	 * @return StockCardModel|null
	 */
	public function card()
	{
		if (
			isset($this->data['card'])
			&& $this->data['card']->id != $this->getCardSession()
		)
		{
			unset($this->data['card']);
		}

		if ( ! array_key_exists('card', $this->data))
		{
			$card_id = $this->getCardSession();

			$this->data['card'] = StockCardModel::find($card_id);
		}

		return $this->data['card'];
	}

	/**
	 * Lay card_id trong session
	 *
	 * @return int
	 */
	protected function getCardSession()
	{
		return t('session')->userdata(static::STORAGE_KEY);
	}

	/**
	 * Gan card_id vao session
	 *
	 * @param int $card_id
	 */
	protected function setCardSession($card_id)
	{
		t('session')->set_userdata(static::STORAGE_KEY, $card_id);
	}

}