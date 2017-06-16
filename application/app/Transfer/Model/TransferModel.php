<?php namespace App\Transfer\Model;

use App\Currency\Model\CurrencyRelationTrait;
use App\Invoice\Model\InvoiceOrderModel;
use App\Purse\Model\PurseModel;
use App\User\Model\UserModel;

class TransferModel extends \Core\Base\Model
{
	use CurrencyRelationTrait;

	protected $table = 'transfer';

	protected $casts = [
		'send_amount'    => 'float',
		'receive_amount' => 'float',
		'amount'         => 'float',
		'fee'            => 'float',
		'net'            => 'float',
	];

	/**
	 * Gan sender
	 *
	 * @param UserModel $sender
	 */
	protected function setSenderAttribute(UserModel $sender)
	{
		$this->additional['sender'] = $sender;
	}

	/**
	 * Lay sender
	 *
	 * @return UserModel|null
	 */
	protected function getSenderAttribute()
	{
		if ( ! array_key_exists('sender', $this->additional))
		{
			$sender_id = $this->getAttribute('sender_id');

			$this->additional['sender'] = UserModel::find($sender_id);
		}

		return $this->additional['sender'];
	}

	/**
	 * Gan sender_purse
	 *
	 * @param PurseModel $sender_purse
	 */
	protected function setSenderPurseAttribute(PurseModel $sender_purse)
	{
		$this->additional['sender_purse'] = $sender_purse;
	}

	/**
	 * Lay sender_purse
	 *
	 * @return PurseModel|null
	 */
	protected function getSenderPurseAttribute()
	{
		if ( ! array_key_exists('sender_purse', $this->additional))
		{
			$sender_purse_id = $this->getAttribute('sender_purse_id');

			$this->additional['sender_purse'] = PurseModel::find($sender_purse_id);
		}

		return $this->additional['sender_purse'];
	}

	/**
	 * Gan send_invoice_order
	 *
	 * @param InvoiceOrderModel $send_invoice_order
	 */
	protected function setSendInvoiceOrderAttribute(InvoiceOrderModel $send_invoice_order)
	{
		$this->additional['send_invoice_order'] = $send_invoice_order;
	}

	/**
	 * Lay send_invoice_order
	 *
	 * @return InvoiceOrderModel|null
	 */
	protected function getSendInvoiceOrderAttribute()
	{
		if ( ! array_key_exists('send_invoice_order', $this->additional))
		{
			$send_invoice_order_id = $this->getAttribute('send_invoice_order_id');

			$this->additional['send_invoice_order'] = InvoiceOrderModel::find($send_invoice_order_id);
		}

		return $this->additional['send_invoice_order'];
	}

	/**
	 * Gan receiver
	 *
	 * @param UserModel $receiver
	 */
	protected function setReceiverAttribute(UserModel $receiver)
	{
		$this->additional['receiver'] = $receiver;
	}

	/**
	 * Lay receiver
	 *
	 * @return UserModel|null
	 */
	protected function getReceiverAttribute()
	{
		if ( ! array_key_exists('receiver', $this->additional))
		{
			$receiver_id = $this->getAttribute('receiver_id');

			$this->additional['receiver'] = UserModel::find($receiver_id);
		}

		return $this->additional['receiver'];
	}

	/**
	 * Gan receiver_purse
	 *
	 * @param PurseModel $receiver_purse
	 */
	protected function setReceiverPurseAttribute(PurseModel $receiver_purse)
	{
		$this->additional['receiver_purse'] = $receiver_purse;
	}

	/**
	 * Lay receiver_purse
	 *
	 * @return PurseModel|null
	 */
	protected function getReceiverPurseAttribute()
	{
		if ( ! array_key_exists('receiver_purse', $this->additional))
		{
			$receiver_purse_id = $this->getAttribute('receiver_purse_id');

			$this->additional['receiver_purse'] = PurseModel::find($receiver_purse_id);
		}

		return $this->additional['receiver_purse'];
	}

	/**
	 * Gan receive_invoice_order
	 *
	 * @param InvoiceOrderModel $receive_invoice_order
	 */
	protected function setReceiveInvoiceOrderAttribute(InvoiceOrderModel $receive_invoice_order)
	{
		$this->additional['receive_invoice_order'] = $receive_invoice_order;
	}

	/**
	 * Lay receive_invoice_order
	 *
	 * @return InvoiceOrderModel|null
	 */
	protected function getReceiveInvoiceOrderAttribute()
	{
		if ( ! array_key_exists('receive_invoice_order', $this->additional))
		{
			$receive_invoice_order_id = $this->getAttribute('receive_invoice_order_id');

			$this->additional['receive_invoice_order'] = InvoiceOrderModel::find($receive_invoice_order_id);
		}

		return $this->additional['receive_invoice_order'];
	}

	/**
	 * Format du lieu
	 *
	 * @param string $key
	 * @param mixed  $option
	 * @return string|null
	 */
	public function format($key, $option = null)
	{
		switch($key)
		{
			case 'send_amount':
			case 'receive_amount':
			case 'amount':
			case 'fee':
			case 'net':
			{
				$amount = $this->getAttribute($key);

				$currency_id = $this->getAttribute('currency_id');

				return currency_format_amount($amount, $currency_id);
			}
		}

		return parent::format($key, $option);
	}

	/**
	 * Cap nhat trang thai
	 *
	 * @param string $status
	 */
	public function updateStatus($status)
	{
		$this->update(compact('status'));

		$this->getAttribute('send_invoice_order')->update(['order_status' => $status]);

		$this->getAttribute('receive_invoice_order')->update(['order_status' => $status]);
	}

	/**
	 * Lay thong tin tu send_invoice_order_id
	 *
	 * @param int $send_invoice_order_id
	 * @return static|null
	 */
	public static function findBySendInvoiceOrder($send_invoice_order_id)
	{
		return static::findWhere(compact('send_invoice_order_id'));
	}

	/**
	 * Lay thong tin tu receive_invoice_order_id
	 *
	 * @param int $receive_invoice_order_id
	 * @return static|null
	 */
	public static function findByReceiveInvoiceOrder($receive_invoice_order_id)
	{
		return static::findWhere(compact('receive_invoice_order_id'));
	}

}