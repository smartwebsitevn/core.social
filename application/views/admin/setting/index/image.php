<?php


   $_data_image = function ($width,$height,$setting) {
    ob_start(); ?>
        <input type="text" value="<?php echo $setting[$width]?>" name="<?php echo $width ?>" placeholder="<?php echo lang('upload_img_width') ?>" class="form-control input-small pull-left">
       <span class="pull-left bigger-150"> x </span>
        <input type="text" value="<?php echo $setting[$height]?>" name="<?php echo $height ?>"  placeholder="<?php echo lang('upload_img_height') ?>" class="form-control input-small pull-left">
    <?php return ob_get_clean();
};

echo macro('mr::form')->row( array(
   'name' 	=> lang('upload_img_max_demision'), 'type'=>'ob',
   'value' 	=>$_data_image( 'upload_img_max_width' , 'upload_img_max_height' ,$setting),
));

/*echo macro('mr::form')->row( array(
   'name' 	=> lang('upload_img_resize_demision'), 'type'=>'ob',
   'value' 	=>$_data_image( 'upload_img_resize_width' , 'upload_img_resize_height' ,$setting),
));*/

echo macro('mr::form')->row( array(
    'name' 	=> lang('upload_img_thumb_primary_demision'), 'type'=>'ob',
    'value' 	=>$_data_image( 'upload_img_thumb_width' , 'upload_img_thumb_height' ,$setting),
));
for($i=1;$i<=5 ;$i++){
    echo macro('mr::form')->row( array(
        'name' 	=> lang('upload_img_thumb_demision').' '.$i, 'type'=>'ob',
        'value' 	=>$_data_image( 'upload_img_thumb'.$i.'_width' , 'upload_img_thumb'.$i.'_height' ,$setting),
    ));
}


echo macro('mr::form')->row( array(
	'param' 	=> 'upload_server_status','type' 		=> 'bool',
	'value' 	=>$setting['upload_server_status'],
));
echo '<div id="upload_server_status_notice" >';
echo macro('mr::form')->row( array(
	'param' 	=> 'upload_server_url',
	'value' 	=>$setting['upload_server_url'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'upload_server_hostname',
	'value' 	=>$setting['upload_server_hostname'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'upload_server_username',
	'value' 	=>$setting['upload_server_username'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'upload_server_password',
	'value' 	=>$setting['upload_server_password'],
));
echo macro('mr::form')->row( array(
    'param' 	=> 'upload_server_save_on_local','type' 		=> 'bool',
    'value' 	=>$setting['upload_server_save_on_local'],
));
echo '</div>';
?>