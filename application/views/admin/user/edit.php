<?php echo macro()->page(); //pr($info);?>
<!-- Main content wrapper -->
<div class="row">
    <div class="col-lg-3 col-md-3">
        <div class="well well-sm white">
            <div class="profile-pic">
                <a href="#">
                    <img src="<?php echo $info->avatar->url; ?>" class="img-responsive" alt="Avatar">
                </a>
            </div>
            <p class="text-center">
                <?php foreach (model('user')->_info_social as $p): ?>
                    <?php if ($info->$p): $p_fa = ($p == 'googleplus' ? 'google-plus' : $p) ?>
                        <a href="<?php echo $info->$p ?>" target="_blank" class="btn btn-<?php echo $p ?> btn-xs"
                           data-placement="top" data-rel="tooltip" title="Visit My <?php echo ucfirst($p) ?>"><i
                                class="fa fa-<?php echo $p_fa ?> icon-only"></i></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </p>

            <div class="text-center  text-danger bigger-120">
                <?php echo lang('balance') . ': ' . $user->purse_default->{'format:balance'} ?><br>
            </div>
            <div class="text-left text-gray">
                <?php echo lang('group') . ': ' . $info->user_group_name ?><br>
                <?php echo lang('status') . ': ' . lang($info->blocked ? 'blocked_yes' : 'blocked_no') ?><br>

                <?php echo lang('verify') . ': ' . lang($info->verify ? 'verify_yes' : 'verify_no') ?><br>
                <?php echo lang('activation') . ': ' . lang($info->activation ? 'activation_yes' : 'activation_no') ?>
                <br>
            </div>
        </div>
        <div class="portlet">
            <div class="portlet-heading bg-primary">
                <div class="portlet-title">
                    <h4><?php echo lang('payment_total_amount'); ?></h4>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-body no-padding">
                <ul class="lists">
                <?php  foreach ($user_groups as $user_group): if($user_group->id != $info->user_group_id) continue ?>
                    <?php foreach ($user_group->payments as $payment): ?>
                        <li>
                            <?php echo $payment->name; ?> :
                            <b class="text-danger"><?php echo $payment->amount; ?></b>
                        </li>
                    <?php endforeach; ?>
                    <div class="clear"></div>
                <?php endforeach; ?>
                </ul>

            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9">
        <form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">
            <div class="tc-tabs"><!-- Nav tabs style 1 -->
                <ul class="nav nav-tabs tab-lg-button tab-color-dark background-dark white">

                    <li class="active"><a href="#user_overview" data-toggle="tab"><i
                                class="fa fa-desktop bigger-130"></i>Overview</a></li>
                    <li><a href="#user_account" data-toggle="tab"><i class="fa fa-user bigger-130"></i>Edit Account</a>
                    </li>
                    <li><a href="#user_info" data-toggle="tab"><i class="fa fa-info bigger-130"></i>Edit Info</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="user_overview">
                        <?php $this->load->view('admin/user/form/overview', $this->data); ?>

                    </div>
                    <div class="tab-pane fade" id="user_account">
                        <?php $this->load->view('admin/user/form/account', $this->data); ?>
                        <div class="form-actions">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" value="<?php echo lang('button_update'); ?>"
                                           class="btn btn-primary"/>
                                    <input type="reset" value="<?php echo lang('button_reset'); ?>"
                                           class="btn btn-inverse"/>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="user_info">
                        <?php $this->load->view('admin/user/form/info', $this->data); ?>
                        <div class="form-actions">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" value="<?php echo lang('button_update'); ?>"
                                           class="btn btn-primary"/>
                                    <input type="reset" value="<?php echo lang('button_reset'); ?>"
                                           class="btn btn-inverse"/>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!--nav-tabs style 1-->

        </form>
    </div>
</div>