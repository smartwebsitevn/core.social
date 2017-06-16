<?php namespace App\Product\Library\Cart;

use Core\ShoppingCart\Cart as BaseCart;

class Cart extends BaseCart
{
	public static function _t()
	{
		$storage = new Storage;

//		$me = new static($storage);
//
//		$me->add(new Item([
//			'product'  => \App\Product\Model\Product::find(20),
//			'amount' => 20.1,
//		]));
//		$me->add(new Item([
//			'product'  => \App\Product\Model\Product::find(21),
//			'amount' => 21.2,
//		]));
//
//		$me->update(2, ['quantity' => 22]);
////		$me->remove(2);
////		$me->destroy();
//
//		$v = $me->find(2);
//		$v = $me->total();

		$me = $storage->restoreCart();

//		pr($v, 0);
		pr($me, 0);
	}

}