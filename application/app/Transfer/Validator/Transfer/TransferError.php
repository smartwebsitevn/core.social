<?php namespace App\Transfer\Validator\Transfer;

class TransferError
{
	const INPUT_INVALID = 'input_invalid';
	const SENDER_PURSE_INVALID = 'sender_purse_invalid';
	const RECEIVER_PURSE_INVALID = 'receiver_purse_invalid';
	const AMOUNT_INVALID = 'amount_invalid';
	const SENDER_PURSE_BALANCE_NOT_ENOUGH = 'sender_purse_balance_not_enough';
	const SENDER_PURSE_BALANCE_INVALID = 'sender_purse_balance_invalid';
	const SEND_AMOUNT_DAILY_EXCEEDED = 'send_amount_daily_exceeded';
}