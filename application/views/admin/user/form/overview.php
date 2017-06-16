<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet no-border">
            <div class="portlet-heading">
                <div class="portlet-title">
                    <h2><?php echo $info->name ?></h2>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-body">
                <div>
                    <?php echo $info->desc ?>
                </div>

                <div class="space-4"></div>

                <address>
                    <a href="mailto:#" id="email"><?php echo $info->email ?></a>
                </address>

                <span class="">Last Login: </span><span><?php echo $info->_last_login ?></span>

                <div class="space-4"></div>

                <!--<ul class="list-inline well well-sm">
                    <li><i class="fa fa-flag bigger-110"></i> <a href="#" id="country" data-value="US" class="editable">Uinited State</a></li>
                    <li><i class="fa fa-calendar bigger-110"></i> <a href="#" id="dob" class="editable">28th March, 2014</a></li>
                    <li><i class="glyphicon glyphicon-certificate bigger-110"></i> RedHat Certification</li>
                </ul>-->
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet no-border-bottom">
            <div class="portlet-heading bg-primary">
                <div class="portlet-title">
                    <h4><i class="fa fa-rss"></i> Balance Change</h4>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="portlet-body no-padding">
                <table class="table table-bordered table-striped table-hover tc-table ">
                    <thead>
                    <tr>
                        <th class="col-4 textC"><?php echo  lang('created') ?></th>
                        <th class="col-2 textC"><?php echo  lang('change') ?></th>
                        <th class="textR"><?php echo  lang('amount') ?></th>
                        <th class="textR"><?php echo  lang('balance') ?></th>
                    </tr>
                    </thead>
                    <tbody class="list_item">
                    <?php foreach ($balances as $it):     ?>
                        <tr>
                            <td class="textC"><?php echo $it->_created_full ?></td>
                            <td class="textC "><?php echo $it->change ?>    </td>
                            <td class="textR">  <?php echo $it->_amount ?>      </td>
                            <td class="textR"> <b><?php echo $it->_balance ?></b> </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
              </div>
        </div>

        <div class="hr hr-12 hr-double"></div>
        <div class="action-buttons pull-right">
            <a href="<?php echo admin_url('log_balance').'?user_key='.$info->email ?>" target="_blank"><i class="fa fa-search-plus"></i> View all</a>
        </div>

    </div>
</div>
<div class="space-20"></div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet no-border-bottom">
            <div class="portlet-heading bg-primary">
                <div class="portlet-title">
                    <h4><i class="fa fa-rss"></i> Recent Activities</h4>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="portlet-body no-padding">
                <ul class="lists">
                    <?php foreach ($activities as $it): ?>
                        <li>
                            <span class="date"><?php echo $it->_created_full ?></span>
                            <?php echo $it->detail ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="hr hr-12 hr-double"></div>
        <div class="action-buttons pull-right">
            <a href="<?php echo admin_url('log_activity/user').'?acc='.$info->id ?>" target="_blank"><i class="fa fa-search-plus"></i> View all</a>
        </div>

    </div>
</div>