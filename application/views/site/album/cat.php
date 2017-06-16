<?php widget('site')->breadcrumbs($breadcrumbs) ?>
<h2 class="node-title"><?php echo $cate->name?></h2>
<div class="box-service home-product">
	<?php $sub_id[] = $cate ? $cate->id : 0;
	$where = array();
	//$where['where']['?cat_id'] = array_merge($sub_id, $cate->_sub_id);
	$where['where']['|cats_id'] = $cate->id;
	$where['sort'] = array('order' => 'asc','created' => 'desc', 'id'=>'desc');
	$_data = array();
	widget($class)->_list($where,'',true, $_data)?>
</div>
