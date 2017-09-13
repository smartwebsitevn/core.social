<?php
/* Name is required */
$_data_country =function($param,$code, $countries)
{
	$path = public_url().'/img/world/';
	ob_start();?>
	<select name="<?php echo $param; ?>"  class="form-control select2"
		<option value="">-=<?php echo lang('layout'); ?>=-</option>
		<?php foreach ($countries as $group): ?>
					<optgroup label="<?php echo $group->name; ?>">

					<?php foreach ($group->countries as $v): ?>
					<option data-image="<?php echo $path.strtolower($v->code).'.gif'?>" value="<?php echo $v->id; ?>" <?php echo form_set_select($v->id, $code); ?>>
			    	<?php echo $v->name; ?>
			        </option>
					<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
	</select>
	<?php return ob_get_clean();
};
/*
echo macro('mr::advForm')->row(array(
    'param' => 'image',
    'type' => 'image',
    '_upload' => $widget_upload_image
));*/
echo macro('mr::advForm')->row(array(
    'param' => 'name', 'value' => $info['name'], 'req' => true,
));
echo macro('mr::advForm')->row(array(
    'param' => 'user_id', 'name' => "Bài viết của thành viên",
    'value' => isset($info['_user']) ? $info['_user']->email : '',
    'attr' => array('class' => 'autocomplete form-control', '_url' => $url_search_user),

));
/*
echo macro('mr::advForm')->row(array(
    'param' => 'link_demo', 'value' => $info['link_demo'],
));
echo '<hr/>';

echo '<div class="form-group param_number ">
        <label class="col-sm-3  control-label " for="price">'.lang('price'). '</label>
        <div class="col-sm-9">
            <input class="input_number" id="price" style="width:200px;float:left" laceholder="0" name="price" value="' . $info['price'] . '" type="text"> / <input class="" style="width:100px"  name="price_suffix" value="' . $info['price_suffix'] . '" type="text">
            <div class="clear"></div>
            <div name="price_error" class="error help-block"></div>
        </div>
    <div class="clearfix"></div>
</div>';*/
/*echo macro('mr::advForm')->row(array(
    'param' => 'price',
    'value' => $info['price'],
    'placeholder' => '0.000000',
    'type' => 'number',
));*/
/*echo macro('mr::advForm')->row(array(
    'param' => 'price_is_contact',
    'value' => $info['price_is_contact'],
    'values' => array(0 => lang('no'), 1 => lang('yes')),
    'type' => 'bool',

));
echo macro('mr::advForm')->row(array(
    'param' => 'price_is_auction',
    'value' => $info['price_is_auction'],
    'values' => array(0 => lang('off'), 1 => lang('on')),
    'type' => 'bool',

    'attr' => ["class" => "toggle_status_ tc"],
    'desc' => lang('price_is_auction_desc'),

));

$auction_data = null;
if ($info && isset($info['_price_is_auction_data'])) {
    $auction_data = $info['_price_is_auction_data'];
}
echo '<div id="price_is_auction_content_1" class="price_is_auction_content" >';
echo macro('mr::advForm')->row(array(
    'param' => 'price_is_auction_data[intro]',
    'name' => lang('price_is_auction_data_intro'),
    'value' => isset($auction_data->intro) ? $auction_data->intro : '',
    'type' => 'html',

));
echo '</div>';
echo '<hr/>';*/

$type_cat_data= function(){

};
echo macro('mr::advForm')->row(array(
    'name' => lang('type_cat'),
    'param' => 'type_cat_id',
    'type' => 'select2',
    'value' => $info['type_cat_id'],
    'values_row' => array($type_cats, 'id', '_name'),
    //'attr' => ["class" => "load_ajax",'_field'=>'a','_url'=>admin_url('type_cat/types'),'onChange'=>'load_ajax(this)'],

));
echo '<div id="data_types"></div>';
/*echo macro('mr::advForm')->row(array(
    'name' => lang('cat'),
    'param' => 'cat_id',
    'type' => 'select2',
    'value' => $info['cat_id'],
    'values_row' => array($categories, 'id', '_name')
));
echo macro('mr::form')->row(array(
    'param' => 'country_id', 'name' => lang('country'),
    'type' => 'select2',
    'value' => $info['country_id'],
    'values_row' => array($countrys, 'id', 'name')
));

echo macro('mr::form')->row(array(
    'name' => lang('country'),
    'param' => 'country_id',
    'type' => 'ob',
    'value' => $_data_country('country_id', $info['country_id'], $countrys),

));*/
/*echo macro('mr::advForm')->row(array(
	'param' => 'model',	'value'=>$info['model'],//	'req' 	=> true,
));
echo macro('mr::advForm')->row(array(
    'param' => 'brief',
    'type' => 'textarea',
    'value' => $info['brief'],
));
*/
echo macro('mr::advForm')->row(array(
    'param' => 'images',
    'type' => 'image',
    '_upload' => $widget_upload_images
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'files',
    'type' 		=> 'file',
    '_upload' 	=> $widget_upload_files
));
echo '<hr>';
echo macro('mr::advForm')->row(array(
    'param' => 'description',
    'type' => 'html',
    'value' => $info['description'],
));
/*
echo macro('mr::advForm')->row(array(
    'param' => 'note',
    'type' => 'html',
    'value' => $info['note'],
));
echo macro('mr::advForm')->row(array(
	'param' => 'tags',
	'value' => isset($info['tags']) ? implode(', ', $info['tags']) : ''
));
//pr($info);
echo macro('mr::advForm')->row(array(
    'param' => 'tags', 'attr' => array('class' => 'tags form-control', '_url' => $url_tag),
    'value' => isset($info['tags']) ? implode(', ', $info['tags']) : '',
));
*/
echo '<hr/>';

echo macro('mr::advForm')->row(array(
    'param' => 'is_new',
    'name' => lang('new'),
    'type' => 'bool',
    'value' => (isset($info['is_new']) ? $info['is_new'] : 0)
));
echo macro('mr::advForm')->row(array(
    'param' => 'is_feature',
    'name' => lang('feature'),
    'type' => 'bool',
    'value' => (isset($info['is_feature']) ? $info['is_feature'] : 0)
));

echo macro('mr::advForm')->row(array(
    'param' => 'status',
    'name' => lang('status'),
    'type' => 'bool_status',
    'value' => (isset($info['status']) ? $info['status'] : 1)
));
echo macro('mr::advForm')->row(array(
    'param' => 'sort_order', 'value' => $info['sort_order'],
));
//widget('admin')->upload_adv($widget_upload_files);
//widget('admin')->upload_adv($widget_upload_adv);
?>
