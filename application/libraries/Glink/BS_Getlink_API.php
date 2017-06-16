<?php
class BS_Getlink_API
{
    public function picasaweb_google_com($link, $sub='', $title='') {
        $get = $this->curl($link);
        return $get;
    }

    public function drive_google_com($link, $sub='', $title='') {
        $get = $this->curl($link);
        return $get;

    }

    public function photos_google_com($link, $sub='', $title='') {
        $get = $this->curl($link);
        return $get;

    }

    public function youtube_com($link, $sub='', $title='') {
        $get = $this->curl($link);
        return $get;

    }

    private function curl($link) {
        //$link ='https://www.youtube.com/watch?v=m4KEhflNFEo';
        if(!mod("product")->setting("movie_private_url")) return ;
        $url_api = mod("product")->setting("movie_private_url");
        $url_api = trim($url_api,"/");
        $url_api .='?link='.$link;
        // write_file_log('gg.txt',"==Curl gui:".$url_api);
        $rs = file_get_contents($url_api);
        $rs = json_decode($rs);
        $sources=[];
        if(isset($rs->playlist[0]->sources)){
            foreach($rs->playlist[0]->sources as $s){
                $sources[] = array('file' => $s->file, 'type' => $s->type, 'label' =>  $s->label, 'default' => $s->default);
            }
        }
        //pr($sources);
        $sources =json_encode($sources);
         //write_file_log('gg.txt',"==Curl nhan:".$sources);
         return $sources;

    }

}