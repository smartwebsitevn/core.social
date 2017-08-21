<?php if (isset($info)): ?>
    <?php $_data_form = function () use ($list, $info, $user) {
        ob_start() ?>
        <form id="commentForm" class="form_action" accept-charset="UTF-8"
              _field_load="<?php echo $info->id; ?>_comment_load"
              action="<?php echo $info->_url_comment_add ?>"

              method="POST">
            <div class="row mt10">
                <div class="col-md-1">
                    <?php $img = (isset($user->avatar) && $user->avatar) ? $user->avatar->url_thumb : public_url('img/user_no_image.png');
                    ?>
                    <a class="pull-left" href="#">
                        <img alt=""
                             src="<?php echo $img ?>"
                             class="avatar">
                    </a>
                </div>
                <div class="col-md-10">
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
        </form>
        <?php return ob_get_clean();
    }
    ?>
    <?php $_data_list = function () use ($list, $info) {
        ob_start() ?>
        <?php
        foreach ($list as $row) {
            ?>
            <li>
                <?php echo widget('comment')->builder_html($row, ['url_reply' => $info->_url_comment_reply, 'field_load' => $info->id . '_comment_load', 'info' => $info]);//$_comment($row); ?>
            </li>
            <?php
        }
        ?>
        <?php return ob_get_clean();
    }
    ?>
    <?php $_data_pagination = function () use ($pages_config) {
        ob_start() ?>
        <?php if (isset($pages_config['total_rows']) && isset($pages_config['per_page']) && $pages_config['total_rows'] > $pages_config['per_page']): ?>
            <?php //pr($pages_config) ?>
            <nav class="page-pagination text-center" event-hook="commentPagination">
                <div class="text-center mt20 mb20">
                    <a href="#0" class="act-pagination-load-more btn btn-default">Xem thêm</a>
                </div>
                <div  style="display: none">
                <?php $this->widget->site->pages($pages_config); ?>
                </div>
            </nav>
        <?php endif; ?>
        <?php return ob_get_clean();
    }
    ?>
    <?php if (isset($load_more) && $load_more): ?>
        <?php echo $_data_list(); ?>
        <?php echo $_data_pagination(); ?>
        <script type="text/javascript">
            nfc.reboot();
        </script>
    <?php else: ?>
        <div class="item-comments media-list comment-list">
            <?php /*if (!user_is_login()): ?>
                <h4>
                    <small><?php echo lang("notice_please_login_to_use_function") ?></small>
                </h4>
            <?php else:*/ ?>
                <div class="comment-list-wraper">
                    <?php if (user_is_login()): ?>
                    <?php echo $_data_form(); ?>
            <?php endif; ?>
                    <ul class="list-unstyled list-comment-0">
                        <?php echo $_data_list(); ?>
                    </ul>
                    <?php echo $_data_pagination(); ?>

                </div>

            <?php //endif; ?>
        </div>
        <?php //echo t('view')->load('tpl::_widget/product/comment/_common/js'); ?>

    <?php endif; ?>

<?php endif; ?>