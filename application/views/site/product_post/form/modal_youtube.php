<?php
$modal['id'] = 'modal_share_video';
$modal['name'] = 'Chia sẻ Video Youtube';
?>
<?php echo macro()->modal_start($modal); ?>
    <form class="form form_action_youtube " method="post" action="<?php echo $action,'?_act=post_youtube'; ?>" >

        <div class="form-group  ">
            <label class=" control-label ">
                Link youtube:</label>
            <div class="clearfix"></div>
            <input class="form-control" name="youtube" type="text">

            <div class="clear"></div>
            <div name="youtube_error" class="error"></div>
        </div>
        <div class="form-actions">
            <div class="form-group">
                <a _submit="true"class="btn btn-default" >Upload</a>
                <a data-dismiss="modal"  class="btn"/><?php echo lang('button_cancel'); ?></a>
            </div>
        </div>

        <div class="clear"></div>
    </form>
<?php echo macro()->modal_end(); ?>