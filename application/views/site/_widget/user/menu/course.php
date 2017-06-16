<div id="my-products" class="my-products-header">
    <div class="container">
        <div class="pos-r my-products-heading">
            <?php
            $title='';
            switch($current){
                case "my":
                    $title=lang('my_product');
                    break;
                case "free":
                    $title=lang('my_product_free');
                    break;
                case "favorite":
                    $title=lang('my_product_favorite');
                    break;
                case "test_result":
                    $title=lang('my_product_test_result');
                    break;

            }
            echo $title;
            ?>
        </div>
        <div class="clearfix"></div>
        <nav class="navbar-menu">
            <div class="navbar-header">
                <button data-target="#navbar-collapse-user-product" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar-collapse-user-product"  class="collapse navbar-collapse">
                <ul class="pos-r nav nav-tabs tabs mt10 mb10">
                    <li class="<?php echo ($current == 'my') ? 'active' : '' ?>"><a  href="<?php echo site_url("user_product")?> " class="fs15 color-white link m0-5 pb20"><?php echo lang('my_product')?></a></li>
                    <li class="<?php echo ($current == 'free') ? 'active' : '' ?>"><a  href="<?php echo site_url('user_product/free')?>" class="fs15 color-white link m0-5 pb20"><?php echo lang('my_product_free')?></a></li>
                    <li class="<?php echo ($current == 'favorite') ? 'active' : '' ?>"><a  href="<?php echo site_url('user_product/favorite')?>" class="fs15 color-white link m0-5 pb20"><?php echo lang('my_product_favorite')?></a></li>
                    <li class="<?php echo ($current == 'test_result') ? 'active' : '' ?>"><a  href="<?php echo site_url('user_product/test_result')?>" class="fs15 color-white link m0-5 pb20"><?php echo lang('my_product_test_result')?></a></li>

                </ul>
            </div>
        </nav>


    </div>
</div>