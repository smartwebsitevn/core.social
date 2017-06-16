<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function debug($title, $data = null, $echo_query = false, $stop = false)
{
	//return;
	echo '<br>' . $title;
	if ($echo_query)
		echo "-> Query:".t('db')->last_query();

	if ($data) {
		if (is_array($data) || is_object($data)) {
			echo ':';
			pr($data, 0);

		} else
			echo ':' . $data;
	}

	if ($stop)
		pr("-STOP-");

}
/**
 * Print R
 */
function pr($arr = null, $exit = TRUE)
{
	echo '<pre>';
	print_r($arr);
	echo '</pre>';

	if ($exit) exit;
}

function pr_db($arr = null, $exit = TRUE)
{
	pr(t('db')->last_query(), 0);
	if ($arr) {
		pr($arr, 0);
	}
	if ($exit) exit;
}

	/**
	 * Luu lai cac gia tri input
	 */
	function debug_log_input()
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->driver('cache');
		
		$_v = $CI->cache->file->get('_debug_log_input');
		$_v = (!is_array($_v)) ? array() : $_v;
		
		if ($CI->input->get('act'))
		{
			pr($_v);
		}
		else
		{
			$row = array();
			$row['ip'] 		= $CI->input->ip_address();
			$row['url'] 	= current_url();
			$row['get'] 	= $CI->input->get();
			$row['post'] 	= $CI->input->post();
			$row['time'] 	= get_date(now(), 'full');
			
			array_unshift($_v, $row);
			$CI->cache->file->save('_debug_log_input', $_v, 365*24*60*60);
		}
	}
	
	/**
	 * Hien thi cac loi cua file lang
	 */
	function debug_lang_error($a = 'vietnamese', $b = 'english')
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->helper('file');
		
		// Khai bao bien
		$dir = APPPATH.'language/';
		$dir = str_replace('\\', '/', $dir);
		$a_dir = $dir.$a.'/';
		$b_dir = $dir.$b.'/';
		
		// Duyet qua cac file cua thu muc A
		$result = array();
		$a_files = get_filenames($a_dir, TRUE);
		foreach ($a_files as $a_file)
		{
			// Neu khong phai la file lang
			if (!preg_match('#_lang\.php$#is', $a_file))
			{
				continue;
			}
			
			// Lay file tuong ung cua thu muc B
			$a_file = str_replace('\\', '/', $a_file);
			$b_file = str_replace($a_dir, $b_dir, $a_file);
			
			// Neu file cua B khong ton tai
			if (!file_exists($b_file))
			{
				$result['file'][] = $b_file;
				continue;
			}
			
			// Lay ngon ngu trong A va B
			$lang = array();
			include($a_file);
			$a_lang = $lang;
			
			$lang = array();
			include($b_file);
			$b_lang = $lang;
			
			// Duyet qua A lang
			foreach ($a_lang as $k => $v)
			{
				// Neu key nay khong ton tai trong B lang
				if (!isset($b_lang[$k]))
				{
					$result['key'][$b_file][] = $k;
					continue;
				}
				
				// Neu gia tri giong nhau
				if ($b_lang[$k] == $v)
				{
					$result['val'][$b_file][] = $k;
				}
			}
			
		}
		
		return $result;
	}
	
	/**
	 * Tu dong tao file lang theo file lang co san
	 */
	function debug_lang_set($a = 'english', $b = 'english_')
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->helper('file');
		
		// Khai bao bien
		$dir = APPPATH.'language/';
		$dir = str_replace('\\', '/', $dir);
		$a_dir = $dir.$a.'/';
		$b_dir = $dir.$b.'/';
		
		// Duyet qua cac file cua thu muc A
		$a_files = get_filenames($a_dir, TRUE);
		foreach ($a_files as $a_file)
		{
			// Neu khong phai la file lang
			if (!preg_match('#_lang\.php$#is', $a_file))
			{
				continue;
			}
			
			// Lay file tuong ung trong thu muc B
			$a_file = str_replace('\\', '/', $a_file);
			$b_file = str_replace($a_dir, $b_dir, $a_file);
			
			// Neu file cua B khong ton tai
			if (!file_exists($b_file))
			{
				continue;
			}
			
			// Lay ngon ngu trong A va B
			$lang = array();
			include($a_file);
			$a_lang = $lang;
			
			$lang = array();
			include($b_file);
			$b_lang = $lang;
			
			// Gan gia tri cua key tu B vao A
			$a_lang = extend($a_lang, $b_lang);
			
			// Luu vao file A
			$content = '<?php'."\n";
			foreach ($a_lang as $k => $v)
			{
				$v = str_replace("'", "\\'", $v);
				$content .= '$lang[\''.$k.'\'] = \''.$v.'\';'."\n";
			}
			write_file($a_file, $content);
		}
	}
	
	/**
	 * Lay danh sach table va cac field cua table
	 */
	function debug_data_get($save = FALSE, $file_name = '_debug_data')
	{
		$CI =& get_instance();
		
		$list = array();
		$tables = $CI->db->list_tables();
		foreach ($tables as $v)
		{
			$list[$v] = $CI->db->list_fields($v);
		}
		
		if ($save)
		{
			$CI->load->driver('cache');
			$CI->cache->file->save($file_name, $list, 365*24*60*60);
		}
		
		return $list;
	}
	
	/**
	 * Hien thi cac loi cua data
	 */
	function debug_data_error($a = '_debug_data_a', $b = '_debug_data_b')
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->driver('cache');
		
		// Lay danh sach cua a, b
		$list_a = $CI->cache->file->get($a);
		$list_b = $CI->cache->file->get($b);
		
		// Duyet qua $list_a de tim loi
		$result = array();
		$result['field'] = $result['table'] = array();
		foreach ($list_a as $table => $fields)
		{
			// Table khong ton tai trong $list_b
			if (!isset($list_b[$table]))
			{
				$result['table'][] = $table;
			}
			else 
			{
				// Field cua table khong ton tai trong $list_b
				foreach ($fields as $field)
				{
					if (!in_array($field, $list_b[$table]))
					{
						$result['field'][$table][] = $field;
					}
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Lay ve danh sach cac file da chinh sua tu 1 thoi diem
	 * @param int $time		Thoi diem chinh sua
	 */
	function debug_list_file_edit($time)
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->helper('file');
		
		// Lay danh sach file
		$list_file = get_filenames(APPPATH, TRUE);
		
		// Lay ra cac file co time edit lan cuoi lon hon $time
		$list = array();
		foreach ($list_file as $file)
		{
			$info = get_file_info($file, 'date');
			if ($info['date'] > $time)
			{
				$list[] = $file;
			}
		}
		
		return $list;
	}
	
	