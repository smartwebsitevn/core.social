<?php
error_reporting(E_ERROR | E_PARSE);
include_once 'BS_Token.php';
class BS_Getlink extends BS_Token
{
    public function picasaweb_google_com($link, $sub='', $title='') {

        preg_match('/https:\/\/picasaweb.google.com\/(.*)\/(.*)#(.+?)/U', $link, $id);
        if($id) {
            $s = explode('?', $id[2]);
            if($s[1])
                $albumUrl = 'https://picasaweb.google.com/data/entry/tiny/user/'.$id[1].'/photoid/'.$id[3].'?'.$s[1];
            else
                $albumUrl = 'https://picasaweb.google.com/data/entry/tiny/user/'.$id[1].'/photoid/'.$id[3];


            $get = $this->curl($albumUrl);

            $mimes = array('image/jpeg', 'image/png', 'image/gif');
            preg_match_all("/<media:content url='(.*)' height='(.*)' width='(.*)' type='(.*)'\/>/U", $get, $data);

            $sources = array();
            foreach($data[2] as $i => $quality)
            {
                if((strpos($data[1][$i], 'm18') || strpos($data[1][$i], 'redirector') and !in_array($data[1][$i], $mimes)))
                    $sources[] = array('file' => $data[1][$i], 'type' => 'mp4', 'label' => '360p', 'default' => true);
                if((strpos($data[1][$i], 'm22') || strpos($data[1][$i], 'redirector')) and !in_array($data[1][$i], $mimes))
                    $sources[] = array('file' => $data[1][$i], 'type' => 'mp4', 'label' => '720p');
                if((strpos($data[1][$i], 'm32') || strpos($data[1][$i], 'm37') || strpos($data[1][$i], 'redirector')) and !in_array($data[1][$i], $mimes))
                    $sources[] = array('file' => $data[1][$i], 'type' => 'mp4', 'label' => '1080p');
            }

            return json_encode( $sources);

        } else {

            $url = urldecode($link);
            if(stristr($url, '#')) list($url, $id) = explode('#', $url);

            $get = $this->curl($url);

            if($id) $gach = explode($id, $get);
            $gach   = explode('{"url":"', ($id)?$gach[7]:$get);
            $thumb  = explode('"', $gach[1]);

            $sources = array();
            for($i=2; $i<5; $i++) {

                $data = urldecode(reset(explode('"', $gach[$i])));
                if(strpos($data, '=m18'))
                    $sources[] = array('file' => $data, 'type' => 'mp4', 'label' => '360p', 'default' => true);
                if(strpos($data, '=m22'))
                    $sources[] = array('file' => $data, 'type' => 'mp4', 'label' => '720p');
                if(strpos($data, '=m32') || strpos($data, '=m37'))
                    $sources[] = array('file' => $data, 'type' => 'mp4', 'label' => '1080p');
            }

            //if($sources)
            // return json_encode(array('playlist' => array('0' => array("sources" => $sources, 'title' => $title, 'image' => $thumb, "tracks" => $sub))));
            return json_encode( $sources);

            //return $this->youtube_com('https://www.youtube.com/watch?v=tpV5O5D0rl8', $sub, $title);
        }
    }
    public function drive_google_com($link, $sub='', $title='') {
        preg_match('/https\:\/\/drive\.google\.com\/file\/d\/(.*)\//', $link, $id);
        $get = $this->curl('https://docs.google.com/get_video_info?docid='.$id[1]);
        $cat = explode('fmt_stream_map=', $get);
        $cat = explode('&', $cat[1]);
        $cat = explode(',', urldecode($cat[0]));
        foreach($cat as $link){
            $cat = explode('|', $link);
            $links = str_replace(array('\u003d', '\u0026','?requiressl=yes&id','transient&app=explorer'), array('=', '&','?app=phim3hd.net&requiressl=yes&id','transient'), $cat[1]);
            $xuly_link = preg_replace(["/[^\/]+\.googlevideo\.com/", "/ipbits=\d{2}/", "/&pl=\d{2}/"], ["redirector.googlevideo.com", 'ipbits=32', '&pl=32'],$links);
            if($cat[0] == 37) {$cur1080p = $xuly_link;}
            if($cat[0] == 22) {$cur720p = $xuly_link;}
            if($cat[0] == 59) {$cur480p = $xuly_link;}
            if($cat[0] == 18) {$cur360p = $xuly_link;}
        }

        $sources = array();
        if(isset($cur1080p)){
            $sources[]=  array('file' => $cur1080p, 'type' => 'mp4', 'label' => '1080p', 'default' => true);
            $sources[]=  array('file' => $cur720p, 'type' => 'mp4', 'label' => '720p', 'default' => false);
            $sources[]=  array('file' => $cur480p, 'type' => 'mp4', 'label' => '480p', 'default' => false);
            $sources[]=  array('file' => $cur360p, 'type' => 'mp4', 'label' => '360p', 'default' => false);
        } elseif(isset($cur720p)){
            $sources[]=  array('file' => $cur720p, 'type' => 'mp4', 'label' => '720p', 'default' => true);
            $sources[]=  array('file' => $cur480p, 'type' => 'mp4', 'label' => '480p', 'default' => false);
            $sources[]=  array('file' => $cur360p, 'type' => 'mp4', 'label' => '360p', 'default' => false);
        } elseif(isset($cur480p)){
            $sources[]=  array('file' => $cur480p, 'type' => 'mp4', 'label' => '480p', 'default' => true);
            $sources[]=  array('file' => $cur360p, 'type' => 'mp4', 'label' => '360p', 'default' => false);
        } elseif(isset($cur360p)){
            $sources[]=  array('file' => $cur360p, 'type' => 'mp4', 'label' => '360p', 'default' => false);
        }

        if($sources)
            return json_encode(array('playlist' => array('0' => array("sources" => $sources, 'title' => '', 'image' => '', "tracks" => ''))));


        return '';

    }
    public function drive_google_com_($link, $sub='', $title='') {

        preg_match('#https://drive.google.com/file/d/(.*)/(.+?)#', $link, $id);

        $link   = 'https://docs.google.com/get_video_info?docid='.$id[1];

        $get = $this->curl($link);
        $url_encoded_fmt_stream_map = $itag = $url = '';

        parse_str($get);

        $data = explode(',',$url_encoded_fmt_stream_map);

        $sources = array();
        foreach($data as $i => $format) {

            parse_str($format);
            $linkMp4    = preg_replace("/\/[^\/]+\.googlevideo\.com/", "/redirector.googlevideo.com", urldecode($url));
            if($itag == '18')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '360p', 'default' => true);
            if($itag == '59')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '480p');
            if($itag == '22')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '720p');
            if($itag == '37')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '1080p');

            $i++;
        }

        // if($sources)
        // return json_encode(array('playlist' => array('0' => array("sources" => $sources, 'title' => $title, 'image' => '', "tracks" => $sub))));
        return json_encode( $sources);

        //return $this->youtube_com('https://www.youtube.com/watch?v=tpV5O5D0rl8', $sub, $title);

    }

    public function photos_google_com($link, $sub='', $title='') {

        $get    = $this->curl($link);

        $data   = explode('","url', $get);
        $data   = explode(']', $data[1]);
        $data   = urldecode($data[0]);

        $data   = str_replace(array('\u003d', '\u0026'), array('=', '&'), 'url'.$data);

        preg_match_all('/url=(.*)&itag=/U', $data, $datas);

        $sources = array();
        foreach($datas[1] as $i => $link) {

            if(strpos($link, '=m18'))
                $sources[] = array('file' => $link, 'type' => 'mp4', 'label' => '360p', 'default' => true);
            if(strpos($link, '=m22'))
                $sources[] = array('file' => $link, 'type' => 'mp4', 'label' => '720p');
            if(strpos($link, '=m32') || strpos($link, '=m37'))
                $sources[] = array('file' => $link, 'type' => 'mp4', 'label' => '1080p');
        }

        $thumb = explode('=', $datas[1][0]);

        // if($sources)
        //return json_encode(array('playlist' => array('0' => array("sources" => $sources, 'title' => $title, 'image' => $thumb[0], "tracks" => $sub))));
        return json_encode( $sources);

        // return $this->youtube_com('https://www.youtube.com/watch?v=tpV5O5D0rl8', $sub, $title);
    }

    public function youtube_com($link, $sub='', $title='') {
        $id     = explode('?v=', $link);

        $links  = 'http://www.youtube.com/get_video_info?&video_id='.$id[1];

        $get = $this->curl($links);

        $url_encoded_fmt_stream_map = $itag = $url = '';

        parse_str($get);

        $data = explode(',',$url_encoded_fmt_stream_map);

        krsort($data);

        $sources = array();
        foreach($data as $i => $format) {

            parse_str($format);
            $linkMp4    = preg_replace(array("/\/[^\/]+\.googlevideo\.com/", "/\/[^\/]+\.youtube\.com/"), "/redirector.googlevideo.com", urldecode($url));
            if($itag == '18')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '360p', 'default' => true);
            if($itag == '59')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '480p');
            if($itag == '22')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '720p');
            if($itag == '37')
                $sources[]  = array('file' => $linkMp4, 'type' => 'mp4', 'label' => '1080p');

            $i++;
        }
        // if($sources)
        //return json_encode(array('playlist' => array('0' => array("sources" => $sources, 'title' => $title, 'image' => '', "tracks" => $sub))));

        return json_encode( $sources);

        // return $this->youtube_com('https://www.youtube.com/watch?v=tpV5O5D0rl8', $sub, $title);
    }

    private function curl2($url) {
        // write_file_log('gg.txt',"==Curl gui:".$url);
        $token  = $this->readFile(md5('access_token').'.token');
        $rs = file_get_contents('http://v6.phim3hd.net/index.php?url='.$url.'&token='.$token);
        // write_file_log('gg.txt',"==Curl nhan:".$rs);
        // return $rs ;

    }
    private function curl($url) {
        $ch = @curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $head[] = "Accept-Language: en-us,en;q=0.5";
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
}