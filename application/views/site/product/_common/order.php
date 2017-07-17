<?php
$_data_stats = function () use ($info) {
    $asset = public_url() . '/site/style/';
    ob_start() ?>
    <table class='table'>
        <tr>
            <td class="icon"><span><img src="<?php echo $asset ?>images/icon/icon01.png" alt="img"></span></td>
            <td class="text">
                <?php if (isset($info->stats_data->time) && $info->stats_data->time): ?>
                    <b><?php echo lang("stats_time") ?>: <?php echo $info->stats_data->time ?></b>
                <?php else: echo "-" ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="icon"><span><img src="<?php echo $asset ?>images/icon/icon02.png" alt="img"></span></td>
            <td class="text">
                <?php if (isset($info->stats_data->lesson) && $info->stats_data->lesson): ?>
                    <b><?php echo lang("stats_lesson") ?>: <?php echo $info->stats_data->lesson ?></b>
                <?php else: echo "-" ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="icon"><span><img src="<?php echo $asset ?>images/icon/icon03.png" alt="img"></span></td>
            <td class="text"><b>Chứng chỉ online</b></td>
        </tr>
    </table>

    <!--  - <?php /*echo lang("stats_quality") */ ?> <br/>
            - <?php /*echo lang("stats_refund") */ ?> <br/>
            - --><?php /*echo lang("stats_checked") */ ?>
    <?php return ob_get_clean();

};
?>
<?php  //pr($info);       ?>
<div class="product-order block-info">

    <form id="product_form_action"  class="form-horizontal" method="post" action="<?php echo site_url("product_cart/add") ?>">
        <input type="hidden" name="id" value="<?php echo $info->id ?>">
        <div class="form-group">
            <label for="input-voucher" class="col-sm-6 control-label">Giá tin bài:</label>
            <div class="col-sm-6">
                <span class="item-price"><?php echo $info->_price; ?> </span>
            </div>
        </div>
        <?php //t('view')->load('tpl::product/_common/info_option'); ?>

        <?php if (!mod("product")->setting('product_order_quick')): ?>
        <div class="form-group">
            <label for="input-voucher" class="col-sm-6 control-label">Số lượng:</label>
            <div class="col-sm-6">
                <div class="item-quantity">
                    <div class="quantity-up-down-select">
                    <span class="btn-up-down down disable">
                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                    </span>
                        <select class="quantity-select" name="qty" title="Số lượng">
                            <?php foreach(range(1,10) as $v): ?>
                                <option <?php echo $v==1?'selected':''; ?> ><?php echo $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    <span class="btn-up-down up">
                         <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>

                    </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if($info->has_voucher): ?>
        <div class="form-group">
            <label for="input-voucher" class="col-sm-6 control-label">Mã giảm giá:</label>
            <div class="col-sm-6">
                <input  id="input-voucher" name="voucher" value="" class="form-control" type="text">

            </div>
        </div>
        <?php endif; ?>
        <?php if($info->_addons): ?>
            <div class="form-group">
                <?php t('view')->load('tpl::product/_common/info_addons'); ?>
            </div>
        <?php endif; ?>


        <?php t('view')->load('tpl::product/_common/info_price_total'); ?>

        <div class="mt20">
            <?php widget('product')->action_add_cart($info) ?>
        </div>

    </form>
</div>
