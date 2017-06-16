<!-- Main content wrapper -->
<?php

$_macro = $this->data;
$_macro['toolbar'] = array(
	array('url' => admin_url('menu_item/add/'.$menu), 'title' => lang('add'), 'icon' => 'plus','attr'=>array('class'=>'btn btn-danger')),
	array('url' => admin_url('menu_item'), 'title' => lang('list'), 'icon' => 'list','attr'=>array('class'=>'btn btn-primary')),
);
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('menu_item'), 'title' => lang('mod_menu_item'),'attr'=>array('class'=>'active') /* 'icon' => 'plus',*/),
	array('url' => admin_url('menu'), 'title' => lang('mod_menu'), /*'icon' => 'list'*/)
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $url_update_order;

$_macro['table']['filters'] = array(
	array( 'param' => 'menu','type'=>'select',
		'value' => $filter['menu'], 'values_row' => array($menus, 'key', 'name'),'values_opts'=>array('value_required'=>1)
	),

);

$_macro['table']['columns'] = array(
	'title'		=> lang('title'),
	//'url'		=> lang('url'),
	'status'		=> lang('status'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$v = ($row->status) ? 'on' : 'off';

	$r = (array) $row;
	$r['title'] 	= $row->_title;
	//$r['status'] 	= macro()->status_color($v) ;
	$r["status"] = '<a class="toggle_action iStar iIcon '.($row->_can_off ? 'on' : '').'"
								_url_on="'.$row->_url_on.'" _url_off="'.$row->_url_off.'"
								_title_on="'.lang('on').'" _title_off="'.lang('off').'"
							></a>';
//$r['action'] 	= $_row_action($row);


$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);


?>

<!--<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4><i class="fa fa-list-ul"></i> <?php /*echo lang('title_list'); */?></h4>
		</div>
	</div>
	<div class="portlet-body no-padding">
		<div class="dd">
			<ol class="dd-list">
				<li class="dd-item" data-id="1">
					<div class="dd-handle">Item 1</div>
				</li>
				<li class="dd-item" data-id="2">
					<div class="dd-handle">Item 2</div>
				</li>
				<li class="dd-item" data-id="3">
					<div class="dd-handle">Item 3</div>
					<ol class="dd-list">
						<li class="dd-item" data-id="4">
							<div class="dd-handle">Item 4</div>
						</li>
						<li class="dd-item" data-id="5">
							<div class="dd-handle">Item 5</div>
						</li>
					</ol>
				</li>
			</ol>
		</div>
	</div>
</div>
<script src="<?php /*echo public_url('admin') */?>/ekoders/js/plugins/jquery-nestable/jquery.nestable.js"></script>
<script type="text/javascript">
	jQuery(function($){
		var updateOutput = function(e)
		{
			var list   = e.length ? e : $(e.target),
				output = list.data('output');

				alert(window.JSON.stringify(list.nestable('serialize')));//, null, 2));

		};
		$('.dd').nestable({})
			.on('change', updateOutput);
	});
</script>-->