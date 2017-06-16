<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4><i class="fa fa-info"></i> <?php echo lang('message_view'); ?></h4>
		</div>

	</div>
	<div class="portlet-body ">
		<table class="table table-bordered table-striped table-hover tc-table">
			<tbody>
			<tr>
				<td class="row_label"><?php echo lang('id'); ?></td>
				<td class="row_item">
					<?php echo $message->id; ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('title'); ?></td>
				<td class="row_item">
					<?php echo htmlentities($message->title); ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('user'); ?></td>
				<td class="row_item">
					<?php $user = $message->user;?>
					<?php if(isset($user->name)):?>
						<b title="<?php echo $user->name; ?>" class="tipE">
							<?php echo word_limiter($user->name, 5); ?> (<b style="color:red"><?php echo lang('user_level_'.$user->level)?></b>)
						</b><br/>
						<span title="<?php echo $user->username.'|'.$user->email; ?>" class="tipE">
        						<?php echo $user->username; ?><br/>
							<?php echo character_limiter_len($user->email, 30); ?><br/>
							<?php echo $user->phone; ?>
        					</span>
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class="row_label"><?php echo lang('status'); ?></td>
				<td class="row_item">
					<?php
					if($message->admin_readed){
						echo '<span class="label label-info">'.lang('status_readed').'</span><br>';
						echo get_date($message->admin_readed_time,'full');
					}
					else
						echo '<span class="label ">'.lang('status_unreaded').'</span>';
					?>
					<br>
					<?php
					if($message->admin_replyed){
						echo '<span class="label label-info">'.lang('status_replyed').'</span><br>';
						echo get_date($message->admin_replyed_time,'full');
					}
					else
						echo '<span class="label ">'.lang('status_unreplyed').'</span>';
					?>
				</td>
			</tr>
			<tr>
				<td class="row_label"><?php echo lang('created'); ?></td>
				<td class="row_item">
					<?php echo $message->_created_full; ?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<b><?php echo lang("content") ?></b><br>

					<?php echo nl2br(htmlentities($message->content)); ?>
					<hr class="m20">
					<b><?php echo lang("reply") ?></b><br>
					<?php	if(!$message->admin_replyed):?>
						<form class="form_action form-horizontal" method="post" >
							<input type="hidden" name="id" value="<?php echo $message->id?>">
							<?php 	echo macro('mr::form')->row(array(
								'param' => 'reply',"type"=>"html"
							)); ?>
							<div class="form-actions">
								<div class="form-group formSubmit">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="<?php echo  lang('button_update'); ?>" class="btn btn-primary" />
									</div>
								</div>
							</div>

							<div class="clear"></div>
						</form>
						<?php else: ?>
						<?php echo nl2br(htmlentities($message->admin_replyed_content)); ?>
						<?php  ?>
					<?php endif; ?>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
		$(document).ready(function()
		{
			$('.form_action').each(function()
			{
				var $this = $(this);
				$this.nstUI('formAction', {
					field_load: $this.attr('_field_load'),
				});
			})
		})
</script>