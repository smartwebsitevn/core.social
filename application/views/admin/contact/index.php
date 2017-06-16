<!-- Main content wrapper -->
<?php
$_row_action =function($row){
	ob_start()
	?>
	<?php if ($row->_can_view): ?>
		<a href="<?php echo $row->_url_view; ?>" title="<?php echo lang('detail'); ?>" class="tipS lightbox" data-width="70%" >
			<img src="<?php echo public_url('admin') ?>/images/icons/color/view.png" />
		</a>
	<?php endif; ?>

	<?php if ($row->_can_del): ?>
		<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action"
		   notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo htmlentities($row->subject); ?></b>"
			>
			<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
		</a>
	<?php endif;
	?>
<?php return ob_get_clean();
};
$_macro = $this->data;
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
$_macro['toolbar'] = array();
$_macro['toolbar_addon']  =array(
	array(
		'url' 	=> $url_export,'title' => lang('button_export'),'icon' => 'plus',
		'attr'=>array('class'=>'btn btn-primary response_action',
			'notice'=>lang('notice_verify_export'),
			'_url' =>$url_export,
		),
	),

);
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('contact'), 'title' => lang('mod_contact'),'attr'=>array('class'=>'active') ),
	//array('url' => admin_url('contact/register'), 'title' => lang('mod_contact_register'),),
	array('url' => admin_url('contact/order'), 'title' => lang('mod_contact_order'),)
);
$_macro['table']['filters'] = array(
	array(  'param' => 'id',
		'value' 	=>$filter['id'],
	),
	array(  'param' => 'email',
		'value' 	=>$filter['email'],
	),
	array(
		'param' => 'read','name' 	=>  lang('status'), 'type'=> 'select',
		'value' => $filter['read'],
		'values_single' =>$verify,'values_opts'=>array('name_prefix'=>'read_'),
	),

);

$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'email'		=> lang('email'),
	'subject'		=> lang('subject'),
	'read'		=> lang('status'),
	'created'	=> lang('created'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$readed_status = ($row->read) ? 'on' : 'off';
	$readed_text = ($row->read) ? 'read_yes' : 'read_no';
	$r = (array) $row;
	$r['read'] = macro()->status_color($readed_status,$readed_text) ;
	$r['subject'] 	= '<a href="'.$row->_url_view.'" title="'.lang('detail').'" class="lightbox">
							'.htmlentities($row->subject).'
						</a>';
	$r['created'] 	= $row->_created_full;
	$r['action'] 	= $_row_action($row);
	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

