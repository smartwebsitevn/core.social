<?php
echo macro()->page(array('toolbar' => array()));

?>
<div class="row">
    <div class="col-lg-12">

        <!-- START YOUR CONTENT HERE -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="well  padding-25" style="background: #fff">
                    <div class="pull-left">

                        <h1 class="bigger-250"><i class="fa fa-leaf text-primary"></i> <?php echo lang('invoice') ?> #<?php echo $info->_id;// $info_id_formated ?></h1>
                    </div>

                    <div class="clearfix"></div>

                    <div class="hr hr-double hr-dotted hr-12"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <address>
                                <strong>Đơn vị bán hàng:</strong> <span class="text-danger"><?php echo  $info->info_system['name'] ?></span><br>
                                <strong><?php echo lang('phone') ?>:</strong> <?php echo $info->info_system['phone'] ?> -
                                <strong><?php echo lang('fax') ?>:</strong> <?php echo $info->info_system['fax'] ?><br/>
                                <strong><?php echo lang('address') ?>:</strong>  <?php echo  $info->info_system['address'] ?><br/>

                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <h4 class="text-primary"><?php echo lang('info') ?></h4>
                            <address>
                                <strong><?php echo lang('invoice_id') ?>:</strong> <span class="text-danger">#<?php echo  $info->_id ?></span><br>
                                <strong><?php echo lang('created') ?>:</strong> <?php echo $info->_created_time ?><br/>
                                <strong><?php echo lang('payment_due') ?>:</strong> <?php echo $info->_payment_due ?><br/>
                                <strong><?php echo lang('amount') ?>:</strong> <strong class="bigger-110 text-primary"> <?php echo /*$info->_amount_change. */$info->_amount ?></strong> <br/>

                                <strong><?php echo lang('payment') ?>:</strong> <?php echo $info->_tran->payment_name ?><br>
                                <strong><?php echo lang('status') ?>:</strong> <?php echo macro()->status_color($info->status,lang('invoice_status_'.$info->status)) ?><br>
                            </address>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <h4 class="text-primary">Người nhận</h4>
                            <address>
                                <?php //foreach('name','phone','email','address') ?>
                                <strong><?php echo lang('name') ?>:</strong> <?php echo $info->info_contact['name'] ?><br>
                                <strong><?php echo lang('phone') ?>:</strong> <?php echo $info->info_contact['phone'] ?><br>

                                <strong><?php echo lang('email') ?>:</strong> <?php echo $info->info_contact['name'] ?><br>
                                <strong><?php echo lang('address') ?>:</strong> <?php echo $info->info_contact['address'] ?><br>

                                <?php /*if ($info->_tran->user_id): ?>
													<strong><?php echo lang('user_balance') ?>:</strong> <?php echo  $info->_tran->_user_balance ?><br>

													<strong><?php echo lang('username') ?>:</strong> <?php echo   t('html')->a(admin_url('user').'?id='.$info->_tran->user_id,$info->_tran->user_name,	array('target' => '_blank')) ?><br>
													<?php if (isset($info->info_contact['group_name'])){ ?>
														<strong><?php echo lang('user_group') ?>:</strong> <?php echo $info->info_contact['group_name'] ?><br>
													<?php  }?>
												<?php endif; */?>

                            </address>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <h4 class="text-primary">Người lập</h4>
                            <address>
                                <?php //foreach('name','phone','email','address')
                                // pr($info->info_pay_to);?>
                                <strong><?php echo lang('name') ?>:</strong> <?php echo $info->info_pay_to['name'] ?><br>
                                <strong><?php echo lang('phone') ?>:</strong> <?php echo $info->info_pay_to['phone'] ?><br>

                                <strong><?php echo lang('email') ?>:</strong> <?php echo $info->info_pay_to['email'] ?><br>
                                <strong><?php echo lang('address') ?>:</strong> <?php echo $info->info_pay_to['address'] ?><br>

                            </address>
                        </div>

                    </div>

                    <table class="table table-striped table-bordered tc-table  tablet no-paging ">
                        <thead>
                        <tr>
                            <th><?php echo lang('stt') ?></th>
                            <th><?php echo lang('order') ?></th>
                            <th><?php echo lang('desc') ?></th>
                            <th  style="width: 100px;" ><?php echo lang('fee_tax') ?></th>
                            <th style="width: 125px;" ><?php echo lang('amount') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sub_total=0;$i=1;
                        foreach($info->_orders as $row):


                            $sub_total += $row->amount?>
                            <tr style="display: table-row;">
                                <td style="width:1%"><?php echo $i++ ?></td>
                                <td style="width:20%"><?php echo $row->title ?></td>
                                <td ><?php echo $row->desc ?></td>
                                <td style="width:15%"><?php echo currency_format_amount_default($row->fee_tax)  ?></td>
                                <td style="width:15%"><?php echo currency_format_amount_default($row->amount) ?></td>
                            </tr>
                            <tr><td colspan="10">
                                    <?php mod('invoice')->module($row->type)->view($row->id); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody></table>

                    <div class="row">
                        <!--<div class="col-lg-8 pull-left">
                            <div class="space-12"></div>

                            <button class="btn btn-success hidden-print" type="button">Pay Online Now <i class="fa fa-check icon-on-right"></i></button>
                        </div>-->

                        <div class="col-lg-4 pull-right">
                            <ul class="list-unstyled text-right bigger-110">
                                <li><span class="text-right">Sub - Total amount:</span> <?php echo  currency_format_amount_default($sub_total); ?></li>
<!--                                <li><span class="text-right">Fee Shipping Total:</span> <?php /*echo  $info->_fee_shipping; */?></li>
-->                                <li><span class="text-right">Tax:</span> <?php echo   $info->_fee_tax?></li>
                            </ul>
                            <p class="text-right bigger-150">Grand Total:<span class="text-danger"> <?php echo $info->_amount ?></span></p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php if ($info->note): ?>
                        <div class="alert alert-danger mt20">
                            <?php echo $info->note; ?>
                        </div>
                    <?php endif; ?>
                    <hr class="separator">
                    <div class="form-actions ">
                        <div class="form-group formSubmit ">
                            <div class="pull-right">
                                <?php if ( ! empty($info->_can_del)): ?>
                                    <a href="" _url="<?php echo $info->_url_del; ?>" class="btn btn-danger mr5 verify_action"
                                       notice="<?php echo lang('notice_confirm_delete', $info->id); ?>"
                                        ><span><?php echo lang('button_del'); ?></span></a>
                                <?php endif; ?>
                                <a href="<?php echo admin_url('invoice') ?>" class="btn  mr5 " ><span><?php echo lang('button_back'); ?></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- END YOUR CONTENT HERE -->

    </div>
</div>
