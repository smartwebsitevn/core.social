<!-- Main content wrapper -->
<?php

$filters =array(


	array( 'param' => 'file_name','name' => lang('file'),
		'value' 	=>$filter['file_name'],
	),
	array( 'param' => 'key',
		'value' 	=>$filter['key'],
	),
    array( 'param' => 'translate',
        'value' 	=>$filter['translate'],
    ),
   array(
       'param' 	=> 'translate_empty', 'type'=> 'select',
       'value' => $filter['translate_empty'],'values_row' =>array($langs,'directory','name'),/*'values_opts'=>array('value_required'=>true)*/
   ),

);

$columns = array();
$columns['key'] = array(lang('key'),array('class'=>'col-2'));
$columns['value'] = array(lang('value'),array('class'=>'col-2'));
$class= 'col-4';
if(count($langs) == 3)
{
    $class= 'col-3';
}elseif(count($langs) == 4)
{
    $class= 'col-2';
}
foreach ($langs as $row)
{
    $columns[$row->directory] = array('<b class="text-danger">'. $row->name.'</b>',array('class'=>$class));  
}

//$columns['action'] 		= lang('action');

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
$_macro['table']['title'] = lang('mod_lang_phrase');
$_macro['table']['columns'] = $columns;

$_rows = array();
foreach ($list as $row)
{

	$r = (array) $row;
	$k=$r['key'];
	$r['key'] ='<b>'.$r['key'].'</b><a href="'.$row->_url_del.'" _url="'.$row->_url_del.'" class="verify_action" notice="'.lang('notice_confirm_del_phrase', $r['key']).'"> - '.lang('del_key').'</a><br><small>'.$r['_file']->file.'</small>';
	//$r['value'] ='<textarea class="form-control" rows="1"   name="values['. $r['id'].']" >'. htmlentities($r['value']).'</textarea>';
	foreach ($langs as $l)
	{
	    $r[$l->directory] ='<textarea class="form-control" rows="1"   name="phrases['. $r['id'].']['.$l->directory.']" >'. htmlentities($r[$l->directory]).'</textarea>';
	}
	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

$toolbar =array(
	array(
		'url' 	=> $url_export,'title' => lang('button_export'),'icon' => 'plus',
		'attr'=>array('class'=>'btn btn-danger response_action',
			'notice'=>lang('notice_confirm_export_phrase'),
			'_url' =>$url_export,
		),
	),
	array(
		'url' 	=> $url_import,'title' => lang('button_import'),'icon' => 'plus',
		'attr'=>array('class'=>'btn btn-primary')
	),
	array(
		'url' 	=> $url_add,'title' => lang('button_add'),'icon' => 'plus',
		'attr'=>array('class'=>'btn btn-primary')
	),
);

echo macro()->page(array('toolbar' => $toolbar));
?>

<div class="row">
	<div class="col-lg-12">
		<?php  echo macro('mr::table')->filters($filters);?>
		<form class="form form_action" action="<?php echo $action ?>">

			<div name="phrases_error" class="error alert alert-danger" style="display:none"></div>
			<a _submit="true" class="btn btn-primary act_translate pull-right mb10">
				<i class="fa fa-pencil"></i> <?php echo lang('button_update'); ?>
			</a>
		<div class="clear"></div>
		<?php  echo macro('mr::table')->table($_macro['table']);?>
		<div class="clear"></div>
			<div name="phrases_error" class="error alert alert-danger" style="display:none"></div>
			<a _submit="true"  class="btn btn-primary act_translate pull-right">
				<i class="fa fa-pencil"></i> <?php echo lang('button_update'); ?>
			</a>
		</form>
		</div>
</div>
