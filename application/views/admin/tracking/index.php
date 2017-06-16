<!-- Main content wrapper -->
<?php

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

//$_macro['table']['sort'] 	= true;
//$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
    array('name' => lang('no'), 'param' => 'no',
        'value' => $filter['no'],
    ),
    array('name' => lang('customer'), 'param' => 'customer',
        'value' => $filter['customer'],
    ),

);

$_macro['table']['columns'] = array(
    'no' => array(lang('no'), array('style'=>"width: 50px")),
    'content' => lang('content'),
    'customer' => lang('customer'),
    'created' => array(lang('created'), array('style'=>"width: 100px")),
    'delivery' => array(lang('delivery'), array('style'=>"width: 100px")),
    'status' => lang('status'),
    'admin' => lang('admin'),
    'action' => lang('action'),
);

$_rows = array();
foreach ($list as $row) {
    $r = (array)$row;
    $r['name'] 	= t('html')->a($row->_url_view,$row->no,array('target'=>'_blank'));
    $r['cat'] = isset($row->_cat->name) ? $row->_cat->name : '';
    $r['status'] 	= lang('tracking_'.mod($class)->config('status')[$row->status]);

    $r['created'] = $row->_created;
    $r['delivery'] = $row->_delivery;
    $r['admin'] = (isset($row->admin->name) ? $row->admin->name : '').'<p style="font-size: 11px">('.$row->_updated_time.')</p>';
    //$r['action'] 	= $_row_action($row);
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

?>
<style>
    .tc-table.table-bordered > thead > tr > th, .tc-table.table-bordered > thead > tr > td {
        white-space: nowrap;
    }
</style>
