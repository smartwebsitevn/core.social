<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Read_captcha_library {

	var $key = NULL;
	var $task_id;
	
	
	/**
	 * Doc captcha
	 * @param string $img_file_name	Duong dan den file captcha
	 */
	function read($img_file_name)
	{
		// Reset task_id
		$this->task_id = -1;
		
		// Read image data of image
		$fp = fopen($img_file_name, "rb");
		if (!$fp) return NULL;
		$file_size = filesize($img_file_name);
		if ($file_size <= 0) return NULL;
		$data = fread($fp, $file_size);
		fclose($fp);
		
		// Use base64 encoding to encode it
		$enc_data = base64_encode($data);
		
		// Check key
		$key = $this->_get_key();
		if (strlen($key) != 40 && strlen($key) != 32)
		{
			return NULL;
		}
		
		// Submit it to server
		$data = $this->_post_data("http://bypasscaptcha.com/upload.php", array(
									"key" 			=> $key,
									"file" 			=> $enc_data,
									"submit" 		=> "Submit",
									"gen_task_id" 	=> 1,
									"base64_code" 	=> 1,
		));
		
		// Get result
		$dict = $this->_get_result($data);
		if (array_key_exists("TaskId", $dict) && array_key_exists("Value", $dict))
		{
			$this->task_id = $dict["TaskId"];
			return $dict["Value"];
		}
		
		return NULL;
	}
	
	/**
	 * Thong bao captcha sau khi doc co dung hay khong
	 * @param bool $is_input_correct
	 */
	function feedback($is_input_correct)
	{
		$this->_post_data("http://bypasscaptcha.com/check_value.php", array(
							"key" 		=> $this->_get_key(),
							"task_id" 	=> $this->task_id,
							"cv" 		=> ($is_input_correct ? 1 : 0),
							"submit" 	=> "Submit",
		));
	}
	
	/**
	 * Lay so lan doc con lai cua key
	 */
	function get_left($key)
	{
		$ret 	= $this->_post_data("http://bypasscaptcha.com/ex_left.php", array("key" => $key));
		$dict 	= $this->_get_result($ret);
		$left	= (isset($dict['Left'])) ? $dict['Left'] : FALSE;
		
		return $left;
	}
	
	/**
	 * Post du lieu doc captcha
	 * @param string 	$url
	 * @param array 	$data
	 */
	private function _post_data($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$ret = curl_exec($ch);
		curl_close($ch);
		
		return $ret;
	}
	
	/**
	 * Loc lay gia tri tu ket qua tra ve cua post_data
	 * @param string $data
	 */
	private function _get_result($data)
	{
		$ret = array();
		
		$lines = explode("\n", $data);
		if ($lines)
		{
			foreach ($lines as $line)
			{
				$x = trim($line);
				if (strlen($x) == 0) continue;
	
				$value = strstr($x, " ");
				$name = "";
				if ($value === FALSE)
				{
					$name = $x;
					$value = "";
				}
				else
				{
					$name = substr($x, 0, strlen($x) - strlen($value));
					$value = trim($value);
				}
				$ret[$name] = $value;
			}
		}
		
		return $ret;
	}
	
	/**
	 * Lay key
	 */
	private function _get_key()
	{
		if ($this->key === NULL)
		{
			$CI =& get_instance();
			$this->key = $CI->setting_model->get('api-read_captcha-key');
		}
		
		return $this->key;
	}
	
}
?>