<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Lay thong tin cua file
	 */
	function file_get_info($file_id, $field = '')
	{		
		$CI =& get_instance();
		$CI->load->model('file_model');
		
		$info = $CI->file_model->get_info($file_id, $field);
		if ( ! $info)
		{
			return FALSE;
		}
		
		$info = file_add_info($info);
		
		return $info;
	}
	
	/**
	 * Them cac thong tin vao file
	 */
	function file_add_info($file)
	{
		if ( ! $file)
		{
			return FALSE;
		}
		
		// Lay url cua file
		if (isset($file->file_name) && isset($file->status) /*&& isset($file->table)*/)
		{
			// Lay config
			$config = config('upload', 'main');
			$folder = config('file', 'main');
			$folder = $folder[$file->status];
			// Lay url chinh
			//$path = $config['folder'].'/'.$folder.'/'.$file->table.'/'.$file->file_name;
			$path = $config['folder'].'/'.$folder.'/'.$file->file_name;
			$path_thumb =  $config['folder'].'/public_thumb/'.$file->file_name;
			$file->_url 	= base_url($path);
			$file->_path 	= $config['path'].$path;


			// Neu file duoc luu tren server khac
			if ( ! empty($file->server)   && $config['server']['status'])
			{
				$file->_url = $config['server']['url'].$folder.'/'.$file->file_name;
			}
			
			// Lay url thumb
			$file->_url_thumb 	= file_get_url_name_fix( base_url($path_thumb), 'thumb');
			$file->_path_thumb 	= file_get_url_name_fix($config['path'].$path_thumb, 'thumb');
		}
		
		// Rut gon ten file
		if (isset($file->orig_name))
		{
			$file->_orig_name = ellipsize($file->orig_name, 32, .5);
		}
		
		return $file;
	}
	
	/**
	 * Lay url tu name fix
	 */
	function file_get_url_name_fix($url, $name_fix)
	{
		$path_info = pathinfo($url);
		if(isset($path_info['extension']))
			$url = preg_replace('/\.'.$path_info['extension'].'$/i', '_'.$name_fix.'.'.$path_info['extension'], $url);
		
		return $url;
	}
	
	/**
	 * Lay hinh anh tu file_name
	 * $file_table: de phuc vu tao anh theo nhom thu muc (thu muc cung ten voi ten bang)
	 */
	//function file_get_image_from_name($file_name,$file_table='', $file_default=null)
    function file_get_image_from_name($file_name, $file_default=null)
	{
		$CI =& get_instance();
		
		$file = new stdClass();
		if ($file_name != '')
		{
			$file->file_name 	= $file_name;
		   //	$file->table 	= $file_table;
			$file->status 		= config('file_public', 'main');
			$file->server 		= ( ! is_null(array_get(config('upload', 'main'), 'server')));
			$file = file_add_info($file);
		}
		
		$image = new stdClass();
		$image->url 		= (isset($file->_url)) ? $file->_url : $file_default;
		$image->url_thumb 	= (isset($file->_url_thumb)) ? $file->_url_thumb : $image->url;
		$image->path	= (isset($file->_path)) ? $file->_path : '';
		$image->name	= (isset($file->file_name)) ? $file->file_name : '';
		//$image->orig_name	= (isset($file->_orig_name)) ? $file->_orig_name : '';

		return $image;
	}
	
	/**
	 * Xoa file cua table
	 * @param string 	$table		Ten table
	 * @param int		$table_id	Table id
	 * @param array 	$name_fix	Danh sach cac file theo name_fix
	 */
	function file_del_table($table, $table_id, $table_field = '', array $name_fix = array())
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->model('file_model');
		
		$files = $CI->file_model->get_list_of_mod($table, $table_id, $table_field);
		foreach ($files as $file)
		{
			file_del($file, $name_fix);
		}
	}
	
	/**
	 * Xoa cac file tam thoi
	 */
	function file_del_temporary($input =[])
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->model('file_model');
		
		$files = $CI->file_model->get_list_temporary($input);
		foreach ($files as $file)
		{
			file_del($file, array('thumb'));
		}
	}
	
	/**
	 * Xoa file
	 * @param object	$file		Thong tin file
	 * @param array 	$name_fix	Danh sach name_fix cua file
	 */
	function file_del($file, array $name_fix = array())
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->model('file_model');
		
		// Neu du lieu dau vao la file_id
		if ( ! is_object($file))
		{
			$file_id = ( ! is_numeric($file)) ? 0 : $file;
			$file = $CI->file_model->get_info($file_id);
		}
		
		// Neu ko ton tai file
		if ( ! $file)
		{
			return FALSE;
		}
		
		// Them thong tin cua file
		$file = file_add_info($file);
		//pr($file);
		// Xoa file tren server
		if (isset($file->_path))
		{
			// Neu file duoc luu tren server hien tai
			$config = config('upload', 'main');
			if (empty($file->server) || ! $config['server']['status'])
			{
				if (file_exists($file->_path))
				{
					unlink($file->_path);
				}
				// Xoa cac file thanh phan
				if (isset($file->_path_thumb) && file_exists($file->_path_thumb))
					unlink($file->_path_thumb);

				foreach ($name_fix as $v)
				{
					$f = file_get_url_name_fix($file->_path, $v);
					if (file_exists($f))
					{
						unlink($f);
					}
				}
			}
			
			// Neu file duoc luu tren server khac
			else 
			{
				$CI->load->library('file_library');
				
				$CI->file_library->server_del($file->file_name, $file->status);
				
				foreach ($name_fix as $v)
				{
					$f = file_get_url_name_fix($file->file_name, $v);
					$CI->file_library->server_del($f, $file->status);
				}
			}
		}
		
		// Xoa file trong data
		if (isset($file->id))
		{
			$CI->file_model->del($file->id);
		}
		
		return TRUE;
	}

	/**
	 * Download file
	 */
	function file_download($file_id)
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->model('file_model');
		$CI->load->helper('download');
		
		// Lay thong tin
		$info = $CI->file_model->get_info($file_id);
		if ( ! $info)
		{
			return FALSE;
		}
		
		// Fix memory_limit
		file_fix_memory_limit();
		
		// Force download
		$info = file_add_info($info);
		force_download($info->orig_name, read_file($info->_path));
		
		return TRUE;
	}
	
	/**
	 * Fix memory limit
	 */
	function file_fix_memory_limit()
	{
		$config_upload = config('upload', 'main');
		$max_size = max($config_upload['max_size'], $config_upload['max_size_admin']);
		$max_size = intval(($max_size*2)/1024);
		$memory_limit = intval(ini_get('memory_limit'));
		
		if ($max_size > $memory_limit)
		{
			ini_set('memory_limit', $max_size.'M');
		}
	}
	
	/**
	 * Tao thumb
	 */
