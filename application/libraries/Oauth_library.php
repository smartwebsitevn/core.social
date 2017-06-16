<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oauth_library
{
	/**
	 * Service factory instance
	 * 
	 * @var \OAuth\ServiceFactory
	 */
	protected $serviceFactory;

	/**
	 * Config
	 *
	 * @var array()
	 */
	protected $config = array();
	
	
	/**
	 * Construct
	 */
	public function __construct()
	{
		// Add namespace
		require_once __DIR__.'/Autoload.php';
		Autoload::getInstance()->addNamespace('OAuth', __DIR__.'/OAuth');
		
		// Create service factory
		$this->serviceFactory = new \OAuth\ServiceFactory();
		
		// Load config
		$this->loadConfig();
	}
	
	/**
	 * Load config
	 */
	protected function loadConfig()
	{
		$config = require APPPATH.'config/oauth.php';
		
		$this->setConfig($config);
	}
	
	/**
	 * Set config
	 * 
	 * @param array $config
	 */
	public function setConfig(array $config)
	{
		$this->config = $config;
	}
	
	/**
	 * Get config
	 * 
	 * @return array()
	 */
	public function getConfig()
	{
		return $this->config;
	}
	
	/**
	 * Create service
	 * 
	 * @param string $service
	 * @param string $url
	 * @param array  $scope
	 * @return \OAuth\Common\Service\ServiceInterface
	 */
	public function consumer($service, $url = null, $scope = null)
	{
		$url = $url ?: current_url();
		
		$scope = $scope ?: $this->getServiceConfig($service, 'scope');
		
		$credentials = $this->createCredentials($service, $url);
		
		$storage = $this->createStorage($this->config['storage']);
		
		return $this->serviceFactory->createService($service, $credentials, $storage, $scope);
	}
	
	/**
	 * Get config of a service
	 * 
	 * @param string $service
	 * @param string $param
	 * @return mixed
	 */
	protected function getServiceConfig($service, $param = null)
	{
		$config = $this->config['consumers'][$service];
		
		return (is_null($param)) ? $config : $config[$param];
	}
	
	/**
	 * Create storage instance
	 * 
	 * @param string $storage
	 * @return \OAuth\Common\Storage\TokenStorageInterface
	 */
	protected function createStorage($storage)
	{
		$class = "\\OAuth\\Common\\Storage\\{$storage}";
		
		return new $class();
	}
	
	/**
	 * Create Credentials of a service
	 * 
	 * @param string $service
	 * @param string $url
	 * @return \OAuth\Common\Consumer\Credentials
	 */
	protected function createCredentials($service, $url)
	{
		$config = $this->getServiceConfig($service);
		
		return new \OAuth\Common\Consumer\Credentials(
			$config['client_id'],
			$config['client_secret'],
			$url
		);
	}
	
}
