<?php namespace App\Deposit\Model;

trait CardTypeRelationTrait
{
	/**
	 * Gan card_type
	 *
	 * @param CardTypeModel $card_type
	 */
	protected function setCardTypeAttribute(CardTypeModel $card_type)
	{
		$this->additional['card_type'] = $card_type;
	}

	/**
	 * Lay card_type
	 *
	 * @return CardTypeModel|null
	 */
	protected function getCardTypeAttribute()
	{
		if ( ! array_key_exists('card_type', $this->additional))
		{
			$card_type_id = $this->getAttribute('card_type_id');

			$this->additional['card_type'] = CardTypeModel::find($card_type_id);
		}

		return $this->additional['card_type'];
	}

}