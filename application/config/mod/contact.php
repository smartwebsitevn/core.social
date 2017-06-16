<?php
foreach (array('contact','register','order')as $k => $v)
{
	$config['contact_types'][$k]  = $v;
	$config['contact_type_'.$v] =  $k;
}
return $config;
