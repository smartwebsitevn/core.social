<?php if(isset($info->images) && $info->images): //pr($info->images) ?>
<div class="product-images">
    <div class="owl-carousel">
        <?php $i = 0;
        foreach ($info->images as $img):
            $type ='image';
            $youtube_id='';
            if($img->type =='youtube'){
                $type = 'video';
                $youtube_id=$img->data;
            }
            $i++;// pr($row)?>
            <div class="item" data-dot="<img src='<?php echo $img->_url_thumb; ?>'>">

                    <img class="img-slide" src="<?php echo $img->_url; ?>">
                <?php if($youtube_id): ?>
                    <div class="item-video" >
                        <div class="item-video-icon"  <?php echo $youtube_id?' data-youtube="'.$youtube_id.'"':'' ?> ></div>
                        <div class="item-video-player"></div>

                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>

