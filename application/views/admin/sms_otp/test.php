<script type="text/javascript"> 
(function($)
{
	$(document).ready(function()
	{
		var main = $('#main_sms');

		$('.btn-primary').click(function(){
			$('.btn-primary').val('Hệ thống đang gửi tin nhắn...');
		});
		
		// Form action
		main.nstUI({
			method:	'formAction',
			formAction:	{
				field_load: main.attr('_field_load')
			}
		});
		
		// Close colorbox
		main.find('input[type=reset]').click(function()
		{
			$.colorbox.close();
			return false;
		});
		
	});
})(jQuery);
</script>


 <div class="portlet" style="width:600px;height:300px;padding:20px" >
 <div class="col-lg-12">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4><i class="fa fa-pencil"></i> Test gửi OTP qua cổng <?php echo $code?></h4>
            </div>
        </div>
         <div  class="panel-collapse collapse in">
            <div class="portlet-body ">
        <form  id="main_sms" action="<?php echo $action; ?>" class="form form-horizontal " accept-charset="UTF-8" method="post">
	
		<div class="form-group param_text">
			<label class="col-sm-3  control-label" for="param_phone"><?php echo lang('phone'); ?>:<span class="req">*</span></label>
			<div class="col-sm-9">
				<input name="phone" id="param_phone" _autocheck="true" class="left" style="width:250px;" type="text" />
				<span name="phone_autocheck" class="autocheck"></span>
				<div name="phone_error" class=" error"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="form-group param_text">
			<label class="col-sm-3  control-label" for="param_smsMessage"><?php echo lang('smsMessage'); ?>:<span class="req">*</span></label>
			<div class="col-sm-9">
				<input name="smsMessage" id="param_smsMessage" _autocheck="true" class="left" style="width:250px;" type="text" />
				<div class="clear"></div>
				<p><?php echo lang('smsMessage_notice')?></p>
				<span name="smsMessage_autocheck" class="autocheck"></span>
				<div name="smsMessage_error" class=" error"></div>
			</div>
			<div class="clear"></div>
		</div>
		 <div class="clear"></div>

        <div class="form-actions">
            <div class="form-group formSubmit">
                <div class="col-sm-offset-3 col-sm-10">
                    <input type="submit" class="btn btn-primary" value="Thực hiện">
                </div>
            </div>
        </div>
		<div class="clear"></div>
	
</form>
</div>
</div>
</div>
</div>
