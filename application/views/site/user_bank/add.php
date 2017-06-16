<div class="panel panel-default">

	<div class="panel-heading">
		<h3 class="panel-title">Thêm tài khoản ngân hàng<?php //echo lang('add')?></h3>
	</div>

	<div class="panel-body">
		<form class="form_action t-form label-right form-horizontal" action="<?php echo $action; ?>" method="post">
			<?php if ( ! empty($message)):?>
				<?php $this->widget->site->message($message); ?>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="name"><?php echo lang('bank'); ?>: <span class="req">*</span></label>
				<div class="col-sm-9">
			<span class="one">
				<select name='bank' _autocheck="true" class="form-control input-long"  >
					<option><?php echo lang('select_bank')?></option>
					<?php foreach ($banks as $row):?>
						<option value='<?php echo $row->id?>'><?php echo $row->name?></option>
					<?php endforeach;;?>
				</select>
			</span>
					<div class="clear"></div>
					<div name="bank_error" class="form-error"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php echo lang('bank_branch')?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="bank_branch" id="param_bank_branch" class="form-control">
					<div class="clear"></div>
					<div class="form-error" name="bank_branch_error"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label" for="name"><?php echo lang('city'); ?>:</label>
				<div class="col-sm-9">
			<span class="one">
			<select name='city' _autocheck="true" class="form-control input-long"  >
				<option><?php echo lang('select_city')?></option>
				<?php foreach ($citys as $row):?>
					<option value='<?php echo $row->id?>'><?php echo $row->name?></option>
				<?php endforeach;;?>
			</select>
			</span>
					<div class="clear"></div>
					<div name="city_error" class="form-error"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php echo lang('bank_account')?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="bank_account" id="param_bank_account" class="form-control">
					<div class="clear"></div>
					<div class="form-error" name="bank_account_error"></div>
				</div>
				<div class="clear"></div>
			</div>


			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php echo lang('bank_account_name')?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="bank_account_name" id="param_bank_account_name" class="form-control">
					<div class="clear"></div>
					<div class="form-error" name="bank_account_name_error"></div>
				</div>
				<div class="clear"></div>
			</div>
<!--
			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php /*echo lang('pin')*/?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input  name="pin" id="param_pin" type="password" class="form-control">
					<div class="clear"></div>
					<div class="form-error" name="pin_error"></div>
				</div>
				<div class="clear"></div>
			</div>-->

			<?php  echo macro('mr::form')->captcha($captcha); ?>

			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>

				<div class="col-sm-9">
					<input type="submit" value="<?php echo lang('add')?>" class="btn btn-default">
				</div>
			</div>
		</form>
	</div>
</div>