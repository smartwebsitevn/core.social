<?php namespace Core\Tree\Command;

/**
 * Class xu ly chuyen danh sach don cap thanh da cap
 */
class MakeNested
{
	/**
	 * Danh sach items dang don cap
	 *
	 * @var array
	 */
	protected $items = [];

	/**
	 * Root id
	 *
	 * @var int
	 */
	protected $root_id = 0;

	/**
	 * Ten cua cac thuoc tinh
	 *
	 * 	$attr_names = [
	 *		'id' => 'id',
	 *		'parent_id' => 'parent_id',
	 *		'children' => 'children',
	 *	];
	 *
	 * @var array
	 */
	protected $attr_names = [];


	/**
	 * Test
	 */
	public static function _t()
	{
		$items = [
			[
				'id' => 1,
				'parent_id' => 0,
			],
			[
				'id' => 2,
				'parent_id' => 0,
			],
			[
				'id' => 11,
				'parent_id' => 1,
			],
			[
				'id' => 12,
				'parent_id' => 1,
			],
			[
				'id' => 31,
				'parent_id' => 3,
			],
		];

		$me = new static($items/*, 0, ['children' => 'subs']*/);

		$v = $me->handle();

		pr($v);
	}

	/**
	 * NestedMaker constructor.
	 *
	 * @param array $items
	 * @param int   $root_id
	 * @param array $attr_names
	 */
	public function __construct(array $items, $root_id = 0, array $attr_names = [])
	{
		$this->items = $items;

		$this->root_id = $root_id;

		$this->attr_names = $attr_names;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		$items = $this->items;

		$result = $this->getChildren($items, $this->root_id);

		return array_merge($result, $items);
	}

	/**
	 * Lay danh sach items con (de quy)
	 *
	 * @param array $items
	 * @param int   $parent_id
	 * @return array
	 */
	protected function getChildren(array &$items, $parent_id)
	{
		$children = [];

		foreach ($items as $i => $item)
		{
			if ($this->getItemAttr($item, 'parent_id') != $parent_id) continue;

			$children[] = $item;

			unset($items[$i]);
		}

		foreach ($children as &$child)
		{
			$child_id = $this->getItemAttr($child, 'id');

			$child_children = $this->getChildren($items, $child_id);

			$this->setItemAttr($child, $this->attrName('children'), $child_children);
		}

		return $children;
	}

	/**
	 * Lay thuoc tinh cua item
	 *
	 * @param mixed  $item
	 * @param string $key
	 * @return mixed
	 */
	protected function getItemAttr($item, $key)
	{
		$key = $this->attrName($key);

		return $this->accessType($item) == 'array'
			? $item[$key]
			: $item->$key;
	}

	/**
	 * Gan thuoc tinh cho item
	 *
	 * @param mixed  $item
	 * @param string $key
	 * @param mixed  $value
	 */
	protected function setItemAttr(&$item, $key, $value)
	{
		$key = $this->attrName($key);

		if ($this->accessType($item) == 'array')
		{
			$item[$key] = $value;
		}
		else
		{
			$item->$key = $value;
		}
	}

	/**
	 * Lay kieu du lieu cua bien
	 *
	 * @param mixed $target
	 * @return string
	 */
	protected function accessType($target)
	{
		if (is_array($target) || $target instanceof \ArrayAccess)
		{
		    return 'array';
		}

		return 'object';
	}

	/**
	 * Lay ten thuoc tinh
	 *
	 * @param string $key
	 * @return string
	 */
	protected function attrName($key)
	{
		return array_get($this->attr_names, $key, $key);
	}

}