<?php if(isset($info->images) && $info->images): //pr($info->images) ?>
<div class="product-images">
    <div class="owl-carousel">
        <?php $i = 0;
        foreach ($info->images as $row): $i++;// pr($row)?>
            <div class="item" data-dot="<img src='<?php echo $row->_url_thumb; ?>'>">
                    <img class="img-slide" src="<?php echo $row->_url; ?>">

            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

