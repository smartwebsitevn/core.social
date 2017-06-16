<!-- Main content wrapper -->
<?php
/*$_row_feature = function ($row) {
    ob_start();
    */?><!--
    <a class="toggle_action iIcon iStar <?php /*if ($row->_can_feature_del) echo 'on'; */?>"
       _url_on="<?php /*echo $row->_url_feature; */?>" _url_off="<?php /*echo $row->_url_feature_del; */?>"
       _title_on="<?php /*echo lang('feature_set'); */?>" _title_off="<?php /*echo lang('feature_del'); */?>"
        ></a>
    --><?php /*return ob_get_clean();
};*/
$_macro = $this->data;

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
    array('name' => lang('id'), 'param' => 'id',
        'value' => $filter['id'],
    ),
    array('name' => lang('name'), 'param' => 'name',
        'value' => $filter['name'],
    ),
    array(
        'name' => lang('feature'), 'param' => 'feature', 'type' => 'select',
        'value' => $filter['feature'],
        'values' =>  array('on' => lang('feature'), 'off' => lang('unfeature')),
    ),
    array(
        'name' => lang('mod_lang'), 'param' => 'lang_id', 'type' => 'select',
        'value' => $filter['lang_id'],
        'values_row'=> array(lang_get_list(),'id','name'),
    ),
    array(
        'name' => lang('status'), 'type' => 'select', 'param' => 'status',
        'value' => $filter['status'],
        'values' => array('on' => lang('active'), 'off' => lang('unactive')),
    ),
);

$_macro['table']['columns'] = array(
    'id' => array(lang('id'), array('style'=>"width: 50px")),
    'name' => lang('name'),
    'status' => lang('status'),
    'view' => lang('view'),
    'download' => lang('download'),
    'feature' => lang('feature'),
    'lang' => lang('mod_lang'),
    'created' => array(lang('created'), array('style'=>"width: 100px")),
    'admin' => lang('admin'),
    'action' => lang('action'),
);

$_rows = array();
foreach ($list as $row) {
    $feature = ($row->feature>0) ? 'success' : 'warning';
    $r = (array)$row;
    $r['name'] 	= t('html')->a($row->_url_view,$row->name,array('target'=>'_blank'));
   // $r['feature'] = $_row_feature($row);
//    $r['feature'] = macro()->active_color($feature) ;
    $r['status'] 	= macro()->active_status_public($row->status, $row->public) ;
    $r['view'] = $row->view;
    $r["feature"] = '<a class="toggle_action iStar iIcon '.($row->_can_feature_del ? 'on' : '').'"
								_url_on="'.$row->_url_feature.'" _url_off="'.$row->_url_feature_del.'"
								_title_on="'.lang('feature_set').'" _title_off="'.lang('feature_del').'"
							></a>';
    $r['lang'] = $row->language->name;
    $r['created'] = $row->_created;
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
