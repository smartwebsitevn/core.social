<?php namespace App\Product\Validator\Purchase;

class Error
{
	const CAN_NOT_BUY_PRODUCT = 'can_not_buy_product';
	const QUANTITY_INVALID = 'quantity_invalid';
	const OUT_OF_STOCK = 'out_of_stock';
	const QUANTITY_OVER = 'quantity_over';
	const PHONE_INVALID = 'phone_invalid';
	const PHONE_PROVIDER_INVALID = 'phone_provider_invalid';
	const AMOUNT_INVALID = 'amount_invalid';
	const ACCOUNT_INVALID = 'account_invalid';
	const SHIP_INVALID = 'ship_invalid';
}