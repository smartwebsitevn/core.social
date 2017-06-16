<?php
echo macro('mr::form')->row( array(
    'param' 	=> 'license_key',
    'value' 	=>$setting['license_key'],'req'=>1,
));
echo macro('mr::form')->row( array(
    'param' 	=> 'license_expired','type'=>'static',
    'value' 	=>$setting['license_expired']
));
echo macro('mr::form')->row( array(
    'param' 	=> 'license_domain','type'=>'static',
    'value' 	=>$setting['license_domain']
));
echo macro('mr::form')->row( array(
    'param' 	=> 'license_status','type'=>'static',
    'value' 	=>$setting['license_status']
));
?>