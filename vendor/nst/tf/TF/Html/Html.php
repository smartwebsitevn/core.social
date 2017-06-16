<?php namespace TF\Html;

class Html
{
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Call method
	 */
	public function __call($method, $args = array())
	{
		// method($attr = array()) -> <link rel="shortcut icon" ... />
		if (in_array($method, array('link')))
		{
			$attr = (isset($args[0])) ? $args[0] : array();
			
			return $this->e($method, $attr);
		}
		
		// method($attr = array()) -> <iframe src="..."></iframe>
		elseif (in_array($method, array('iframe')))
		{
			$attr = (isset($args[0])) ? $args[0] : array();
			
			return $this->e($method, '', $attr);
		}
		
		// method($title, $attr = array()) -> <h1>Title</h1>
		elseif (in_array($method, array('b', 'button', 'font', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'i', 'li', 'p', 'span', 'strong', 'u')))
		{
			$title 	= (isset($args[0])) ? $this->he($args[0]) : '';
			$attr 	= (isset($args[1])) ? $args[1] : array();
			
			return $this->e($method, $title, $attr);
		}
		
		// method($content, $attr = array()) -> <div>Content</div>
		elseif (in_array($method, array('div')))
		{
			$content = (isset($args[0])) ? $args[0] : '';
			$attr 	 = (isset($args[1])) ? $args[1] : array();
			
			return $this->e($method, $content, $attr);
		}
	}
	
	/**
	 * Generate a HTML element
	 * 
	 * @param mixed $e
	 * 		e('div') -> <div>
	 * 		e('div', array('class' => 'abc')) -> <div class="abc">
	 * 		e('div', 'content') -> <div>content</div>
	 * 		e('div', 'content', array('class' => 'abc')) -> <div class="abc">content</div>
	 */
	public function e($e)
	{
		$e = strtolower($e);
		$_end = (in_array($e, array('br', 'hr', 'meta', 'link', 'base', 'img', 'embed', 'param', 'area', 'col', 'input'))) ? ' />' : '>';
		$num_args = func_num_args();
		
		if ($num_args == 3) // e('div', 'content', array('class' => 'abc')) -> <div class="abc">content</div>
		{
			$content 	= func_get_arg(1);
			$attr 		= func_get_arg(2);
			
			return '<' . $e . $this->attr($attr) . $_end . $content . '</' . $e . '>';
		}
		elseif ($num_args == 2)
		{
			$arg2 = func_get_arg(1);
			
			if (is_array($arg2)) // e('div', array('class' => 'abc')) -> <div class="abc">
			{
				return '<' . $e . $this->attr($arg2) . $_end;
			}
			else // e('div', 'content') -> <div>content</div>
			{
				return '<' . $e . $_end . $arg2 . '</' . $e . '>';
			}
		}
		else // e('div') -> <div>
		{
			return '<' . $e . $_end;
		}
	}
	
	/**
	 * Close a HTML element
	 * 
	 * @param string $e
	 */
	public function end($e)
	{
		$e = strtolower($e);
		
		return '</' . $e . '>';
	}
	
	
/*
 * ------------------------------------------------------
 *  HTML elements
 * ------------------------------------------------------
 */
	/**
	 * Generate Form open element (method POST default)
	 * 	form($action)
	 * 	form($action, $method)
	 * 	form($action, $attr)
	 *
	 * @param string $action
	 * @param mixed  $attr
	 */
	public function form($action, $attr = array())
	{
		$attr = ( ! is_array($attr)) ? array('method' => $attr) : $attr; // form($action, $method)
		$attr = array_merge(array('method' => 'post', 'accept-charset' => 'UTF-8'), $attr);
		$attr['action'] = $action;
		
		// Form upload file
		if ( ! isset($attr['enctype']) && isset($attr['file']))
		{
			$attr['enctype'] = 'multipart/form-data';
			unset($attr['file']);
		}
		
		return $this->e('form', $attr);
	}
	
