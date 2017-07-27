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
            <input class="form-control"  name="name" type="text">

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
                'value' => null,
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
            <label class="control-label " for="_0139986ea3b9305d0cff9534db437d81">
                Nội dung bài viết:
            </label>

            <div class="clear"></div>
                <textarea name="description" rows="5" id="_0139986ea3b9305d0cff9534db437d81"
                          class="form-control "></textarea>

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
                <?php  widget('site')->upload($widget_upload_image) ?>

                <?php widget('site')->upload($widget_upload_files) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>


<?php /* ?>
<div class="block block-up-img">
    <div class="block-title">
        Hình ảnh giới thiệu về công việc
        <a title="Xóa" class="removes hide_target " data-param="images" style="width: 50px;margin-left:10px ">
            <span class="icon"></span> Xóa
        </a>
    </div>
    <div class="block-content">
        <span class="label-text">Hãy thêm 3 hình ảnh để giới thiệu thêm về môi trường làm việc hoặc văn hóa công ty của bạn</span>

        <div class="row file-up-image">
            <?php widget('site')->upload($widget_upload_images) ?>

        </div>
    </div>
</div>

<div class="block block-up-img mt20 ">
    <div class="block-title">
        Đính kèm file
        <a title="Xóa" class="removes hide_target " data-param="files" style="width: 50px;margin-left:10px ">
            <span class="icon"></span> Xóa
        </a>
    </div>
    <div class="block-content">
        <span class="label-text">Bạn có thể đính kèm file để giới thiệu thêm cho tin tuyển dụng của bạn</span>
        <div class="profile-file " title="Tải profile" >
            <?php widget('site')->upload($widget_upload_files) ?>
        </div>

    </div>
</div>
<script type="text/javascript" src="<?php echo public_url('site') ?>/theme/js/script.js"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload/script.js"></script>
<?php */ ?>