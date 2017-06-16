
<?php $this->load->view('admin/coupon/_common'); ?>

<!-- Main content wrapper -->
<div class="wrapper">

   	<!-- Form -->
	<form class="form" id="form" action="<?php echo $action; ?>" method="post">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/add.png" class="titleIcon" />
					<h6><?php echo lang('add'); ?> <?php echo lang('mod_coupon'); ?></h6>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_code"><?php echo lang('code'); ?>:</label>
					<div class="formRight">
						<span class="oneTwo req"><strong><?php echo $code?></strong></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_name"><?php echo lang('name'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<span class="oneTwo"><input name="name" id="param_name" _autocheck="true" type="text" /></span>
						<span name="name_autocheck" class="autocheck"></span>
						<div name="name_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>

			
				<div class="formRow">
					<label class="formLeft" for="param_discount"><?php echo lang('discount'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<span>
						<input name="discount" id="param_discount"  type="text" class="left format_number"  style="width:100px;"/>
						<select name="discount_type"  class="left" style="width:150px;margin-left:5px;height:22px">
							<option value=""><?php echo lang('select_discount_type'); ?>:</option>
							<?php foreach ($discount_types as $k => $v): ?>
								<option value="<?php echo $k; ?>">
									<?php echo lang('discount_type_'.$v); ?>
								</option>
							<?php endforeach; ?>
						</select>
						</span>
						<span name="discount_autocheck" class="autocheck"></span>
						<div name="discount_error" class="clear error"></div>
						<span name="discount_type_autocheck" class="autocheck"></span>
						<div name="discount_type_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_number_user"><?php echo lang('number_user'); ?>:</label>
					<div class="formRight">
						<span class="oneTwo"><input name="number_user" id="param_number_user" value="" _autocheck="true" type="text" /></span>
						<span name="number_user_autocheck" class="autocheck"></span>
						<div name="number_user_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_expire"><?php echo lang('expire'); ?>:</label>
					<div class="formRight">
						<span class="oneTwo"><input name="expire" id="param_expire" value="" type="text" class="datepicker" /></span>
						<span name="expire_autocheck" class="autocheck"></span>
						<div name="expire_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_status"><?php echo lang('status'); ?>:</label>
					<div class="formRight">
						<span class="oneTwo">
						    <input type="radio" name="status" value="0" id="status_0"/>
							<label for="status_0"><?php echo lang('no');?></label>
							
							<input type="radio" name="status" value="1" id="status_1" checked/>
							<label for="status_1"><?php echo lang('yes');?></label>
			
						</span>
						<span name="status_autocheck" class="autocheck"></span>
						<div name="status_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
           		<div class="formSubmit">
           			<input type="submit" value="<?php echo lang('button_add'); ?>" class="redB" />
           			<input type="reset" value="<?php echo lang('button_reset'); ?>" class="basic" />
           		</div>
        		<div class="clear"></div>
        		
			</div>
		</fieldset>
	</form>
	
</div>
