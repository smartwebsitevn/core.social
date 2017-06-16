<!-- Main content wrapper -->
<?php

$_macro = $this->data;
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('faq'), 'title' => lang('mod_faq'),'attr'=>array('class'=>'active')),
	array('url' => admin_url('faq_cat'), 'title' => lang('mod_faq_cat'), )
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
    array('name' => lang('id'), 'param' => 'id',
        'value' => $filter['id'],
    ),
    array('name' => lang('question'), 'param' => 'question',
        'value' => $filter['question'],
    ),
    array(
        'name' => lang('cat'), 'param' => 'cat', 'type' => 'select',
        'value' => $filter['cat'],
        'values_row' => array($cats, 'id', 'name'),
    ),

    array(
        'name' => lang('status'), 'type' => 'select', 'param' => 'active',
        'value' => $filter['status'],
        'values' => array('1' => lang('active'), '0' => lang('unactive')),
    ),
);

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'question' => lang('question'),
    'cat_id' => lang('cat'),
    'status' => lang('status'),
    'created' => lang('created'),
    'action' => lang('action'),
);

$_rows = array();
foreach ($list as $row) {
    $status = ($row->status) ? 'on' : 'off';
    $r = (array)$row;
    $r['cat_id'] = isset($row->_cat->name)?$row->_cat->name:"";
    $r['status'] 	= macro()->status_color($status) ;
    $r['created'] = $row->_created;
    //$r['action'] 	= $_row_action($row);
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;
echo macro()->page($_macro);

