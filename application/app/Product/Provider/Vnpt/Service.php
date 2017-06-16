<?php namespace App\Product\Provider\Vnpt;

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
use VnptEpayCdv;

require_once APPPATH.'libraries/vnpt/VnptEpayCdv.php';

class Service extends ProviderService
{
	/**
	 * Doi tuong api service
	 *
	 * @var VnptEpayCdv
	 */
	protected $api;


	/**
	 * Lay cac dich vu ho tro
	 *
	 * @return array
	 */
	public function getServices()
	{
		return ['card', 'topup_mobile', 'topup_mobile_post', 'topup_game'];
	}

	/**
	 * Test ket noi
	 *
	 * @return TestResponse
	 */
	public function test()
	{
		$res = $this->api()->queryBalance();

		if ( ! $this->isApiResponseSuccess($res))
		{
			return TestResponse::error($this->getApiResponseError($res), $res);
		}

		return TestResponse::success($res);
	}

	/**
	 * Lay balance
	 *
	 * @return GetBalanceResponse
	 */
	public function getBalance()
	{
		$res = $this->api()->queryBalance();

		if ( ! $this->isApiResponseSuccess($res))
		{
			return GetBalanceResponse::error($this->getApiResponseError($res));
		}

		$balance = $res->balance_avaiable;

		return GetBalanceResponse::success($balance);
	}

	/**
	 * Mua ma the
	 *
	 * @param BuyCardRequest $request
	 * @return BuyCardResponse
	 */
	public function buyCard(BuyCardRequest $request)
	{
		list($provider, $amount) = $request->parseKeyConnection(2);

		if ( ! $provider || ! $amount)
		{
			return BuyCardResponse::error('Param key_connection invalid');
		}

		$res = $this->api()->downloadSoftpin($request->request_id, $provider, $amount, $request->quantity);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return BuyCardResponse::error($this->getApiResponseError($res));
		}

		$cards = $res->listCards->listCards;
		$cards = $this->parseCardsResponse($cards);

		return BuyCardResponse::success($cards);
	}

	/**
	 * Lay ma the
	 *
	 * @param GetCardRequest $request
	 * @return GetCardResponse
	 */
	public function getCard(GetCardRequest $request)
	{
		$res = $this->api()->reDownloadSoftpin($request->request_id);

		if ( ! $this->isApiResponseSuccess($res))
		{
		    return GetCardResponse::error($this->getApiResponseError($res));
		}

		$cards = $res->listCards->listCards;
		$cards = $this->parseCardsResponse($cards);

		return GetCardResponse::success($cards);
	}

	/**
	 * Lay thong tin giao dich
	 *
	 * @param FindTranRequest $request
	 * @return FindTranResponse
	 */
	public function findTran(FindTranRequest $request)
	{
		return ($request->command == 'topup_mobile_post')
			? $this->findTranTopupMobilePost($request)
			: $this->findTranOther($request);
	}

	/**
	 * Lay thong tin giao dich topup mobile post
	 *
	 * @param FindTranRequest $request
	 * @return FindTranResponse
	 */
	protected function findTranTopupMobilePost(FindTranRequest $request)
	{
		$res = $this->api()->checkOrdersCDV($request->request_id);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return new FindTranResponse([
				'status'  => ProviderTranStatus::FAILED,
				'message' => $this->getApiResponseError($res),
			]);
		}

		return new FindTranResponse([
			'status'        => ProviderTranStatus::SUCCESS,
			'provider_tran' => $res,
		]);
	}

	/**
	 * Lay thong tin giao dich khac
	 *
	 * @param FindTranRequest $request
	 * @return FindTranResponse
	 */
	protected function findTranOther(FindTranRequest $request)
	{
		$type = $request->command == 'buy_card' ? 2 : 1;

		$res = $this->api()->checkTrans($request->request_id, $type);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return new FindTranResponse([
				'status'  => ProviderTranStatus::FAILED,
				'message' => $this->getApiResponseError($res),
			]);
		}

		return new FindTranResponse([
			'status' => ProviderTranStatus::SUCCESS,
		]);
	}

	/**
	 * Nap tien dien thoai
	 *
	 * @param TopupMobileRequest $request
	 * @return TopupMobileResponse
	 */
	public function topupMobile(TopupMobileRequest $request)
	{
		list($provider, $amount) = $request->parseKeyConnection(2);

		if ( ! $provider || ! $amount)
		{
			return TopupMobileResponse::error('Param key_connection invalid');
		}

		$res = $this->api()->topup($request->request_id, $provider, $request->account, $amount);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return TopupMobileResponse::error($this->getApiResponseError($res));
		}

		return TopupMobileResponse::success();
	}

	/**
	 * Nap tien dien thoai tra sau
	 *
	 * @param TopupMobilePostRequest $request
	 * @return TopupMobilePostResponse
	 */
	public function topupMobilePost(TopupMobilePostRequest $request)
	{
		list($provider) = $request->parseKeyConnection(1);

		if ( ! $provider)
		{
			return TopupMobilePostResponse::error('Param key_connection invalid');
		}

		$type = ($provider == 'VNP') ? 4 : 2;

		$res = $this->api()->paymentCDV($request->request_id, $provider, $type, $request->account, $request->amount);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return TopupMobilePostResponse::error($this->getApiResponseError($res));
		}

		return TopupMobilePostResponse::success([
			'provider_tran' => $res,
		]);
	}

	/**
	 * Nap tien game
	 *
	 * @param TopupGameRequest $request
	 * @return TopupGameResponse
	 */
	public function topupGame(TopupGameRequest $request)
	{
		list($provider, $amount) = $request->parseKeyConnection(2);

		if ( ! $provider || ! $amount)
		{
			return TopupGameResponse::error('Param key_connection invalid');
		}

		$res = $this->api()->topup($request->request_id, $provider, $request->account, $amount);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return TopupGameResponse::error($this->getApiResponseError($res));
		}

		return TopupGameResponse::success();
	}

	/**
	 * Lay doi tuong api
	 *
	 * @return VnptEpayCdv
	 */
	protected function api()
	{
		if (is_null($this->api))
		{
			$this->api = new VnptEpayCdv([
				'partner_name' => $this->setting('partner_name'),
				'key_sofpin'   => $this->setting('key_sofpin'),
				'url'          => $this->setting('url'),
			]);
		}

		return $this->api;
	}

	/**
	 * Kiem tra ket qua tra ve tu api co thanh cong hay khong
	 *
	 * @param mixed $response
	 * @return bool
	 */
	protected function isApiResponseSuccess($response)
	{
		return $response && in_array((int) $response->errorCode, [0, 23, 99]);
	}

	/**
	 * Lay api response error
	 *
	 * @param mixed $response
	 * @return string
	 */
	protected function getApiResponseError($response)
	{
		return data_get($response, 'message', 'Unknown error');
	}

	/**
	 * Phan tich danh sach cards lay tu api
	 *
	 * @param mixed $list
	 * @return array
	 */
	protected function parseCardsResponse($list)
	{
		$cards = [];

		foreach ($list as $row)
		{
			$row = explode('|', $row);

			$cards[] = new Card([
				'code'   => $row[3],
				'serial' => $row[2],
				'expire' => $row[4],
			]);
		}

		return $cards;
	}
}