	/**
	 * Generate Label element
	 * 
	 * @param string $title
	 * @param string $for
	 * @param array  $attr
	 */
	public function label($title, $for = '', $attr = array())
	{
		if ($for != '')
		{
			$attr['for'] = $for;
		}
		
		return $this->e('label', $this->he($title), $attr);
	}
	
	/**
	 * Generate Input element (type Text default)
	 * 	input($name)
	 * 	input($name, $value)
	 * 	input($name, $value, $type)
	 * 	input($name, $value, $attr)
	 * 
	 * @param string $name
	 * @param string $value
	 * @param mixed  $attr
	 */
	public function input($name, $value = NULL, $attr = array())
	{
		$attr = ( ! is_array($attr)) ? array('type' => $attr) : $attr; // input($name, $value, $type)
		$attr = array_merge(array('type' => 'text'), $attr);
		$attr['name'] 	= $name;
		$attr['value'] 	= $value;
		
		return $this->e('input', $attr);
	}
	
	/**
	 * Generate Input Password element
	 */
	public function password($name, $value = NULL, $attr = array())
	{
		$attr['type'] = 'password';
		
		return $this->input($name, $value, $attr);
	}
	
	/**
	 * Generate Input File element
	 */
	public function file($name, $attr = array())
	{
		$attr['type'] = 'file';
		
		return $this->input($name, NULL, $attr);
	}
	
	/**
	 * Generate Input Password element
	 */
	public function hidden($name, $value = NULL, $attr = array())
	{
		$attr['type'] = 'hidden';
		
		return $this->input($name, $value, $attr);
	}
	
	/**
	 * Generate Input Submit element
	 * 	submit($value)
	 * 	submit($value, $name)
	 * 	submit($value, $attr)
	 */
	public function submit($value = NULL, $attr = array())
	{
		$attr = ( ! is_array($attr)) ? array('name' => $attr) : $attr; // submit($value, $name)
		$attr['type'] 	= 'submit';
		$attr['value'] 	= $value;
		
		return $this->e('input', $attr);
	}
	
	/**
	 * Generate Input Submit element
	 * 	submit($value)
	 * 	submit($value, $name)
	 * 	submit($value, $attr)
	 */
	public function reset($value = NULL, $attr = array())
	{
		$attr['type'] 	= 'reset';
		$attr['value'] 	= $value;
		
		return $this->e('input', $attr);
	}
	
	/**
	 * Generate Checkbox element
	 */
	public function checkbox($name, $value = 1, $checked = FALSE, $attr = array())
	{
		$attr['type'] = 'checkbox';
		if ($checked)
		{
			$attr['checked'] = 'checked';
		}
		
		return $this->input($name, $value, $attr);
	}
	
	/**
	 * Generate Radio element
	 */
	public function radio($name, $value = NULL, $checked = FALSE, $attr = array())
	{
		$attr['type'] = 'radio';
		if ($checked)
		{
			$attr['checked'] = 'checked';
		}
		
		return $this->input($name, $value, $attr);
	}
	
	/**
	 * Generate Textarea element
	 * 	textarea($name)
	 * 	textarea($name, $value)
	 * 	textarea($name, $value, $size) => $size = '5x50' (rows=5, cols=50)
	 * 	textarea($name, $value, $attr)
	 * 
	 * @param string $name
	 * @param string $value
	 * @param array  $attr
	 */
	public function textarea($name, $value = '', $attr = array())
	{
		$attr = ( ! is_array($attr)) ? array('size' => $attr) : $attr; // textarea($name, $value, $size)
		$attr = array_merge(array('size' => '5x50'), $attr);
		$attr['name'] = $name;
		
		// Handle size
		if (isset($attr['size']))
		{
			$size = explode('x', $attr['size']);
			$attr['rows'] = $size[0];
			$attr['cols'] = isset($size[1]) ? $size[1] : NULL;
			unset($attr['size']);
		}
		
		return $this->e('textarea', $this->he($value), $attr);
	}
	
