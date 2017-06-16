<?php $_id = '_' . random_string('unique'); ?>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            var $main = $('#<?php echo $_id; ?>');

            $main.find('[name=bank]').change(function () {
                var $bank = $(this).val();

                $('[_bank_info]').hide();
                $('[_bank_info=' + $bank + ']').show();

            })

        });
    })(jQuery);
</script>


<div id="<?php echo $_id; ?>">

    <?php
    /*
    $bank_blank = array();
    $bank_blank[0]  = new stdClass();
    $bank_blank[0]->id = '';
    $bank_blank[0]->name = lang('select_bank');
    
    $banks = array_merge($bank_blank, $banks);
   
    $type = array();
    $type[] = lang('select_type');
    $types = array_merge($type, $types);
    */

    $_form_bank = function () use ($banks) {
        $banks = array_pluck($banks, 'name');

       // return array_merge(array('' => lang('select_bank')), array_combine($banks, $banks));
        return $banks;
    };

    $_form_bank_info = function () use ($banks) {
        ob_start(); ?>

        <div id="bank_info">

            <?php foreach ($banks as $bank): ?>
                <?php if (isset($bank->acc_id)): ?>
                    <div _bank_info="<?php echo $bank->name; ?>" style="display:none;">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <?php echo lang('acc_id'); ?>
                            </label>

                            <div class="col-sm-9 mt5">
                                <b><?php echo $bank->acc_id; ?></b>
                            </div>

                            <div class="clear"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <?php echo lang('acc_name'); ?>
                            </label>

                            <div class="col-sm-9 mt5">
                                <b><?php echo $bank->acc_name; ?></b>
                            </div>

                            <div class="clear"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <?php echo lang('acc_bank_branch'); ?>
                            </label>

                            <div class="col-sm-9 mt5">
                                <b><?php echo $bank->branch; ?></b>
                            </div>

                            <div class="clear"></div>
                        </div>

                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>

        <?php return ob_get_clean();
    };

    echo macro('mr::form')->form(array(

        'action' => $action,
        'title' => lang('title_deposit_bank_add'),

        'rows' => array(
            widget('tran')->menu('deposit_bank')
        ,

            array(
                'param' => 'type',
                'type' => 'select',
                'name' => lang('transfer_type'),
              //  'values' => array_merge(array('' => lang('select_type')), array_combine($types, $types)),
                'values' => $types,
                'req' => true,
            ),

            array(
                'param' => 'bank',
                'name' => lang('transfer_bank'),
                'type' => 'select',
                'values' => $_form_bank(),
                'req' => true,
            ),

            $_form_bank_info(),

            array(
                'param' => 'acc_name',
                'name' => lang('transfer_acc_name'),
                'req' => true,
            ),

            array(
                'param' => 'acc',
                'name' => lang('transfer_acc'),
                //'req' => true,
            ),

            array(
                'param' => 'amount',
                'name' => lang('transfer_amount'),
                'req' => true,
            ),

            array(
                'param' => 'desc',
                'name' => lang('transfer_desc'),
                'req' => true,
            ),

            array(
                'param' => 'date',
                'type' => 'date',
                'name' => lang('transfer_date'),
                'value' => get_date(),
            ),

        ),

    ));
    ?>

</div>
