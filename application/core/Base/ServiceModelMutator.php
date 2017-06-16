<?php namespace Core\Base;

class ServiceModelMutator
{
	/**
	 * Chinh sua
	 *
	 * @param Model $model
	 * @param array $data
	 * @return Model
	 */
	public function edit(Model $model, array $data)
	{
		$model->update($data);

		return $model;
	}

	/**
	 * Xoa
	 *
	 * @param Model $model
	 */
	public function delete(Model $model)
	{
		$model->delete();
	}

}