	/**
	 * Generate Select element
	 * 
	 * @param string $name
	 * @param array  $options
	 * @param mixed  $value
	 * @param array  $attr
	 */
	public function select($name, $options = array(), $value = NULL, $attr = array())
	{
		$attr['name'] = $name;
		if (isset($attr['multi']))
		{
			$attr['multiple'] = 'multiple';
			unset($attr['multi']);
		}
		
		$html = array();
		foreach ($options as $v => $t)
		{
			if (is_array($t))
			{
				$html[] = $this->optgroup($v, $t, $value);
			}
			else 
			{
				$html[] = $this->option($t, $v, $this->_is_selected($v, $value));
			}
		}
		
		return $this->e('select', implode('', $html), $attr);
	}
	
	/**
	 * Generate Optgroup element
	 * 
	 * @param string $label
	 * @param array  $options
	 * @param mixed  $value
	 * @param array  $attr
	 */
	public function optgroup($label, $options = array(), $value = NULL, $attr = array())
	{
		$attr['label'] = $label;
		
		return $this->e('optgroup', $this->options($options, $value), $attr);
	}
	
	/**
	 * Generate Option element
	 * 
	 * @param string $title
	 * @param string $value
	 * @param bool   $selected
	 * @param array  $attr
	 */
	public function option($title, $value = NULL, $selected = FALSE, $attr = array())
	{
		$attr['value'] = $value;
		if ($selected)
		{
			$attr['selected'] = 'selected';
		}
		
		return $this->e('option', $this->he($title), $attr);
	}
	
	/**
	 * Generate List Option element
	 * 
	 * @param array $options
	 * @param mixed $value
	 */
	public function options($options, $value = NULL)
	{
		$html = array();
		foreach ($options as $v => $t)
		{
			$html[] = $this->option($t, $v, $this->_is_selected($v, $value));
		}
		
		return implode('', $html);
	}
	
