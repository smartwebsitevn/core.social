<?php namespace App\User\Service;

use TF\Support\Collection;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserGroupModel as UserGroupModel;
use App\Payment\Model\PaymentModel as PaymentModel;

class UserGroupService extends \Core\Base\ServiceModelMutator
{
	/**
	 * Danh sach UserGroup
	 *
	 * @var Collection
	 */
	protected $list;


	/**
	 * Them moi
	 *
	 * @param array $data
	 * @return UserGroupModel
	 */
	public function add(array $data)
	{
		$data = array_add($data, 'sort_order', now());

		return UserGroupModel::create($data);
	}

	/**
	 * Lay danh sach
	 *
	 * @return Collection
	 */
	public function lists()
	{
		if (is_null($this->list))
		{
		    $this->list = UserGroupModel::all();
		}

		return $this->list;
	}

	/**
	 * Lay thong tin
	 *
	 * @param int $id
	 * @return UserGroupModel|null
	 */

	public function find($id)
	{
		return $this->lists()->whereLoose('id', $id)->first();
	}

	/**
	 * Lay thong tin tu type
	 *
	 * @param string $type
	 * @return UserGroupModel|null
	 */
	public function findByType($type)
	{
		return $this->lists()->whereLoose('type', $type)->first();
	}

	/**
	 * Lay nhom ap dung cho khach
	 *
	 * @return UserGroupModel
	 */
	public function getForGuest()
	{
		return $this->findByType('guest');
	}

	/**
	 * Lay nhom ap dung cho thanh vien thuong
	 *
	 * @return UserGroupModel
	 */
	public function getForUser()
	{
		return $this->findByType('user');
	}
	/**
	 * Lay nhom ap dung cho thanh vien thuong
	 *
	 * @return UserGroupModel
	 */
	public function getForPartner()
	{
		return $this->findByType('partner');
	}
	/**
	 * Kiem tra user_group co duoc phep su dung payment hay khong
	 *
	 * @param UserGroupModel $user_group
	 * @param PaymentModel   $payment
	 * @return bool
	 */
	public function canUsePayment(UserGroupModel $user_group, PaymentModel $payment)
	{
		return array_key_exists($payment->id, $user_group->payments);
	}
}