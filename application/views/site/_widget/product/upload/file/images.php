<?php if (count($list)): ?>

    <?php $_rd = random_string('unique'); ?>

    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                var $main = $('.file_list_<?php echo $_rd; ?>');
                var sort = <?php echo 1//(int) $sort; ?>;
                $main.find('.do_action').nstUI('doAction', {
                    event_complete: function (data, settings) {
                        load_ajax($main.parents('#file_list'));
                    }
                });
                // Hide it
                $main.find('.hideit').click(function () {
                    $(this).fadeOut();
                });
                // Lightbox
                $main.find('.lightbox').nstUI({
                    method: 'lightbox'
                });

                // Sort
                if (sort) {
                    $main.find('.file_image_list').sortable({
                        items: '.file_image_item',
                        placeholder: 'file_image_item item_placeholder',
                        stop: function (event, ui) {
                            var items = new Array();
                            $main.find('.file_image_item').each(function () {
                                items.push($(this).attr('data-item'));
                            });

                            $(this).nstUI('loadAjax', {
                                url: '<?php echo $url_update_order; ?>',
                                data: {items: items.join()},
                                field: {load: '_', show: ''},
                            });
                        },
                    });
                }

            });
        })(jQuery);
    </script>
    <style type="text/css">

        /**
         * List file image
         */
        .file_image_list {
        }

        .file_image_item {
            float: left;
            margin: 0 10px 10px 0;
            padding: 4px;
            position: relative;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 2px;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
        }

        .file_image_item:hover {
            border-color: #999;
        }

        .file_image_item .file_image_img {
            height: 100px;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            margin: 0 auto;
        }

        .file_image_item .file_image_img img {
            width: 100px;
            max-height: 100px;
        }

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

        .file_image_item.item_placeholder {
            border: 1px dashed #bbb;
            width: 100px;
            height: 100px;
        }

        .file_image_single {
            float: left;
            padding: 4px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 2px;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
        }

        .file_image_single .file_image_single_img {
            height: 100px;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            margin: 0 auto;
        }

        .file_image_single .file_image_single_img img {
            width: 100px;
            max-height: 100px;
        }
    </style>

    <div class="file_list_<?php echo $_rd; ?>">
        <!-- List -->
        <div class="file_image_list">
            <?php foreach ($list as $row): ?>
                <div class="file_image_item" data-item="<?php echo $row->id; ?>">
                    <div class="file_image_img">
                        <a href="<?php echo $row->_url; ?>?lightbox&rel=<?php echo $_rd; ?>" class="lightbox">
                            <img src="<?php echo $row->_url; ?>"/>
                        </a>
                    </div>

                    <div class="file_image_actions">

                        <a data-url="<?php echo $row->_url_del; ?>" data-action="confirm" class="do_action"
                           title="<?php echo lang('delete'); ?>"
                           data-notice="<?php echo lang('notice_confirm_delete'); ?>:<br><strong><?php echo $row->orig_name; ?></strong>"
                            >
                            <img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png"/>
                        </a>

                        <div class="clear"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="clear"></div>
    </div>

<?php endif; ?>