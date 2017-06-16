<?php namespace Core\Tree\Command;

/**
 * Class xu ly chuyen danh sach da cap thanh don cap
 */
class FlattenNested
{
	/**
	 * Danh sach items dang da cap
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
	 * FlattenNested constructor.
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
		return $this->flatten($this->items, $this->root_id);
	}

	/**
	 * Tao danh sach items don cap tu items da cap
	 *
	 * @param array $items
	 * @param int   $parent_id
	 * @return array
	 */
	protected function flatten(array $items, $parent_id)
	{
		$result = [];

		foreach ($items as $item)
		{
			$this->setItemAttr($item, 'parent_id', $parent_id);

			$children = array_pull($item, $this->attrName('children'));

			$result[] = $item;

			if (is_array($children))
			{
				$item_id = $this->getItemAttr($item, 'id');

				$result = array_merge($result, $this->flatten($children, $item_id));
			}
		}

		return $result;
	}

	/**
	 * Lay thuoc tinh cua item
	 *
	 * @param array  $item
	 * @param string $key
	 * @return mixed
	 */
	protected function getItemAttr(array $item, $key)
	{
		$key = $this->attrName($key);

		return $item[$key];
	}

	/**
	 * Gan thuoc tinh cho item
	 *
	 * @param array  $item
	 * @param string $key
	 * @param mixed  $value
	 */
	protected function setItemAttr(array &$item, $key, $value)
	{
		$key = $this->attrName($key);

		$item[$key] = $value;
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