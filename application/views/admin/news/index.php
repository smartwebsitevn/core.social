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
$_macro['toolbar_sub'] = array(
    array('url' => admin_url('news'), 'title' => lang('mod_news'),'attr'=>array('class'=>'active') ),
    array('url' => admin_url('news_cat'), 'title' => lang('mod_news_cat'),)
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

//$_macro['table']['sort'] 	= true;
//$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
    array('name' => lang('id'), 'param' => 'id',
        'value' => $filter['id'],
    ),
    array('name' => lang('title'), 'param' => 'title',
        'value' => $filter['title'],
    ),
    array(
        'name' => lang('cat'), 'param' => 'cat_news', 'type' => 'select',
        'value' => $filter['cat_news'],
        'values_row' => array($list_cat_news, 'id', 'name'),
    ),
    array(
        'name' => lang('option'), 'param' => 'option', 'type' => 'select',
        'value' => $filter['option'],
        'values' => $options, 'values_opts' => array('name_prefix' => 'option_'),
    ),
    array(
        'name' => lang('status'), 'type' => 'select', 'param' => 'active',
        'value' => $filter['title'],
        'values' => array('1' => lang('active'), '0' => lang('unactive')),
    ),
);

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'title' => lang('title'),
    'cat' => lang('cat'),
    'feature' => lang('feature'),
    'status' => lang('status'),
    'created' => lang('created'),
   /* 'lang'		=> lang('lang'),
    'admin' => lang('admin'),*/
    'action' => lang('action'),
);

$_rows = array();
foreach ($list as $row) {
    $status = ($row->status) ? 'on' : 'off';
    $feature = ($row->feature>0) ? 'on' : 'off';
    $r = (array)$row;
    $r['title'] 	= t('html')->a($row->_url_view,$row->title,array('target'=>'_blank'));
    $r['cat'] = $row->_cat->name;
   // $r['feature'] = $_row_feature($row);
    $r["feature"] = '<a class="toggle_action iStar iIcon '.($row->_can_feature_del ? 'on' : '').'"
								_url_on="'.$row->_url_feature.'" _url_off="'.$row->_url_feature_del.'"
								_title_on="'.lang('feature_set').'" _title_off="'.lang('feature_del').'"
							></a>';
    $r['status'] 	= macro()->status_color($status) ;
    $r['created'] = $row->_created;
    $r['lang'] 	= macro('mr::table')->langs_url($row);
    $r['admin'] = (isset($row->admin->name) ? $row->admin->name : '').'<p style="font-size: 11px">('.$row->_updated_time.')</p>';
    //$r['action'] 	= $_row_action($row);
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

