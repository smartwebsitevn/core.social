<?php if (isset($list) && $list): ?>
    <ol class="list-social-point-highest">
        <?php foreach ($list as $row):    //pr($row);?>
            <li class="item-social">
                    <div class="item-name">
                        <?php t('view')->load('tpl::_widget/product/display/item/info_name', ['row' => $row]) ?>

                    </div>
                    <div class="item-meta">
                        <span class="point">
                            <span class="value"><?php echo number_format($row->point_total) ?></span>
                            points
                        </span>
                    </div>
                    <?php echo widget('product')->action_favorite($row) ?>

            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>