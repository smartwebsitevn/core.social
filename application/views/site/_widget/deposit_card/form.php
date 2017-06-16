<?php if(!user_is_login()):?>
<script>
$(document).ready(function(){
	$('#deposit_card').find('input[type="submit"]').parents('.col-sm-9').html('<p style="color:red">Bạn cần đăng nhập để sử dụng chức năng này</p>');
	$('#deposit_card').find('input[type="submit"]').remove();
})
</script>
<?php endif;?>
<?php 
	echo macro('mr::form')->form([

		//'title' => lang('title_deposit_card'),

		'data' 	=> $input,
	    'action' => site_url('deposit_card'),
		'rows' => [
		
			'<div name="card_error" class="nNote nWarning hideit"></div>',
			
			[
				'param' 	=> 'type',
				'type' 		=> 'select',
				'name' 		=> lang('card_type'),
				'values' 	=> macro('mr::form')->make_options(array_pluck($types, 'name', 'id')),
				'req' 		=> true,
			],
		
			[
				'param' 	=> 'code',
				'name' 		=> lang('card_code'),
				'req' 		=> true,
				'attr' 		=> [
					'placeholder' => 'Nhập mã số sau lớp bạc mỏng',
				],
			],
		
			[
				'param' 	=> 'serial',
				'name' 		=> lang('card_serial'),
				'req' 		=> true,
				'attr' 		=> [
					'placeholder' => 'Nhập mã serial nằm sau thẻ',
				],
			],

		],

	]);
?>
