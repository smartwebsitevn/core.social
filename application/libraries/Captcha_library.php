<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha_library
{
	protected $length = 4;
	protected $width  = 80;
	protected $height = 25;
	
	
	/**
	 * Tao captcha
	 */
	public function create()
	{
		$code = $this->_code_create();
		
		$this->_code_save($code);
		
		$this->_create_image($code);
	}
	
	/**
	 * Kiem tra captcha
	 * 
	 * @param string $value
	 * @return boolean
	 */
	public function check($value)
	{
		$value = strtolower($value);

		$code = $this->_code_get();
		
		return ($value && $value === $code);
	}
	
	/**
	 * Xoa captcha
	 */
	public function del()
	{
		$this->_code_del();
	}
	
	/**
	 * Reset tat ca captcha
	 */
	public function reset()
	{
		$this->del();
	}
	
	/**
	 * Tao captcha url
	 *
	 * @return string
	 */
	public function url()
	{
		return site_url('captcha/four');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tao code random
	 */
	protected function _code_create()
	{
		$code = random_string('alnum', 5);
		
		$code = strtolower($code);
		
		return $code;
	}
	
	/**
	 * Luu code vao session
	 */
	protected function _code_save($value)
	{
		$session_name = $this->_code_get_session_name();
		
		$this->_ci('session')->set_userdata($session_name, $value);
	}
	
	/**
	 * Lay code tu session
	 */
	protected function _code_get()
	{
		$session_name = $this->_code_get_session_name();
		
		return $this->_ci('session')->userdata($session_name);
	}
	
	/**
	 * Xoa code khoi session
	 */
	protected function _code_del()
	{
		$session_name = $this->_code_get_session_name();

		$this->_ci('session')->unset_userdata($session_name);
	}
	
	/**
	 * Lay session name cua code
	 */
	protected function _code_get_session_name()
	{
		return 'captcha';
	}

	// --------------------------------------------------------------------
	
	/**
	 * Tao image cho captcha
	 */
	protected function _create_image($code)
	{
		// Config
        $size	= 14;
        $angle	= 0;
        $font 	= APPPATH.'files/font/captcha.ttf';
        $width 	= $this->width; 
        $height = $this->height;
        
		// Create image
		if (function_exists('imagecreatetruecolor'))
		{
			$image = imagecreatetruecolor($width, $height);
		}
		else
		{
			$image = imagecreate($width, $height);
		}
		
		// Assign colors
        $bg_color 		= imagecolorallocate($image, 238, 238, 238);
		$border_color	= imagecolorallocate ($image, 153, 102, 102);
		$grid_color 	= imagecolorallocate($image, 255, 182, 182);
        $text_color 	= imagecolorallocate($image, 0, 0, 0);
  		
        // Create background
        imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);
     	
        // Create border
        //imagerectangle($image, 0, 0, $width-1, $height-1, $border_color);
       	
        // Create the spiral pattern
		$length		= strlen($code);
		$x_axis		= rand(6, (360/$length)-16);
		$y_axis 	= ($angle >= 0 ) ? rand($height, $width) : rand(6, $height);
		$theta		= 1;
		$thetac		= 7;
		$radius		= 16;
		$circles	= 20;
		$points		= 32;
		
		for ($i = 0; $i < ($circles * $points) - 1; $i++)
		{
			$theta = $theta + $thetac;
			$rad = $radius * ($i / $points );
			$x = ($rad * cos($theta)) + $x_axis;
			$y = ($rad * sin($theta)) + $y_axis;
			$theta = $theta + $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1 = ($rad1 * cos($theta)) + $x_axis;
			$y1 = ($rad1 * sin($theta )) + $y_axis;
			//imageline($image, $x, $y, $x1, $y1, $grid_color);
			$theta = $theta - $thetac;
		}
        
   		// Write the text
        $x = intval(($width - (strlen($code) * 10)) / 2);
        $y = intval(($height + 12) / 2);
	    if (function_exists('imagettftext'))
        {
			imagettftext($image, $size, $angle, $x, $y, $text_color, $font, $code);
        }
        else 
        {
  			imagestring($image, $size, $x, 5, $code, $text_color);
        }
  		
		// Generate the image
		header('Content-type: image/jpeg');
		imagejpeg($image, NULL, 100);
		imagedestroy($image);	
		exit;
	}
	
	/**
	 * Lay doi tuong cua CI
	 * 
	 * @param string $key
	 * @return mixed
	 */
	protected function _ci($key = null)
	{
		$CI = get_instance();
		
		return is_null($key) ? $CI : $CI->$key;
	}
	
}