
<?php
ob_start();
    $where = array();
    $where['sort'] = array('order' => 'asc','created' => 'desc', 'id'=>'desc');
    $_data = array();
    widget($class)->_list($where,'',true, $_data)?>

<?php
$body = ob_get_clean();
echo macro()->box([
    'name' => lang($class),
    'body' => $body,
]);
?>
