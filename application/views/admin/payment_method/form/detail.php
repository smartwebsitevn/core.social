<?php

/* Images */

echo macro('mr::advForm')->row(array(
    'param' => 'banner',
    'type' => 'image',
    '_upload' => $widget_upload_banner
));
echo macro('mr::advForm')->row(array(
    'param' => 'icon',
    'type' => 'image',
    '_upload' => $widget_upload_icon
));
echo macro('mr::advForm')->row(array(
    'param' => 'icon_fa',
    'attr'=>['placeholder'=>"example: arrows"],
    'desc'=>t('html')->a('http://fontawesome.io/icons/' ,'ICONS HERE' ,array('target'=>'_blank')) ,
));

echo macro('mr::advForm')->row(array(
    'param' => 'images',
    'type' => 'image',
    '_upload' => $widget_upload_images
));

echo macro('mr::form')->row(array(
    'param' 	=> 'files',
    'type' 		=> 'file',
    '_upload' 	=> $widget_upload_files
));

echo '<hr>';

echo macro('mr::advForm')->row(array(
    'param' => 'sort_order', 'value' => $info['sort_order'],
));

?>