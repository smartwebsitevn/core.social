<?php if ($can_do): ?>
	<a  href="#0" title='' class="icon icon-favorite do_action <?php if ($favorited) echo 'on'; ?>"
		data-action="toggle"
		data-field="_"
		data-url-on="<?php echo $url_favorite ?>"
		data-url-off="<?php echo $url_favorite_del ?>"
		data-title-on='<?php echo lang("action_favorite_del") ?>'
		data-title-off='<?php echo lang("action_favorite") ?>'
		data-class-on="active"
		>
	</a>
<?php endif; ?>
