<?php namespace App\Product\Library;

use App\Product\Library\Provider\FindTranRequest;
use App\Product\Library\Provider\FindTranResponse;
use App\Product\Library\Provider\GetCardRequest;
use App\Product\Library\Provider\GetCardResponse;
use App\Product\Library\Provider\ProviderTranStatus;
use App\Product\Library\Provider\TopupGameRequest;
use App\Product\Library\Provider\TopupGameResponse;
use App\Product\Library\Provider\TopupMobilePostRequest;
use App\Product\Library\Provider\TopupMobilePostResponse;
use App\Product\Library\Provider\TopupMobileRequest;
use App\Product\Library\Provider\TopupMobileResponse;
use App\Product\Model\ProviderModel as ProviderModel;
use App\Product\Library\Provider\TestResponse;
use App\Product\Library\Provider\GetBalanceResponse;
use App\Product\Library\Provider\BuyCardRequest;
use App\Product\Library\Provider\BuyCardResponse;

abstract class ProviderService
{
	/**
	 * Doi tuong ProviderFactory
	 *
	 * @var ProviderFactory
	 */
	protected $factory;

	/**
	 * Doi tuong ProviderModel
	 *
	 * @var ProviderModel
	 */
	protected $model;


	/**
	 * ProviderService constructor.
	 *
	 * @param ProviderFactory $factory
	 * @param ProviderModel   $model
	 */
	public function __construct(ProviderFactory $factory, ProviderModel $model)
	{
		$this->factory = $factory;
		$this->model = $model;
	}

	/**
	 * Lay cac dich vu ho tro
	 *
	 * @return array
	 */
	abstract public function getServices();

	/**
	 * Test ket noi
	 *
	 * @return TestResponse
	 */
	abstract public function test();

	/**
	 * Lay balance
	 *
	 * @return GetBalanceResponse
	 */
	abstract public function getBalance();

	/**
	 * Mua ma the
	 *
	 * @param BuyCardRequest $request
	 * @return BuyCardResponse
	 */
	public function buyCard(BuyCardRequest $request)
	{
		return BuyCardResponse::error('This function has not been undefined');
	}

	/**
	 * Lay ma the
	 *
	 * @param GetCardRequest $request
	 * @return GetCardResponse
	 */
	public function getCard(GetCardRequest $request)
	{
		return GetCardResponse::error('This function has not been undefined');
	}

	/**
	 * Nap tien dien thoai
	 *
	 * @param TopupMobileRequest $request
	 * @return TopupMobileResponse
	 */
	public function topupMobile(TopupMobileRequest $request)
	{
		TopupMobileResponse::error('This function has not been undefined');
	}

	/**
	 * Nap tien dien thoai tra sau
	 *
	 * @param TopupMobilePostRequest $request
	 * @return TopupMobilePostResponse
	 */
	public function topupMobilePost(TopupMobilePostRequest $request)
	{
		TopupMobilePostResponse::error('This function has not been undefined');
	}

	/**
	 * Nap tien game
	 *
	 * @param TopupGameRequest $request
	 * @return TopupGameResponse
	 */
	public function topupGame(TopupGameRequest $request)
	{
		TopupGameResponse::error('This function has not been undefined');
	}

	/**
	 * Lay thong tin giao dich
	 *
	 * @param FindTranRequest $request
	 * @return FindTranResponse
	 */
	public function findTran(FindTranRequest $request)
	{
		return new FindTranResponse([
			'status'  => ProviderTranStatus::FAILED,
			'message' => 'This function has not been undefined',
		]);
	}

	/**
	 * Lay so luong ton kho cua cac san pham
	 *
	 * @param array $products
	 * @return array array(product_id => available, ...)
	 */
	public function getAvailables(array $products)
	{
		$result = [];

		foreach ($products as $product)
		{
			$result[$product->id] = -1;
		}

		return $result;
	}

	/**
	 * Nha cung cap co su dung kho the hay khong
	 *
	 * @return bool
	 */
	public function useStockCard()
	{
		return false;
	}

	/**
	 * Tao request id
	 *
	 * @return string
	 */
	public function makeRequestId()
	{
		$rand = '';

		for ($i = 1; $i <= 6; $i++)
		{
			$rand .= mt_rand(0, 9);
		}

		return time().$rand;
	}

	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	protected function setting($key = null, $default = null)
	{
		return array_get($this->model->setting, $key, $default);
	}

	/**
	 * Lay ProviderFactory
	 *
	 * @return ProviderFactory
	 */
	public function getFactory()
	{
		return $this->factory;
	}

	/**
	 * Lay ProviderModel
	 *
	 * @return ProviderModel
	 */
	public function getModel()
	{
		return $this->model;
	}
}