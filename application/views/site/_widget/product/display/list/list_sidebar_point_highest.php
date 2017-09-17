<?php if (isset($list) && $list): ?>
    <ol class="list-social-point-highest">
        <?php foreach ($list as $row):    //pr($row);?>
            <li class="item-social">
                    <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                            <?php echo $row->name; ?></a>
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