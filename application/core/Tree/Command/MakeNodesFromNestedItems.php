<?php namespace Core\Tree\Command;

use Tree\Node\Node;
use Tree\Node\NodeInterface;

class MakeNodesFromNestedItems
{
	/**
	 * Danh sach items
	 *
	 * @var array
	 */
	protected $items = [];

	/**
	 * Ten thuoc tinh children
	 *
	 * @var string
	 */
	protected $children_name = 'children';


	/**
	 * Test
	 */
	public static function _t()
	{
		$items = [
			[
				'id' => 1,
				'children' => [
					[
						'id' => 11,
					],
					[
						'id' => 12,
					],
				],
			],
			[
				'id' => 2,
			],
		];

		$me = new static($items);

		$v = $me->handle();

		pr($v);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array  $items
	 * @param string $children_name
	 */
	public function __construct(array $items, $children_name = null)
	{
		$this->items = $items;

		if ($children_name)
		{
			$this->children_name = $children_name;
		}
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		return $this->makeNodes($this->items);
	}

	/**
	 * Tao danh sach Node
	 *
	 * @param array              $items
	 * @param NodeInterface|null $parent
	 * @return array
	 */
	protected function makeNodes(array $items, NodeInterface $parent = null)
	{
		$nodes = [];

		foreach ($items as $item)
		{
			$node = $this->makeNode($item);

			if ($parent)
			{
			    $parent->addChild($node);
			}

			$nodes[] = $node;

			if ($children = $this->getItemChildren($item))
			{
				$nodes = array_merge($nodes, $this->makeNodes($children, $node));
			}
		}

		return $nodes;
	}

	/**
	 * Tao Node cua item
	 *
	 * @param array $item
	 * @return Node
	 */
	protected function makeNode(array $item)
	{
		return new Node($this->makeNodeValue($item));
	}

	/**
	 * Tao Node value
	 *
	 * @param array $item
	 * @return array
	 */
	protected function makeNodeValue(array $item)
	{
		return array_except($item, $this->children_name);
	}

	/**
	 * Lay Item children
	 *
	 * @param array $item
	 * @return array|null
	 */
	protected function getItemChildren(array $item)
	{
		return array_get($item, $this->children_name);
	}

}