	/**
	 * Check current value
	 */
	protected function _is_selected($value, $selected)
	{
		if (is_array($selected))
		{
			return in_array($value, $selected) ? TRUE : FALSE;
		}
		
		return ((string) $value == (string) $selected) ? TRUE : FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Generate A element
	 *
	 * @param  string  $url
	 * @param  string  $title
	 * @param  array   $attr
	 * @return string
	 */
	public function a($url, $title = NULL, $attr = array())
	{
		if (is_null($title) || $title === FALSE) $title = $url;
		
		$attr['href'] = $url;
		
		return $this->e('a', $this->he($title), $attr);
	}
	
	/**
	 * Generate Img element
	 *
	 * @param  string  $url
	 * @param  string  $alt
	 * @param  array   $attr
	 * @return string
	 */
	public function img($url, $alt = NULL, $attr = array())
	{
		$attr['src'] = $url;
		$attr['alt'] = $alt;
		
		return $this->e('img', $attr);
	}
	
	/**
	 * Generate Meta element
	 * 
	 * @param string $name
	 * @param string $content
	 * @param string $type		'name' || 'equiv'
	 */
	public function meta($name, $content, $type = 'name')
	{
		$type = ($type == 'equiv') ? 'http-equiv' : $type;
		
		$attr = array();
		$attr[$type] = $name;
		$attr['content'] = $content;
		
		return $this->e('meta', $attr);
	}
	
	/**
	 * Generate a link to a JavaScript file.
	 *
	 * @param  string  $url
	 * @param  array   $attr
	 * @return string
	 */
	public function script($url, $attr = array())
	{
		$attr = array_merge(array('type' => 'text/javascript'), $attr);
		$attr['src'] = $url;
		
		return $this->e('script', '', $attr).PHP_EOL;
	}
	
	/**
	 * Generate a link to a CSS file.
	 *
	 * @param  string  $url
	 * @param  array   $attr
	 * @return string
	 */
	public function style($url, $attr = array())
	{
		$attr = array_merge(array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'), $attr);
		$attr['href'] = $url;
		
		return $this->e('link', $attr).PHP_EOL;
	}
	
	/**
	 * Generate BR element
	 * 
	 * @param 	int 	$num
	 * @return 	string
	 */
	public function br($num = 1)
	{
		return str_repeat($this->e('br'), $num);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Generate an ordered list of items.
	 *
	 * @param  array   $list
	 * @param  array   $attr
	 * @return string
	 */
	public function ol($list, $attr = array())
	{
		return $this->_listing('ol', $list, $attr);
	}

	/**
	 * Generate an un-ordered list of items.
	 *
	 * @param  array   $list
	 * @param  array   $attr
	 * @return string
	 */
	public function ul($list, $attr = array())
	{
		return $this->_listing('ul', $list, $attr);
	}

	/**
	 * Create a listing HTML element.
	 *
	 * @param  string  $type
	 * @param  array   $list
	 * @param  array   $attr
	 * @return string
	 */
	protected function _listing($type, $list, $attr = array())
	{
		$html = '';

		if (count($list) == 0) return $html;

		// Essentially we will just spin through the list and build the list of the HTML
		// elements from the array. We will also handled nested lists in case that is
		// present in the array. Then we will build out the final listing elements.
		foreach ($list as $key => $value)
		{
			$html .= $this->_listing_element($key, $type, $value);
		}

		$attr = $this->attr($attr);

		return "<{$type}{$attr}>{$html}</{$type}>";
	}

	/**
	 * Create the HTML for a listing element.
	 *
	 * @param  mixed    $key
	 * @param  string  $type
	 * @param  string  $value
	 * @return string
	 */
	protected function _listing_element($key, $type, $value)
	{
		if (is_array($value))
		{
			return $this->_listing_nested($key, $type, $value);
		}
		else
		{
			return '<li>'.$this->he($value).'</li>';
		}
	}

	/**
	 * Create the HTML for a nested listing attribute.
	 *
	 * @param  mixed    $key
	 * @param  string  $type
	 * @param  string  $value
	 * @return string
	 */
	protected function _listing_nested($key, $type, $value)
	{
		if (is_int($key))
		{
			return $this->_listing($type, $value);
		}
		else
		{
			return '<li>'.$key.$this->_listing($type, $value).'</li>';
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Generate Table element
	 * 
	 * @param array $list
	 * @param array $attr
	 */
	public function table(array $list, $attr = array())
	{
		// Get attr global of tr
		$attr_tr = $this->_attr_get_sub($attr, array('tr', 'td'));
		$attr_tr = array_merge($attr_tr['tr'], array('attr_td' => $attr_tr['td']));
		$attr_tr['he'] = $this->_attr_get_param($attr, 'he', TRUE, TRUE);
		
		// Var function create list tr
		$create_trs = function($list, $attr)
		{
			$html = array();
			foreach ($list as $tds)
			{
				$html[] = $this->tr($tds, $attr);
			}
			
			return implode('', $html);
		};
		
		// Create table
		$html = array();
		if (isset($list['thead']) || isset($list['tbody']) || isset($list['tfoot']))
		{
			foreach ($list as $p => $trs)
			{
				$attr_tr['sub'] = ($p == 'thead') ? 'th' : 'td';
				$html[] = $this->e($p, $create_trs($trs, $attr_tr));
			}
			
			$html = implode('', $html);
		}
		else 
		{
			$html = $create_trs($list, $attr_tr);
		}
		
		return $this->e('table', $html, $attr);
	}
	
	/**
	 * Generate Tr element
	 * 
	 * @param array $tds
	 * @param array $attr
	 */
	public function tr(array $tds, $attr = array())
	{
		// Get input
		$sub	= $this->_attr_get_param($attr, 'sub', 'td', TRUE);
		$he		= $this->_attr_get_param($attr, 'he', TRUE, TRUE);
		$attr_td = $this->_attr_get_sub($attr, 'td');
		
		// Create html
		$html = array();
		foreach($tds as $td)
		{
			$td = ( ! is_array($td)) ? array($td, array()) : $td;
			$td[0] = ($he) ? $this->he($td[0]) : $td[0];
			$td[1] = array_merge($attr_td, $td[1]);
			
			$html[] = $this->e($sub, $td[0], $td[1]);
		}
		
		return $this->e('tr', implode('', $html), $attr);
	}
	
	
/*
 * ------------------------------------------------------
 *  Extra support
 * ------------------------------------------------------
 */
	/**
	 * Generates non-breaking space entities based on number supplied
	 * 
	 * @param 	int		$num
	 * @return 	string
	 */
	public function nbs($num = 1)
	{
		return str_repeat("&nbsp;", $num);
	}
	
	/**
	 * Generate a HTML link to an email address.
	 *
	 * @param  string  $email
	 * @param  string  $title
	 * @param  array   $attr
	 * @return string
	 */
	public function mailto($email, $title = NULL, $attr = array())
	{
		$email = $this->encode_email($email);
		$title = $title ?: $email;
		$email = $this->obfuscate('mailto:') . $email;
		
		$attr['href'] = $email;
		
		return $this->e('a', $this->he($title), $attr);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Build an HTML attribute string from an array.
	 *
	 * @param  array  $attr
	 * @return string
	 */
	public function attr($attr)
	{
		$html = array();

		// For numeric keys we will assume that the key and the value are the same
		// as this will convert HTML attributes such as "required" to a correct
		// form like required="required" instead of using incorrect numerics.
		foreach ((array) $attr as $key => $value)
		{
			$element = $this->_attr_element($key, $value);

			if ( ! is_null($element)) $html[] = $element;
		}

		return count($html) > 0 ? ' '.implode(' ', $html) : '';
	}

	/**
	 * Build a single attribute element.
	 *
	 * @param  string  $key
	 * @param  string  $value
	 * @return string
	 */
	protected function _attr_element($key, $value)
	{
		if (is_numeric($key)) $key = $value;
		
		if ( ! is_null($value)) return $key.'="'.$this->he($value).'"';
	}
	
	/**
	 * Get attr sub
	 * 
	 * @param array  $attr
	 * @param string $sub
	 * @return mixed
	 */
	protected function _attr_get_sub(array &$attr, $sub)
	{
		// Get of list
		if (is_array($sub))
		{
			$result = array();
			foreach ($sub as $s)
			{
				$result[$s] = $this->_attr_get_sub($attr, $s);
			}
		
			return $result;
		}
		
		return $this->_attr_get_param($attr, 'attr_'.$sub, array(), TRUE);
	}
	
	/**
	 * Get value of param in attr
	 * 
	 * @param array  $attr
	 * @param string $param
	 * @param mixed  $default
	 * @param bool   $unset
	 * @return mixed
	 */
	protected function _attr_get_param(array &$attr, $param, $default = array(), $unset = FALSE)
	{
		// Get of list
		if (is_array($param))
		{
			$result = array();
			foreach ($param as $p)
			{
				$result[$p] = $this->_attr_get_param($attr, $p, $default, $unset);
			}
		
			return $result;
		}
		
		$value = $default;
		if (isset($attr[$param]))
		{
			$value = $attr[$param];
			
			if ($unset)
			{
				unset($attr[$param]);
			}
		}
		
		return $value;
	}
	
	/**
	 * Obfuscate a string to prevent spam-bots from sniffing it.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function obfuscate($value)
	{
		$safe = '';

		foreach (str_split($value) as $letter)
		{
			if (ord($letter) > 128) return $letter;

			// To properly obfuscate the value, we will randomly convert each letter to
			// its entity or hexadecimal representation, keeping a bot from sniffing
			// the randomly obfuscated letters out of the string on the responses.
			switch (rand(1, 3))
			{
				case 1:
					$safe .= '&#'.ord($letter).';'; break;

				case 2:
					$safe .= '&#x'.dechex(ord($letter)).';'; break;

				case 3:
					$safe .= $letter;
			}
		}

		return $safe;
	}
	
	/**
	 * Convert an HTML string to entities.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function he($value)
	{
		return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}
	
	/**
	 * Convert entities to HTML characters.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function decode($value)
	{
		return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
	 *
	 * @param  string  $email
	 * @return string
	 */
	public function encode_email($email)
	{
		return str_replace('@', '&#64;', $this->obfuscate($email));
	}
	
}