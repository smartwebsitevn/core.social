<?php
	$_data_module = function ($modules) {
		ob_start();
		//pr($modules);
		?>
		<?php foreach($modules as $k=>$types): ?>
			<div><?php echo lang('mod_'.$k) ?>:
			<?php foreach($types as $type): ?>
				<?php foreach($type as $row): ?>
					<a href="<?php echo $row['callback']."?lightbox&width=90%&height=90%" ?>" class="lightbox"><?php echo $row['title'] ?></a> |
				<?php endforeach; ?>
			<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		<?php return ob_get_clean();
	};
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
	'param' 	=> 'menu','type' =>'hidden',
	'value' => $menu,
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'holder','type' =>'hidden',
);
$_macro['form']['rows'][] = array(
	'param' => 'title',
	'req' => true,
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'url','req' 		=> 1,
	'req' => true,
);
$_macro['form']['rows'][] = array(
	'param'=>'link_module', 'type' 	=> 'ob',
	'value' => $_data_module($modules),
);

$_macro['form']['rows'][] = array(
	'param' => 'parent_id','name'=>lang('parent_menu'), 'type'=>'select',
	'values_row'=> array($parents,'id','_title'),
);
$_macro['form']['rows'][] = array(
	'param' => 'target','type'=>'select',
	'values'=> array('_self'=>'_self','_top'=>'_top','_blank'=>'_blank','_parent'=>'_parent'),
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'sort_order'
);

$_macro['form']['rows'][] = array(
	'param' => 'icon',
	'attr'=>['placeholder'=>"example: arrows"],
	'desc'=>t('html')->a('http://fontawesome.io/icons/' ,'ICONS HERE' ,array('target'=>'_blank')) ,

);
$_macro['form']['rows'][] = array(
	'param' => 'nofollow','type' => 'bool',
);
$_macro['form']['rows'][] = array(
	'param' => 'status','type' => 'bool_status',
	'value'=>$info?$info['status']:1,
);

echo macro()->page($_macro);
?>
