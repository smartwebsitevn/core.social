<?php

use App\User\UserFactory as UserFactory;
use App\User\Model\UserGroupModel as UserGroupModel;
use App\User\Handler\Form\UserGroup as UserGroupFormHandler;

class User_group extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/user/user_group');
	}

	/**
	 * Them moi
	 */
	public function add()
	{
		$user_group = new UserGroupModel;

		$this->_make_form($user_group);
	}

	/**
	 * Chinh sua
	 */
	public function edit()
	{
		$id = t('uri')->rsegment(3);

		$user_group = UserGroupModel::find($id);

		if ( ! $user_group || ! $user_group->can('edit'))
		{
			set_message(lang('notice_can_not_do'));

			$this->_redirect();
		}

		$this->_make_form($user_group);
	}

	/**
	 * Tao form xu ly
	 *
	 * @param UserGroupModel $user_group
	 */
	protected function _make_form(UserGroupModel $user_group)
	{
		$form = new UserGroupFormHandler($user_group);

		$this->_run_form_handler($form);
	}

	/**
	 * Xoa
	 */
	public function delete()
	{
		$this->_dispatchAction('delete', function(UserGroupModel $user_group)
		{
			UserFactory::userGroup()->delete($user_group);
		});
	}

	/**
	 * Thuc hien action
	 *
	 * @param string   $action
	 * @param callable $handle
	 */
	protected function _dispatchAction($action, callable $handle)
	{
		$this->_action_model_handler([
			'model'  => 'App\User\Model\UserGroupModel',
			'action' => $action,
			'handle' => $handle,
		]);
	}

	/**
	 * List
	 */
	public function index()
	{
		$this->_list([
			'page'    => false,
			'sort'    => true,
			'display' => false,
		]);
		$this->data['list'] = UserGroupModel::makeCollection($this->data['list']);
		//pr($this->data['list'] );
		$this->_display();
	}

}