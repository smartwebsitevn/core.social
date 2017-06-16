<?php namespace App\Purse\Model;

use App\Purse\Model\PurseModel as PurseModel;

trait PurseRelationTrait
{
	/**
	 * Gan purse
	 *
	 * @param PurseModel $purse
	 */
	protected function setPurseAttribute(PurseModel $purse)
	{
		$this->additional['purse'] = $purse;
	}

	/**
	 * Lay purse
	 *
	 * @return PurseModel|null
	 */
	protected function getPurseAttribute()
	{
		if ( ! array_key_exists('purse', $this->additional))
		{
			$purse_id = $this->getAttribute('purse_id');

			$this->additional['purse'] = PurseModel::find($purse_id);
		}

		return $this->additional['purse'];
	}
}