<style>
.unexpire{
	color:#006400;
}
.expired{
	color:red;
}
</style>
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

    [
        'param' => 'exxpire_to',
        'type'  => 'date',
        'name'  => lang('from_date'),
        'value' => array_get($filter, 'exxpire_to'),
    ],
    
    [
        'param' => 'exxpire_to_to',
        'type'  => 'date',
        'name'  => lang('to_date'),
        'value' => array_get($filter, 'exxpire_to_to'),
    ],
);

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'name' => lang('name'),
    'price' => lang('price'),
    'expire' => lang('expire'),
    'feature' => lang('feature'),
    'status' => lang('status'),
    'action' => lang('action'),
);



$_data_name = function ($row) {
    //pr($row);
    ob_start(); ?>

    <a href="<?php echo $row->_url_view; ?>" target="_blank" style="float:left; margin-right: 5px;">
        <img src="<?php echo $row->image->url_thumb; ?>" height="40px" width="50px"/>
    </a>
    <a href="<?php echo $row->_url_view; ?>" target="_blank">
        <?php echo $row->name; ?>
    </a>
    <?php return ob_get_clean();
};
$_data_price = function ($row) {
    ob_start(); ?>
        <span class="label label-success"> <?php echo $row->_price; ?></span>
    <?php return ob_get_clean();
};
$_rows = array();
foreach ($list as $row) {
    $feature = $row->feature ? 'yes' : 'no';
    $status = ($row->status) ? 'on' : 'off';
    $r = (array)$row;
   // $r['name'] 	= $row->name;
   // $r['price'] =  $row->_price ;

    $r['name'] = $_data_name($row);
    $r['price'] = $_data_price($row);

    $r['expire'] =  '<b class="'.$row->expire.'">'.lang($row->expire).'</b><br/>'.$row->_expire_from .'<br/>' . $row->_expire_to ;
    $r['feature'] = macro()->status_color($feature);
    $r['status']  = macro()->status_color($status) ;
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

