<!-- Main content wrapper -->
<?php
$_row_action =function($row){
	ob_start()
	?>
	<?php //if ($row->_can_view): ?>
	<a href="<?php echo $row->_url_view ?>" title="<?php echo lang('view'); ?>" data-width="60%"  data-height="80%" class="btn btn-info btn-xs lightbox  " ><?php echo lang('view'); ?></a>
	<?php //endif; ?>


	<?php if ($row->_can_del): ?>
		<a _url="<?php echo $row->_url_del; ?>"    notice="<?php echo lang('notice_confirm_del'); ?>" title="<?php echo lang('delete'); ?>" class="btn btn-danger btn-xs  verify_action "
			><?php echo lang('delete'); ?></a>
	<?php endif; ?>
	<br>
	<?php if ($row->status): ?>
		<a href="#0" class="btn btn-warning btn-xs  verify_action mt5" notice="Bạn có chắc muốn hủy xác thực bình luận này?<?php ?>"
		   _url="<?php echo admin_url('comment/unverify/' . $row->id) ?>">Hủy xác thực</a>
	<?php else: ?>
		<a href="#0" class="btn btn-info btn-xs  verify_action mt5" notice="Bạn có chắc muốn xác thực bình luận này?<?php ?>"
		   _url="<?php echo admin_url('comment/verify/' . $row->id) ?>">Xác thực</a>
	<?php endif; ?>

	<?php return ob_get_clean();
};
$_macro = $this->data;
$_macro['toolbar'] = array(
	array('url' => admin_url('comment/site_edit'), 'title' => lang('setting'))


);
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('comment'), 'title' => lang('mod_comment') ),
	array('url' => admin_url('comment/site'), 'title' => lang('mod_comment_site'),'attr'=>array('class'=>'active'))
);
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['filters'] = array(
	array(  'param' => 'user','name' => lang('user_id'),
		'value' 	=>$filter['user'],
	),
	/*array(  'param' => 'table_name','name' => lang('id'),
		'value' 	=>$filter['table_name'],
	),*/
	array(
		'name' => lang('verify'), 'type' => 'select', 'param' => 'status',
		'value' => $filter['status'],
		'values' => array('on' => lang('status_yes'), 'off' => lang('status_no')),
	),
	array(
		'param' => 'readed','name' 	=>  lang('check'), 'type'=> 'select',
		'value' => $filter['readed'],
		'values' => array('yes' => lang('read_yes'), 'no' => lang('read_no')),
	),

);

$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'user'		=> lang('user'),
	'rate'		=> lang('rate'),
	'status'		=> lang('verify'),
	'readed'		=> lang('check'),
	'created'		=> lang('created'),
	'action' 	=> lang('action'),
);


$_data_title = function ($row) {
	ob_start(); ?>

	<a href="<?php echo $row->_url_view; ?>" target="_blank" style="float:left; margin-right: 5px;">
		<img src="<?php echo $row->image->url_thumb; ?>" height="40px" width="50px" />
	</a>
	<a href="<?php echo $row->_url_view; ?>" target="_blank" >
		<?php echo $row->name.'<br> [ID '.$row->id.']';; ?>
	</a>

	<?php return ob_get_clean();
};

$_rows = array();
foreach ($list as $row)
{
	//pr($row);
	$status = ($row->status) ? 'on' : 'off';
	$status_text = ($row->status) ? 'status_yes' : 'status_no';
	$readed_status = ($row->readed) ? 'on' : 'off';
	$readed_text = ($row->readed) ? 'read_yes' : 'read_no';
	$r = (array) $row;
	$r['rate'] 	= $r['rate'] . ' sao';
	if($row->user)
		$r['user'] 	= $row->user->name.'<br> [User ID '.$row->user->id.']';
	else
		$r['user'] 	= '[deleted]';
	$r['status'] = macro()->status_color($status,$status_text);
	$r['readed'] = macro()->status_color($readed_status,$readed_text) ;
	$r['created'] 	= isset($row->_created_full)?$row->_created_full:'';
	$r['action'] 	= $_row_action($row);
	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

