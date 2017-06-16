<?php
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['sort'] 	= true;
	$_macro['table']['sort_url_update'] = $sort_url_update;
	
	$_macro['table']['columns'] = array(
		'name' 		=> lang('name'),
		'sid'	=> lang('id'),
		'key'	=> lang('key'),
		'player'	=> lang('player'),
		'status'	=> lang('status'),
		'action' 	=> lang('action'),
	);



	$_row_action = function ($row) {
	ob_start(); ?>
	<div class="btn-group btn-group-xs action-buttons">
		<?php if (isset($row->_can_test) && $row->_can_test): ?>
			<a href="<?php echo $row->_url_test; ?>" title="<?php echo lang('test'); ?>"
			   class="btn btn-primary btn-xs lightbox ">
				<?php echo lang('button_test'); ?>
			</a>

		<?php endif; ?>
		<?php if ($row->_can_edit): ?>
			<a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>"
			   class="btn btn-warning btn-xs ">
				<?php echo lang('button_edit'); ?>
			</a>
		<?php endif; ?>

		<?php if ($row->_can_del): ?>
			<a href="" _url="<?php echo $row->_url_del; ?>"
			   title="<?php echo lang('delete'); ?>"
			   class="btn btn-danger btn-xs  verify_action"
			   notice="<?php echo lang('notice_are_you_sure_want_to_delete'); ?>:<br><b><?php echo $row->name; ?></b>"
				>
				<?php echo lang('button_delete'); ?>
			</a>
		<?php endif; ?>
		<a title="<?php echo lang('sort'); ?>" class="  js-sortable-handle"
		   style="cursor:move;">
			<i class="fa fa-arrows-alt icon-only"></i>
		</a>
	</div>


	<?php return ob_get_clean();
};
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['name'] 	= '<b>'.$row->name.'</b>'. "<br>$row->url";
		$r['sid'] 	= '<span class="label label-info">S'.$row->id.'</span>';
		$r['key'] 	=lang('server_type_'.$row->key);
		$r['player'] 	=lang('player_type_'.$row->player);
		$r['status'] 	= macro()->status_color($row->_status);

		$r['action'] 	= $_row_action($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);