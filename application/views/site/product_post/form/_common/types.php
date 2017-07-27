<?php
foreach ($types as $type) {
    $v = null;
    foreach ($types_values as $item) {
        if ($item->type_id == $type->id) {
            $v = $item->type_item_id;
        }
    }

   /* echo macro('mr::advForm')->row(array(
        'name' => $type->name,
        'param' => 'types[' . $type->id . ']',
        'type' => 'select2',
        'value' => $v,
        'values_row' => array($type->items, 'id', 'name'),
        //'req' => true,
    ));*/

    $_data_info = array(
        //'name' => $type->name,
        'param' => 'types[' . $type->id . ']',
        'type' => 'select',
        'value' => $v,
        'value_default' =>  $type->name,
        'values_row' => array($type->items, 'id', 'name'),
        'show_error' =>false
    );
    echo macro('mr::form')->info($_data_info);
}
?>