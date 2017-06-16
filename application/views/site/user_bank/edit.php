
<script type="text/javascript">
	$(document).ready(function () {
		$('.form_action').each(function()
		{
			var $this = $(this);
			$this.nstUI('formAction', {
				field_load: $this.attr('_field_load'),
				event_complete: function(data){
					if(data.location)
						location.href = data.location;
					else
						window.location.reload();
				},
				event_error: function(data)
				{

					// Reset captcha
					if (data['security_code'])
					{
						var captcha = $this.find('img[_captcha]').attr('id');
						if (captcha)
						{
							change_captcha(captcha);
						}
					}
				},
			});
		});
	})
</script>
<style>
	.t-form  .col-sm-9 select.input-long{
		width:325px !important;
	}
</style>
<div class="panel panel-default">

	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('edit')?></h3>
	</div>

	<div class="panel-body">
		<form class="form_action t-form label-right form-horizontal" action="<?php echo $action; ?>" method="post">

			<div class="form-group">
				<label class="col-sm-3 control-label" for="name"><?php echo lang('bank')?>:</label>
				<div class="col-sm-9">
					<?php echo $info->bank->name?>
					<div class="clear"></div>
					<div name="bank_error" class="form-error"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php echo lang('bank_branch')?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="bank_branch" value="<?php echo $info->bank_branch;?>" id="param_bank_branch" class="form-control">
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
						<option value='<?php echo $row->id?>' <?php echo form_set_select($row->id, $info->city_id)?>><?php echo $row->name?></option>
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
					<input type="text" name="bank_account" id="param_bank_account" value="<?php echo $info->bank_account;?>" class="form-control">
					<div class="clear"></div>
					<div class="form-error" name="bank_account_error"></div>
				</div>
				<div class="clear"></div>
			</div>


			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php echo lang('bank_account_name')?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="bank_account_name" id="param_bank_account_name" value="<?php echo $info->bank_account_name;?>" class="form-control">
					<div class="clear"></div>
					<div class="form-error" name="bank_account_name_error"></div>
				</div>
				<div class="clear"></div>
			</div>
<!--
			<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php /*echo lang('pin')*/?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input  name="pin" id="param_pin"  type="password"  class="form-control">
					<div class="clear"></div>
					<div class="form-error" name="pin_error"></div>
				</div>
				<div class="clear"></div>
			</div>
-->
			<?php  echo macro('mr::form')->captcha($captcha); ?>

			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>

				<div class="col-sm-9">
					<input type="submit" value="<?php echo lang('edit')?>" class="btn btn-default">
				</div>
			</div>
		</form>
	</div>
</div>
