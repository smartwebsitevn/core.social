<?php
$public_url_js=public_url('js');
?>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/script.js"></script>

<?php

echo macro('mr::form')->form([

	'action'     => $user->_url_edit,
	'title'      => lang('title_user_edit'),
	'btn_submit' => lang('button_update'),

	'rows' => [

		macro('mr::form')->row_title(lang('block_change_pass')),

		[
			'param' => 'password',
			'type' 	=> 'password',
			'name' 	=> lang('password_new'),
			'desc' 	=> lang('note_password_change'),
		],

		[
			'param' => 'password_repeat',
			'type' 	=> 'password',
		],

		macro('mr::form')->row_title(lang('block_change_info')),

		[
			'param' => 'name',
			'name' 	=> lang('full_name'),
			'value' => $user->name,
			'req' 	=> true,
		],
		[
			'param' 	=> 'avatar','name'=>lang('Avatar'),
			'type' 		=> 'image',
			'_upload' 	=> $upload_avatar,
		],

		[
			'param' => 'address',
			'value' => $user->address,
		],

		[
			'param' => 'password_old',
			'type' 	=> 'password',
			'req' 	=> true,
		],

	],

]);