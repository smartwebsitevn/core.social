<?php namespace App\Product\Library\Cart;

use Core\ShoppingCart\StorageInterface;
use Core\ShoppingCart\Cart;
use CI_Session as Session;

class Storage implements StorageInterface
{
	/**
	 * Key luu tru
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Doi tuong Session
	 *
	 * @var Session
	 */
	protected $session;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param string $key
	 */
	public function __construct($key = 'product.order.cart')
	{
		$this->key = $key;

		$this->session = t('session');
	}

	/**
	 * Luu cart
	 *
	 * @param Cart $cart
	 */
	public function saveCart(Cart $cart)
	{
		$data = $this->makeDataStorage($cart);

		$this->setData($data);
	}

	/**
	 * Tao data luu tru
	 *
	 * @param Cart $cart
	 * @return array
	 */
	protected function makeDataStorage(Cart $cart)
	{
		$items = array_map(function(Item $item)
		{
			return array_filter([
				'product_id' => $item->getProduct()->id,
				'quantity'   => $item->getQuantity(),
				'options'    => $item->getOptions(),
			]);
		}, $cart->items());

		return compact('items');
	}

	/**
	 * Luu data
	 *
	 * @param array $data
	 */
	public function setData(array $data)
	{
		count($data)
			? $this->session->set_userdata($this->key, $data)
			: $this->session->unset_userdata($this->key);
	}

	/**
	 * Lay data
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->session->userdata($this->key) ?: [];
	}

	/**
	 * Lay storage key
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

}