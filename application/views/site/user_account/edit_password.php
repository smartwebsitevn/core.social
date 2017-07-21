<form class="form form-horizontal form_action" method="post" action="<?php echo $user->_url_edit; ?>">
	<input type="hidden" name="_type" value="password">

	<div class="panel-heading">
		<div class="row">
			<div class="col-md-6">
				<h1 class="panel-title">Thay đổi mật khẩu</h1>

			</div>

		</div>

	</div>
	<div class="panel-body">
		<?php
		$rows[] =    [
			'param' => 'password_old',
			'type' 	=> 'password',
			'req' 	=> true,
		];
		$rows[] =    [
			'param' => 'password','type' 	=> 'password',
			'name' 	=> lang('password_new'),
			'req' 	=> true,

		];

		$rows[] =    [
			'param' => 'password_repeat',
			'type' 	=> 'password',
			'req' 	=> true,

		];

		?>
		<div class="row">
			<div class="col-md-12">
			<?php foreach ($rows as $row) {
				echo macro('mr::form')->row($row);
			} ?>
			</div>
			<div class="col-md-12 text-right">
				<a class="btn btn-outline btn-sm mr20"_submit="true"  >Cập nhập</a>
				<a class="show-account-password">Hủy</a>
			</div>
		</div>
	</div>
</form>
