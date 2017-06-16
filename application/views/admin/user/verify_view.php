<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('#main_popup');

		// Confirm action
		main.find('.confirm_action').nstUI({
			method:	'confirmAction',
			confirmAction: {
				field_load: 'confirm_action_load'
			}
		});
		
	});
})(jQuery);
</script>


<div class="widget mg0 form_load" style="width:600px;" id="main_popup" style="position:relative;">

	<div class="title">
		<img src="<?php echo public_url('admin'); ?>/images/icons/dark/user.png" class="titleIcon" />
		<h6><?php echo lang('title_verify_info'); ?></h6>
	</div>
	
	<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable">
		<tbody>
			<tr>
				<td style="width:150px;"><?php echo lang('full_name'); ?></td>
				
				<td class="fontB">
					<div class="left"><?php echo $user_verify->name; ?></div>
					
					<div class="right">
						<?php if ($user->_can_verify_accept): ?>
							<a href="" _url="<?php echo $user->_url_verify_accept; ?>" class="button blueB ml5 confirm_action" 
								_notice="<?php echo lang('notice_verify_are_you_sure_want_to_accept', $user_verify->name, $user_group_verify); ?>"
							>
								<span><?php echo lang('button_accept'); ?></span>
							</a>
						<?php endif; ?>
						
						<?php if ($user->_can_verify_cancel): ?>
							<a href="" _url="<?php echo $user->_url_verify_cancel; ?>" class="button redB ml5 confirm_action" 
								_notice="<?php echo lang('notice_verify_are_you_sure_want_to_cancel', $user_verify->name); ?>"
							>
								<span><?php echo lang('button_cancel'); ?></span>
							</a>
						<?php endif; ?>
						
						<div id="confirm_action_load"></div>
					</div>
				</td>
			</tr>
			
			<?php $user_verify->paypal_emails = implode('<br>', $user_verify->paypal_emails); ?>
			<?php foreach (array('phone', 'address', 'card_no', 'card_place', 'card_date'/*, 'paypal_emails'*/) as $p): ?>
				<tr>
					<td><?php echo lang($p); ?></td>
					<td class="fontB"><?php echo $user_verify->$p; ?></td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td colspan="2">
					<div class="gallery pd0 right">
					   <ul>
						<?php foreach (array('image_card_front', 'image_card_back', 'image_photo') as $p): ?>
							<?php if (empty($user_verify->$p)) continue; ?>
							<li>
								<a href="<?php echo $user_verify->$p; ?>" target="_blank" title="<?php echo lang($p); ?>">
									<img src="<?php echo $user_verify->$p; ?>" style="height:100px; max-width:300px;" />
								</a>
							</li>
						<?php endforeach; ?>
					   </ul> 
					   <div class="fix"></div>
				   </div>
				</td>
			</tr>
			
		</tbody>
	</table>
	
</div>
        