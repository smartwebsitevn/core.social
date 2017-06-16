<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Media_widget extends MY_Widget
{
    /**
     * Ham khoi dong
     */
    function __construct()
    {
        //$this->lang->load('site/media');
    }



    /*===============================================================
    * HANDLE PLAYER
    *=================================================================*/

    function player($link_data, $options = [], $temp = 'player')
    {
        $user = user_get_account_info();
        $link_data = json_decode($link_data);

        //== su ly play theo server
        $sv = $this->input->get('sv'); // tru 1 vi phan tu bat dau tu 0, khi hien phia nguoi dung ta + 1 cho dep
        $sv=$sv?$sv-1:null;
        if($sv && isset($link_data->servers) && isset($link_data->servers[$sv])){
            // neu co ton tai server su phong, thi set lay thong tin link mac dinh
            $tmp =$link_data->servers[$sv];
            $link_data->server = $tmp->server;
            $link_data->link = $tmp->link;
            $link_data->sub = $tmp->sub;
        }

        $link_current = mod('media')->process_link_detail($link_data);
        // pr($link_current);
        //========= Su ly Server phim =========
        // Lay thong tin server
        $servers=$server_cur = $player =
        $link = $link_encode =
        $links = $links_encode =
        $subs =$time_current= null;
        if ($link_current) {
            $server_cur = model('server')->get_info($link_current->server);
            if ($server_cur) {
                $player = $link_current->_player;
                $link = $link_current->_link;
                $subs = $link_current->_sub;

                $servers_tmp = isset($link_current->servers)? $link_current->servers:null;
                if($servers_tmp)
                    foreach($servers_tmp as $k=>$s){
                        $server = model('server')->get_info($s->server,'name,status');
                        if($server && $server->status){
                            $is_current=false;
                            if(!$sv){
                                if( $k==0  )
                                    $is_current=true;
                            }
                            elseif( $sv==$k )
                                $is_current=true;
                            $s->is_current = $is_current;
                            $s->name = $server->name;
                            $s->url =  current_url().'?sv='.($k+1);
                            $servers[] =$s;
                        }
                    }
            }

            //== Su ly play tiep
            $id = array_get($options, 'id', 0);
            if ($user && $id > 0) {
                //kiem tra da luu hay chua
                $key =md5($link_current->link);
                $watched = model('product_to_view')->get_info_rule(array('product_id' => $id, 'user_id' => $user->id,"key"=>$key));
                if (!$watched) {
                    //them vao table movie_subscribe
                    $data = array();
                    $data ['product_id'] = $id;
                    $data ['key'] = $key;
                    $data ['user_id'] = $user->id;
                    $data ['created'] = now();
                    model('product_to_view')->create($data);
                } else {
                    $time_current = $watched->time_current;
                }

            }
        }
        //pr($server_cur);
        //  pr($link_current);
        $this->data['_id'] = random_string(); // cap id dinh danh cho moi player
        $this->data['servers'] = $servers;
        $this->data['server_cur'] = $server_cur;
        $this->data['link_current'] = $link_current;
        $this->data['player'] = $player;
        // link
        $this->data['link'] = $link;
        $this->data['subs'] = $subs;

        $this->data['height'] = array_get($options, 'height', null);
        $this->data['image_url'] = array_get($options, 'image_url', public_url('site/images/no_banner.png'));
        $this->data['is_mobile'] = t("input")->is_mobile();
        $this->data['time_current'] = $time_current;
        $this->data['auto_play'] = 0;
        if ($this->input->get('auto_play'))
            $this->data['auto_play'] = 1;
        //pr( $this->data);
        $temp = 'tpl::_widget/player/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
}