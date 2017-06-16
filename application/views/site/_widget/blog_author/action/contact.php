<?php if(!$can_do): ?>
	<?php if(0): ?>
		<a  href="javascript:void(0)" title='<?php echo lang('contact_title') ?>'    class="action btn act-notify-modal" data-content="<?php echo lang('notice_contacted') ?>" ><i class="fa fa-envelope-o"></i> <?php echo lang('action_contact') ?></a>
	<?php else: ?>
	<a  href="javascript:void(0)" title="<?php echo lang('contact_title') ?>" data-toggle="modal" data-target="#modal-contact" class="action btn"><i class="fa fa-envelope-o"></i> <?php echo lang('action_contact') ?></a>
	<div id="modal-contact" class="modal fade" tabindex="-1" role="dialog"     >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true"></span></button>
				<h4 class="modal-title"><?php echo lang('contact_title') ?></h4>
			</div>
			<div class="modal-body">
				<form class="form_action form-horizontal" method="post" action="<?php echo $url_contact ?>">

					<div class="form-group row ">
						<label class="col-sm-3 control-label">
							<?php echo lang("client_name") ?>
						</label>
						<div class="col-sm-9">
							<input name="contact_name" type="text" placeholder="" class="form-control">
							<div name="contact_name_error" class="error"></div>
						</div>
					</div>
					<div class="form-group row ">
						<label class="col-sm-3 control-label">
							<?php echo lang("client_email") ?>
						</label>

						<div class="col-md-9">
							<input name="email" type="text" placeholder="" class="form-control">
							<div name="email_error" class="error"></div>
						</div>
					</div>
					<div class="form-group row ">
						<label class="col-sm-3 control-label">
							<?php echo lang("title") ?>
						</label>

						<div class="col-md-9">
							<input name="subject" type="text" placeholder="" class="form-control">
							<div name="subject_error" class="error"></div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 control-label">
							<?php echo lang("contact_opinion") ?>

						</label>
						<div class="col-sm-9">
							<textarea name="message"  rows="4"
									  class="form-control"></textarea>
							<div name="message_error" class="error"></div>
						</div>
					</div>
					<?php echo macro('mr::form')->captcha($captcha); ?>
					<button class="btn btn-danger" type="submit"><?php echo lang("button_submit") ?></button>
					<button data-dismiss="modal" class="btn btn-link" type="button"><?php echo lang("button_cancel") ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
	<?php endif; ?>
<?php else: ?>
	<a  href="javascript:void(0)" title='Góp ý - Cải thiện player'    class="action btn  act-notify-modal" data-content="<?php echo lang('notice_please_login_to_use_function') ?>" ><i class="fa fa-envelope-o"></i> Góp ý<?php //echo lang('action_report') ?></a>
<?php endif; ?>