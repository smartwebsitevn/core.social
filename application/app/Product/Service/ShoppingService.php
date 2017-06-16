<?php namespace App\Product\Service;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Library\Cart\Cart;
use App\Product\Library\Cart\Item as CartItem;
use App\Product\Library\Cart\Storage as CartStorage;
use App\Product\Model\ProductModel as ProductModel;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;
use TF\Support\Collection;

class ShoppingService
{
	/**
	 * Doi tuong Cart
	 *
	 * @var Cart
	 */
	protected $cart;

	/**
	 * Doi tuong UserModel
	 *
	 * @var UserModel
	 */
	protected $user;


	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->cart = $this->newCart();

		$this->user = UserFactory::auth()->user();

		$this->restoreCart();
	}

	/**
	 * Tao doi tuong Cart
	 *
	 * @return Cart
	 */
	protected function newCart()
	{
		$storage = new CartStorage;

		return new Cart($storage);
	}

	/**
	 * Khoi phuc cart tu data luu tru
	 */
	protected function restoreCart()
	{
		$storage_data = $this->cart->getStorage()->getData();

		$storage_items = array_get($storage_data, 'items', []);

		$product_ids = array_pluck($storage_items, 'product_id');

		$products = $this->findProducts($product_ids);

		foreach ($storage_items as $storage_item)
		{
			$product_id = array_get($storage_item, 'product_id');
			$quantity 	= array_get($storage_item, 'quantity', 1);
			$options 	= array_get($storage_item, 'options', []);

			$product = $products->whereLoose('id', $product_id)->first();

			if ( ! $product) continue;

			$item = $this->makeCartItem($product, $quantity, $options);

			$this->cart->add($item);
		}
	}

	/**
	 * Lay danh sach products tu ids
	 *
	 * @param array $ids
	 * @return Collection
	 */
	protected function findProducts(array $ids)
	{
		$products = (new ProductModel())->newQuery()->filter_get_list(['id' => $ids]);

		return ProductModel::makeCollection($products);
	}

	/**
	 * Lay doi tuong Cart
	 *
	 * @return Cart
	 */
	public function cart()
	{
		return $this->cart;
	}

	/**
	 * Them san pham vao gio hang
	 *
	 * @param ProductModel $product
	 * @param int          $quantity
	 * @param array        $options
	 * @return CartItem
	 */
	public function addProduct(ProductModel $product, $quantity = 1, array $options = [])
	{
		$cart_item = $this->makeCartItem($product, $quantity, $options);

		$this->cart()->add($cart_item);

		return $cart_item;
	}

	/**
	 * Cap nhat quantity cua CartItem
	 *
	 * @param CartItem $cart_item
	 * @param int      $quantity
	 */
	public function updateQuantity(CartItem $cart_item, $quantity)
	{
		$data = $this->makeOrderAmount($cart_item->getProduct(), $quantity);

		$this->cart()->update($cart_item->getId(), $data);
	}

	/**
	 * Tao doi tuong CartItem
	 *
	 * @param ProductModel $product
	 * @param int          $quantity
	 * @param array        $options
	 * @return CartItem
	 */
	protected function makeCartItem(ProductModel $product, $quantity, array $options)
	{
		return new CartItem(array_merge(
			$this->makeOrderAmount($product, $quantity),
			compact('product', 'options')
		));
	}

	/**
	 * Xu ly order amount
	 *
	 * @param ProductModel $product
	 * @param int          $quantity
	 * @return array
	 */
	protected function makeOrderAmount(ProductModel $product, $quantity)
	{
		return ProductFactory::order()->makeOrderAmount([
			'product'  => $product,
			'quantity' => $quantity,
			'user'     => $this->user,
		]);
	}

}