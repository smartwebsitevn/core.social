<?php if ($can_do): ?>
    <a  href="#0" title='' class="btn btn-link btn-sm  do_action <?php if ($favorited) echo 'on'; ?>"
        data-action="toggle"
        data-url-on="<?php echo $url_favorite ?>"
        data-url-off="<?php echo $url_favorite_del ?>"
        data-title-on='<?php echo lang("action_favorite_del") ?>'
        data-title-off='<?php echo lang("action_favorite") ?>'
        data-text-on='<i class="pe-7s-like"></i> <?php echo lang("action_favorite_del") ?>'
        data-text-off='<i class="pe-7s-like"></i> <?php echo lang("action_favorite") ?>'
        data-class-on="active"
        >
    </a>

   <?php else: ?>
    <a class="btn btn-link btn-sm act-notify-modal" title='<?php echo lang("action_favorite") ?>' href="javascript:void(0)"
       data-content="<?php echo lang("notice_please_login_to_use_function") ?>"><i
            class="fa fa-bookmark"></i> <?php echo lang("action_favorite") ?></a>
<?php endif; ?>
