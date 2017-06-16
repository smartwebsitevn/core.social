<?php namespace Core\FormHandler;

interface FormHandlerInterface
{
	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation();

	/**
	 * Xu ly form khi du lieu hop le
	 *
	 * @return mixed
	 */
	public function submit();

	/**
	 * Xu ly form error
	 *
	 * @return array
	 */
	public function error();

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form();

}