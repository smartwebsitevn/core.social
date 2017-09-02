<?php
$info = isset($info) ? (array)$info : null;
?>


<h3>Thông tin cá nhân</h3>
<div class="hr hr-12 mb30"></div>
<?php


echo macro('mr::form')->row(array(
    'param' => 'avatar',
    'type' => 'image',
    '_upload' => $widget_upload_avatar,
));
echo macro('mr::form')->row(array(
    'param' => 'banner',
    'type' => 'file',
    '_upload' => $widget_upload_banner,
));
echo macro('mr::form')->row(array(
    'param' => 'attach',
    'type' => 'file',
    '_upload' => $widget_upload_attach,
));

foreach (model('user')->_info_genneral as $p) {
    if (in_array($p, array('desc'))) {
        echo macro('mr::form')->row(array(
            'param' => $p, 'type' => 'html',
            'value' => $info[$p]
        ));
    } else {
        echo macro('mr::form')->row(array(
            'param' => $p,
            'value' => $info[$p]
        ));
    }
}
if (model('user')->_info_id) {


    echo '<h3>Thông tin chứng minh thư</h3>
		<div class="hr hr-12 mb30"></div>';
    foreach (model('user')->_info_id as $p) {
        echo macro('mr::form')->row(array(
            'param' => $p,
            'value' => $info[$p]
        ));
    }
}
if (model('user')->_info_card) {

    echo '<h3>Thông tin ngân hàng</h3>
		<div class="hr hr-12 mb30"></div>';
    foreach (model('user')->_info_card as $p) {
        echo macro('mr::form')->row(array(
            'param' => $p,
            'value' => $info[$p]
        ));
    }
}

if (model('user')->_info_social) {
    echo '<h3>Mạng xã hội</h3>
		<div class="hr hr-12 mb30"></div>';
    foreach (model('user')->_info_social as $p) {
        echo macro('mr::form')->row(array(
            'param' => $p,
            'value' => $info[$p]
        ));
    }
}


?>


