<?php namespace TF\Support\Contracts;

interface MessageProviderInterface {

	/**
	 * Get the messages for the instance.
	 *
	 * @return \TF\Support\MessageBag
	 */
	public function getMessageBag();

}
