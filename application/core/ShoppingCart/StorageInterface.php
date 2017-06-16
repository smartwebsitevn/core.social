<?php namespace Core\ShoppingCart;

interface StorageInterface
{
	/**
	 * Luu cart
	 *
	 * @param Cart $cart
	 */
	public function saveCart(Cart $cart);

	/**
	 * Luu data
	 *
	 * @param array $data
	 */
	public function setData(array $data);

	/**
	 * Lay data
	 *
	 * @return array
	 */
	public function getData();
}