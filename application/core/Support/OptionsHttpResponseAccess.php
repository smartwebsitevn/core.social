<?php namespace Core\Support;

class OptionsHttpResponseAccess extends OptionsAccess
{
	protected $config = [

		'redirect' => [
			'cast' => 'string',
		],

		'view' => [
			'cast' => 'string',
		],

		'data' => [
			'default' => [],
			'allowed_types' => 'array',
		],

		'layout' => [
			'default' => false,
		],

		'content' => [
			'cast' => 'string',
		],

	];

	/**
	 * Tao response redirect
	 *
	 * @param string $url
	 * @return static
	 */
	public static function redirect($url)
	{
		return new static(['redirect' => $url]);
	}

	/**
	 * Tao response hien thi view
	 *
	 * @param string $view
	 * @param array  $data
	 * @return static
	 */
	public static function view($view, array $data = [])
	{
		return new static(compact('view', 'data'));
	}

	/**
	 * Tao response hien thi tpl
	 *
	 * @param string      $view
	 * @param array       $data
	 * @param bool|string $layout
	 * @return static
	 */
	public static function tpl($view, array $data = [], $layout = true)
	{
		return new static(compact('view', 'data', 'layout'));
	}

	/**
	 * Tao response hien thi html content
	 *
	 * @param string $content
	 * @return static
	 */
	public static function content($content)
	{
		return new static(compact('content'));
	}

	/**
	 * Gui response
	 */
	public function send()
	{
		if ($url = $this->get('redirect'))
		{
			redirect($url);
		}

		if ($this->get('view'))
		{
			return $this->sendResponseView();
		}

		echo $this->get('content');
	}

	/**
	 * Gui response dang view
	 *
	 * @return mixed
	 */
	protected function sendResponseView()
	{
		$view = $this->get('view');

		$data = $this->get('data');

		$layout = $this->get('layout');

		$layout_value = $layout === true ? '' : $layout;

		return $layout
			? t('tpl')->display($view, $layout_value, $data)
			: view($view, $data);
	}

}