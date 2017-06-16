<?php //t('widget')->movie->display_list($list,$widget->setting['style']) ?>
<?php
$temp = 'tpl::_widget/news/display/page/' . $widget->setting['style'];
$settings =$widget->setting;
$settings['name'] =$widget->name;
view($temp,$settings);
?>

