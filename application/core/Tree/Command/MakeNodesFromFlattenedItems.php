<?php namespace Core\Tree\Command;

use Core\Tree\FlattenedItem;

class MakeNodesFromFlattenedItems
{
	/**
	 * Danh sach items
	 *
	 * @var array
	 */
	protected $items = [];



	/**
	 * Test
	 */
	public static function _t()
	{
		$items = [
			(new FlattenedItem(1)),
			(new FlattenedItem(2)),
			(new FlattenedItem(11, 1)),
			(new FlattenedItem(12, 1)),
			(new FlattenedItem(31, 3)),
		];

		$me = new static($items);

		$v = $me->handle();

		pr($v);
		pr($me);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $items
	 */
	public function __construct(array $items)
	{
		$this->items = $items;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		$this->makeNestedNodes();

		return $this->listNodes();
	}

	/**
	 * Thuc hien tao da cap cho cac node cua items
	 */
	protected function makeNestedNodes()
	{
		foreach ($this->items as $item)
		{
			$parent = $this->findItem($item->getParentId());

			if ( ! $parent) continue;

			$parent->getNode()->addChild($item->getNode());
		}
	}

	/**
	 * Tim item
	 *
	 * @param string $item_id
	 * @return FlattenedItem|null
	 */
	protected function findItem($item_id)
	{
		return array_first($this->items, function($i, FlattenedItem $item) use ($item_id)
		{
			return $item->getId() == $item_id;
		});
	}

	/**
	 * Lay danh sach nodes cua cac items
	 *
	 * @return array
	 */
	protected function listNodes()
	{
		return array_map(function(FlattenedItem $item)
		{
			return $item->getNode();
		}, $this->items);
	}

}