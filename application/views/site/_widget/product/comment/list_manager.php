<?php if (isset($info) && $list): ?>
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
        <div class="item-comments media-list comment-list">
                <div class="comment-list-wraper">
                    <?php if (user_is_login()): ?>
                    <?php echo $_data_form(); ?>
                     <?php endif; ?>
                    <ul class="list-unstyled list-comment-0">
                        <?php echo $_data_list(); ?>
                    </ul>
                </div>
        </div>

<?php endif; ?>