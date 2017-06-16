<?php namespace Core\ShoppingCart;

class Cart
{
	/**
	 * Doi tuong Storage
	 *
	 * @var StorageInterface
	 */
	protected $storage;

	/**
	 * Danh sach items
	 *
	 * @var array
	 */
	protected $items = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param StorageInterface $storage
	 */
	public function __construct(StorageInterface $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * Them item
	 *
	 * @param Item $item
	 */
	public function add(Item $item)
	{
		$this->items[] = $item;

		$this->save();
	}

	/**
	 * Lay thong tin item
	 *
	 * @param int $item_id
	 * @return Item|null
	 */
	public function find($item_id)
	{
		return array_first($this->items, function($i, Item $item) use ($item_id)
		{
			return $item->getId() == $item_id;
		});
	}

	/**
	 * Kiem tra su ton tai cua item
	 *
	 * @param int $item_id
	 * @return bool
	 */
	public function has($item_id)
	{
		return $this->find($item_id) ? true : false;
	}

	/**
	 * Update item data
	 *
	 * @param int   $item_id
	 * @param array $data
	 */
	public function update($item_id, array $data)
	{
		if ( ! $item = $this->find($item_id)) return;

		$item->fill($data);

		$this->save();
	}

	/**
	 * Xoa item
	 *
	 * @param $item_id
	 */
	public function remove($item_id)
	{
		if ( ! $this->has($item_id)) return;

		$this->items = array_filter($this->items, function(Item $item) use ($item_id)
		{
			return $item->getId() != $item_id;
		});

		$this->save();
	}

	/**
	 * Reset cart
	 */
	public function destroy()
	{
		$this->items = [];

		$this->save();
	}

	/**
	 * Lay danh sach items
	 *
	 * @return array
	 */
	public function items()
	{
		return $this->items;
	}

	/**
	 * Lay tong so tien cua cac item
	 *
	 * @return float
	 */
	public function subtotal()
	{
		return array_reduce($this->items, function($total, Item $item)
		{
			$total += $item->getAmount();

			return $total;
		});
	}

	/**
	 * Lay tong so tien can thanh toan
	 *
	 * @return float
	 */
	public function total()
	{
		return $this->subtotal();
	}

	/**
	 * Lay tong so items
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}

	/**
	 * Luu cart
	 */
	public function save()
	{
		$this->storage->saveCart($this);
	}

	/**
	 * Lay doi tuong Storage
	 *
	 * @return StorageInterface
	 */
	public function getStorage()
	{
		return $this->storage;
	}

}