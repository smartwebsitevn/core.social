<script>
$(document).ready(function(){
	$('input[name="delete_all"]').keyup(function(){
		$('input.services_day').val($(this).val());
	});
})
</script>
<style>
input.services_day, input[name="delete_all"]{
	width:200px;
}
</style>
<?php
	$mr = [];
	$args = $this->data;

	$rows = array();
	
	$rows[] = [
	    'type'  => 'text',
	    'param' => 'delete_all',
	    'desc'  => lang('day_note'),
	    'attr'   => array('placeholder' => 'Nhập số ngày'),
	];
	
	$rows[] = [
			'type'  => 'text',
			'param' => 'username',
			'name'  => 'Thành viên muốn xóa lịch sử',
			'desc'  => 'Nếu muốn xóa cho tất cả thành viên thì bỏ trống',
			'attr'   => array('placeholder' => 'Nhập ID hoặc tài khoản, email, phone thành viên muốn xóa lịch sử'),
	];
	
	$rows[] = '<h3 style="margin:10px">'.lang('services').'</h3>';
	
	foreach ($services as $service)
	{
	    $rows[] = [
			    'type'  => 'text',
				'param' => $service['key'],
	            'name'  => $service['name'],
				'desc'  => lang('day_note'), 
	            'attr'   => array('class' => 'services_day', 'placeholder' => 'Nhập số ngày'),
			];
	}
	
	$rows[] = [
	    'type'  => 'password',
	    'param' => 'password',
	    'req'   => true
	];
	
	$args['form'] = [

		'title' => lang('mod_delete'),
		'rows' => $rows,
	];

	$args['toolbar'] = array();
	echo macro()->page($args);
	