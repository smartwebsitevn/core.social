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

    function comment_list($info, $type, $temp = '')
    {
        $user = user_get_account_info();

        // Tai cac file thanh phan
        $filter = [
            'status' => config('verify_yes', 'main'),
            'table_id' => $info->id,
            'table_name' => $type
        ];
        $total = model('comment')->filter_get_total($filter);

        $page_size = 2;
        $input = [
            'order' => array('created', 'DESC'),
        ];

        if (!isset($input['limit'])) {
            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }

        // chi hien cap 1
        $filter['parent_id'] = 0;
        $list = $this->builder_sub($filter);

        // Tao chia trang
        $pages_config = array();
        if (isset($total)) {
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter);
            // pr( $pages_config['base_url'] );
            // $pages_config['base_url'] = current_url(1);
            $pages_config['total_rows'] = $total;
            $pages_config['per_page'] = $page_size;
            $pages_config['cur_page'] = $limit;
        }

        $this->data['pages_config'] = $pages_config;

        $this->data['type'] = $type;

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

    function builder_sub($filter)
    {
        $input = [
            'order' => array('created', 'DESC'),
        ];
        $list_sub = model('comment')->filter_get_list($filter, $input);
        //pr_db($list_sub,0);
        foreach ($list_sub as &$sub) {
            $user = model('user')->get_info($sub->user_id, 'name,avatar,vote_total');
            $sub->user = null;
            if ($user) {
                $user->avatar = file_get_image_from_name($user->avatar, public_url('img/no_user.png'));
                $sub->user = $user;
            }
            $filter['parent_id'] = $sub->id;
            $sub->subs = $this->builder_sub($filter);
        }
        return $list_sub;

    }

    function builder_html($row)
    {
        ob_start();
        $name = $row->user ? $row->user_name : 'admin';
        $img = (isset($row->user->avatar) && $row->user->avatar) ? $row->user->avatar->url_thumb : public_url('img/user_no_image.png');
        ?>
        <div class="row mt10">
            <div class="col-md-1">
                <a class="pull-left" href="#">
                    <img alt=""
                         src="<?php echo $img ?>"
                         class="avatar">
                </a>


            </div>
            <div class="col-md-11">
                <div  class="info">
                    <span class="name"><?php echo $name ?></span>

                        <?php if ($row->user): ?>
                            <span
                                class="points"> <b><?php echo number_format($row->user->vote_total) ?></b> <?php echo lang("count_point") ?>
                                </span>
                        <?php endif; ?>
                        <span class="date_created"> <b><?php echo  get_date($row->created)   ?></b> </span>

                </div>

                <p class="comment-content"><?php echo $row->content ?></p>

                <?php if ($row->level < 2): ?>
                    <div class="comment-action">
                           <span>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('product/vote/' . $row->id) . "?act=like" ?>"><i
                                    class="pe-7s-angle-up-circle"></i></a>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('product/vote/' . $row->id) . "?act=dislike" ?>"><i
                                    class="pe-7s-angle-down-circle"></i></a>
                            </span>
                        <a data-toggle="collapse" href="#reply_<?php echo $row->id ?>" aria-expanded="false"
                           aria-controls="reply_<?php echo $row->id ?>"
                           class="reply-btn">Trả lời (<?php echo isset($row->subs) ? count($row->subs) : 0 ?>) </a>
                    </div>
                    <div class="collapse  mt20  " id="reply_<?php echo $row->id ?>">
                        <form class="form_action" accept-charset="UTF-8"
                              action="<?php echo site_url('comment/reply/' . $row->id) ?>" method="POST">

                            <!--<img src="<?php /*//echo !$user->avatar?$user->avatar->url_thumb:public_url('site/layout/img/default-avatar.png')*/ ?>" class="media-object user-avatar pull-left">-->

                            <div class="form-group text-right">
                                  <textarea name="content"
                                            placeholder="<?php echo lang("comment") ?>"
                                            class="form-control"></textarea>
                                <a  _submit="true" class="mt10 pull-right">Post</a>


                                <div class="clear"></div>
                                <div name="content_error" class="error "></div>
                                <div name="user_error" class="error "></div>
                            </div>

                        </form>
                        <?php if (isset($row->subs) && $row->subs): ?>
                            <ul class="list-unstyled">
                                <?php foreach ($row->subs as $sub): //pr($sub);?>
                                    <li>
                                        <?php echo $this->builder_html($sub) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>


        </div>

        <?php
        return ob_get_clean();
    }
}