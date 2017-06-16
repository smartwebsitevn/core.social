<div class="row nhan-xet">
    <p class="title">Đánh giá</p>
    <div class="col-md-12">
        <div class="box-nx">
            <div class="left">
                <?php

               // model('setting')->del_group('site-rating');
                $rating= setting_get_group('site-rating');
                //pr($rating);
                if(!$rating){
                    $_data = array();
                    $_data['comment_count'] 		= 0;
                    $_data['rate_total']    		= 0;
                    $_data['rate']    		= 0;
                    $arrs = array(
                        1 => 'rate_one',
                        2 => 'rate_two',
                        3 => 'rate_three',
                        4 => 'rate_four',
                        5 => 'rate_five'
                    );
                    foreach( $arrs as $i)
                    {
                        $_data[$i] =0;
                    }
                    // neu chua co thi tao
                     model('setting')->set_group('site-rating', $_data);
                    $rating= setting_get_group('site-rating');

                }
                $rating= (object)$rating ;
               // pr_db($rating);
                ?>
                <p><?php echo lang('average_rating')?></p>
                <p class="count-view"><?php echo $rating->rate_total ?> <?php echo lang('rate_times') ?></p>
                <p class="star">
                    <?php
                    $i = 1;
                    while( $i < $rating->rate )
                    {
                        ?>
                        <i></i>
                        <?php
                        $i++;
                    }
                    if( $rating->rate - ($i - 1) > 0 )
                    {
                        ?>
                        <i style="width: <?php echo 18 * ($rating->rate - ( $i - 1 ) ) ?>px"></i>
                        <?php
                    }
                    ?>
                </p>
                <p class="diem"><?php echo $rating->rate ?></p>
            </div>
            <div class="">
                <?php
                $ratio = $rating->rate_total / 100;
                $arrs = [ null, $rating->rate_one, $rating->rate_two, $rating->rate_three, $rating->rate_four, $rating->rate_five ];
                for( $i = 5; $i > 0; $i-- )
                {
                    $percent = 0;
                    if( $ratio )
                        $percent = round($arrs[$i] / $ratio);
                    ?>
                    <div class="box-progress">
                        <span><?php echo $i ?> <?php echo lang('star') ?></span>
                        <div class="progress">
                            <div
                                class="progress-bar progress-bar-info"
                                role="progressbar"
                                aria-valuenow="20"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                style="width: <?php echo $percent ?>%"
                                >
                                <span class="sr-only"></span>
                            </div>
                        </div>
                        <?php /* ?>
                        <span><?php //echo $percent?>% </span>
                         <?php */ ?>

                        <span class="count-view">(<?php echo $arrs[$i]?>)</span>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt10">
        <form id="rate-form" method="post" name="rate-form" novalidate="novalidate" action="<?php echo site_url('comment/add') ?>" class="form-horizontal form_action">
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.star.hover > i').on('click', function(){
                        var point = $(this).data('point');
                        $('.star.hover .active').removeClass('active');
                        $(this).addClass('active');
                        $('#rate-form input[name=rate]').val(point);
                    });
                });
            </script>
            <input type="hidden" name="table_id" value="1" />
            <input type="hidden" name="table_name" value="site" />
            <input type="hidden" name="rate" value="" />

            <div name="user_error" class="error alert alert-danger " style="display: none"></div>

            <div class="form-group">
                <label for="input1" class="col-md-12 control-label"><span>1</span>Đánh giá của bạn</label>
                <div class="col-md-12">
                <span class="star hover">
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
            <div class="form-group">
                <label for="textarea" class="col-md-12 control-label mb10"><span>2</span>Nội dung</label>
                <div class="col-sm-12">
                    <textarea class="form-control required" id="textarea" rows="3" name="content"></textarea>
                    <div class="clear"></div>
                    <div name="content_error" class="error "></div>

                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-default">Gửi đánh giá</button>
                </div>
            </div>
        </form>
    </div>
</div>