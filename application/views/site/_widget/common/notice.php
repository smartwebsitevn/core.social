<?php
$notice = mod('notice')->get($type);
if($notice): ?>
	<div class="text-center container mt20">
		<div class="alert  alert-dismissible alert-warning-job2" role="alert" style="width:100%;max-width:850px;margin-left:auto;margin-right:auto;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"></span></button>
			<strong>Thông báo: </strong>
			<br>
			<?php echo $notice->content ?>
		</div>
	</div>
	<div class="clear"></div>
<?php endif; ?>