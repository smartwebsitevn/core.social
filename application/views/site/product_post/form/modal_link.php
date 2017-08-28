<?php
$modal['id'] = 'modal_share_link';
$modal['name'] = 'Chia sẻ link';
?>
<?php echo macro()->modal_start($modal); ?>
<form class="form form_action_link " method="post" action="<?php echo $action,'?_act=post_link'; ?>" >

    <div class="form-group  ">
        <label class=" control-label ">
            Link:</label>
        <div class="clearfix"></div>
        <input class="form-control" name="link" type="text">

        <div class="clear"></div>
        <div name="link_error" class="error"></div>
    </div>
    <div class="form-actions">
        <div class="form-group">
            <a _submit="true"class="btn btn-default" >Chia sẻ</a>
            <a data-dismiss="modal"  class="btn"/><?php echo lang('button_cancel'); ?></a>
        </div>
    </div>

    <div class="clear"></div>
</form>
<?php echo macro()->modal_end(); ?>
