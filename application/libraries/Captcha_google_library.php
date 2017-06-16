<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha_google_library
{
    function check()
    {
        $CI =& get_instance();
        
        //config
        $api_url     = config('captcha_google_api_url', 'main');
        $secret_key  = config('captcha_google_secret_key', 'main');

        //post data
        $site_key    = $CI->input->post('g-recaptcha-response');
        $remoteip    = $CI->input->ip_address();
        
        $api_url = $api_url.'?secret='.$secret_key.'&response='.$site_key.'&remoteip='.$remoteip;
        $response = file_get_contents($api_url);
        $response = json_decode($response);
       // pr($response);
        $err="error-codes";
        // khi khai bao dung captcha , lan 1 $response->success, khi click tiep thi no tra ve rong
        // khi khai bao sai se phat sinh them bien error-codes ta dua vao day de bat loi
        if(isset($response->$err))
        {
            return false;
        }

        /*if(!isset($response->success))
        {
            return false;
        }
        if($response->success != true)
        {
            return false;
        }*/

        return true;
    }
}
