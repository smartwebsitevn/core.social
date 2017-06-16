<?php foreach ($banners as $banner): ?>
    <div class="row bl-tu-van">
        <div class="images">
            <a href="javascript:void(0)" class="item do_action" data-loader="_"
               data-url="<?php echo site_url('banner/click/' . $banner->id) ?>" rel="nofollow"
               style="display:block!important;">
                <img src="<?php echo $banner->image->url; ?>" style="width:100%;">
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
<?php endforeach; ?>