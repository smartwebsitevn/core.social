<?php $type = $widget->setting['type'];

//pr($items);
?>

<?php if ($type == 'top'): ?>
    <?php foreach ($items as $i => $item):
        $has_sub = $item->_sub ? 1 : 0;
        $target = $item->target?' target="'.$item->target.'" ' :'';
        ?>
        <li class="<?php if ($item->_is_active) echo 'active'; ?>  <?php if ($has_sub) echo "dropdown" ?>  ">
            <a  <?php echo $target ?>  href="<?php echo $item->url ?>" <?php if ($has_sub){ ?>class="dropdown-toggle"
                data-toggle="dropdown"<?php } ?>><?php echo $item->title ?> <?php if ($has_sub) { ?> <span
                    class="caret"></span><?php } ?></a>
            <?php if ($has_sub) { ?>
                <ul class="sub-menu dropdown-menu">
                    <?php foreach ($item->_sub as $s) {
                        ?>
                        <li><a href="<?php echo $s->url ?>" title="<?php echo $s->title ?>"><?php echo $s->title ?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </li>


    <?php endforeach; ?>

<?php elseif ($type == 'top_cat'): ?>
    <?php foreach ($items as $i => $item):
        $target = $item->target?' target="'.$item->target.'" ' :'';

        $has_sub = false;
        if ($item->holder && strpos($item->holder, 'product_cat') === 0) {
            $product_cat = explode(':', $item->holder);
            if (count($product_cat) >= 2 && $product_cat[1]) {
                $product_cat_ids = explode(',', $product_cat[1]);
                $product_cat = model('product_cat')->filter_get_list(['id' => $product_cat_ids, 'show' => 1]);
                if ($product_cat)
                    $has_sub = true;
            }
        }

        ?>
        <li class="<?php if ($item->_is_active) echo 'active'; ?>  <?php if ($has_sub) echo "dropdown" ?>  ">
            <a <?php echo $target ?> href="<?php echo $item->url ?>" <?php if ($has_sub){ ?>class="dropdown-toggle"
                data-toggle="dropdown"<?php } ?>><?php echo $item->title ?> <?php if ($has_sub) { ?> <span
                    class="caret"></span><?php } ?></a>
            <?php if ($has_sub) { ?>
                <ul class="sub-menu dropdown-menu">
                    <?php foreach ($product_cat as $r) {
                        $r = mod('product_cat')->url($r);
                        ?>
                        <li><a href="<?php echo $r->_url_view ?>"
                               title="<?php echo $r->name ?>"><?php echo $r->name ?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </li>


    <?php endforeach; ?>

<?php elseif ($type == 'footer'): ?>


    <div class="footer-menu-wrapper">
        <div class="container_12">

            <ul class="footer-menu">

                <?php foreach ($items as $i => $item):

                    $target = $item->target?' target="'.$item->target.'" ' :'';
                    ?>

                    <li><a <?php echo $target ?> href="<?php echo $item->url; ?>"
                                                 class="<?php if ($item->_is_active) echo 'active'; ?>"
                            <?php if ($item->nofollow) echo 'rel="nofollow"'; ?>
                            ><?php echo $item->title; ?></a></li>

                <?php endforeach; ?>

            </ul>

        </div>
    </div>


<?php endif; ?>
