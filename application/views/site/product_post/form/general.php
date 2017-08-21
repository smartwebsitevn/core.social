<?php
/*echo macro('mr::advForm')->row(array(
    'name' => lang('cat'),
    'param' => 'cat_id',
    'type' => 'select2',
    'value' => $info['cat_id'],
    'values_row' => array($categories, 'id', '_name')
));*/
//widget('admin')->upload_adv($widget_upload_files);
//widget('admin')->upload_adv($widget_upload_adv);
?>
    <div class="block-info">
        <div class="form-group  ">
            <label class=" control-label ">
                Tiêu đề bài viết:</label>
            <div class="clearfix"></div>
            <input class="form-control" value="<?php echo $info['name'] ?>"  name="name" type="text">

            <div class="clear"></div>
            <div name="name_error" class="error"></div>

        </div>
        <div class="form-group">
            <label class="control-label " >
                Danh mục:
            </label>

            <div class="clearfix"></div>
            <?php
            $product_cats = model('type_cat')->get_list_hierarchy([], ['show' => 1]);
            $_data_info = array(
                'param' => 'type_cat_id',
                'type' => 'select',
                'input_attr' => array('id' => 'type_cat_id', 'event-hook' => 'eventChangeTypeCat'),
                'value' => $info['type_cat_id'],
                'values_row' => array($product_cats, 'id', 'name'),
                'show_error' => false
            );
            echo macro('mr::form')->info($_data_info);
            ?>
            <div id="data_types" style=" display: inline-block;"></div>
            <div class="clear"></div>
            <div name="type_error" class="error"></div>
        </div>
        <div class="form-group">
            <div class="upload-action row p40" >
                <div class="col-md-6">
                    <a id="upload-media" class="upload-type">
                        <i class="pe-7s-photo"></i>
                       <span>Up hình ảnh</span> <br>
                        <span>Chia sẻ video</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a id="upload-link" class="upload-type">
                        <i class="pe-7s-exapnd2"></i>
                        <span>Chia se</span><br>
                        <span>một đường link</span>

                    </a>
                </div>
            </div>
            <?php t('view')->load('tpl::product_post/form/post_link') ?>
            <?php t('view')->load('tpl::product_post/form/post_media') ?>
        </div>
    </div>
    <div class="block-info">
        <div class="form-group">
            <label class="control-label " >
                Nội dung bài viết:
            </label>

            <div class="clear"></div>
                <textarea name="description" rows="10"      class="form-control  "><?php echo $info['description'] ?></textarea>

            <div class="clear"></div>
            <div name="description_error" class="error"></div>
        </div>
    </div>
    <div class="block-info">
        <div class="form-group">
            <label class="col-sm-12  control-label " for="_0139986ea3b9305d0cff9534db437d81">
                Tệp đính kèm:
            </label>

            <div class="col-sm-12">
                <?php widget('site')->upload($widget_upload_files, array('temp' => 'tpl::_widget/product/upload/files')) ?>

            </div>
            <div class="clearfix"></div>
        </div>
    </div>

