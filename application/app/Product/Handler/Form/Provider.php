<?php namespace App\Product\Handler\Form;

use Core\Base\FormHandler;
use App\Product\ProductFactory as ProductFactory;
use App\Product\Model\ProviderModel as ProviderModel;
use App\Product\Library\ProviderFactory;

class Provider extends FormHandler
{
	/**
	 * Doi tuong ProviderModel
	 *
	 * @var ProviderModel
	 */
	protected $provider;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param ProviderModel $provider
	 * @param array         $input
	 */
	public function __construct(ProviderModel $provider, array $input = null)
	{
		$this->provider = $provider;

		parent::__construct($input);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['name', 'desc', 'status', 'setting'];
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	protected function rules()
	{
		$rules = [
			'name' => 'required',
			'desc' => 'required',
		];

		$setting_rules = $this->providerInstance()->settingRules('setting');

		return array_merge($rules, $setting_rules);
	}

	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation()
	{
		$this->setValidationRules($rules = $this->rules());

		return array_keys($rules);
	}

	/**
	 * Kiem tra setting
	 *
	 * @param null $error
	 * @return bool
	 */
	protected function validateSetting(&$error = null)
	{
		$setting = $this->input('setting', []);

		if ( ! $this->providerInstance()->validateSetting($setting, $error))
		{
			$error = $error ?: lang('notice_value_invalid', lang('setting'));

			return false;
		}

		return true;
	}

	/**
	 * Xu ly form khi du lieu hop le
	 */
	public function submit()
	{
		if ( ! $this->validateSetting($error))
		{
			return [
				'complete' => false,
				'setting'  => $error,
			];
		}

		$provider = $this->provider;

		$data = $this->inputOnly($this->params());

		if ($provider->getKey())
		{
			ProductFactory::makeService('ProviderService')->edit($provider, $data);
		}
		else
		{
			ProductFactory::makeService('ProviderService')->install($provider->key, $data);
		}
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		$provider = $this->provider;

		$title = $provider->getKey() ? 'edit' : 'install';
		$title = lang('title_provider_'.$title);

		$setting_config = $this->providerInstance()->settingFormConfig('setting', $provider->setting);

		$setting_form = $this->providerInstance()->settingForm($setting_config);

		return compact('provider', 'title', 'setting_config', 'setting_form');
	}

	/**
	 * Lay doi tuong ProviderFactory
	 *
	 * @return ProviderFactory
	 */
	protected function providerInstance()
	{
		return ProductFactory::provider($this->provider->key);
	}

}