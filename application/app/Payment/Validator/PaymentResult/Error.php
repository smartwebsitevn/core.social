<?php namespace App\Payment\Validator\PaymentResult;

use App\Payment\Validator\PaymentPay\Error as PaymentPayError;

class Error extends PaymentPayError
{
	const TRAN_INVALID = 'tran_invalid';
	const PAYMENT_RESULT_UNSUCCESSFUL = 'payment_result_unsuccessful';
	const TRAN_ID_RESULT_INVALID = 'tran_id_result_invalid';
	const PAYMENT_AMOUNT_INVALID = 'payment_amount_invalid';
}