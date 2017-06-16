<?php namespace Core\Model;

trait TokenMakerTrait
{
	/**
	 * Lay token goc
	 *
	 * @return string
	 */
	protected function getTokenAttribute()
	{
		return security_encode($this->getKey().'-'.$this->getAttribute('secret_key'));
	}

	/**
	 * Tao token
	 *
	 * @param string $key
	 * @return string
	 */
	public function token($key = null)
	{
		$token = $this->getAttribute('token');

		return $key ? security_encode($token, $key) : $token;
	}
}