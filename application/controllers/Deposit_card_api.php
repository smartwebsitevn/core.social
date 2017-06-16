<?php

use App\Currency\Model\CurrencyModel;
use App\Deposit\Command\DepositCardCommand;
use App\Deposit\Job\DepositCard;
use App\Deposit\Library\CardDeposit;
use App\Deposit\Model\CardTypeModel;
use App\Purse\Model\PurseModel;
use App\Purse\PurseFactory;
use App\User\Model\UserModel;
use App\User\UserFactory;

class Deposit_card_api extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		parent::__construct();
		$user_id = strval($this->uri->rsegment('3'));
		if(!$user_id)
		{
		    redirect_login_return();
		}
		$user = model('user')->get_info_rule(array('username' => $user_id));
		if ( !$user)
		{
		    redirect_login_return();
		}
		
		$this->data['user'] = $user;
		
		t('lang')->load('site/deposit_card_api');
		t('lang')->load('modules/deposit/common');
		t('lang')->load('modules/deposit/deposit_card');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['type']		= array('card_type', 'required|trim|callback__check_type');
		$rules['code']		= array('card_code', 'required|trim|xss_clean');
		$rules['serial']	= array('card_serial', 'required|trim|xss_clean');
		$rules['card']		= array('', 'callback__check_card');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra type
	 */
	public function _check_type($value)
	{
		if ( ! $this->_get_card_type($value))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
			return false;
		}
		
		return TRUE;
	}
	
	/**
	 * Kiem tra thong tin card
	 */
	public function _check_card()
	{
		$ip = t('input')->ip_address();

		// Ip hien tai da bi block
		if (model('ip_block')->check($ip))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_ip_blocked', $ip));
			
			return false;
		}

		// Gui request den cac providers
		$input = $this->_get_input();

		$providers = array_filter([$input['type']->provider, $input['type']->provider_sub]);

		list($status, $result, $provider) = $this->_request_check_card($providers, $input);

		// Xu ly so lan check card
		$this->_handle_count_check($status);

		// Neu card khong hop le
		if ( ! $status)
		{
			$message = array($result);
			
			if ($m = $this->_make_warning_block_ip())
			{
				$message[] = $m;
			}
			
			$this->form_validation->set_message(__FUNCTION__, implode('<br>', $message));
			
			return false;
		}

		$this->data['card'] = array_merge($input, $result, compact('provider'));
		
		return true;
	}

	/**
	 * Gui yeu cau check card
	 *
	 * @param array $providers
	 * @param array $input
	 * @return array [$status, $result, $provider]
	 */
	protected function _request_check_card(array $providers, array $input)
	{
		$status = false;
		$result = null;
		$provider = null;

		foreach ($providers as $provider)
		{
			list($status, $result) = $this->_request_provider_check_card($provider, $input);

			if ($status) break;
		}

		return [$status, $result, $provider];
	}

	/**
	 * Gui yeu cau den provider check card
	 *
	 * @param string $provider
	 * @param array  $input
	 * @return array
	 */
	protected function _request_provider_check_card($provider, array $input)
	{
		$log_id = $this->_log($provider, $input);

		list($status, $result) = $this->_perform_check_card(
			$provider,
			$input['type']->key,
			$input['code'],
			$input['serial']
		);

		$this->_log_result($log_id, $status, $result);

		return [$status, $result];
	}

	/**
	 * Luu log
	 *
	 * @param string $provider
	 * @param array  $input
	 * @return int
	 */
	protected function _log($provider, array $input)
	{
		$log_id = 0;
		
		$user = $this->data['user'];
		model('deposit_card_log')->create([
			'provider' 	=> $provider,
			'type' 		=> $input['type']->key,
			'code' 		=> $input['code'],
			'serial' 	=> $input['serial'],
			'status' 	=> mod('deposit_card_log')->status('pending'),
			'user_id' 	=> $user->id,
			'ip' 		=> t('input')->ip_address(),
		], $log_id);
		
		return $log_id;
	}
	
	/**
	 * Luu log result
	 * 
	 * @param int $log_id
	 * @param bool $api_status
	 * @param string|array $api_result
	 */
	protected function _log_result($log_id, $api_status, $api_result)
	{
		if ($api_status)
		{
			model('deposit_card_log')->update($log_id, [
				'amount' 	=> $api_result['amount'],
				'status' 	=> mod('deposit_card_log')->status('completed'),
				'message' 	=> 'success',
				'result' 	=> json_encode($api_result['data']),
			]);
		}
		else
		{
			model('deposit_card_log')->update($log_id, [
				'status' 	=> mod('deposit_card_log')->status('failed'),
				'message' 	=> (string) $api_result,
			]);
		}
	}
	
	/**
	 * Tao noi dung canh bao block ip
	 * 
	 * @return string
	 */
	protected function _make_warning_block_ip()
	{
		$count_max = $this->_mod()->setting('fail_count_max');
		
		if ( ! $count_max) return;
		
		$max 	= $this->_mod()->setting('fail_count_max');
		$minute = $this->_mod()->setting('fail_block_timeout');
		
		return lang('notice_warning_block_ip', compact('max', 'minute'));
	}
	
	/**
	 * Lay input
	 * 
	 * @return array
	 */
	protected function _get_input()
	{
		$type_id 	= $this->input->post('type');
		$code 		= $this->input->post('code');
		$serial		= $this->input->post('serial');
		
		$type = $this->_get_card_type($type_id);
		
		return compact('type_id', 'type', 'code', 'serial');
	}
	
	/**
	 * Lay thong tin card type
	 * 
	 * @param int $id
	 * @return false|object
	 */
	protected function _get_card_type($id)
	{
		return model('card_type')->get_info_rule(array(
			'id' => $id,
			'status' => 1,
		));
	}
	
	/**
	 * Lay danh sach card type
	 */
	protected function _get_card_types()
	{
		return mod('card_type')->get_list(array('status' => 1));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Home
	 */
	public function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('type', 'code', 'serial');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Reset rules
				$this->form_validation->reset_rules();
				
				// Gan dieu kien cho cac bien
				$params = array('card');
				$this->_set_rules($params);
				
				// Xu ly du lieu
				if ($this->form_validation->run())
				{
					$result = $this->_index_submit();
				}
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		$this->_index_view();
	}

	/**
	 * Xu ly index submit
	 *
	 * @return array
	 */
	protected function _index_submit()
	{
		try
		{
			$purse = $this->_getUserPurseVnd();

			$card = $this->data['card'];

			return [
				'complete' => true,
				'data'     => $this->_depositCard($purse, $card),
				'location' => $this->_url().'?'.http_build_query(array_only(t('input')->post(), ['type'])),
			];
		}
		catch (\Exception $e)
		{
			return [
				'complete' => false,
				'card' => $e->getMessage(),
			];
		}
	}

	/**
	 * Thuc hien nap tien
	 *
	 * @param PurseModel $purse
	 * @param array      $card
	 * @return array
	 */
	protected function _depositCard(PurseModel $purse, array $card)
	{
	    $user = $this->data['user'];
	    $user_group_id = $user->user_group_id;
	    
	    //lay phi theo nhom thanh vien
		$fee = mod('card_type')->get_fee($card['type'], $user_group_id);

		$amount = $card['amount'] * (100 - $fee) * 0.01;

		$card_discount = $this->getCardTypeDiscount($card['type']->key, $card['provider']);
		$card_discount = $card_discount ?: $fee;

		$profit = $fee - $card_discount;

		$command = new DepositCardCommand([
			'purse'    => $purse,
			'amount'   => $amount,
			'fee'      => $fee,
			'card'     => new CardDeposit([
				'type'   => CardTypeModel::find($card['type']->id),
				'code'   => $card['code'],
				'serial' => $card['serial'],
				'amount' => $card['amount'],
				'profit' => $profit,
			]),
			'provider' => $card['provider'],
			'data'     => ['data' => $card['data']],
		]);

		$deposit_card = (new DepositCard($command))->handle();

		set_message(lang('notice_deposit_success', [
			'card_type'   => $card['type']->name,
			'card_amount' => number_format($card['amount']),
			'amount'      => $deposit_card->format('amount'),
		]));

		return [
			'type'              => $card['type']->name,
			'card_amount'       => $card['amount'],
			'amount'            => $amount,
			'_card_amount'      => number_format($card['amount']),
			'_amount'           => $deposit_card->format('amount'),
			'invoice_order_id'  => $deposit_card->invoice_order->id,
			'invoice_order_url' => $deposit_card->invoice_order->url('view'),
		];

		return $deposit_card->invoice_order->url('view');
	}

	/**
	 * Lay discount cua card type tung ung voi nha cung cap
	 *
	 * @param srting $card_type
	 * @param string $provider
	 * @return float
	 */
	protected function getCardTypeDiscount($card_type, $provider)
	{
		$provider_discounts = lib('payment_card')->{$provider}->get_discount() ?: [];

		return array_get($provider_discounts, $card_type); //lấy chiết khấu của loại thẻ từ nhà cung cấp
	}

	/**
	 * Lay purse VND cua user hien tai
	 *
	 * @return UserModel
	 * @throws Exception
	 */
	protected function _getUserPurseVnd()
	{
		$currency = CurrencyModel::findWhere(['code' => 'VND']);

		if ( ! $currency)
		{
			throw new Exception('Currency VND not found');
		}

		$user = $this->data['user'];
		$user = UserFactory::auth()->user($user);

		$purse = PurseFactory::purse()->get($user, $currency);

		if ( ! $purse)
		{
			throw new Exception("Can't get purse of user");
		}

		return $purse;
	}
	
	/**
	 * Xu ly index view
	 */
	protected function _index_view()
	{
		$this->data['types'] = mod('card_type')->get_list(array('status' => 1));
		$this->data['input'] = array_only((array) t('input')->get(), array('type', 'code', 'serial'));
		//$this->_history();
		
		page_info('title', lang('title_deposit_card'));
		
		view('tpl::deposit_card_api/layout', $this->data);
		
	}
	
	/**
	 * Goi den api kiem tra card
	 * 
	 * @param string $type
	 * @param string $code
	 * @param string $serial
	 * @return array
	 */
	protected function _perform_check_card($provider, $type, $code, $serial)
	{
		$api_result = array();
		$api_status = t('payment_card')->$provider->check($type, $code, $serial, $api_result);

		//$api_status = true;
		//$api_result['amount'] = 100000;
    	//$api_result['data'] = $this->input->post();
		
		/* $api_status = false;
		$api_result = 'error'; */

		return [$api_status, $api_result];
	}
	
	/**
	 * Xu ly so lan check card
	 * 
	 * @param bool $api_status
	 */
	protected function _handle_count_check($api_status)
	{
		if ( ! $api_status)
		{
			$this->_handle_count_fail();
		}
		else 
		{
			if (model('ip')->action_count_get('deposit_card_fail'))
			{
				model('ip')->action_count_set('deposit_card_fail', 0);
			}
		}
	}
	
	/**
	 * Xu ly so lan nhap sai card
	 */
	protected function _handle_count_fail()
	{
		$count_max = $this->_mod()->setting('fail_count_max');
		$block_timeout = $this->_mod()->setting('fail_block_timeout') * 60;
		
		if ( ! $count_max) return;
		
		$count = model('ip')->action_count_change('deposit_card_fail', 1);
		
		if ($count >= $count_max)
		{
			$ip = t('input')->ip_address();
			
			model('ip_block')->set($ip, $block_timeout);
			model('ip')->action_count_set('deposit_card_fail', 0);
		}
	}
	
	/**
	 * Tao Lich su
	 */
	protected function _history()
	{
	    $user = $this->data['user'];
	    
		$this->_list([
			'input' => [
				'select' => 'deposit_card.*, tran.created',
				'where' => [
					'tran.user_id' => $user->id,
					'deposit_card.status' => mod('order')->status('completed'),
				],
			],
			'page_size' => 10,
			'display' => false,
		]);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Nap tien bang the cao thu cong
	 */
	public function _offline()
	{
		// Lay thong tin
		$me = $this;
		$params = $this->_offline_get_params();
		
		// Get
		if ($this->input->get('act') == 'get')
		{
			$this->_offline_act_get();
		}
		
		// Tao form
		$form = array();
		
		$form['validation']['params'] = $params;
		$form['validation']['method'] = '_offline_set_rules';
		
		$form['submit'] = function() use ($me, $params)
		{
			$args = array_only($this->input->post(), $params);
			
			return $me->_offline_create_order($args);
		};
		
		$form['form'] = function() use ($me)
		{
			$me->data['providers'] 	= $me->_mod()->setting('offline_providers');
			$me->data['url_get'] 	= current_url().'?act=get';
			
			page_info('breadcrumbs', array(current_url(), lang('title_deposit_card')));
			
			page_info('title', lang('title_deposit_card'));
			
			$me->_display();
		};

		$me->_form($form);
	}
	
	/**
	 * Lay thong tin json
	 */
	protected function _offline_act_get()
	{
		$type 	= $this->input->post('type');
		$amount = currency_handle_input($this->input->post('amount'));
			
		$detail = $this->_offline_get_detail(compact('type', 'amount'));
		foreach (array('amount', 'amount_discount') as $p)
		{
			$detail['_'.$p] = currency_convert_format_amount($detail[$p]);
		}
			
		set_output('json', json_encode($detail));
	}
	
	/**
	 * Lay params
	 * 
	 * @return array
	 */
	protected function _offline_get_params()
	{
		return array('type', 'amount', 'code', 'serial');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _offline_set_rules($params)
	{
		$rules = array();
		$rules['type']		= array('card_type', 'required|trim|callback__offline_check_type');
		$rules['amount']	= array('card_amount', 'required|trim|callback__offline_check_amount');
		$rules['code']		= array('card_code', 'required|trim|xss_clean');
		$rules['serial']	= array('card_serial', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra type
	 */
	public function _offline_check_type($value)
	{
		if ( ! $this->_mod()->get_row('offline_providers', $value))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Kiem tra amount
	 */
	public function _offline_check_amount($value)
	{
		$value = currency_handle_input($value);
		
		if ($value <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Tao thong tin chi tiet order
	 * 
	 * @param array $args
	 * @return array
	 */
	protected function _offline_get_detail(array $args)
	{
		$type = $this->_mod()->get_row('offline_providers', $args['type']);
		
		$discount = (float) data_get($type, 'discount');
		$discount = min(max(0, $discount), 100);
		
		$amount_discount = $args['amount'] * (100 - $discount) * 0.01;
		
		return array_merge($args, compact('type', 'discount', 'amount_discount'));
	}
	
	/**
	 * Tao order
	 * 
	 * @param array $args
	 * @return array
	 */
	protected function _offline_create_order(array $args)
	{
		$user = $this->data['user'];
		
		$args = $this->_offline_get_detail($args);
		
		// Tao tran
		$tran_id = mod('tran')->create(array(
			'type' 		=> 'deposit_card',
			'amount' 	=> $args['amount_discount'],
			'user_id' 	=> $user->id,
			'status' 	=> 'verify',
		));
		
		// Tao deposit_card
		$data = array_only($args, array('amount', 'code', 'serial', 'amount_discount', 'discount'));
		
		$this->_model()->create(array_merge($data, array(
			'id' 		=> $tran_id,
			'type' 		=> $args['type']->name,
			'status' 	=> mod('order')->status('pending'),
		)));
		
		return site_url('tran/view/'.$tran_id);
	}
	
}
