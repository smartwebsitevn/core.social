<?php if (isset($info->images) && $info->images):
    //pr($info->images)
    ?>
    <div class="block-products-items-popup block-products-items">
        <div class="list-social">
            <div class="item-social ">
                <div class="item-media ">
                    <div class="images ">
                        <?php $i = 0;
                        foreach ($info->images as $img): $i++;// pr($info)
                            $type = 'image';
                            $youtube_id = '';
                            if ($img->type == 'youtube') {
                                $type = 'video';
                                $youtube_id = $img->data;
                            }
                            ?>
                            <div class="item item-<?php echo $i ?>">
                                <img class="lazyload" data-src="<?php echo $img->_url; ?>">
                                <?php if ($youtube_id): ?>
                                    <div class="item-video">
                                        <div
                                            class="item-video-icon" <?php echo $youtube_id ? ' data-youtube="' . $youtube_id . '"' : '' ?> ></div>
                                        <div class="item-video-player"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <a  class="btn btn-default btn-block" data-dismiss="modal" aria-label="Close" >
            Đóng
        </a>
    </div>

<?php endif; ?>

<?php /*if(isset($info->images) && $info->images): //pr($info->images) ?>
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
                $i++;// pr($info)?>
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
 <script type="text/javascript">
    $(document).ready(function () {
        nfc.reboot();

        $('.product-images .owl-carousel').owlCarousel({
            loop: false,
            margin: 0,
            responsiveClass: true,
            items: 1,
            autoplay: false,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            nav: true,
            dots: true,
            dotsData: true,
            navText: ["", ""],
            smartSpeed: 700,
        })
    })
</script>
<?php endif; */ ?>
<script type="text/javascript">
    $(document).ready(function () {
        nfc.reboot();
    })
</script>