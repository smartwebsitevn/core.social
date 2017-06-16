<?php namespace App\Currency\Service;

use Core\Support\Arr;
use TF\Support\Collection;
use App\Currency\Model\CurrencyModel;

class CurrencyService
{
	/**
	 * Danh sach currency
	 *
	 * @var Collection
	 */
	protected $list;

	/**
	 * Currency id mac dinh
	 *
	 * @var int
	 */
	protected $default_id;

	/**
	 * Currency id hien thi hien tai
	 *
	 * @var
	 */
	protected $current_id;


	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->list = CurrencyModel::all();

		$this->default_id = model('currency')->get_default()->id;

		$this->current_id = currency_get_cur()->id;
	}

	/**
	 * Lay danh sach
	 *
	 * @return Collection
	 */
	public function lists()
	{
		return $this->list;
	}

	/**
	 * Lay thong tin currency
	 *
	 * @param int $id
	 * @return CurrencyModel|null
	 */
	public function find($id)
	{
		return $this->lists()->whereLoose('id', $id)->first();
	}

	/**
	 * Lay currency mac dinh
	 *
	 * @return CurrencyModel
	 */
	public function getDefault()
	{
		return $this->find($this->default_id);
	}

	/**
	 * Lay currency hien thi hien tai
	 *
	 * @return CurrencyModel
	 */
	public function getCurrent()
	{
		return $this->find($this->current_id);
	}

}