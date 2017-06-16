<?php
//pr($checkout);
$form = array(
    array(
        'param' => 'name',
        'placeholder' => lang('name'),
        'type' => 'text',
        'value' => $checkout['name'],
        'req' => true
    ),
    array(
        'param' => 'phone',
        'placeholder' => lang('phone'),
        'type' => 'text',
        'value' => $checkout['phone'],
        'req' => true
    ),
    array(
        'param' => 'email',
        'placeholder' => lang('email'),
        'type' => 'text',
        'value' => $checkout['email'],
    ),
    array(
        'param' => 'city',
        'type' => 'select2',
        'value' => $checkout['city'],
        'values_row' => array($cities, 'id', 'name'),
        'req' => true

    ),
    array(
        'param' => 'address',
        'placeholder' => lang('address'),
        'type' => 'textarea',
        'value' => $checkout['address'],
    ),
    /* array(
         'param' => 'country',
         'type' => 'select2',
         'value' => $checkout['country'],
         'values_row' => array($countries, 'id', 'name')
     ),
     array(
         'param' => 'city',
         'type' => 'select2',
         'refer' => 'country',
         'ajax' => array(
             'loader' => 'City',
             'source' => 'city'
         ),
         'value' => $checkout['city'],
         'values_row' => array($cities, 'id', 'name')
     )*/

);

foreach ($form as $row) {
    if (isset($row['refer']))
        echo macro('mr::advForm')->row($row, $form);
    else
        echo macro('mr::advForm')->row($row);
}

?>