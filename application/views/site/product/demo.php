<div class="switcher animated bounceInUp">
    <a class="demo-logo" href="<?php echo site_url() ?>"><img src="<?php echo widget('site')->setting_image('logo') ?>"
                                                            alt="<?php echo widget('site')->setting('title') ?>"/></a>
    <a class="product-name" href="<?php echo $info->_url_view ?>"><?php echo $info->name ?></a>
    <a class="switcher-toggle" title="Bỏ khung">Bỏ khung</a>
    <ul class="actions ">
        <li  class="product-price">
            <?php echo $info->_price ?>
        </li>

        <li class="product-add-cart">
            <form id="product_form_action" class="form-horizontal" method="post"
                  action="<?php echo site_url("product_cart/add") ?>">
                <input type="hidden" name="id" value="<?php echo $info->id ?>">
                <?php widget('product')->action_add_cart($info) ?>
            </form>
        </li>
    </ul>


    <ul class="respond icon-links">
        <li><a class="desktop active" title="Xem website theo kiểu máy tính">Xem website theo kiểu máy tính</a></li>
        <li><a class="tablet activePortrait inactive" title="Xem website theo kiểu máy tính bảng">Xem website theo kiểu
                máy tính bảng</a></li>
        <li><a class="phone activePortrait inactive" title="Xem website theo kiểu điện thoại di động">Xem website theo
                kiểu điện thoại di động</a></li>
    </ul>
</div>
<div class="theme-wrapper notiOS">
    <iframe src="<?php echo $info->link_demo ?>" frameborder="0" height="100%" width="100%" id="theme-demo"></iframe>
</div>
