<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comment_widget extends MY_Widget
{


    /**
     * Ham khoi dong
     */
    function __construct()
    {
        // Tai cac file thanh phan
       // $this->lang->load('site/comment');
    }

    /**
     * ---------------
     * Bình luận
     * ---------------
     *
     * @param  [type] $info [description]
     * @param  string $temp [description]
     * @return [type]       [description]
     *
     */
    function comment($info, $type , $temp = '')
    {
        // Tai cac file thanh phan
        $this->load->model('comment_model');
        $input = $filter = $list = array();

        $total_all = 0;
        $star = array(1, 2, 3, 4, 5);
        foreach ($star as $st)
        {
            $filter['status'] = config('verify_yes', 'main');
            $filter['table_id'] = $info->id;
            $filter['table_name'] = $type;
            $filter['rate'] = $st;
            $total = $this->comment_model->filter_get_total($filter, $input);

            if ($total > 0) {
                $row = new stdClass();
                $row->id = $st;
                $row->total = $total;
                $list[$st] = $row;
                $total_all += $total;
            }

        }

        if ($total_all > 0) {
            foreach ($list as $row) {
                $percent = ($row->total / $total_all) * 100;
                $row->percent = $percent;
            }
        }
        $this->data['type'] = $type;
        $this->data['info'] = $info;
        $this->data['star'] = $star;
        $this->data['list'] = $list;

        $this->data['url_comment'] = site_url('comment/add/' . $info->id);

        // Hien thi view
        $temp = (!$temp) ? 'tpl::_widget/comment/form' : $temp;
        $this->load->view($temp, $this->data);
    }

    function comment_list( $info, $type , $temp = '' )
    {

        // Tai cac file thanh phan
        $this->load->helper('user');

        $filter = [
            'status' => config('verify_yes', 'main'),
            'table_id' => $info->id,
            'table_name' => $type
        ];
        $total = model('comment')->filter_get_total($filter);

        $page_size = 5;
        $input = [
            'order' => array('created', 'DESC'),
            'limit' => array(0, $page_size)
        ];
        // chi hien cap 1
        $filter['parent_id'] = 0;
        $list = model('comment')->filter_get_list($filter, $input);

        foreach ($list as $row)
        {
            $user = model('user')->get_info( $row->user_id ,'username,name,avatar');
            $row->user=null;
            if($user){
                //$user->avatar =0;//user_get_avatar($user->avatar_id);
                $user->avatar = file_get_image_from_name($user->avatar, public_url('img/no_user.png'));
                $row->user = $user;

            }
            if (isset($row->created)) {
                $row->_created = get_date($row->created);
                $row->_created_time = get_date($row->created, 'time');
            }
        }

        $user = user_get_account_info();
        $this->data['user'] = $user;
        $this->data['info'] = $info;
        $this->data['list'] = $list;
        $this->data['total'] = $total;
        $this->data['page_size'] = $page_size;
        $this->data['ajax_pagination_total'] = ceil($total / $page_size);


        // Hien thi view
       // $temp = (!$temp) ? 'site/_widget/'.$type.'/comment/list' : $temp;
        $temp = (!$temp) ? 'list' : $temp;
        $temp = 'site/_widget/comment/' . $temp;
        $this->load->view($temp, $this->data);
    }


}