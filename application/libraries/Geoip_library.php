<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * GeoIp Library
 *
 * @author		***
 * @version		2014-01-06
 */

// --------------------------------------------------------------------

/**
 * Autoload class
 */
spl_autoload_register(function($class)
{
	$class = str_replace('\\', '/', $class);
	
	if (preg_match('#^(GeoIp2|MaxMind)/#i', $class))
	{
		require_once dirname(__FILE__).'/geoip/'.$class.'.php';
	}
});

// --------------------------------------------------------------------

/**
 * Library
 */
use GeoIp2\Database\Reader;

class Geoip_library {
	
	// Data hien tai
	protected $_data = '';
	
	// Doi tuong cua Reader
	protected $_reader = NULL;
	
	
	/**
	 * Ham khoi dong
	 * @param array $config		Config
	 */
	public function __construct(array $config = array())
	{
		// Ket noi den data
		$data = (isset($config['data'])) ? $config['data'] : '';
		$this->connect($data);
	}
	
	/**
	 * Ket noi den data
	 * @param string $data	File data
	 */
	public function connect($data = '')
	{
		// Neu data da duoc ket noi roi
		$data = ($data == '') ? 'GeoIP2-Country.mmdb' : $data;
		if ($this->_data == $data && $this->_reader)
		{
			return;
		}
		
		// Ket noi den data
		$file = dirname(__FILE__).'/geoip/Data/'.$data;
		$this->_reader 	= new GeoIp2\Database\Reader($file);
		$this->_data 	= $data;
	}
	
	/**
	 * Lay thong tin country
	 * @param string $ip		IP address
	 * @param string $error		Error output
	 */
	public function country($ip = null, &$error = '')
	{
		$ip = $this->get_ip($ip);
		try {
			return $this->_reader->country($ip);
		}
		catch (Exception $e) {
			$error = $e->getMessage();
			return FALSE;
		}
	}
	
	/**
	 * Lay country code
	 * 
	 * @param string|null $ip
	 * @return string
	 */
	public function country_code($ip = null)
	{
		$country = $this->country($ip);
		
		$country = data_get($country, 'country.isoCode');
		
		return strtoupper($country);
	}
	
	/**
	 * Lay thong tin city
	 * @param string $ip		IP address
	 * @param string $error		Error output
	 */
	public function city($ip = null, &$error = '')
	{
		$ip = $this->get_ip($ip);
		
		try {
			return $this->_reader->city($ip);
		}
		catch (Exception $e) {
			$error = $e->getMessage();
			return FALSE;
		}
	}
	
	/**
	 * Lay ip
	 * 
	 * @param null|string $ip
	 * @return string
	 */
	protected function get_ip($ip)
	{
		return is_null($ip) ? t('input')->ip_address() : $ip;
	}
	
}