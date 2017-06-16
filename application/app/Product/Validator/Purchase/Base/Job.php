<?php namespace App\Product\Validator\Purchase\Base;

abstract class Job extends FactoryAccessor
{
	/**
	 * Thuc hien xu ly
	 *
	 * @return mixed
	 */
	abstract public function handle();
}