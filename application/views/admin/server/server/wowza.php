<?php
$setting = isset($setting) ? (array)$setting : null;

$desc =[];
$desc['port'] =lang('wowza_port_note');
$desc['application'] =lang('wowza_application_note');

foreach (array(/*'ip',*/'streaming',  'port','application', 'token', 'query_prefix') as $p) {
$req=false;
    if(in_array($p,array('ip', 'streaming', 'application', 'port', )))
        $req=true;

    if ($p == 'streaming')

        echo macro('mr::form')->row(array(
            'param' => 'streaming', 'name' => lang('wowza_streaming'),
            'req' => 1,
            'type' => 'select',
            'value' => $setting['streaming'],
            'values_single' => $wowza_streaming_types,
            'values_opts'=>array('name_prefix'=>'wowza_streaming_'),

        ));
    else
    {
        $params =array(
            'name' => lang('wowza_' . $p),
            'param' => $p, 'req' => $req,
            'value' => $setting[$p] );

        if (isset($desc[$p]))
            $params['desc'] =$desc[$p];

        echo macro('mr::form')->row(    $params);


    }

}
?>

