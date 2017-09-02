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
//pr($filter);
$filter_types = isset($filter['types'])?$filter['types']:[];
if ($types) {
    foreach ($types as $type) {
        // pr($type);
        $value = null;
        $name = $type->name;
        if ($filter_types) {
            foreach ($filter_types as $k => $v) {
                //echo '<br>-k:'.$k.' - v:'.$v;
                if ($k == $type->id) {
                    //echo '<br>==>k = type:'.$type->id;
                    foreach ($type->items as $item) {
                        if ($v == $item->id) {
                            $value = $item->id;
                            $name = $item->name;
                            break;
                        }
                    }
                }
                if($value ) break;
            }
        }

        // $value =isset($filter['types['..']'])
       // echo '<br>-type:'.$type->id;
       // echo '<br>+name:'.$name;
       // echo '<br>+value:'.$value;
        echo macro()->filter_dropdown_obj(['value' => $value, 'values' => $type->items, 'param' => 'types[' . $type->id . ']', 'name' => $name]);
    }
}
//pr($filter_types, 0);
//pr($filter, 0);
?>
