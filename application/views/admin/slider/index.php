
<?php
	$_row_action = function($row)
	{
		ob_start();?>
		
		<?php if ($row->_can_edit): ?>
			<a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>" class="tipS"
			><img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" /></a>
		<?php endif; ?>
		
		<?php if ($row->_can_del): ?>
			<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action" 
				notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo $row->name; ?></b>"
			><img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" /></a>
		<?php endif; ?>
		
		<?php return ob_get_clean();
	};


	$_macro = $this->data;

$_macro['toolbar_sub'] = array(
	array('url' => admin_url('slider_item'), 'title' => lang('mod_slider_item'),/*'icon' => 'list'*/),

	array('url' => admin_url('slider'), 'title' => lang('mod_slider') ,'attr'=>array('class'=>'active') /* 'icon' => 'plus',*/),
);

	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['columns'] = array(
		'key' 		=> lang('key'),
		'name'		=> lang('name'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['action'] = $_row_action($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	