<?php namespace Core\Support\Traits;

/**
 * Trait xu ly lay error lang
 */
trait ErrorLangTrait
{
	/**
	 * Bien luu trang thai load error lang
	 *
	 * @var bool
	 */
	protected $error_lang_loaded = false;

	/**
	 * Lay duong dan file error lang
	 *
	 * @return string
	 */
	abstract protected function getErrorLangPath();

	/**
	 * Lay lang error
	 *
	 * @param string $key
	 * @param array  $replace
	 * @return string
	 */
	public function errorLang($key, $replace = [])
	{
		if ( ! $this->error_lang_loaded)
		{
			t('lang')->load($this->getErrorLangPath());

			$this->error_lang_loaded = true;
		}

		$args = func_get_args();

		$args[0] = 'error_'.$args[0];

		return call_user_func_array('lang', $args);
	}

}