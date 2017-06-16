<?php if($can_do): ?>
	<?php if($reported): ?>
		<a  href="javascript:void(0)" title='<?php echo lang("action_report") ?>'    class="action btn act-notify-modal" data-content="<?php echo lang("notice_reported") ?>" ><i class="fa fa-warning"></i> <?php echo lang('action_report') ?></a>
	<?php else: ?>
	<a  href="javascript:void(0)" title="<?php echo lang("action_report") ?>" data-toggle="modal" data-target="#modal-report" class="action btn"><i class="fa warning"></i> <?php echo lang('action_report') ?></a>
	<div id="modal-report" class="modal fade" tabindex="-1" role="dialog"     >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true"></span></button>
				<h4 class="modal-title"><?php echo lang("action_report") ?><?php echo lang("action_report") ?></h4>
			</div>
			<div class="modal-body">
				<form class="form_action form-horizontal" method="post" action="<?php echo $url_report ?>">
					<div class="form-group p20">
                        	<textarea name="content" placeholder="<?php echo lang("report_content") ?>" rows="4"
								  class="form-control"></textarea>

						<div name="content_error" class="error"></div>
					</div>
					<!--<div class="form-group row ">
						<div class="col-md-6">
							<input name="email" type="text" placeholder="Email" class="form-control">

							<div name="email_error" class="error"></div>
						</div>
						<div class="col-md-6">
							<input name="phone" type="text" placeholder="So dien thoai " class="form-control">

							<div name="phone_error" class="error"></div>
						</div>
					</div>-->
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
	<a  href="javascript:void(0)" title='<?php echo lang("action_report") ?>'    class="action btn  act-notify-modal" data-content="<?php echo lang('notice_please_login_to_use_function') ?>" ><i class="fa fa-warning"></i> <?php echo lang('action_report') ?></a>
<?php endif; ?>