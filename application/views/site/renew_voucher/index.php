<div class="panel panel-default " id="plan_order_form">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('title_renew_voucher'); ?></h3>
	</div>
	<div class="panel-body">
		<?php echo widget('tran')->menu('voucher')
 ?>
		<form class="form_action t-form label-right form-horizontal" action="<?php echo $action; ?>" method="post">
			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php echo lang('key') ?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="key" id="param_key" class="form-control">
					<div class="clear"></div>
					<div class="error" name="key_error"></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php echo macro('mr::form')->captcha($captcha); ?>
			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-9">
					<input type="submit" value="<?php echo lang('renew') ?>" class="btn btn-default">
				</div>
			</div>
		</form>
	</div>
</div>


<div id="result_voucher">
</div>