function file_create_thumb($source_image, $size = array(), $name_fix = '')
{
	$CI =& get_instance();

	$config_upload = config('upload', 'main');
	$path= $config_upload['path'].$config_upload['folder'].'/public_thumb';
	if ( ! count($size))
	{

		$config_upload_img = $config_upload['img'];

		$size['width'] 	= $config_upload_img['thumb_width'];
		$size['height'] = $config_upload_img['thumb_height'];
	}

	$config = array();
	$config['image_library'] 	= 'gd2';
	$config['source_image']		= $source_image;
	$config['new_image']      = $path;
	$config['create_thumb'] 	= TRUE;
	$config['maintain_ratio'] 	= $config_upload_img['maintain_ratio'];
	$config['quality'] 			= 100;
	$config['width'] 			= $size['width'];
	$config['height'] 			= $size['height'];

	if ($name_fix)
	{
		$config['thumb_marker']	= '_'.$name_fix;
	}

	$obj = 'image_lib_'.random_string('unique');
	$CI->load->library('image_lib', $config, $obj);
	if ($CI->{$obj}->resize())
	{
		return TRUE;
	}

	return FALSE;
}

	/**
	 * Resize hinh anh
	 */
	function file_resize($source_image, $size = array(), $check_size = FALSE)
	{
		$CI =& get_instance();
		
		if ( ! count($size))
		{	
			$config_upload = config('upload', 'main');
			$config_upload = $config_upload['img'];
			
			$size['width'] 	= $config_upload['resize_width'];
			$size['height'] = $config_upload['resize_height'];
		}
		
		// Check image size
		if ($check_size && file_exists($source_image))
		{
			$image_size = getimagesize($source_image);
			if (isset($image_size[0]) && $image_size[0] <= $size['width'] && isset($image_size[1]) && $image_size[1] <= $size['height'])
			{
				return TRUE;
			}
		}
		
		$config = array();
		$config['image_library'] 	= 'gd2';
		$config['source_image']		= $source_image;
		$config['maintain_ratio'] 	= TRUE;
		$config['quality'] 			= 100;
		$config['width'] 			= $size['width'];
		$config['height'] 			= $size['height'];
		
		$obj = 'image_lib_'.random_string('unique');
		$CI->load->library('image_lib', $config, $obj); 
		if ($CI->{$obj}->resize())
		{
		    return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Upload file len server luu tru, dong thoi xoa file tam
	 * @param string 	$file_name	Ten file
	 * @param int 		$status		Trang thai file (0 = public | 1 = private)
	 * @param array 	$name_fix	Danh sach name_fix cua file
	 */
	function file_upload_server($file_name, $status, array $name_fix = array())
	{
		// Neu khong su dung server luu tru
		$config = config('upload', 'main');
		/*if ( ! isset($config['server']) )
		{
			return;
		}*/
		if (!$config['server']['status'])
		{
			return;
		}
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->library('file_library');
		
		// Thuc hien upload
		$CI->file_library->server_upload($file_name, $status, TRUE);
		
		foreach ($name_fix as $v)
		{
			$f = file_get_url_name_fix($file_name, $v);
			$CI->file_library->server_upload($f, $status, TRUE);
		}
	}
	
	/**
	 * Lay path cua file trong folder upload
	 * @param string 	$file_name	Ten file
	 */
	function file_upload_path($file_name)
	{
		$config = config('upload', 'main');
		$path 	= $config['path'].$config['folder'].'/'.$file_name;
		
		return $path;
	}
	
	
	function file_get_name($file)
	{
		// Convert back slashes to forward slashes
		$file = str_replace('\\', '/', $file);
		$slash = strrpos($file, '/');
		if ($slash !== false)
		{

			return substr($file, $slash + 1);
		}
		else
		{

			return $file;
		}
	}
	// tao ten moi tu ten cu
	function file_create_new_name($file_name)
	{
		//===== B_SU LY TEN  =====
		$unique=  now();// random_string('unique');
		$ext = substr(strrchr($file_name, '.'), 1);
		$file_name_new=$file_name;
		// bo xung them chuoi unique o cuoi
		$file_name_new=preg_replace('#\.[^.]*$#', '', $file_name_new);//cat bo phan mo rong
		$file_name_new=convert_vi_to_en($file_name_new);// loai bo dau
		// lam an toan ten
		$file_name_new=preg_replace(array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#'), '', $file_name_new);
		//ten moi

		$file_name_new = $file_name_new.'_'.$unique.".".$ext;
		// chuyen thanh ten thuong
		$file_name_new = strtolower($file_name_new );
		return $file_name_new;
		//===== E_SU LY TEN ANH =====
	}

/*
  * Tim các file ?ã quá h?n
  * Xóa các file cache ?ã quá h?n
  * Time het han duoc luu trong noi dung file, no luu ngay het han
  */
function file_delete_cache_output() {

	// get danh sach file
	$cache_path = ! config_item ( 'cache_path' ) ? APPPATH . 'cache/' : config_item ( 'cache_path' );
	$files = get_filenames ( $cache_path );

	// kiem tra file
	foreach ( $files as $key => $name ) {
		$filepath = $cache_path . $name;
		if (! $fp = @fopen ( $filepath, FOPEN_READ )) {
			continue;
		}

		flock ( $fp, LOCK_SH );

		$cache = '';
		if (filesize ( $filepath ) > 0) {
			$cache = fread ( $fp, filesize ( $filepath ) );
		}

		flock ( $fp, LOCK_UN );
		fclose ( $fp );

		// Strip out the embedded timestamp
		if (! preg_match ( "/(\d+TS--->)/", $cache, $match )) {
			continue;
		}

		// If so we'll delete it.
		if (time () >= trim ( str_replace ( 'TS--->', '', $match ['1'] ) )) {
			if (is_really_writable ( $cache_path )) {
				@unlink ( $filepath );
				continue;
			}
		}
	}

}
	// Tao file cache voi thu muc con
	function write_file($path, $data=null, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE)
	{
		// Modifications here
		# Usage:
		# $this->cache->save('user/favourites/page1', $favourites);
		# Will create folder 'user/favourites' at cache folder and file 'page1' with data
		if (strpos($path, '/') !== false) {
			$folders = explode('/', $path);
			unset($folders[count($folders) - 1]);
			$dir = implode('/', $folders);
			if(!file_exists ($dir))
			//pr($dir);
			mkdir($dir, 0744, TRUE);
		}
		// End of modifications

		if ( ! $fp = @fopen($path, $mode))
		{
			return FALSE;
		}
	if(!$data) return;
		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;
	}


function conver_file_size($size = 0, $from = 'm', $to = 'b', $info = false)
{
	if(!$size) return 0;

	$byte = array('t','g', 'm', 'k', 'b');

	if(!in_array($from, $byte) || !in_array($to, $byte))
		return false;

	// tim vi tri cua from
	$from = array_search($from, $byte);
	// tim vi tri cua to
	$to = array_search($to, $byte);

	// neu from va to cung 1 kieu
	if($from == $to)
	{
		if($info)
		{
			return get_info_size($size, $byte[$to]);
		}
		return $size;
	}
	// from > to
	if($from > $to)
	{
		$int = $from - $to;
		for($i = 0; $i < $int; $i++)
		{
			$size = $size / 1024;
		}

		if($info)
		{
			return get_info_size($size, $byte[$to]);
		}
		return $size;
	}

	// from < to
	$int = $to - $from;
	for($i = 0; $i < $int; $i++)
	{
		$size = $size * 1024;
	}

	if($info)
	{
		return get_info_size($size, $byte[$to]);
	}
	return $size;
}

function get_info_size($sizes = 0, $from = 'm')
{
	$byte = array('b','k', 'm','g', 't');
	$space = array('B','KB','MB','GB','T');

	// tim vi tri cua from
	$from = array_search($from, $byte);

	for ($i = $from; $i < count($space); $i++){
		if($sizes > 1024)
			$sizes = $sizes / 1024;
		else
			return round($sizes,2).' '.$space[$i];
	}
	return round($sizes,2).' '.$space[count($space)-1];
}


function file_parse($file)
{
	$type=$icon =$image= '';
	$can_view = 0;
	$can_view_online = 0;
	$ext = strtolower(substr(strrchr($file, '.'), 1));
	if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp'])) {
		$type = "image";
		$can_view = 1;
		$icon ='file-image-o';

	} else if (in_array($ext, ['zip', 'rar'])) {
		$type = "compress";
	} else if (in_array($ext, ['doc', 'docx'])) {
		$type = "document";
		$can_view = 1;
		$can_view_online = 1;
		$icon ='file-word-o';
	} else if (in_array($ext, ['xls', 'xlsx'])) {
		$type = "sheet";
		$can_view = 1;
		$can_view_online = 1;
		$icon ='file-excel-o';

	} else if (in_array($ext, ['pdf'])) {
		$type = "pdf";
		$can_view = 1;
		$can_view_online = 1;
		$icon ='file-pdf-o';

	} else if (in_array($ext, ['txt'])) {
		$type = "txt";
		$can_view = 1;
		$can_view_online = 1;
		$icon ='file-text-o';

	} else if (in_array($ext, ['mp3'])) {
		$type = "audio";

	} else if (in_array($ext, ['mp4', 'avi', 'mpg', 'flv'])) {
		$type = "video";
	} else if (in_array($ext, ['swf'])) {
		$type = "flash";
	}

	if($type){
		$image= public_url("js/jquery/filemanager/images/media/" . $type.'.png');

	}


	$fileinfo = ['type' => $type, 'icon' => $icon, 'image' => $image, 'can_view' => $can_view, 'can_view_online' => $can_view_online];

	return $fileinfo;
}