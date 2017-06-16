
<div class="panel panel-default">

	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('titleConfim')?></h3>
	</div>

	<div class="panel-body">
		<form class="form_action t-form label-right form-horizontal" action="<?php echo $action; ?>" method="post">
			<div class="form-group param_static">
				<label class="col-sm-3 control-label"><?php echo lang("bank")?></label>
				<div class="col-sm-9">
					<div style="font-size:16px; font-weight:600; padding-top:5px;">
						<b class="text-primary"><?php echo $info->bank->name?></b>
					</div>
				</div>
			</div>
			<div class="form-group param_static">
				<label class="col-sm-3 control-label"><?php echo lang("bank_account")?></label>
				<div class="col-sm-9">
					<div style="font-size:16px; font-weight:600; padding-top:5px;">
						<b class="text-primary"><?php echo $info->bank_account?></b>
					</div>
				</div>
			</div>
			<div class="form-group param_static">
				<label class="col-sm-3 control-label"><?php echo lang("bank_account_name")?></label>
				<div class="col-sm-9">
					<div style="font-size:16px; font-weight:600; padding-top:5px;">
						<b class="text-primary"><?php echo $info->bank_account_name?></b>
					</div>
				</div>
			</div>
			<?php  echo mod('user_security')->form($key_confim); ?>

			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>

				<div class="col-sm-9">
					<input type="submit" value="<?php echo lang('btnConfim')?>" class="btn btn-default">
				</div>
			</div>
		</form>
	</div>
</div>
