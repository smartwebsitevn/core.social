<div class="panel-heading">
	<div class="row">
		<div class="col-md-6">
			<h1 class="panel-title">Cài đặt tài khoản</h1>
		</div>
		<div class="col-md-6 text-right">
			<a class="btn btn-default show-account-info-edit" href="#0">Chỉnh sửa</a>
		</div>
	</div>

</div>
<?php //pr($user); ?>
<div class="panel-body p0">
	<table class="table  table-hover">
		<tbody>
		<tr>
			<td width="25%">
				<div class="item-photo">
				<?php t('view')->load('tpl::_widget/user/display/item/info_avatar',['row'=>$user]) ?>
				</div>
			</td>
			<td>
				<div class="item-attach">
					<?php if ($user->attach_id): //pr($user); ?>
						<?php $attach= $user->attach?>
						<?php if (!empty($attach) && file_exists($attach->path)): ?>
							<?php
							$file_infos = file_parse($attach->path);
							?>

								<a href="<?php echo $attach->url ?>"
								   target="_blank">
									<i class="fa fa-<?php echo $file_infos['icon'] ?>"></i><br>

									<?php echo $attach->name ?>
								</a>
						<?php endif; ?>

					<?php endif; ?>


					<?php // view('tpl::_widget/user/display/item/info_attach_name',['row'=>$user]) ?>
				</div>
			</td>
		</tr>
		<tr>
			<td><b><?php echo lang('name'); ?></b></td>
			<td><?php echo $user->name; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('profession'); ?></b></td>
			<td><?php echo $user->profession; ?></td>
		</tr>

		<tr>
			<td><b><?php echo lang('phone'); ?></b></td>
			<td><?php echo $user->phone; ?></td>
		</tr>
		<tr>
			<td width="25%"><b><?php echo lang('email'); ?></b></td>
			<td><?php echo $user->email; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('facebook'); ?></b></td>
			<td><?php echo $user->facebook; ?></td>
		</tr>

		<tr>
			<td><b><?php echo lang('website'); ?></b></td>
			<td><?php echo $user->website; ?></td>
		</tr>
		<?php /* ?>
		<tr>
			<td><b><?php echo lang('gender'); ?></b></td>
			<td><?php echo $user->_gender; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('birthday'); ?></b></td>
			<td><?php echo $user->birthday; ?></td>
		</tr>

 		<?php */ ?>



		<tr>
			<td><b>Loại thành viên<?php //echo lang('user_type'); ?></b></td>
			<td><?php echo $user->_type_name; ?></td>
		</tr>
		<tr>
			<td><b>Lĩnh vực hoạt động<?php //echo lang('user_type'); ?></b></td>
			<td>
				<?php echo  $user->_job_name; ?>
			</td>
		</tr>
		<?php /* ?>
		<tr>
			<td><b><?php echo lang('user_group'); ?></b></td>
			<td><?php echo $user->user_group->name; ?></td>
		</tr>
		<?php */ ?>
		<tr>
			<td><b>Nơi làm việc<?php // echo lang('country'); ?></b></td>
			<td><?php echo  $user->_working_city_name; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('address'); ?></b></td>
			<td><?php echo $user->address; ?></td>
		</tr>
		<tr>
			<td><b>Tự giới thiệu<?php //echo lang('birthday'); ?></b></td>
			<td><?php echo macro()->more_word($user->desc,40); ?></td>

		</tr>
		<tr>
			<td><b><?php echo lang('user_date_added'); ?></b></td>
			<td><?php echo get_date($user->created, "full"); ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('last_login'); ?></b></td>
			<td><?php echo  get_date($user->last_login, "full")?></td>
		</tr>

		</tbody>
	</table>
</div>
