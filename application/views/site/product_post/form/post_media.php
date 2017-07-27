<div id="upload-media-content" class="upload-action-data" style="display: none">
    <?php widget('site')->upload($widget_upload_images) ?>
    <?php //if(isset($info->images) && $info->images): //pr($info->images) ?>
    <div class="product-images">
        <div class="owl-carousel">
            <?php $i = 0;
            /* foreach ($info->images as $row): $i++;// pr($row)?>
                 <div class="item" data-dot="<img src='<?php echo $row->_url_thumb; ?>'>">
                     <img class="img-slide" src="<?php echo $row->_url; ?>">
                 </div>
             <?php endforeach; */ ?>
        </div>
    </div>
    <div id="_temp" class="hide">
        <div class="item" data-dot="<img src='{src}'>">
            <img class="img-slide" src="{src}">
            <a onclick="del(this)" class="act_del_item" data-index="{index}" style="position: absolute;top: 10px; right:10px;">Del</a>
        </div>
    </div>

    <?php //widget('site')->upload(${'upload_image'.$i}, array('temp' => 'tpl::_widget/company/upload_file/image')) ?>
    <input type="file" multiple id="gallery-photo-add">
    <a>Up Video</a>
    <?php // endif; ?>
    <div class="gallery">
    </div>
</div>