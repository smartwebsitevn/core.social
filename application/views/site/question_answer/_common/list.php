<form class="form-comment-box form_action" method="POST" action="<?php echo site_url('question_answer') ?>">
	<div class="form-item">
		<div class="form-input pull-left">
			<textarea class="text-textarea" name="question" placeholder="<?php echo lang('question_write') ?>"></textarea>
			<br/>
			<div name="question_error" class="error"></div>
		</div>
		<div class="form-action">
			<button type="submit" class="submit"><?php echo lang('question_submit') ?></button>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="text-comment">
		<?php foreach ($list as $row):?>
			<div class="item-cmt">
				<div class="avatar-img">
					<?php /* ?>
                    <img alt="" src="<?php echo isset($row->user)?$row->user->avatar->url_thumb:public_url('img/user_no_image.png')?>" class="media-object user-avatar">
                    <?php */ ?>
					<img alt="" src="<?php echo $row->user_avatar->url_thumb?>" class="media-object user-avatar">

				</div>
				<div class="info">
					<div class="name">
						<a href="javascript:void()"><?php echo $row->user_name ?></a>
					</div>
					<div class="body">
						<p><?php echo $row->question ?></p>
					</div>
					<div class="time">
						<span class="reply"><a href="#"><?php echo lang('question_time') ?></a></span>
						<span><?php echo $row->_created_time ?></span>
						<?php if($row->answer != ''): ?><br>
							<?php  echo handle_content($row->answer, 'output') ?>
						<?php endif; ?>

					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</form>