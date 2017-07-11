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
<div class="panel-body">
	<table class="table table-bordered table-hover">
		<tbody>
		<tr>
			<td width="25%"><b><?php echo lang('account'); ?></b></td>
			<td><?php echo $user->email; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('full_name'); ?></b></td>
			<td><?php echo $user->name; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('phone'); ?></b></td>
			<td><?php echo $user->phone; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('gender'); ?></b></td>
			<td><?php echo $user->_gender; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('birthday'); ?></b></td>
			<td><?php echo $user->birthday; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('country'); ?></b></td>
			<td><?php echo  isset($user->_country)?$user->_country->name:''; ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('address'); ?></b></td>
			<td><?php echo $user->address; ?></td>
		</tr>

		<tr>
			<td><b><?php echo lang('user_date_added'); ?></b></td>
			<td><?php echo get_date($user->created, "full"); ?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('last_login'); ?></b></td>
			<td><?php echo  get_date($user->last_login, "full")?></td>
		</tr>
		<tr>
			<td><b><?php echo lang('user_group'); ?></b></td>
			<td><?php echo $user->user_group->name; ?></td>
		</tr>
		</tbody>
	</table>
</div>
