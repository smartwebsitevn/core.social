<?php

use TF\Container\Container;

/**
 * App Core Class
 *
 * Class xu ly cac lop cua app
 *
 * @author		***
 * @version		2014-11-19
 */
class MY_App extends Container
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->app_register();
	}

	/**
	 * Dang ki cac lop xu ly cua app
	 */
	protected function app_register()
	{
		// Debug
		if (defined('ENVIRONMENT') && ENVIRONMENT == 'development')
		{
			$whoops = new \Whoops\Run;
			$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
			$whoops->register();
		}

		// Autoload
		$autoload = require APPPATH.'../vendor/autoload.php';
		$autoload->set('app', APPPATH);
		$this->instance('autoload', $autoload);

		// Event
		$this->bindShared('event', function($app)
		{
			return new TF\Events\Dispatcher($app);
		});

		// Filesystem
		$this->bindShared('file', function($app)
		{
			return new TF\Filesystem\Filesystem();
		});

		// Html
		$this->bindShared('html', function($app)
		{
			return new TF\Html\Html();
		});

		// Model
		$this->bindShared('model', function($app)
		{
			return load_class('Models', 'core');
		});

		// Library
		$this->bindShared('lib', function($app)
		{
			return load_class('Lib', 'core');
		});

		// Template
		$this->bindShared('tpl', function($app)
		{
			return load_class('Tpl', 'core');
		});

		// Macro
		$this->bindShared('macro', function($app)
		{
			return load_class('Macro', 'core');
		});

		// View
		$this->bindShared('view', function($app)
		{
			return load_class('View', 'core');
		});

		// Extra
		foreach (array('pre_handle', 'mod', 'widget', 'payment', 'card', 'payment_card', 'sms_otp', 'sms_gateway') as $p)
		{
			$this->bindShared($p, function ($app) use ($p)
			{
				t('load')->library($p . '_library', NULL, $p);

				return t($p);
			});
		}
	}

	/**
	 * Bat dau app
	 */
	public function app_boot()
	{
		// License
		$this->boot_license();

		// Module
		t('load')->library('module_library', NULL, 'module');

		// Template
		t('tpl')->boot();

		// Pre handle
		t('pre_handle')->system->boot();
	}

	/**
	 * Boot license
	 */
	protected function boot_license()
	{
		if ( ! method_exists(t('pre_handle')->license, 'boot'))
		{
			show_error('Invalid license');
		}

		t('pre_handle')->license->boot();
	}

	/**
	 * Tao class name cho cac class trong app
	 *
	 * @param string $class
	 * @return string
	 */
	public function make_class_name($class)
	{
		$class = 'app/' . $class;
		$class = str_replace('/', '\\', $class);
		$class = strtolower($class);

		return $class;
	}

}
