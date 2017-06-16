<hr style="border-color:#ddd"/>
<?php if($subscribed):?>

<div  id="subscribe-unsubscribe" >
<h4 class="subscribe-title"><?php echo lang('subscribed_title')?></h4>
<div id="subscribe-message" class="text-block">
    <span class="subscribe-promote"><?php echo lang('subscribed_thank')?></span>

	<a  title='<?php echo lang('button_subscribe_del')?>' class="btn btn-link do_action"
		data-url="<?php echo $url_subscribe_del ?>"
		><i class="fa fa-eye"></i> <?php echo lang('button_subscribe_del') ?></a>
</div>
</div>

<?php
//== chua dang ky theo doi
else :?>
<div  id="subscribe-container-pre" >
<h4 >
	<?php
	//pr(mod('movie')->config('movie_type_series'));
	//pr($movie);
	if($movie->_type == 'series')
		echo lang('subscribe_title_series');
	//if($movie->type ==  mod('movie')->config('movie_type_trailer'))
	//	echo lang('subscribe_title_trailer')
	?>
</h4>
<div class="text-block">
    <span ><?php echo lang('subscribe_hint')?></span>
	<?php if($can_do): ?>

		<a  title='<?php echo lang('button_subscribe')?>' class="btn btn-link do_action"
			data-url="<?php echo $url_subscribe ?>"
			><i class="fa fa-eye"></i> <?php echo lang('button_subscribe') ?></a>
		<?php /* ?>
		<a rel="nofollow" title="<?php echo lang('button_subscribe')?><?php echo $movie->name?>" class="btn btn-link" id="btn-subscribe"><i class="fa fa-eye"></i> <?php echo lang('button_subscribe')?></a>
		<?php */ ?>

 <?php else: ?>
	<a class="btn btn-link act-notify-modal"  data-content="<?php echo lang('notice_please_login_to_use_function') ?>"><i class="fa fa-eye"></i> <?php echo lang('button_subscribe')?></a>
<?php endif; ?>
</div>
</div>
 <?php /* //chuc nang dang ky theo doi danh cho khach vang lai?>
 <script type="text/javascript">

$(document).ready(function()
{
	$('#btn-subscribe').click(function(){
		$('#subscribe-container-pre').hide();
		$('#subscribe-container').show();

	})
	$('.form_action_subscribe').nstUI({
					method:	'formAction',
					formAction:	{
						field_load: $(this).attr('_field_load'),
						event_complete: function(data)
						{
							//$('#subscribe-container').hide();
							//$('#subscribe-unsubscribe').show();
							window.location.reload();
						}

					}
	});

});
</script>
<div id="subscribe-container" style="display:none" >
<h4 class="subscribe-title"><?php echo lang('subscribe_title')?></h4>
	<form class="form_action_subscribe form-horizontal" action="<?php echo $url_subscribe?>" method="post">
		<div class="form-group">
			<label  class="col-sm-2 control-label"><?php echo $this->lang->line('client_email'); ?>:<span class="req">*</span></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" name="client_email">
				<div name="client_email_error" class="error"></div>

			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('client_name'); ?>:<span class="req">*</span></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" name="client_name">
				<div name="client_name_error" class="error"></div>

			</div>
		</div>
		<?php echo macro('mr::form')->captcha(array('layout_opts'=>array('label_col'=>2,'input_col'=>10))); ?>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button class="btn btn-link" type="submit"><i class="fa fa-eye"></i> <?php echo $this->lang->line('button_subscribe'); ?></button>
			</div>
		</div>

	</form>

</div>
<div  id="subscribe-unsubscribe" style="display:none">
<h4 class="subscribe-title"><?php echo lang('subscribed_title')?></h4>
<div id="subscribe-message" class="text-block">
    <span class="subscribe-promote"><?php echo lang('subscribed_thank')?></span>
    <a rel="nofollow" data-hash="" href="" title="<?php echo lang('button_subscribed_del')?><?php echo $movie->name?>" class="btn btn-link" id="btn-unsubscribe"><?php echo lang('button_subscribed_del')?></a>
</div>
</div>
<div class="clear"></div>
<?php */?>
 <?php endif;?>
<hr style="border-color:#ddd"/>