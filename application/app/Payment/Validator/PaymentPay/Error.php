<?php namespace App\Payment\Validator\PaymentPay;

class Error
{
	const INPUT_INVALID = 'input_invalid';
	const TRAN_NOT_EXIST = 'tran_not_exist';
	const PAYMENT_NOT_EXIST = 'payment_not_exist';
	const CAN_NOT_PAY_TRAN = 'can_not_pay_tran';
	const PAYMENT_INVALID = 'payment_invalid';
	const TRAN_OWNER_INVALID = 'tran_owner_invalid';
	const USER_CAN_NOT_USE_PAYMENT = 'user_can_not_use_payment';
	const CAN_NOT_USE_PAYMENT_FOR_INVOICE = 'can_not_use_payment_for_invoice';
	const PAYMENT_AMOUNT_DAILY_EXCEEDED = 'payment_amount_daily_exceeded';
}