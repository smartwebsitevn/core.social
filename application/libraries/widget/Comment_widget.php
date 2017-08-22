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
    function comment($info, $type, $temp = '')
    {
        // Tai cac file thanh phan
        $input = $filter = $list = array();

        $total_all = 0;
        $star = array(1, 2, 3, 4, 5);
        foreach ($star as $st) {
            $filter['status'] = config('verify_yes', 'main');
            $filter['table_id'] = $info->id;
            $filter['table_name'] = $type;
            $filter['rate'] = $st;
            $total = model('comment')->filter_get_total($filter, $input);

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
    function comment_list($info, $type,$filter=[],$input=[], $temp = '', $temp_options = array())
    {
        $user = user_get_account_info();
        if ($user)
            $user = mod('user')->add_info($user);

        $filter['status']= config('verify_yes', 'main');
        $list = $this->get_list($info->id,$type,$filter,$input);

        $this->data['user'] = $user;
        $this->data['info'] = $info;
        $this->data['type'] = $type;
        $this->data['list'] = $list[0];
        $this->data['pages_config'] = $list[1];
        $this->data['load_more'] =  $this->input->get("load_more", false);

        // Hien thi view
        // $temp = (!$temp) ? 'site/_widget/'.$type.'/comment/list' : $temp;
        $temp_full = array_get($temp_options, 'temp_full', false);

        if (!$temp_full) {
            $temp = (!$temp) ? 'list' : $temp;
            $temp = 'site/_widget/comment/' . $temp;
        }
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->_display($this->_make_view($temp, __FUNCTION__), $return);
        else
            $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    function display_list($info, $type,$list,$pages_config, $temp = '', $temp_options = array())
    {
        $user = user_get_account_info();
        if ($user)
            $user = mod('user')->add_info($user);

        $this->data['user'] = $user;
        $this->data['info'] = $info;
        $this->data['type'] = $type;
        $this->data['list'] = $list;
        $this->data['pages_config'] = $pages_config;

        $this->data['load_more'] =  $this->input->get("load_more", false);

        // Hien thi view
        // $temp = (!$temp) ? 'site/_widget/'.$type.'/comment/list' : $temp;
        $temp_full = array_get($temp_options, 'temp_full', false);

        if (!$temp_full) {
            $temp = (!$temp) ? 'list' : $temp;
            $temp = 'site/_widget/comment/' . $temp;
        }


        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->_display($this->_make_view($temp, __FUNCTION__), $return);
        else
            $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    function get_list($table_id, $table_type,$filter=[],$input=[])
    {
        // Tai cac file thanh phan
        $filter['status']= config('verify_yes', 'main');
        $filter['table_id']= $table_id;
        $filter['table_name']= $table_type;
        $total = model('comment')->filter_get_total($filter);

        $page_size = 5;
        $input['order'] = array('created', 'DESC');

        if (!isset($input['limit'])) {
            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }
        // chi hien cap 1

        $filter['parent_id'] =  array_get($filter, 'parent_id', 0);
        $list = $this->builder_list($filter,$input);

        // Tao chia trang
        $pages_config = array();
        if (isset($total) && isset($limit)) {
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter);
            // pr( $pages_config['base_url'] );
            // $pages_config['base_url'] = current_url(1);
            $pages_config['total_rows'] = $total;
            $pages_config['per_page'] = $page_size;
            $pages_config['cur_page'] = $limit;
        }
        return [$list,$pages_config];
    }
    function builder_list($filter,$input=[])
    {
        $list = model('comment')->filter_get_list($filter, $input);
       // pr_db();
        foreach ($list as &$row) {
            $user = model('user')->get_info($row->user_id, 'id,user_group_id,name,avatar,avatar_api,vote_total');
            $row->user = null;
            if ($user) {
                //$user->avatar = file_get_image_from_name($user->avatar, public_url('img/no_user.png'));
                $user = mod('user')->add_info($user);
                $row->user = $user;
            }
            $filter['parent_id'] = $row->id;
            $row->subs = $this->builder_list($filter);
        }
        return $list;

    }

    function builder_html($row,$options=[])
    {
        ob_start();
        $field_load = array_get($options,'field_load',null);
        $name = isset($row->user) ? $row->user->name : 'admin';
        //$img = (isset($row->user->avatar) && $row->user->avatar) ? $row->user->avatar->url_thumb : public_url('img/user_no_image.png');
        $url_comment_reply =isset($options['url_reply'])?$options['url_reply'].'&id='.$row->id: site_url('comment/reply/' . $row->id);
        $url_comment_vote =isset($options['url_vote'])?$options['url_vote'].'&id='.$row->id: site_url('comment/vote/' . $row->id);
        ?>
        <div class="row mt10">
            <div class="col-md-1">
                      <?php echo view('tpl::_widget/user/display/item/info_avatar', array('row' => $row->user)); ?>
            </div>
            <div class="col-md-11">
                <div class="info">
                    <span class="name"><a href="#0<?php //echo $row->user->_url_view ?>"><?php echo $name ?></a></span> -
                    <span class="date"><?php echo get_date($row->created) ?> </span>

                    <?php /*if ($row->user): ?>
                        <span
                            class="points"> <b><?php echo number_format($row->user->vote_total) ?></b> <?php echo lang("count_point") ?>
                                </span>
                    <?php endif; */?>
                </div>

                <p class="comment-content"><?php echo $row->content ?></p>

                <?php if ($row->level < 5): ?>
                    <div class="comment-action">
                        <?php echo widget('comment')->action_vote($row) ?>

                        <a data-toggle="collapse" href="#reply_<?php echo $row->id ?>" aria-expanded="false"
                           aria-controls="reply_<?php echo $row->id ?>"
                           class="reply-btn">Trả lời (<?php echo isset($row->subs) ? count($row->subs) : 0 ?>) </a>
                    </div>
                    <div class="collapse  mt20  " id="reply_<?php echo $row->id ?>">
                        <form class="form_action" accept-charset="UTF-8" _field_load="<?php echo $field_load; ?>"   action="<?php echo $url_comment_reply ?>" method="POST">
                            <!--<img src="<?php /*//echo !$user->avatar?$user->avatar->url_thumb:public_url('site/layout/img/default-avatar.png')*/ ?>" class="media-object user-avatar pull-left">-->

                            <div class="form-group text-right">
                                <div class="row">
                                    <div class="col-md-11">
                                <textarea name="content"
                                          placeholder="<?php echo lang("comment") ?>"
                                          class="form-control auto_height " maxlength="255"></textarea>

                                        <div name="content_error" class="error "></div>
                                        <div name="user_error" class="error "></div>
                                    </div>
                                    <div class="col-md-1">
                                        <a _submit="true" class="btn btn-default btn-xs pull-right">Post</a>
                                    </div>


                                </div>
                            </div>

                        </form>
                        <div id="reply_<?php echo $row->id; ?>_comment_show">
                        <?php if (isset($row->subs) && $row->subs): ?>
                            <ul class="list-unstyled list-comment-<?php echo $row->id ?>">
                                <?php foreach ($row->subs as $sub): //pr($sub);?>
                                    <li>
                                        <?php echo $this->builder_html($sub,$options) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>


        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * Vote
     */
    function action_vote($comment, $temp = '')
    {
        $id = $comment->id;
        $can_do = true;
        $voted = false;
        $user = user_get_account_info();
        if ($user) {
            //kiem tra da luu hay chua
            $data = array();
            $data ['table_name'] = 'comment';
            $data ['table_id'] =$id;
            $data ['user_id'] =$user->id;
            $voted = model('social_vote')->get_info_rule(array('table_name' => 'comment', 'table_id' => $id, 'user_id' =>$user->id));
        }
        // pr($voted);
        $url_vote= site_url('comment/vote/' . $id );;
        $this->data['can_do'] = $can_do;
        $this->data['info'] = $comment;
        $this->data['voted'] = $voted;
        $this->data['url_like'] = $url_vote. "?act=like";
        $this->data['url_like_del'] =  $url_vote. "?act=like_del";
        $this->data['url_dislike'] =  $url_vote. "?act=dislike";
        $this->data['url_dislike_del'] = $url_vote. "?act=dislike_del";


        $temp = (!$temp) ? 'vote' : $temp;
        $temp = 'tpl::_widget/comment/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
}