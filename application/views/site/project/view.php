<script>
    $(document).ready(function(){
        $('.project-images .owl-carousel').owlCarousel({
            loop:true,
            margin:0,
            responsiveClass:true,
            items: 1,
            autoplay:true,
            autoplayTimeout:5000,
            autoplayHoverPause:true,
            nav:true,
            dots:true,
            dotsData:true,
            navText: ["",""],
            smartSpeed:700,
        })
    });
</script>

<?php if(isset($info->images) && $info->images): //pr($info->images) ?>
    <div class="project-images">
        <div class="owl-carousel">
            <?php $i = 0;
            foreach ($info->images as $row): $i++;// pr($row)?>
                <div class="item" data-dot="<img src='<?php echo $row->_url_thumb; ?>'>">
                    <img class="img-slide"src="<?php echo $row->_url; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

