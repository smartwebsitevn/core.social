<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	/**
	 * Gan dieu kien cho cac bien
	 * @param array $params	Danh sach bien
	 * @param array $rules	Danh sach dieu kien cua cac bien
	 */
	public function set_rules_params($params, array $rules)
	{
		$params = ( ! is_array($params)) ? array($params) : $params;

		foreach ($params as $param)
		{
			if ( ! isset($rules[$param]))
			{
				continue;
			}

			$rule = $rules[$param];
			$rule[0] = ($rule[0] != '') ? 'lang:'.$rule[0] : '';
			$this->set_rules($param, $rule[0], $rule[1]);
		}
		return $this;
	}
	
	/**
	 * Reset dieu kien cua cac bien
	 */
	public function reset_rules()
	{
		$this->_field_data = array();
		
		return $this;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Custom validation
	 * 
	 * @param mixed  $value
	 * @param string $callback
	 * @return bool
	 */
	public function custom($value, $callback)
	{
		$error = null;
		
		$result = $this->_call_custom_callback($callback, array($value, &$error));
		
		if ( ! $result)
		{
			$this->set_message(__FUNCTION__, $error);
		}
		
		return $result;
	}
	
	/**
	 * Goi custom callback
	 * 
	 * @param string $input
	 * @param array  $args
	 * @return bool
	 */
	protected function _call_custom_callback($input, array $args = array())
	{
		list($callback, $arg) = $this->_parse_custom_callback($input);
		
		$callback = $this->_make_custom_callback($callback);
		
		$args[] = $arg;
		
		return call_user_func_array($callback, $args);
	}
	
	/**
	 * Phan tich custom callback
	 * 
	 * @param string $input
	 * @return array
	 */
	protected function _parse_custom_callback($input)
	{
		$input = explode(':', $input, 2);
		
		$callback = array_get($input, 0);
		
		$arg = array_get($input, 1);
		
		return array($callback, $arg);
	}
	
	/**
	 * Tao custom callback
	 * 
	 * @param string $callback
	 * @return array|mixed
	 */
	protected function _make_custom_callback($callback)
	{
		$callback = explode('@', $callback, 2);
		
		$object = array_get($callback, 0);
		$method = array_get($callback, 1);
		
		$object = $this->_make_custom_object($object);
		
		return $method ? array($object, $method) : $object;
	}
	
	/**
	 * Tao custom object
	 * 
	 * @param string $segments
	 * @return mixed
	 */
	protected function _make_custom_object($segments)
	{
		$segments = explode('.', $segments);
		
		$object = get_instance();
		foreach ($segments as $segment)
		{
			$object = $object->$segment;
		}
		
		return $object;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Captcha validation
	 * 
	 * @param string $value
	 * @param string $type
	 * @return boolean
	 */
	public function captcha($value, $type = 'four')
	{

		if(config('captcha_type', 'main') =='google'){
			if ( ! lib('captcha_google')->check())
			{
				$this->set_message(__FUNCTION__, lang('form_validation_required'));

				return false;
			}
		}
		else{
			if ( ! lib('captcha')->check($value, $type))
			{
				$this->set_message(__FUNCTION__, lang('notice_value_incorrect'));

				return false;
			}
		}
		return true;
	}

	/**
	 * is_unique validate (unique:table,column,except,idColumn)
	 *
	 * @param string $value
	 * @param string $args
	 * @return bool
	 */
	public function is_unique($value, $args)
	{
		$args = str_getcsv($args);

		list($table, $column, $except, $id_column) = array_pad($args, 4, null);

		$where = [$table.'.'.$column => $value];

		if (count($args) > 2)
		{
			$id_column = is_null($id_column) ? 'id' : $id_column;

			$where[$table.'.'.$id_column.' !='] = $except;
		}

		return ! $this->CI->db->limit(1)->get_where($table, $where)->num_rows();
	}
	public function filter_html($value)
	{
		if(preg_match('#(?<=<)\w+(?=[^<]*?>)#', $value)){
			return FALSE;
		}
		return TRUE;
	}
}