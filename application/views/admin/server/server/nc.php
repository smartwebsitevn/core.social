<?php
$setting = isset($setting) ? (array)$setting : null;
foreach (array('streaming', 'secret', 'time') as $p) {
$req=false;
   // if(in_array($p,array('ip', 'streaming', 'application', 'port', )))
        $req=true;

    if ($p == 'streaming')

        echo macro('mr::form')->row(array(
            'param' => 'streaming', 'name' => lang('nc_streaming'),
            'req' => 1,
            'type' => 'select',
            'value' => $setting['streaming'],
            'values_single' => $nc_streaming_types,
            'values_opts'=>array('name_prefix'=>'nc_streaming_'),
        ));
    else
        echo macro('mr::form')->row(array(
            'name' => lang('nc_' . $p),
            'param' => $p, 'req' => $req,
            'value' => $setting[$p],

        ));
}
?>
