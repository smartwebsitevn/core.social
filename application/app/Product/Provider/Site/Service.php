<?php namespace App\Product\Provider\Site;

use App\Product\Library\Provider\BuyCardRequest;
use App\Product\Library\Provider\BuyCardResponse;
use App\Product\Library\Provider\Card;
use App\Product\Library\Provider\FindTranRequest;
use App\Product\Library\Provider\FindTranResponse;
use App\Product\Library\Provider\GetBalanceResponse;
use App\Product\Library\Provider\GetCardRequest;
use App\Product\Library\Provider\GetCardResponse;
use App\Product\Library\Provider\ProviderTranStatus;
use App\Product\Library\Provider\TestResponse;
use App\Product\Library\Provider\TopupGameRequest;
use App\Product\Library\Provider\TopupGameResponse;
use App\Product\Library\Provider\TopupMobilePostRequest;
use App\Product\Library\Provider\TopupMobilePostResponse;
use App\Product\Library\Provider\TopupMobileRequest;
use App\Product\Library\Provider\TopupMobileResponse;
use App\Product\Library\ProviderService;
use VtcGoods;


class Service extends ProviderService
{
	/**
	 * Lay cac dich vu ho tro
	 *
	 * @return array
	 */
	public function getServices()
	{
		return ['ship'];

	}

	/**
	 * Test ket noi
	 *
	 * @return TestResponse
	 */
	public function test()
	{

		return TestResponse::success();
	}

	/**
	 * Lay balance
	 *
	 * @return GetBalanceResponse
	 */
	public function getBalance()
	{

		return null;

	}



}