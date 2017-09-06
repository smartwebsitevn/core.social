<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends MY_Controller{
	
    public function __construct(){
    parent::__construct();
		
    $this->load->helper('utf8');
    //$this->load->helper('functions');
    
    // Tai cac file thanh phan
	$this->lang->load('admin/media');
    define( 'DIR_MEDIA_UPLOAD', $this->_pathAssetsUpload(1).'public/');
    define( 'DIR_MEDIA_CACHE', $this->_pathAssetsUpload(1).'public_cache/');
    define( 'HTTP_MEDIA_UPLOAD', $this->_pathAssetsUpload().'public/');
    define( 'HTTP_MEDIA_CACHE', $this->_pathAssetsUpload().'public_cache/');
 
  }
  
  private $error = array();

function _pathAssetsUpload($f=FALSE){

    $upload=config('upload', 'main');
  	if($f){
       return  $upload['path'].$upload['folder'] .'/';
  	}
    else {
       return base_url($upload['folder']).'/';
   }

}
  public function index() {
  	
        $this->data['title']           = lang('media.heading_title');

        $this->data['entry_files']     = lang('media.entry_files');
    	$this->data['entry_folder']    = lang('media.entry_folder');
		$this->data['entry_move']      = lang('media.entry_move');
		$this->data['entry_copy']      = lang('media.entry_copy');
		$this->data['entry_rename']    = lang('media.entry_rename');

		$this->data['button_folder']   = lang('media.button_folder');
		$this->data['button_delete']   = lang('media.button_delete');
		$this->data['button_move']     = lang('media.button_move');
		$this->data['button_copy']     = lang('media.button_copy');
		$this->data['button_rename']   = lang('media.button_rename');
		$this->data['button_modify']   = lang('media.button_modify');
		$this->data['button_upload']   = lang('media.button_upload');
		$this->data['button_refresh']  = lang('media.button_refresh');
		$this->data['button_submit']   = lang('media.button_submit');
		$this->data['button_back']   = lang('media.button_back');
		$this->data['error_select']    = lang('media.meg.error_select');
		$this->data['error_directory'] = lang('media.meg.error_directory');
        //$this->data['no_image'] = 'No image';
        $this->data['confirm_delete']  = lang('media.meg.confirm_delete');
        //===============================
		$this->data['base']          =  base_url();
		$this->data['directory']     =  HTTP_MEDIA_UPLOAD;   // tao duongn dan tuong doi toi file o day
        $fckeditor = $this->input->get('CKEditorFuncNum');

        if ($fckeditor) {
			$this->data['fckeditor'] = $fckeditor;
		} else {
			$this->data['fckeditor'] = false;
		}
        $field=$this->input->get('field');
        if ($field) {
			$this->data['field'] = $field;
		}else {
			$this->data['field'] = '';
		}
		$this->data['path_upload'] = HTTP_MEDIA_UPLOAD;
		$this->data['path_assets'] = public_url().'/';
		
		$action =array('image','directory','folders','files','create','delete','move','copy','rename','resize','modify','upload');
		foreach ($action as $act){
				$this->data['media_url_'.$act] = admin_url('media/'.$act);
		}
		$this->data['media_url'] = admin_url('media') . '/';
		$this->data['no_image'] = $this->data['path_assets'].'js/jquery/filemanager/images/no_image.jpg';

        $this->load->view('admin/media/index', $this->data);
	}

	public function modify() {
		//$this->load->model('tool/image');
        $image= $this->input->get_post('image');
        $path = DIR_MEDIA_UPLOAD.$image;
        // kiem tra file
		if (!file_exists($path))
		{
			redirect_admin();
		}
		 $url_back= $this->input->get_post('url_back');
		if(!$url_back)
			 $url_back =admin_url('media');
		if ($this->input->post('_submit'))
		{
			$image_data = $_POST['image_data'];
			$image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data));
			//pr($path);
			file_put_contents($path,$image_data);
			$json['location'] = $url_back;
			set_output('json', json_encode($json));
			return;
		}
		
        $this->data['path_upload'] = HTTP_MEDIA_UPLOAD;
		$this->data['path_assets'] = public_url().'/';
		$this->data['image'] = $this->data['path_upload'].$image;
		$this->data['image_name'] = $image;
		$this->data['media_url_modify'] = admin_url('media/modify');
		$this->load->view('admin/media/modify', $this->data);
	}
	public function image() {
		//$this->load->model('tool/image');
         $image= $this->input->get('image');
		if ($image) {
			set_output('html',$this->resize(html_entity_decode($image, ENT_QUOTES, 'UTF-8'), 100, 100));
		}
	}



    public function directory() {

        $json = array();

        $directory = $this->input->post('directory');

        //	if ($directory) {

        $directories = glob(rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', $directory), '/') . '/*', GLOB_ONLYDIR);

        // pr($directories);

        if ($directories) {

            $i = 0;



            foreach ($directories as $directory) {

                $json[$i]['data'] = basename($directory);

                $json[$i]['attributes']['directory'] = utf8_substr($directory, strlen(DIR_MEDIA_UPLOAD));



                $children = glob(rtrim($directory, '/') . '/*', GLOB_ONLYDIR);



                if ($children) {

                    $json[$i]['children'] = ' ';

                }



                $i++;

            }

        }

        //}

        set_output('json', json_encode($json));

    }



    public function files() {

        $json = array();

        $directory = $this->input->post('directory');
        if (!empty($directory)) {

            $directory = DIR_MEDIA_UPLOAD . str_replace('../', '', $directory);

        } else {

            $directory = DIR_MEDIA_UPLOAD;

        }

        //echo $directory;

        $upload = $this->config->item("upload", "main");

        $allowed = explode('|', $upload['allowed_types']);

        //pr($allowed);

        $files = glob(rtrim($directory, '/') . '/*');

        //pr($files);

        if ($files) {
 			//echo '<br>d:'.DIR_MEDIA_UPLOAD;
            foreach ($files as $file) {

                if (is_file($file)) {

                    $ext = substr(strrchr($file, '.'), 1);

                } else {

                    $ext = '';

                }



                if (in_array(strtolower($ext), $allowed)) {
                    $size = filesize($file);
                    $i = 0;
                    $suffix = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
                    while (($size / 1024) > 1) {
                        $size = $size / 1024;
                        $i++;
                    }

                    //echo '<br>==file:'.$file;
                   
                    $json[] = array(

                        'filename' => basename($file),

                        'file' => substr($file, strlen(DIR_MEDIA_UPLOAD)),

                        'size' => round(utf8_substr($size, 0, utf8_strpos($size, '.') + 4), 2) . $suffix[$i]

                    );
                    //pr( $json);

                }

            }

        }

        set_output('json', json_encode($json));

    }



    public function create() {

        $json = array();

        $directory = $this->input->post('directory');

        //	if (!empty($directory)) {

        //if (isset($this->request->post['directory'])) {

        $name = $this->input->post('name');

        if ($name) {

            $directory = rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', $directory), '/');



            if (!is_dir($directory)) {

                $json['error'] = lang('media.meg.error_directory');

            }



            if (file_exists($directory . '/' . str_replace('../', '', $name))) {

                $json['error'] = lang('media.meg.error_exists');

            }

        } else {

            $json['error'] = lang('media.meg.error_name');

        }

        //} else {

        //	$json['error'] =' loi tao thu muc' ;//lang('media.meg.error_directory');

        //	}



        /* 	if (!$this->user->hasPermission('modify', 'common/filemanager')) {

          $json['error'] = lang('media.meg.error_permission');

          } */



        if (!isset($json['error'])) {

            mkdir($directory . '/' . str_replace('../', '', $name), 0777);



            $json['success'] = lang('media.meg.create');

        }



        set_output('json', json_encode($json));

    }



    public function delete() {

        //$this->language->load('common/filemanager');



        $json = array();

        $path = $this->input->post('path');

        if ($path) {

            $path = rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', html_entity_decode($path, ENT_QUOTES, 'UTF-8')), '/');



            if (!file_exists($path)) {

                $json['error'] = lang('media.meg.error_select');

            }



            if ($path == rtrim(DIR_MEDIA_UPLOAD, '/')) {

                $json['error'] = lang('media.meg.error_delete');

            }

        } else {

            $json['error'] = lang('media.meg.error_select');

        }



        /* 	if (!$this->user->hasPermission('modify', 'common/filemanager')) {

          $json['error'] = lang('media.meg.error_permission');

          } */



        if (!isset($json['error'])) {

            if (is_file($path)) {

                unlink($path);

            } elseif (is_dir($path)) {

                $files = array();



                $path = array($path . '*');



                while (count($path) != 0) {

                    $next = array_shift($path);



                    foreach (glob($next) as $file) {

                        if (is_dir($file)) {

                            $path[] = $file . '/*';

                        }



                        $files[] = $file;

                    }

                }



                rsort($files);



                foreach ($files as $file) {

                    if (is_file($file)) {

                        unlink($file);

                    } elseif (is_dir($file)) {

                        rmdir($file);

                    }

                }

            }



            $json['success'] = lang('media.meg.delete');

        }



        set_output('json', json_encode($json));

    }



    public function move() {

        //$this->language->load('common/filemanager');



        $json = array();

        $from = $this->input->post('from');

        $to = $this->input->post('to');

        if ($from && $to) {

            $from = rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', html_entity_decode($from, ENT_QUOTES, 'UTF-8')), '/');



            if (!file_exists($from)) {

                $json['error'] = lang('media.meg.error_missing');

            }



            if ($from == DIR_MEDIA_UPLOAD) {

                $json['error'] = lang('media.meg.error_default');

            }



            $to = rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', html_entity_decode($to, ENT_QUOTES, 'UTF-8')), '/');



            if (!file_exists($to)) {

                $json['error'] = lang('media.meg.error_move');

            }



            if (file_exists($to . '/' . basename($from))) {

                $json['error'] = lang('media.meg.error_exists');

            }

        } else {

            $json['error'] = lang('media.meg.error_directory');

        }



        /* if (!$this->user->hasPermission('modify', 'common/filemanager')) {

          $json['error'] = lang('media.meg.error_permission');

          } */



        if (!isset($json['error'])) {

            rename($from, $to . '/' . basename($from));



            $json['success'] = lang('media.meg.move');

        }



        set_output('json', json_encode($json));

    }



    public function copy() {

        //	$this->language->load('common/filemanager');



        $json = array();

        $path = $this->input->post('path');

        $name = $this->input->post('name');

        if ($path && $name) {

            if ((utf8_strlen($name) < 3) || (utf8_strlen($name) > 255)) {

                $json['error'] = lang('media.meg.error_filename');

            }



            $old_name = rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', html_entity_decode($path, ENT_QUOTES, 'UTF-8')), '/');



            if (!file_exists($old_name) || $old_name == DIR_MEDIA_UPLOAD) {

                $json['error'] = lang('media.meg.error_copy');

            }



            if (is_file($old_name)) {

                $ext = strrchr($old_name, '.');

            } else {

                $ext = '';

            }



            $new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($name, ENT_QUOTES, 'UTF-8') . $ext);



            if (file_exists($new_name)) {

                $json['error'] = lang('media.meg.error_exists');

            }

        } else {

            $json['error'] = lang('media.meg.error_select');

        }



        /* 	if (!$this->user->hasPermission('modify', 'common/filemanager')) {

          $json['error'] = lang('media.meg.error_permission');

          } */



        if (!isset($json['error'])) {

            if (is_file($old_name)) {

                copy($old_name, $new_name);

            } else {

                $this->recursiveCopy($old_name, $new_name);

            }



            $json['success'] = lang('media.meg.copy');

        }



        set_output('json', json_encode($json));

    }



    function recursiveCopy($source, $destination) {

        $directory = opendir($source);



        @mkdir($destination);



        while (false !== ($file = readdir($directory))) {

            if (($file != '.') && ($file != '..')) {

                if (is_dir($source . '/' . $file)) {

                    $this->recursiveCopy($source . '/' . $file, $destination . '/' . $file);

                } else {

                    copy($source . '/' . $file, $destination . '/' . $file);

                }

            }

        }



        closedir($directory);

    }



    public function folders() {

        //$this->response->setOutput($this->recursiveFolders(DIR_MEDIA_UPLOAD ));

        $this->_setOutput('html', $this->recursiveFolders(DIR_MEDIA_UPLOAD));

    }



    protected function recursiveFolders($directory) {

        $output = '';



        $output .= '<option value="' . utf8_substr($directory, strlen(DIR_MEDIA_UPLOAD)) . '">' . utf8_substr($directory, strlen(DIR_MEDIA_UPLOAD)) . '</option>';



        $directories = glob(rtrim(str_replace('../', '', $directory), '/') . '/*', GLOB_ONLYDIR);



        foreach ($directories as $directory) {

            $output .= $this->recursiveFolders($directory);

        }



        return $output;

    }



    public function rename() {

        //$this->language->load('common/filemanager');

        $path = $this->input->post('path');

        $name = $this->input->post('name');

        $json = array();



        if ($path && $name) {

            if ((utf8_strlen($name) < 3) || (utf8_strlen($name) > 255)) {

                $json['error'] = lang('media.meg.error_filename');

            }



            $old_name = rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', html_entity_decode($path, ENT_QUOTES, 'UTF-8')), '/');



            if (!file_exists($old_name) || $old_name == DIR_MEDIA_UPLOAD) {

                $json['error'] = lang('media.meg.error_rename');

            }



            if (is_file($old_name)) {

                $ext = strrchr($old_name, '.');

            } else {

                $ext = '';

            }



            $new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($name, ENT_QUOTES, 'UTF-8') . $ext);



            if (file_exists($new_name)) {

                $json['error'] = lang('media.meg.error_exists');

            }

        }



        /* if (!$this->user->hasPermission('modify', 'common/filemanager')) {

          $json['error'] = lang('media.meg.error_permission');

          } */



        if (!isset($json['error'])) {

            rename($old_name, $new_name);



            $json['success'] = lang('media.meg.rename');

        }



        set_output('json', json_encode($json));

    }


    public function upload() {

        $this->_upload_pre();
        $directory = $this->input->post('directory');
        // Xoa cac file tam thoi
        file_del_temporary();
        // Tao config upload
        $folder_upload ='public/'.$directory;
        $config 				= config('upload', 'main');
        $config['upload_path'] 	= $config['path'].$config['folder'].'/'.$folder_upload;
        $config['max_size'] 	= $config['max_size_admin'];
        $config['file_name'] 	= $_FILES['file']['name'];
        $config['allowed_types']= $config['allowed_types'];

        // Thuc hien upload file
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file'))
        {
            // Lay thong tin cua file vua upload
            $upload_data = $this->upload->data();
            $server	= ( ! isset($config['server'])) ? TRUE : $config['server'];
            // Chuyen file len server luu tru
            if ($server)
            {
                $name_fix = array();
                file_upload_server($upload_data['file_name'], $folder_upload, $name_fix);
            }
            // Khai bao du lieu tra ve
            $json['error'] = lang('media.meg.uploaded');
            $output = json_encode($json);
            set_output('json', $output);
        }
        else{
            $json['error'] =  $this->upload->display_errors('','') ;//lang('media.meg.error_uploaded');
            $output = json_encode($json);
            set_output('json', $output);
        }

    }
    public function _upload_pre() {
        $json = array();
        $directory = $this->input->post('directory');
        //	if ($directory) {
        $upload = $this->config->item("upload", "main");
        //$upload = $upload['file'];
        //pr($_FILES['file']);
        if (isset($_FILES['file']) && $_FILES['file']['tmp_name']) {
            $filename = basename(html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8'));
            if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
                $json['error'] = lang('media.meg.error_filename');
            }
            $directory = rtrim(DIR_MEDIA_UPLOAD . str_replace('../', '', $directory), '/');
            if (!is_dir($directory)) {
                $json['error'] = lang('media.meg.error_directory');
            }
            if ($_FILES['file']['size'] > $upload['max_size_admin']*1024) {
                $json['error'] = lang('media.meg.error_file_size');
            }




            $allowed = explode('|', $upload['allowed_types']);
               $ext = substr(strrchr($filename, '.'), 1);
           /* if (!in_array($_FILES['file']['type'], $allowed)) {
                $json['error'] = lang('media.meg.error_file_type').'_1_'."allowed_types = ".$upload['allowed_types']." Kieu file = ".$_FILES['file']['type'];
            }*/

            if (!in_array(strtolower($ext), $allowed)) {
                $json['error'] = lang('media.meg.error_file_type',implode(',',$allowed));
            }

            if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {

                $json['error'] = 'media.meg.error_upload_' . $this->request->files['image']['error'];

            }

        }
        else {
            $json['error'] = lang('media.meg.error_file').'_4_'.'Filename = '.$_FILES['file'].'<br>_tmp file = '.$_FILES['file']['tmp_name'];
        }
        if (isset($json['error'])) {

            set_output('json', json_encode($json));
        }

            //===== B_SU LY TEN  =====
             $_FILES['file']['name'] = file_create_new_name($_FILES['file']['name']);
           /* $unique=  now();// random_string('unique');
            $file_name_new=$_FILES['file']['name'];
            // bo xung them chuoi unique o cuoi
            $file_name_new=preg_replace('#\.[^.]*$#', '', $file_name_new);//cat bo phan mo rong
            $file_name_new=convert_vi_to_en($file_name_new);// loai bo dau
            // lam an toan ten
            $file_name_new=preg_replace(array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#'), '', $file_name_new);
            //ten moi
            $file_name_new = $file_name_new.'_'.$unique.".".$ext;
            // chuyen thanh ten thuong
            $file_name_new = strtolower($file_name_new );
            $_FILES['file']['name']=$file_name_new;*/
            //===== E_SU LY TEN ANH =====
    }



    public function resize($filename, $width, $height) {

        //echo DIR_MEDIA_UPLOAD . $filename;

        if (!file_exists(DIR_MEDIA_UPLOAD . $filename) || !is_file(DIR_MEDIA_UPLOAD . $filename)) {

            return;

        }

        $info = pathinfo($filename);
        $extension = $info['extension'];

        $old_image = $filename;

        $new_image = utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        $image_cache = DIR_MEDIA_CACHE . $new_image;

        // echo '<br>image_c='.$image_cache;

        if (!file_exists($image_cache) || (filemtime(DIR_MEDIA_UPLOAD . $old_image) > filemtime($image_cache))) {

            $path = '';

            $directories = explode('/', dirname($new_image));

            //echo dirname( $new_image);pr($directories);

            foreach ($directories as $directory) {

                $path = $path . '/' . $directory;



                if (!file_exists(DIR_MEDIA_CACHE . $path)) {

                    @mkdir(DIR_MEDIA_CACHE . $path, 0777);

                }

            }

            $config = array();

            $config['image_library'] = 'gd2';

            $config['source_image'] = DIR_MEDIA_UPLOAD . $old_image;

            $config['new_image'] = $image_cache;

            $config['width'] = $width;

            $config['height'] = $height;

            $config['maintain_ratio'] = FALSE;

            $obj = 'image_lib_' . random_string('unique');

            $this->load->library('image_lib', $config, $obj);

            $this->$obj->resize();

        }

        return HTTP_MEDIA_CACHE . $new_image;

    }
}