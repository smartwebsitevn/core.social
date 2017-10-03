<?php// if (user_is_login()): ?>
    <?php if (user_is_manager($user)): ?>
    <form class="form_action" accept-charset="UTF-8"
      _field_load="<?php echo $info->id; ?>_comment_load"
      action="<?php echo $info->_url_comment_add ?>"
      method="POST">
    <div class="row mt10 mb10">
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
<?php else: ?>
    <?php /*if(t('input')->is_ajax_request()): ?>
    <div class="alert alert-danger">Bạn phải đăng nhập mới bình luận được bài viết này</div>
    <?php endif;*/ ?>
<?php endif; ?>