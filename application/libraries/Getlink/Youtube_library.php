<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Youtube_library
{
    private function get_content($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    function youtube_id_from_url($url)
    {
        $pattern = '%^# Match any youtube URL
           (?:https?://)?  # Optional scheme. Either http or https
           (?:www\.)?      # Optional www subdomain
           (?:             # Group host alternatives
             youtu\.be/    # Either youtu.be,
           | youtube\.com  # or youtube.com
             (?:           # Group path alternatives
               /embed/     # Either /embed/
             | /v/         # or /v/
             | /watch\?v=  # or /watch\?v=
             )             # End path alternatives.
           )               # End host alternatives.
           ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
           $%x';
        $result = preg_match($pattern, $url, $matches);
        if (false !== $result && isset($matches[1])) {
            return $matches[1];
        }
        return false;
    }

    public function getLink($url)
    {
        $id = $this->youtube_id_from_url($url);
        $api = 'http://www.youtube.com/get_video_info?&video_id='.$id;
        $video_info = $this->get_content($api);

        $url_encoded_fmt_stream_map = '';
        parse_str($video_info);

        if (isset($url_encoded_fmt_stream_map)) {
            $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
        } else {
            return 'No encoded format stream found.';
        }
        if (count($my_formats_array) == 0) {
            return 'No format stream map found - was the video id correct?';
        }
        $avail_formats = array();
        $i = 0;
        $ipbits = $ip = $itag = $sig = $quality = $type = $url = '';
        $expire = time();
        
        foreach ($my_formats_array as $format) {
            parse_str($format);
            $type = explode(';', $type);
            if ($quality == 'hd720') {
                $quality = 'HD';
            }
            if ($quality == 'medium' && $type[0] == 'video/mp4') {
                $quality = 'SD';
            }
            if ($quality == 'SD' || $quality == 'HD') {
                $avail_formats[$i]['q'] = $quality;
                $avail_formats[$i]['type'] = $type[0];
                $avail_formats[$i]['link'] = isset($url) ? urldecode($url) . '&signature=' . $sig :
                    '';
                parse_str(urldecode($url));
                $i++;
            }
        }
        return array_reverse($avail_formats);
    }
    
}
/*
$url = 'https://www.youtube.com/watch?v=_21YCEaZTzM';
$data = Youtube::model()->getLink($url);
echo '<pre>';
print_r($data);
*/
?>