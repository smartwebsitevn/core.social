<?php namespace App\Transaction\Handler\Request;

use Core\Base\RequestHandler;
use App\Transaction\Model\TranModel as TranModel;
use App\User\UserFactory as UserFactory;

class TranView extends RequestHandler
{
	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data = [];


	/**
	 * Thuc hien xu ly
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try
		{
			$this->validate();
		}
		catch (\Exception $e)
		{
			return $this->error($e->getMessage());
		}

		return $this->success();
	}

	/**
	 * Validate du lieu
	 *
	 * @throws \Exception
	 */
	protected function validate()
	{
		if ( ! $this->getTran())
		{
			throw new \Exception(lang('notice_value_not_exist', lang('tran')));
		}

		if ( ! $this->checkToken())
		{
			throw new \Exception(lang('notice_value_invalid', lang('token')));
		}

		if ( ! $this->checkAccess())
		{
			throw new \Exception(lang('notice_do_not_have_permission'));
		}
	}

	/**
	 * Kiem tra token
	 *
	 * @return bool
	 */
	protected function checkToken()
	{
		return $this->getTran()->token('view') === $this->input('token');
	}

	/**
	 * Kiem tra quyen truy cap
	 *
	 * @return bool
	 */
	protected function checkAccess()
	{
		return UserFactory::auth()->checkAccess([
			'user_id' => $this->getTran()->user_id,
			'ip'      => $this->getTran()->user_ip,
		]);
	}

	/**
	 * Xu ly response error
	 *
	 * @param string $error
	 */
	protected function error($error)
	{
		set_message($error);

		redirect();
	}

	/**
	 * Xu ly response success
	 *
	 * @return array
	 */
	protected function success()
	{
		$tran = $this->getTran();

		return compact('tran');
	}

	/**
	 * Lay thong tin tran
	 *
	 * @return TranModel|null
	 */
	public function getTran()
	{
		if ( ! array_key_exists('tran', $this->data))
		{
			$this->data['tran'] = TranModel::find($this->input('tran_id'));
		}

		return $this->data['tran'];
	}
}