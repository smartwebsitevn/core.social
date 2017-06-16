<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Run_file_upload extends CI_Controller
{

    /**
     * Chay cac file upload
     */
    function index()
    {
        // Tai file thanh phan
        $this->load->helper('common');
        $this->load->helper('file');

        // Tao duong dan file
        $upload = config('upload', 'main');
        $file = $upload['path'] . $this->uri->uri_string();
        if (!file_exists($file)) {
            $this->_err();
        }

        $allowed_types = $upload['img']['allowed_types'];
        $allowed_types = explode('|', $allowed_types);

        //== kiem tra file hien thoi
        $ext = explode('.', $file);
        if (!is_array($ext) || empty($ext)) {
            $this->_err();
        }
        $ext = end($ext);

        if (
            !in_array($ext, $allowed_types) ||
            preg_match('#php|Php|pHp|PHP|pHP|PhP|PHp|phP|ini|log|tpl|inc|sql|php3|php4|php5|php6|phtml|pl|py|jsp|asp|shtml|sh|cgi#i', $ext)
        )
            $this->_err();


        // Gan header
        header('Content-type: ' . get_mime_by_extension($file));
        header('Content-Length: ' . filesize($file));
        header('Accept-Ranges: none');
        header('Last-Modified: ' . mdate('%D, %d %M %Y %H:%i:%s') . ' GMT');

        // Cache
        header('Cache-Control: max-age=' . (10 * 24 * 60 * 60) . ', must-revalidate');
        header('Expires: ' . mdate('%D, %d %M %Y %H:%i:%s', strtotime('now +10 days')) . ' GMT');

        // No cache
        /*header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: '.mdate('%D, %d %M %Y %H:%i:%s'));*/

        // Doc file
        @readfile($file);
        exit();
    }

    function _err()
    {
        show_404();
    }
}
