<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class User_notice_mod extends MY_Mod
{
    public function url($row)
    {
        if (isset($row->seo_url) && $row->seo_url)
            $row->_url_view = site_url("user_notice/" . $row->seo_url . '-i' . $row->id);

        return $row;
    }

    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function send($user_id,$title,  $opts = [])
    {
        $data = [];
        if ($user_id) {
            if (is_numeric($user_id)) {
                $user = model('user')->get_info($user_id, 'id,name,email');
            } else {
                $user = $user_id;
            }
        }
        if (!$user) {

        }
        $data['title'] = $title;

        $data['user_id'] = $user->id;
        if (isset($opts['content']) && $opts['content'])
            $data['content'] = $opts['content'];
        if (isset($opts['url']) && $opts['url'])
            $data['url'] = $opts['url'];
        if (isset($opts['table']) && $opts['table'])
            $data['table'] = $opts['table'];
        $data['created'] = now();
        $this->_model()->create($data);
        return true;
    }


}