<?php if(!$ordered): ?>
	<?php if($can_do): ?>
		<a  class="btn btn-link btn-link pr5 pl5"  onclick="mfc.order_movie()"    href="javascript:void(0)" title='<?php echo lang("action_buy") ?>' ><i class="fa fa-shopping-cart"></i> <?php echo lang("action_buy") ?></a>
	<?php else: ?>
		<a class="btn btn-link btn-link pr5 pl5 act-notify-modal" title='<?php echo lang("action_buy") ?>'  href="javascript:void(0)"  data-content="<?php echo lang('notice_please_login_to_use_function') ?>"><i class="fa fa-shopping-cart"></i> <?php echo lang("action_buy") ?></a>
	<?php  endif; ?>
<?php endif; ?>