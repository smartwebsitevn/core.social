<?php if ($can_do): ?>
    <?php if($favorited): ?>
        <a class="btn btn-dd do_action" href="javascript:void(0)" title='<?php echo lang("action_favorite_del") ?>'
           data-url="<?php echo $url_favorite_del ?>"
            ><i class="fa fa-bookmark"></i> <?php echo lang("action_favorite_del") ?></a>
    <?php else: ?>
        <a class="btn btn-dd do_action" href="javascript:void(0)" title='<?php echo lang("action_favorite") ?>'
           data-url="<?php echo $url_favorite ?>"
            ><i class="fa fa-bookmark"></i> <?php echo lang("action_favorite") ?></a>
    <?php endif; ?>

   <?php else: ?>
    <a class="btn btn-dd act-notify-modal" title='<?php echo lang("action_favorite") ?>' href="javascript:void(0)"
       data-content="<?php echo lang("notice_please_login_to_use_function") ?>"><i
            class="fa fa-bookmark"></i> <?php echo lang("action_favorite") ?></a>
<?php endif; ?>
