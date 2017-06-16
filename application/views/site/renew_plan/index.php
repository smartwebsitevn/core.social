<script type="text/javascript">
    var cart_url_update = '<?php echo $url_update; ?>';
</script>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            var main = $('#plan_order_form');
            var plan = main.find('[name=plan]');
            cart_update();

            plan.change(function () {
                cart_update();
            });
            function cart_update() {
                var cart_amount = main.find('#cart_amount');

                $(this).nstUI({
                    method: "loadAjax",
                    loadAjax: {
                        url: cart_url_update + '&' + main.find('form').serialize(),
                        field: {load: 'cart_update_load', show: ''},
                        datatype: 'json',
                        event_complete: function (data) {
                            if (data['_amount']) {
                                cart_amount.html(data['_amount']);
                                //$('#coupon_content').show();
                            } else {
                                //$('#coupon_content').hide();
                            }
                        }
                    }
                });
            };

        });
    })(jQuery);
</script>

<div class="panel panel-default" id="plan_order_form">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $title; ?></h3>
    </div>
    <div class="panel-body">
        <form class="form_action t-form label-right form-horizontal" action="<?php echo $action; ?>" method="post">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="name"><?php echo lang('plan'); ?>: <span class="req">*</span></label>

                <div class="col-sm-9">
                    <select name="plan" id="param_plan" class="form-control input-long">
                        <option value="0">-=<?php echo lang('select_plan') ?>=-</option>
                        <?php foreach ($list as $row): ?>
                            <option value="<?php echo $row->id ?>"><?php echo $row->day ?> <?php echo lang('day') ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="clear"></div>
                    <div name="plan_error" class="error"></div>
                </div>
                <div class="clear"></div>
            </div>
            <!--<div class="form-group param_text">
				<label for="param_title" class="col-sm-3 control-label"><?php /*echo lang('coupon')*/ ?>:	<span class="req">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="coupon_code" id="param_coupon_code" class="form-control">
					<div class="clear"></div>
					<div class="error" name="coupon_code_error"></div>
				</div>
				<div class="clear"></div>
			</div>-->

            <div class="form-group param_static">
                <label class="col-sm-3 control-label">
                    <?php echo lang('amount_payment') ?>
                </label>

                <div class="col-sm-9">
                    <div class="text-danger ng-binding" id="cart_amount"
                         style="font-size:16px; font-weight:600; padding-top:5px;">0đ
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-error" name="purse_balance_error"></div>
                </div>

            </div>

            <div class="form-group param_static">
                <label class="col-sm-3 control-label">
                    <?php echo lang('payment_method') ?>
                </label>

                <div class="col-sm-9">
                    <input type="radio" name="payment_method" value="card" class="mt5 " checked> <?php echo lang('payment_method_card') ?> &nbsp;&nbsp;&nbsp;&nbsp;

                    <input type="radio" name="payment_method" value="balance" class="mt5">  <?php echo lang('payment_method_balance') ?>

                    <div class="clearfix"></div>
                    <div name="payment_method_error" class="error"></div>
                </div>

            </div>

            <div class="payment_method_balance ">
                <div class="form-group param_static">
                    <label class="col-sm-3 control-label ">
                        <?php echo lang('current_balance') ?>
                    </label>
                    <div class="col-sm-9">
                        <b  class="text-danger ng-binding" style="font-size:16px; font-weight:600; padding-top:5px;"> <?php echo $purse->{'format:balance'} ?></b>
                        <div class="clearfix"></div>
                        <div name="amount_balance_error" class="error"></div>
                    </div>
                </div>
                <?php
                echo mod('user_security')->form('payment');
                ?>
            </div>
            <div class="payment_method_card" style="display: none">
                <div class="form-group">
                    <label class="col-sm-3 control-label ">
                    </label>
                    <div class="col-sm-9">
                        <div name="card_error" class="alert alert-danger hideit" style="display: none"></div>
                    </div>
                </div>
                <?php
                echo macro('mr::form')->row(array(
                    'param' => 'type', 'name' => lang('card_type'), 'type' => 'select',
                    'values' => macro('mr::form')->make_options(array_pluck($types, 'name', 'id')), 'req' => true,
                ));

                echo macro('mr::form')->row(array(
                    'param' => 'code', 'name' => lang('card_code'), 'req' => true, /*'attr' => array('placeholder' => 'Nhập mã số sau lớp bạc mỏng')*/
                ));

                echo macro('mr::form')->row(array(
                    'param' => 'serial', 'name' => lang('card_serial'), 'req' => true, /*'attr' => array('placeholder' => 'Nhập mã serial nằm sau thẻ')*/
                ));
                ?>
            </div>


            <?php echo macro('mr::form')->captcha($captcha); ?>

            <div class="form-group">
                <label class="col-sm-3 control-label">&nbsp;</label>

                <div class="col-sm-9">
                    <input type="submit" value="<?php echo lang('renew') ?>" class="btn btn-default">
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {


        $('input[name=payment_method]').bind('click', function () {
            show_payment_method()
        });
        show_payment_method();

        function show_payment_method() {
            var val = $('input[name=payment_method]:checked').val();

            if (val == 'balance') {
                $('.payment_method_balance').show();
                $('.payment_method_card').hide();

            }
            else {
                $('.payment_method_balance').hide();
                $('.payment_method_card').show();
            }
        }

    })
</script>