<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// V 1.0 - 02/07/2013

class My_Image_lib extends CI_Image_lib
{
    /**
	 * Add Copyright image
	 */
    function addWatermark($settings,$path_img,$path_copyright="")
	{
	    if(!is_array($settings) || count($settings)<=0)
            return false;
       //echo '<br> tao thumb:<br>';
        $config = array();
        $config['image_library']  = 'gd2';
        $config['source_image']   = $path_img;

        if(!empty($path_copyright))
            $config['wm_overlay_path'] = $path_copyright;
        else
            $config['wm_overlay_path'] = public_url('img/images/copyright.png');

        foreach($settings as $k=>$v)
        {
           $config[$k] = $v;
        }
        /*$config['wm_type'] = 'overlay';
        $config['wm_vrt_alignment'] = 'bottom';
        $config['wm_hor_alignment'] = 'left';
        $config['wm_hor_offset'] = '20';
        $config['wm_vrt_offset'] = '0';
        $config['wm_type'] = 'text';
        $config['wm_text'] = 'nencer.com';
        $config['wm_font_size']    = '55';
        $config['wm_font_color'] = '#0033CC';
        */
        //print_r($config);
        $CI =& get_instance();
        $obj = 'image_lib_'.random_string('unique');
        $CI->load->library('image_lib', $config, $obj);
        if ($CI->$obj->watermark())
        {
            return TRUE;
        }
        return FALSE;
	}
    /**
	 * create Thumb image
	 */
    function createThumb($full_path_img,$thumb_dir,$width,$height,$maintain_ratio=true)
	{
        //echo '<br> tao thumb:<br>';
        $config = array();
        $config['image_library']  = 'gd2';
        $config['source_image']   = $full_path_img;
        $config['new_image']      = $thumb_dir;
        $config['create_thumb']   = TRUE;
        $config['maintain_ratio']   = $maintain_ratio;
        $config['quality']      = 100;
        $config['width']      = $width;
        $config['height']       = $height;
        $CI =& get_instance();
        $obj = 'image_lib_'.random_string('unique');
        $CI->load->library('image_lib', $config, $obj);
        //$this->initialize($config);
        //if ($this->resize())
        if ($CI->$obj->resize())
       // if ($CI->$obj->watermark())
        {
            return TRUE;
        }
        return FALSE;
	}
	function cropImg($full_path_img,$crop_dir,$x,$y,$width,$height,$check_size=true)
    {
        //echo '<br> tao thumb:<br>';

        if ($check_size && file_exists($full_path_img))
        {
             $image_size = getimagesize($full_path_img);
            if (isset($image_size[0]) && $image_size[0] <= $width && isset($image_size[1]) && $image_size[1] <= $height)
            {
                //echo '<br> Size khong du tieu chuan de crop';
                return TRUE;
            }
        }
        //echo '<br> Size du tieu chuan de crop';
        $config = array();
        $config['image_library']  = 'gd2';
        $config['source_image']   = $full_path_img;
        $config['create_thumb']   = TRUE;
        $config['thumb_marker']   = '_crop';
        $config['new_image']      = $crop_dir;
        $config['maintain_ratio'] = FALSE;
        $config['quality']      = 100;
        $config['x_axis']      = $x;
        $config['y_axis']       = $y;
        $config['width'] = $width;
        $config['height'] = $height;
        //echo '<br> cau hinh crop :';     print_r($config);
        static $CI=NULL;
		if (!$CI)  $CI =& get_instance();
        $obj = 'image_lib_crop'.random_string('unique');
        $CI->load->library('image_lib', $config, $obj);
        // $CI->$obj->initialize($config);
        // $this->initialize($config);
        // if ($this->crop())
        if($CI->$obj->crop())
        {
            //echo '<br> Crop thanh cong';
            return TRUE;
        }
        //echo '<br> Crop ko thanh cong';
        return FALSE;
    }
	function resizeImg($full_path_img,$width,$height,$maintain_ratio=true,$check_size=true)
    {
        //echo '<br> tao thumb:<br>';
        if ($check_size && file_exists($full_path_img))
        {
            $image_size = getimagesize($full_path_img);
            if (isset($image_size[0]) && $image_size[0] <= $width && isset($image_size[1]) && $image_size[1] <= $height)
            {
                return TRUE;
            }
        }
        $config = array();
        $config['image_library']  = 'gd2';
        $config['source_image']   = $full_path_img;
        $config['maintain_ratio']   = $maintain_ratio;// FALSE : co dung kich thuoc khong theo ti le anh
        $config['quality']      = 100;
        $config['width']      = $width;
        $config['height']       = $height;
        //echo '<br> cau hinh :';     print_r($config);
        $CI =& get_instance();
        $obj = 'image_lib_'.random_string('unique');
        $CI->load->library('image_lib', $config, $obj);
       // $this->initialize($config);
       // if ($this->resize())
       if ($CI->$obj->resize())
        {
            return TRUE;
        }
        return FALSE;
    }
    /**
	 * Upload image
	 */
	public function upload($file_upload="image",$folder,$thumb = TRUE){
    $img = "";
    $data = array('image'=>'','thumb'=>'');
    //print_r($_FILES);
	if($_FILES[$file_upload]['name'] != NULL){
        //echo '<br>File='.$_FILES[$file_upload]['name'];
	    $_config = & load_class("Config","core");
		$_config->load("main",TRUE);
		$upload  = $_config->item("upload","main");
		$upload   = $upload['img'];
        $path_base= pathAssetsUpload(TRUE);
        $config['upload_path']   =  $path_base.$folder.'/';
        $config['overwrite']     = $upload['overwrite']   ;
        $config['encrypt_name']  = $upload['encrypt_name'] ;
        $config['allowed_types'] = $upload['allowed_types'];
        $config['max_size']      = $upload['max_size_admin'];

        $config['max_width']     = $upload['max_width'];
        $config['max_height']    = $upload['max_height'];
        $config['remove_spaces'] = true; // xoa khoang trang

        $CI =& get_instance();
        $unique=   random_string('unique');
        $obj = 'uploadfile_'.$unique;
        if(!$upload['encrypt_name'] ){
        //===== B_SU LY TEN ANH =====
        $file_name_new=$_FILES[$file_upload]['name'];
        // bo xung them chuoi unique o cuoi
        $pos=strrpos($file_name_new, '.') + 1;
		$ext= substr($file_name_new,$pos ); // lay phan mo rong
        $file_name_new=preg_replace('#\.[^.]*$#', '', $file_name_new);//cat bo phan mo rong
        $file_name_new=removeSignText($file_name_new);// loai bo dau
        // lam an toan ten
	   	$file_name_new=preg_replace(array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#'), '', $file_name_new);
        //ten moi
        $file_name_new = $file_name_new.'_'.$unique.".".$ext;
        $_FILES[$file_upload]['name']=$file_name_new;
        //===== E_SU LY TEN ANH =====
        }

        // kiem tra xem thu muc upload co ton tai neu khong thi tao
        if(!is_dir($config['upload_path'])) {
            $CI->load->helper('directory');
            directory_create($path_base,$folder);
        }
        $CI->load->library('upload', $config, $obj);
        if(!$CI->$obj->do_upload($file_upload))
        {
            return $CI->$obj->display_errors('','');
            //show_error($msg);
        }
        else
        {

            $img           = $CI->$obj->data();
            $data['image'] = $img['file_name'];
            $data['file_name'] = $img['file_name'];
            $data['orig_name'] = $img['orig_name'];
            $attributes = array();
            $attributes['type'] = $img['file_type'];// => image/jpeg
            $attributes['ext']  = $img['file_ext'];// => .jpg
            $attributes['size'] = $img['file_size'];// => 81.83
            $attributes['width'] = $img['image_width'] ;//=> 800
            $attributes['height']= $img['image_height'];// => 600
            $data['attributes'] =  $attributes;
            //================== Process orther =====================
            $m_upload=FALSE;
            if(isset($upload[$folder]))
            $m_upload = $upload[$folder];// cau hinh upload cua module

            $ratio= $upload['maintain_ratio'];
            if(isset($m_upload['maintain_ratio']))
                $ratio= $m_upload['maintain_ratio'];
            //== Add copyright
            if($upload['copyright_add']){

                $this->addWatermark($upload['copyright_settings'],$img['full_path']);
            }
            //== resize image
            if($upload['auto_resize'])
            {
            	if(isset($m_upload['resize_width']) && isset($m_upload['resize_height']) )// neu module co yeu cau resize rieng
					$this->resizeImg($img['full_path'],$m_upload['resize_width'],$m_upload['resize_height'],$ratio);
            	else{
                	$this->resizeImg($img['full_path'],$upload['resize_width'],$upload['resize_height'],$ratio);
                    }
            }

            /*create thumb*/
            if($thumb && $m_upload){
                $dir_resize    = $config['upload_path'].'thumb';
                if($m_upload)
                if($this->createThumb($img['full_path'],$dir_resize,$m_upload['thumb_width'],$m_upload['thumb_height'],$ratio)){
                	$img_resize    = explode('.', $img['file_name']);
                	$data['thumb'] = $img_resize['0'].'_thumb.'.$img_resize['1'];
                }
            }
            /*crop upload*/
            if($m_upload && isset($m_upload['crop_x'])){
                //print_r($m_upload);
                $dir_crop    = $config['upload_path'].'crop';
                if($this->cropImg($img['full_path'],$dir_crop,$m_upload['crop_x'],$m_upload['crop_y'],$m_upload['crop_width'],$m_upload['crop_height'])){
                	$img_crop    = explode('.', $img['file_name']);
                	$data['crop'] = $img_resize['0'].'_crop.'.$img_resize['1'];
                }
            }
        }
     }
    return $data;
    }
   /**
	 * Delete image
	*/
    function delete($folder='',$image='',$thumb='',$crop=''){
    	$path = pathAssetsUpload(TRUE).$folder;
    	if($image!=''){
    		$file = $path.'/'.$image;
    		if(file_exists($file))
                 unlink($file);
    	}
        if($thumb!=''){
    		$file = $path.'/thumb/'.$thumb;
            if(file_exists($file))
                 unlink($file);
    	}
        if($crop!=''){
    		$file = $path.'/crop/'.$crop;
            if(file_exists($file))
                 unlink($file);
    	}
    }

}//end class
