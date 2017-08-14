<div class="product-info-main detail-social">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <?php t('view')->load('tpl::product/_common/main') ?>

        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="sticky-element" data-limiter="#footer">
                <div class="slimscroll" data-height="500px">
                    <?php t('view')->load('tpl::product/_common/info_author') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        nfc.reboot();
        $('.product-images .owl-carousel').owlCarousel({
            loop: true,
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