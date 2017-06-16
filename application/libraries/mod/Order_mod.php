<?php

class Order_mod extends MY_Mod
{
	/**
	 * Lay status id
	 *
	 * @param string $name
	 * @return int|false
	 */
	public function status($name)
	{
		return config("order_status_{$name}", 'main');
	}

	/**
	 * Lay status name
	 *
	 * @param int $id
	 * @return string
	 */
	public function status_name($id)
	{
		return array_get($this->statuss(), $id);
	}

	/**
	 * Lay statuss
	 *
	 * @return array
	 */
	public function statuss()
	{
		return config('order_statuss', 'main');
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param object $row
	 * @param string $action
	 * @return boolean
	 */
	public function can_do($row, $action)
	{
		if ( ! $row) return FALSE;

		$status = $this->status_name($row->status);

		switch ($action)
		{
			case 'edit':
			case 'view':
			case 'get':
			{
				return TRUE;
			}

			case 'payment':
			case 'active':
			case 'active_hand':
			case 'accept':
			case 'recode':
			case 'completed':
			case 'refund':
			{
				return ($status == 'pending');
			}

			case 'cancel':
			{
				return ($status != 'completed' && $status != 'cancel');
			}
			case 'del':
			{
				return ($status == 'completed' || $status == 'cancel');
			}
		}

		return FALSE;
	}

}