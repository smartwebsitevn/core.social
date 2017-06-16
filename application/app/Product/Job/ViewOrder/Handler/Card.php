<?php namespace App\Product\Job\ViewOrder\Handler;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Job\ViewOrder\Handler;
use App\Product\Model\OrderCardsModel as OrderCardsModel;
use TF\Support\Collection;

class Card extends Handler
{
	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		$cards = $this->listCards();

		return compact('cards');
	}

	/**
	 * Lay danh sach cards
	 *
	 * @return Collection
	 */
	protected function listCards()
	{
		return (get_area() == 'admin')
			? ProductFactory::order()->getCards($this->getOrder())
			: ProductFactory::order()->getCardsForUser($this->getOrder());
	}
}