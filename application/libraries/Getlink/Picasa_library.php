<?php
class Picasa_library
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
    public function getLink($url)
    {
        if (stristr($url, '#'))
            list($url, $id) = explode('#', $url);
        $data = $this->get_content($url);
        $list = array();
        if (isset($id) && $id)
            $all_link = explode($id, $data);
        else
            return $list;
        $explode = explode('{"url":"', $id);
        if ($id) {
            if (isset($all_link[7])) {
                $eplode_str = $all_link[7];
            } else {
                $eplode_str = $data;
            }
            $explode = explode('{"url":"', $eplode_str);
            $all_link = explode('{"url":"', $eplode_str);
        }


        if (isset($all_link[2])) {
            $explode = explode('"', $all_link[2]);
            $tmp['q'] = 'SD';
            $tmp['link'] = urldecode(reset($explode));
            $tmp['type'] = 'video/mp4';
            
            $list[] = $tmp;
        }
        $has_hd = false;
        if (isset($all_link[3])) {

            preg_match("/type\\\":\\\"([a-z0-9\/-]+)/", $all_link[3], $match);
            //Kiểm tra khác link flash mới tiếp tục lấy
            $type = isset($match[1]) ? $match[1] : 'video/mpeg4';
            if ($type != 'application/x-shockwave-flash' && count($match) > 0) {
                $explode = explode('"', $all_link[3]);
                $has_hd = true;
                $tmp['q'] = 'HD';
                $tmp['link'] = urldecode(reset($explode));
                $tmp['type'] = 'video/mp4';
                
                $list[] = $tmp;
            }
        }
        if (isset($all_link[4])) {

            preg_match("/type\\\":\\\"([a-z0-9\/-]+)/", $all_link[4], $match);
            $type = isset($match[1]) ? $match[1] : 'video/mpeg4';
            if ($type != 'application/x-shockwave-flash') {

                $explode = explode('"', $all_link[4]);
                $tmp['q'] = 'FHD';
                $tmp['link'] = urldecode(reset($explode));
                $tmp['type'] = 'video/mp4';
                
                if (strpos($tmp['link'], 'redirector.googlevideo.com')) {
                    $list[] = $tmp;
                }
            }
        }

        if (isset($all_link[5]) && !$has_hd) {
            preg_match("/type\\\":\\\"([a-z0-9\/-]+)/", $all_link[5], $match);
            $type = isset($match[1]) ? $match[1] : 'video/mpeg4';
            if ($type != 'application/x-shockwave-flash') {

                $explode = explode('"', $all_link[5]);
                $tmp['q'] = 'HD';
                $tmp['link'] = urldecode(reset($explode));
                $tmp['type'] = 'video/mp4';
                
                $list[] = $tmp;
            }
        }
        return $list;
    }
}

//Geting stated
/*
$url = 'https://picasaweb.google.com/100986328016549439685/UPLINK_63_VuDieuTuoiTre?authkey=Gv1sRgCOuvwNa28euD9AE#6207961343448289762';
$list_link = Picasa::model()->getLink($url);
echo '<pre>';
print_r($list_link);
*/
?>