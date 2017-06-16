<?php /* ?>

    <div  class="ratings-and-reviews">
    <div class="ratings-and-reviews__title"><span class="">Ý kiến đánh giá</span></div>
    <div class="ratings-and-reviews__filter-controls">
        <div class="ratings-and-reviews__average-rate-container">
            <div class="ratings-and-reviews__average-rate-number">   <?php echo $product->rate ?></div>
            <div class="ratings-and-reviews__rate-stars tooltip-container">
                        <span class="star-rating--static star-rating--large"> <span
                                style="width: <?php echo 28 * ($product->rate ) ?>px;">></span> </span>

            </div>
            <span class="ratings-and-reviews__average-rate-text">   (<?php echo number_format($product->rate_total) ?> đánh giá)</span>
        </div>
        <?php //pr($product); ?>

        <table class="ratings-and-reviews__filters">
            <tbody>
            <?php
            $ratio = $product->rate_total / 100;
            $arrs = [ null, $product->rate_one, $product->rate_two, $product->rate_three, $product->rate_four, $product->rate_five ];
            for( $i = 5; $i > 0; $i-- )
            {
                $percent = 0;
                if( $ratio )
                    $percent = round($arrs[$i] / $ratio);
                ?>
                <tr class="ratings-and-reviews__star-rating">
                    <td class="ratings-and-reviews__gauge">
                        <div class="ratings-and-reviews__progress"><span class="ratings-and-reviews__filled"
                                                                         style="width: <?php echo $percent ?>%;"> </span></div>
                    </td>
                    <td class="ratings-and-reviews__level star-rating--static star-rating--small">
                        <span  style="width: <?php echo 20 * $i ?>%;"></span>
                    </td>
                    <td class="ratings-and-reviews__count btn-link">
                        <span class=""><?php //echo $percent .'% &nbsp;&nbsp;' ?>(<?php echo $arrs[$i] ?>)</span>

                    </td>
                </tr>
                <?php
            }
            ?>

            </tbody>
        </table>
    </div>

</div>
                           <?php */ ?>


<form id="rate-form" method="post" name="rate-form" novalidate="novalidate"
      action="<?php echo site_url('comment/add') ?>" class="form-horizontal form_action">
    <script type="text/javascript">
        $(document).ready(function () {
            $('.star.hover > i').on('click', function () {
                var point = $(this).data('point');
                $('.star.hover .active').removeClass('active');
                $(this).addClass('active');
                $('#rate-form input[name=rate]').val(point);
            });
        });
    </script>
    <input type="hidden" name="table_id" value="<?php echo $info->id ?>"/>
    <input type="hidden" name="table_name" value="<?php echo $type ?>"/>
    <input type="hidden" name="rate" value=""/>
    <?php if (mod("product")->setting('rate_allow')) { ?>

        <div class="row mt20">
            <div class="col-md-12">
                <strong class=" pull-left"> Đánh giá* </strong>
                <span class="star hover pull-left">
                    <i data-point="5"></i>
                    <i data-point="4"></i>
                    <i data-point="3"></i>
                    <i data-point="2"></i>
                    <i data-point="1"></i>
                </span>

                <div class="clear"></div>
                <div name="rate_error" class="error "></div>
            </div>
        </div>
    <?php } ?>
    <div class="row mt10">
        <div class="col-md-12 mb20">
            <strong> Ý kiến của bạn * </strong>
        </div>
        <div class="col-md-1">
            <i class="fa fa-user fa-3x" aria-hidden="true"></i>
        </div>
        <div class="col-md-11">
            <textarea class="form-control required" id="textarea" rows="3" name="content"></textarea>

            <div class="clear"></div>
            <div name="content_error" class="error "></div>
            <div name="user_error" class="error "></div>
        </div>
    </div>
    <button type="submit" class="btn btn-success pull-right margintop10 vote_submit_btn_2 mt10"> Gửi đánh giá <i
            class="fa fa-star" aria-hidden="true"></i></button>
</form>
<div class="clearfix"></div>
