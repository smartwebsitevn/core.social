<?php

//pr($types);
/*foreach ($types as $type) {
    $v = null;
    foreach ($types_values as $item) {
        if ($item->type_id == $type->id) {
            $v = $item->type_item_id;
        }
    }

    echo macro('mr::advForm')->row(array(
        'name' => $type->name,
        'param' => 'types[' . $type->id . ']',
        'type' => 'select2',
        'value' => $v,
        'values_row' => array($type->items, 'id', 'name'),
        //'req' => true,
    ));
}*/
if ($types) {
    foreach ($types as $type) {
        echo macro()->filter_dropdown_obj(['value' => 1, 'values' => $type->items, 'param' => 'types[' . $type->id . ']', 'name' => $type->name]);
    }
}
?>
