<?php namespace App\Product\Provider\Vtc;

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

require_once APPPATH.'libraries/vtc/VtcGoods.php';

class Service extends ProviderService
{
	/**
	 * Doi tuong api service
	 *
	 * @var VtcGoods
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
		$res = $this->api()->getBalance();

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
		$res = $this->api()->getBalance();

		if ( ! $this->isApiResponseSuccess($res))
		{
			return GetBalanceResponse::error($this->getApiResponseError($res));
		}

		$balance = array_get($res, 'PartnerBalance');

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

		$res = $this->api()->buyCard($request->request_id, $provider, $amount, $request->quantity);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return BuyCardResponse::error($this->getApiResponseError($res));
		}

		$cards_res = $this->api()->getCard($provider, $amount, $res['VTCTransID']);

		$cards = array_get($cards_res, 'ListCard') ?: [];
		$cards = $this->parseCardsResponse($cards);

		return BuyCardResponse::success($cards, [
			'provider_tran_id' => $res['VTCTransID'],
			'provider_tran'    => $res,
			'balance'          => $res['PartnerBalance'],
		]);
	}

	/**
	 * Lay ma the
	 *
	 * @param GetCardRequest $request
	 * @return GetCardResponse
	 */
	public function getCard(GetCardRequest $request)
	{
		$res = $this->api()->checkPartnerTransCode($request->request_id, 1);

		if ( ! array_get($res, 'VTCTransID'))
		{
			return GetCardResponse::error($this->getApiResponseError($res));
		}

		list($provider, $amount) = $request->parseKeyConnection(2);

		if ( ! $provider || ! $amount)
		{
			return GetCardResponse::error('Param key_connection invalid');
		}

		$cards_res = $this->api()->getCard($provider, $amount, $res['VTCTransID']);

		$cards = array_get($cards_res, 'ListCard') ?: [];
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
		$type = $request->command == 'buy_card' ? 1 : 2;

		$res = $this->api()->checkPartnerTransCode($request->request_id, $type);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return new FindTranResponse([
				'status'  => ProviderTranStatus::FAILED,
				'message' => $this->getApiResponseError($res),
			]);
		}

		return new FindTranResponse([
			'status'           => ProviderTranStatus::SUCCESS,
			'provider_tran_id' => $res['VTCTransID'],
			'provider_tran'    => $res,
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

		$res = $this->api()->topupTelco($request->request_id, $provider, $request->account, $amount);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return TopupMobileResponse::error($this->getApiResponseError($res));
		}

		return TopupMobileResponse::success([
			'provider_tran' => $res,
			'balance'       => $res['PartnerBalance'],
		]);
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

		$res = $this->api()->topupTelco($request->request_id, $provider, $request->account, $request->amount);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return TopupMobilePostResponse::error($this->getApiResponseError($res));
		}

		return TopupMobilePostResponse::success([
			'provider_tran' => $res,
			'balance'       => $res['PartnerBalance'],
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

		$res = $this->api()->topupPartner($request->request_id, $provider, $request->account, $amount);

		if ( ! $this->isApiResponseSuccess($res))
		{
			return TopupGameResponse::error($this->getApiResponseError($res));
		}

		return TopupGameResponse::success([
			'provider_tran' => $res,
			'balance'       => $res['PartnerBalance'],
		]);
	}

	/**
	 * Lay doi tuong api
	 *
	 * @return VtcGoods
	 */
	protected function api()
	{
		if (is_null($this->api))
		{
		    $this->api = new VtcGoods([
				'partnerCode' => $this->setting('partner_code'),
				'keyDecode'   => $this->setting('key_decode'),
			]);
		}

		return $this->api;
	}

	/**
	 * Kiem tra ket qua tra ve tu api co thanh cong hay khong
	 *
	 * @param array $response
	 * @return bool
	 */
	protected function isApiResponseSuccess(array $response)
	{
		return in_array($response['ResponseCode'], [1, -290]);
	}

	/**
	 * Lay api response error
	 *
	 * @param array $response
	 * @return string
	 */
	protected function getApiResponseError(array $response)
	{
		return array_get($response, 'ResponseCode').': '.array_get($response, 'ResponseMsg');
	}

	/**
	 * Phan tich danh sach cards lay tu api
	 *
	 * @param array $list
	 * @return array
	 */
	protected function parseCardsResponse(array $list)
	{
		$cards = [];

		foreach ($list as $row)
		{
			$cards[] = new Card([
				'code'   => $row['CardCode'],
				'serial' => $row['CardSerial'],
				'expire' => $row['ExpriceDate'],
			]);
		}

		return $cards;
	}

}