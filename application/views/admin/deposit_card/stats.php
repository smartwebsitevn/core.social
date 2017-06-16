<?php  $_id = '_' . random_string('unique');?>
<style>
#deposit_card_stats tr, #deposit_card_stats thead tr th{
	text-align:center !important;
}
#deposit_card_stats tr b{
	color:#006400;
	font-size:18px;
}
</style>
<div class="portlet" >
      <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4>
                    <i class="fa fa-list-ul"></i>
                    <?php echo lang('deposit_card_stats')?>
                </h4>
            </div>
            <div class="portlet-widgets">
                <a href="#<?php echo $_id?>" data-parent="#accordion" data-toggle="collapse"><i class="fa fa-chevron-down"></i></a>
                <span class="divider"></span>
            </div>
            <div class="clearfix"></div>
       </div>
       <div id="<?php echo $_id?>" class="panel-collapse collapse in">
            <div class="portlet-body no-padding">
                  <table id="deposit_card_stats" class="table table-bordered table-striped table-hover tc-table ">
                      <thead>
                           <tr>
                              <th><?php echo lang('total_card_amount')?></th>
                              <th><?php echo lang('total_deposit_amount')?></th>
                              <th><?php echo lang('total_profit_amount')?></th>
                           </tr>
                      </thead>
                      <tbody class="list_item">
                          <tr>
                               <td><b><?php echo $amount ?></b></td>
                               <td><b><?php echo $amount_discount ?></b></td>
                               <td><b><?php echo $profit_amount ?></b></td>
                          </tr>
                      </tbody>
                  </table>
            </div>
       </div>
</div>
