<?php if (count($list)): ?>

    <?php $_rd = random_string('unique'); ?>

    <script type="text/javascript">
        (function($)
        {
            $(document).ready(function()
            {
                var $main = $('.file_list_<?php echo $_rd; ?>');
                var sort = <?php echo 1//(int) $sort; ?>;
                $main.find('.do_action').nstUI('doAction',{
                    event_complete: function(data, settings)
                    {
                        load_ajax($main.parents('#file_list'));
                    }
                });
                // Hide it
                $main.find('.hideit').click(function()
                {
                    $(this).fadeOut();
                });
                // Lightbox
                $main.find('.lightbox').nstUI({
                    method:	'lightbox'
                });


                $('.product-images .owl-carousel').owlCarousel({
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
        })(jQuery);
    </script>
    <style type="text/css">

        .file_image_item .file_image_actions {
            position: absolute;
            top: 4px;
            right: 4px;
            padding: 5px;
            display: none;
            background: #333;
            border-radius: 2px;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
        }

        .file_image_item .file_image_actions a {
            display: inline;
            margin-left: 5px;
        }

        .file_image_item .file_image_actions a:first-child {
            margin-left: 0;
        }

        .file_image_item:hover .file_image_actions {
            display: block;
        }

    </style>
    <div class="file_list_<?php echo $_rd; ?>">
            <div class="product-images">
                <div class="owl-carousel">
                    <?php foreach ($list as $row): ?>
                        <div class="item" data-dot="<img src='<?php echo $row->_url_thumb; ?>'>">

                            <div class="file_image_item" data-item="<?php echo $row->id; ?>">
                                <div class="file_image_img">
                                    <a href="<?php echo $row->_url; ?>?lightbox&rel=<?php echo $_rd; ?>"
                                       class="lightbox">
                                        <img class="img-slide" src="<?php echo $row->_url; ?>"/>
                                    </a>
                                </div>

                                <div class="file_image_actions">
                                    <a data-url="<?php echo $row->_url_del; ?>" data-action="confirm" class="do_action"
                                      title="<?php echo lang('delete'); ?>"
                                       data-notice="<?php echo lang('notice_confirm_delete'); ?>:<br><strong><?php echo $row->orig_name; ?></strong>"
                                        >
                                        <img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
    </div>

<?php endif; ?>
