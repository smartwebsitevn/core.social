<?php namespace App\Accountant\Library;

class OtherReason extends Reason
{
	/**
	 * Tao reason
	 *
	 * @param string $desc
	 * @return Reason
	 */
	public static function make($desc)
	{
		return new static(compact('desc'));
	}

	/**
	 * Lay mo ta
	 *
	 * @return string
	 */
	public function desc()
	{
		return $this->getOption('desc');
	}
}