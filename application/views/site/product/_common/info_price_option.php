<div class="prod-offer  no-padding">
    <?php //pr($info);
    $currency = model('currency')->get_default();
    if( isset($info->_manufacture) )
    {
        $name = url_title(convert_vi_to_en($info->_manufacture->name));
        $url = site_url("thuong-hieu/{$name}-{$info->_manufacture->id}");
        ?>
        <div class="info-prod info-spec prod-brand">
            <span class="title">Th??ng hi?u:</span> <span class="info">
                <a class="brand-link" href="<?php echo $url ?>" target="_blank"><?php echo $info->_manufacture->name ?></a></span>
        </div>
        <?php
    }
    if( $info->warranty )
    {
        ?>
        <div class="info-prod info-spec">
            <span class="title">Bảo hành:</span> <span class="info"><span title="B?o hành <?php echo $info->warranty ?>"><?php echo $info->warranty ?></span></span>
        </div>
        <?php
    }

    ?>

    <!-- Giá s?n ph?m -->
    <div class="info-prod info-price prod-price">
        <span class="title"><?php echo lang('price') ?>:</span>
        <span class="info">
        <?php
        echo $info->_price;
        ?></span>
        <?php
        if( $info->_tax_class )
        {
            ?>
            <span class="more" data-toggle="tooltip" title="<?php echo $info->_tax_class->description; ?>">(<?php echo $info->_tax_class->name ?>)</span>
            <?php
        }
        ?>
    </div>
    Giam gia:
    <?php
    if( isset( $info->_price_discount ) )
    {
        //pr($info->_price_discount);
        ?>
        <div class="info-prod">
            <span class="title">&nbsp;</span> 
            <span class="info">
            <?php
            foreach( $info->_price_discount as $k => $v )
                echo "> $k s?n ph?m: {$v[1]}<br />";
            ?>
            </span>
        </div>
        <?php
    }
    ?>


    <?php
    if(! empty($info->_price_reduce) )
    {
        ?>
        <div class="info-prod info-price prod-save-money">
            <span class="title">Ti?t ki?m:</span> <span class="info"><?php echo $info->_price_reduce ?> (<?php echo $info->_price_percent ?>%)</span>
        </div>
    <?php   }?>

    <?php t('view')->load('tpl::product/_common/info_option') ?>
    <?php

    //if( $info->status )
    if( 1 )
    {
        if( $info->quantity == 0 )
        {
            ?>
            <div class="info-prod number-buy">
                <span class="title">Tình tr?ng:</span>
                <span>H?t hàng</span>
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="info-prod number-buy">
                <span class="title">T?n kho:</span>
                <span><?php echo $info->quantity ?> s?n ph?m</span>
            </div>
            <?php
        }
    }

    //if( !$info->status || ( $info->status && $info->quantity > 0 ) )
    if( $info->quantity > 0 )
    {
        ?>
        <div class="info-prod number-buy">
            <span class="title">Ch?n s? l??ng:</span> 
            <span class="add-number">
                <span class="ui-spinner ui-widget ui-widget-content ui-corner-all">
                    <input autocomplete="off" class="txtQty qty-txt-box ui-spinner-input" min="1" name="quantity" value="1">
                </span>
            </span>
            <span><span class="stock stk_y" content="in_stock" itemprop="availability">Còn hàng</span></span>
        </div>
        <div class="Prod-credit">
            <a class="btn-order button-buy" href="#" rel="nofollow" data-productId="<?php echo  $info->id ?>">
                <span class="btn-order-icon icon-spcart"></span>
                <span class="text-btn text-btn-buy">??t mua</span>
            </a>
            <a class="btn-order send-support" href="#" rel="nofollow">
                <span class="btn-order-icon icon-love"></span>
                <span class="text-btn text-btn-support">T? v?n</span>
            </a>
        </div>
        <?php
    }

    ?>
</div>
<hr>