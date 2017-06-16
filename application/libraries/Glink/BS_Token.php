<?php error_reporting(E_ERROR | E_PARSE);

/*$client_id      = '290656907020-g2fiflu5oj7k21ava609s73lgcvjuvng.apps.googleusercontent.com';
$client_secret  = 'nv8mZ7-JBEaVdVyuktJkh_cU';
$redirect_uris  = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$redirect_uris  = explode('?', $redirect_uris);
$redirect_uris  = $redirect_uris[0];*/

class BS_Token
{
    var $auth_url = 'https://accounts.google.com/o/oauth2/auth';
    var $token_url = 'https://www.googleapis.com/oauth2/v4/token';
    var $client_id = '';
    var $client_secrect = '';
    var $redirect_url = '';

    function __construct()
    {
        $this->client_id =  mod("product")->setting('google_oauth_id');
        $this->client_secrect = mod("product")->setting('google_oauth_key');
        $this->redirect_url = mod("product")->setting('google_oauth_redirect');

    }

    public function login($location = '')
    {
        $authUrl = $this->auth_url;
        $authUrl .= '?access_type=offline';
        $authUrl .= '&approval_prompt=force';
        $authUrl .= '&response_type=code';
        $authUrl .= '&scope=https%3A%2F%2Fpicasaweb.google.com%2Fdata%2F%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fyoutube';
        $authUrl .= '&client_id=' . $this->client_id;
        $authUrl .= '&redirect_uri=' . $this->redirect_url;
        $authUrl .= '&state';
        if ($location) return $authUrl;
        else header('Location: ' . $authUrl);
    }

    public function get_code()
    {

        $r = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
       // write_file_log('gg.txt', "====:" . $r);
       // write_file_log('gg.txt', $_GET);

        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $token = $this->access_token($code);
            //write_file_log('gg.txt', "=token" . json_encode($token));

            $_SESSION['gg_token'] = isset($token['access_token']) ? $token['access_token'] : '';
            $_SESSION['refresh_token'] = isset($token['refresh_token']) ? $token['refresh_token'] : '';
            $this->writeFile(md5('refresh_token') . '.refToken', $_SESSION['refresh_token']);
            $this->writeFile(md5('access_token') . '.token', $_SESSION['gg_token']);
            setcookie('ref', 'BSplugin', time() + 1800);
            header('Location: ' . filter_var('http://' . $_SERVER["SERVER_NAME"], FILTER_SANITIZE_URL));
        }

        $refresh_token = $this->readFile(md5('refresh_token') . '.refToken');
        if (!isset($_COOKIE['ref'])) {
            if ($refresh_token) {
                unset($_SESSION['gg_token']);
                $key = $_SESSION['gg_token'] = $this->readFile(md5('access_token') . '.token');

                if (!$key) {

                    $token = $this->refresh_token($refresh_token);

                    $_SESSION['gg_token'] = $token['access_token'];

                    $this->writeFile(md5('access_token') . '.token', $_SESSION['gg_token']);

                    setcookie('ref', 'BSplugin', time() + 1800);

                } else setcookie('ref', 'BSplugin', time() + 1800);

            } else
                $this->login();
        }
    }

    public function access_token($code)
    {
        $post = array(
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secrect,
            'redirect_uri' => $this->redirect_url,
            'grant_type' => 'authorization_code'
        );
        $response = $this->curl($post);
        return json_decode($response, true);
    }

    public function refresh_token($refresh_token)
    {
        $post = array(
            'refresh_token' => $refresh_token,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secrect,
            'grant_type' => 'refresh_token'
        );
        $response = $this->curl($post);
        return json_decode($response, true);
    }

    public function writeFile($pathFile, $data)
    {
        $pathFile = APPPATH . 'cache/glink/' . $pathFile;
       // write_file_log('gg.txt', "ghi file:" . $pathFile . ' data=' . $data);
        $fileOpen = fopen($pathFile, 'w');
        fwrite($fileOpen, $data);
        fclose($fileOpen);
        return true;
    }

    public function readFile($pathFile)
    {
        $pathFile = APPPATH . 'cache/glink/' . $pathFile;
        if (!file_exists($pathFile))
            return false;
        date_default_timezone_set("Asia/Saigon");
        $timeNow = time();
        $fileTime = filemtime($pathFile);
        $timeOut = $timeNow - 1800;
        if ($fileTime > $timeOut)
            return file_get_contents($pathFile);
        if ($check)
            return unlink($pathFile);
        else
            return file_get_contents($pathFile);
    }

    private function curl($post)
    {
        $ch = curl_init();
        $postText = http_build_query($post);
        $options = array(

            CURLOPT_URL => $this->token_url,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postText
        );
        curl_setopt_array($ch, $options);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
}
//$BS_Token   = new BS_Token;
//$BS_Token->get_code();