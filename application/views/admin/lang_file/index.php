<!-- Main content wrapper -->
<?php
$_row_action=function($row){
	ob_start(); ?>
<div class="btn-group btn-group-xs action-buttons">
	<?php if ($row->_can_sync): ?>
		<a href="" _url="<?php echo $row->_url_sync; ?>" title="<?php echo lang('reset'); ?>" class="btn btn-primary btn-xs verify_action"
		   notice="<?php echo lang('notice_confirm_reset'); ?>: <b><?php echo $row->file; ?></b>"
			>
			<?php echo lang('button_reset') ?>
		</a>
	<?php endif; ?>
	<?php if ($row->_can_phrase): ?>
		<a href="<?php echo $row->_url_phrase; ?>" title="<?php echo lang('phrase'); ?>" class="btn btn-warning btn-xs">
			<?php echo lang('button_translate') ?>
		</a>
	<?php endif; ?>

	<?php if ($row->_can_del): ?>
		<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="btn btn-danger btn-xs verify_action"
		   notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo $row->file; ?></b>"
			>
			<?php echo lang('button_del') ?>
		</a>
	<?php endif; ?>
	</div>
<?php return ob_get_clean();
};
$_macro['toolbar'] = array('toolbar' =>
	array(
		'url' 	=> admin_url('lang_file/import'),'title' => lang('import'),'icon' => 'plus',
		'attr'=>array('class'=>'btn btn-danger verify_action',
			'notice'=>lang('notice_confirm_import_file',$lang->name),
			'_url' =>$url_import,
		),
	),
	array(
		'url' 	=> admin_url('lang_file'),	'title' => lang('list'),	'icon' => 'list','attr'=>array('class'=>'btn btn-primary'),
	),);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['filters'] =array(
	array(
		'param' 	=> 'lang', 'type'=> 'select',
		'value' => $filter['lang'],'values_row' =>array($langs,'id','name'),'values_opts'=>array('value_required'=>true)
	),
	array( 'param' => 'file',
		'value' 	=>$filter['file'],
	),

);

$_macro['table']['columns'] = array(
	'file' 		=> lang('file'),
	'updated' 		=> lang('updated'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$r = (array) $row;
	$r['updated'] 	=get_date($r['updated'],'full');
	$r['action'] 	= $_row_action($row);
	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);
?>
<div class="alert alert-danger"><?php echo lang('note_lang_delete') ?></div>

	
