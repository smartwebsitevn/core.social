<?php if ($can_do): ?>
	<a  href="#0" title='' class="btn btn-default btn-sm  do_action <?php if ($subscribed) echo 'on'; ?>"
		data-action="toggle"
		data-url-on="<?php echo $url_subscribe ?>"
		data-url-off="<?php echo $url_subscribe_del ?>"
		data-title-on='<?php echo lang("action_subscribe_del") ?>'
		data-title-off='<?php echo lang("action_subscribe") ?>'
		data-text-on='<i class="pe-7s-like"></i> Hủy theo dõi<?php //echo lang("action_subscribe_del") ?>'
		data-text-off='<i class="pe-7s-like"></i> Theo dõi<?php // echo lang("action_subscribe") ?>'
		data-class-on="active"
		>
	</a>

<?php else: ?>
	<a class="btn btn-default btn-sm act-notify-modal" title='<?php echo lang("action_subscribe") ?>' href="javascript:void(0)"
	   data-content="<?php echo lang("notice_please_login_to_use_function") ?>"><i
			class="fa fa-bookmark"></i> <?php echo lang("action_subscribe") ?></a>
<?php endif; ?>
