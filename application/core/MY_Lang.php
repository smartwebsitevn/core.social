<?php

class MY_Lang extends CI_Lang
{
	/**
	 * Lay lang
	 *
	 * @param  string  		$key
	 * @param  array|string $replace
	 * @return string
	 */
	public function line($key, $replace = array())
	{
		$key = (string)$key;

		$line = array_get($this->language, $key, $key);
		
		$args = func_get_args();
		$args[0] = $line;
		
		return call_user_func_array(array($this, 'make_line'), $args);
	}
	
	/**
	 * Xu ly line
	 * 
	 * @param string 		$line
	 * @param  array|string $replace
	 * @return string
	 */
	public function make_line($line, $replace = array())
	{
		if (is_array($replace)) // make_line(line, array(param => value))
		{
			$line = $this->makeReplacements($line, $replace);
		}
		else // make_line(line, var1, var2, ...)
		{
			$args = func_get_args();
			array_shift($args);
			
			if ( ! empty($args))
			{
				$line = vsprintf($line, $args);
			}
		}
		
		return $line;
	}

	/**
	 * Xu ly gan bien vao lang
	 *
	 * @param  string  $line
	 * @param  array   $replace
	 * @return string
	 */
	protected function makeReplacements($line, array $replace)
	{
		$replaceTags = array(array('{', '}'));
		
		$ps = array();
		foreach ($replace as $key => $value)
		{
			foreach ($replaceTags as $tag)
			{
				$k = '';
				if (is_array($tag))
				{
					$k = $tag[0] . $key . $tag[1];
				}
				else 
				{
					$k = $tag . $key;
				}
				
				$ps[$k] = $value;
			}
		}

		return strtr($line, $ps);
	}


	public function load_($langfile = '', $idiom = '', $return = false, $add_suffix = true, $alt_path = '')
	{
		$langfile = str_replace('.php', '', $langfile);

		if ($add_suffix == true)
		{
			$langfile = str_replace('_lang.', '', $langfile) . '_lang';
		}
		$lang_cache = $langfile;

		$langfile .= '.php';

		if (in_array($langfile, $this->is_loaded, true))
		{
			return;
		}
     
		//$lang_translate = $this->translate_system_model->get($lang_id ,$path[0], $path[1] );
		$config = &get_config();
		//pr($config ['language'] );
		if ($idiom == '')
		{
			$deft_lang = (!isset ($config ['language'])) ? 'english' : $config ['language'];
			$idiom = ($deft_lang == '') ? 'english' : $deft_lang;
		}
		
		//echo '<br>========= Lang file:'.$idiom.$langfile;
		// Lay danh sach trong cache
		$lang = lang_get_cache($idiom, $lang_cache);

		// Neu khong ton tai thi cap nhat cache tu data va get lai
		if ($lang === false)
		{
			$lang_obj = model('lang')->get_info_rule(array('directory' => $idiom));
			//lay lang trong CSDL
			$lang_file_obj = model('lang_file')->get($langfile);
			// neu ton tai file lang trong he thong
			if ($lang_file_obj)
			{
				$lang = model('lang_phrase')->lang_get_translates($lang_obj->directory, $lang_file_obj->id);
				if ($lang)
				{
					lang_set_cache($idiom, $lang_cache, $lang);
				}
				//	t()->cache->file->save($lang_cache, $lang, config('cache_expire_long', 'main'));
				//pr($lang,false);
			}
		}

		// Determine where the language file is and load it
		if (!$lang)
		{
			//echo '<br>Ko co:'.$lang_translated_cache;
			$found = false;

			foreach (get_instance()->load->get_package_paths(true) as $package_path)
			{
				if (file_exists($package_path . 'language/' . $idiom . '/' . $langfile))
				{
					include($package_path . 'language/' . $idiom . '/' . $langfile);
					$found = true;
					break;
				}
			}

			//echo '<br>r='.$package_path.'language/'.$idiom.'/'.$langfile;
			// load file lang tu folder base mac dinh
			/*if ($found !== TRUE && file_exists ( APPPATH . 'language/' . config( 'language_base','main' ) . '/' . $langfile )) {
				include (APPPATH . 'language/' .config( 'language_base','main' ). '/' . $langfile);
				$found = TRUE;
			}*/

			if ($found !== true)
			{
				show_error('Unable to load the requested language file: language/' . $idiom . '/' . $langfile);
			}
		}

		if (!isset ($lang))
		{
			log_message('error', 'Language file contains no data: language/' . $idiom . '/' . $langfile);

			return;
		}

		if ($return == true)
		{
			return $lang;
		}

		$this->is_loaded [] = $langfile;
		$this->language = array_merge($this->language, $lang);
		unset ($lang);
		log_message('debug', 'Language file loaded: language/' . $idiom . '/' . $langfile);

		return true;
	}

	function get($langfile = '', $idiom = '', $alt_path = '')
	{
		$langfile = str_replace('.php', '', $langfile);

		$langfile .= '.php';

		$config = &get_config();

		if ($idiom == '')
		{
			$deft_lang = (!isset ($config ['language'])) ? 'english' : $config ['language'];
			$idiom = ($deft_lang == '') ? 'english' : $deft_lang;
		}

		// Determine where the language file is and load it
		if ($alt_path != '' && file_exists($alt_path . 'language/' . $idiom . '/' . $langfile))
		{
			include($alt_path . 'language/' . $idiom . '/' . $langfile);
		}
		else
		{
			$found = false;

			foreach (get_instance()->load->get_package_paths(true) as $package_path)
			{
				if (file_exists($package_path . 'language/' . $idiom . '/' . $langfile))
				{
					include($package_path . 'language/' . $idiom . '/' . $langfile);
					$found = true;
					break;
				}
			}
			//echo '<br>r='.$package_path.'language/'.$idiom.'/'.$langfile;
			// load file lang tu folder base mac dinh
			/*if ($found !== TRUE && file_exists ( APPPATH . 'language/' . $config ['language_base'] . '/' . $langfile )) {
				//echo '<br>base='.$package_path.'language/'.$config['language_base'].'/'.$langfile;
				include (APPPATH . 'language/' . $config ['language_base'] . '/' . $langfile);
				$found = TRUE;
			}*/

			if ($found !== true)
			{
				show_error('Unable to load the requested language file: language/' . $idiom . '/' . $langfile);
			}
		}

		if (!isset ($lang))
		{
			log_message('error', 'Language file contains no data: language/' . $idiom . '/' . $langfile);

			return;
		}

		return $lang;
	}

	function check_exits($langfile, $idiom)
	{
		$langfile = str_replace('.php', '', $langfile);
		$langfile .= '.php';
		$config = &get_config();

		$found = false;

		foreach (get_instance()->load->get_package_paths(true) as $package_path)
		{
			if (file_exists($package_path . 'language/' . $idiom . '/' . $langfile))
			{
				include($package_path . 'language/' . $idiom . '/' . $langfile);
				$found = true;
				break;
			}
		}

		return $found;

	}
}