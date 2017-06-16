<?php  echo macro()->page_heading(lang('title_contact_send'))?>
<?php echo macro()->page_body_start()?>
<form class="t-form form_action form-horizontal p40" action="<?php echo $action; ?>" method="post">

	<div class="form-group">
		<label class="col-sm-2 control-label" for="param_name"><?php echo lang('name'); ?>:<span class="req">*</span></label>
		<div class="col-sm-9">
			<input name="name" id="param_name" value="<?php echo $client['name']; ?>" type="text" class="t-input form-control" />
			<span name="name_autocheck" class="autocheck"></span>
			<div class="clear"></div>
			<div name="name_error" class="error"></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="param_email"><?php echo lang('email'); ?>:<span class="req">*</span></label>
		<div class="col-sm-9">
			<input name="email" id="param_email" value="<?php echo $client['email']; ?>" type="text" class="t-input form-control" />
			<span name="email_autocheck" class="autocheck"></span>
			<div class="clear"></div>
			<div name="email_error" class="error"></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="param_subject"><?php echo lang('subject'); ?>:<span class="req">*</span></label>
		<div class="col-sm-9">
			<input name="subject" id="param_subject" type="text" class="t-input form-control" />
			<span name="subject_autocheck" class="autocheck"></span>
			<div class="clear"></div>
			<div name="subject_error" class="error"></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="param_message"><?php echo lang('message'); ?>:<span class="req">*</span></label>
		<div class="col-sm-9">
			<textarea name="message" id="param_message" class="t-input form-control autosize" ></textarea>
			<span name="message_autocheck" class="autocheck"></span>
			<div class="clear"></div>
			<div name="message_error" class="error"></div>
		</div>
		<div class="clear"></div>
	</div>

	<?php echo macro('mr::form')->captcha(['layout_opts'=>['label_col'=>2]]); ?>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-9">
			<button type="submit" class="btn btn-default"><?php echo lang('button_send'); ?></button>
		</div>
	</div>



</form>
<?php echo macro()->page_body_end()  ?>

