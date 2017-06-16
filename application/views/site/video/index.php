
<?php
ob_start();
$where = array();
widget($class)->_list($where,'',true)?>

<?php
$body = ob_get_clean();
echo macro()->box([
    'name' => lang($class),
    'body' => $body,
]);
?>
