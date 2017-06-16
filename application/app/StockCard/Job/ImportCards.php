<?php namespace App\StockCard\Job;

use App\Admin\Model\AdminModel;
use App\Product\Model\ProductModel;
use App\StockCard\Model\StockCardModel;

class ImportCards extends \Core\Base\Job
{
	/**
	 * Thong tin product
	 *
	 * @var ProductModel
	 */
	protected $product;

	/**
	 * Danh sach cards
	 *
	 * @var array
	 */
	protected $cards;

	/**
	 * Admin thuc hien
	 *
	 * @var AdminModel
	 */
	protected $admin;

	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param ProductModel $product
	 * @param array        $cards
	 * @param AdminModel   $admin
	 * @param array        $data
	 */
	public function __construct(ProductModel $product, array $cards, AdminModel $admin, array $data = [])
	{
		$this->product = $product;
		$this->cards = $cards;
		$this->admin = $admin;
		$this->data = $data;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		$result = [];

		foreach ($this->cards as $card)
		{
			$result[] = StockCardModel::create(array_merge($this->data, [
				'product_id'   => $this->product->id,
				'product_name' => $this->product->name,
				'code'         => $card['code'],
				'serial'       => $card['serial'],
				'expire'       => $card['expire'],
				'admin_id'     => $this->admin->id,
			]));
		}

		return $result;
	}
}