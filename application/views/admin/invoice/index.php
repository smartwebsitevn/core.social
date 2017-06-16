<!-- Main content wrapper -->
<?php
//pr($filter);
echo macro()->page(array('toolbar' => array(),
                       /*'toolbar_sub' =>array(
                        array(
        					'url' 	=> admin_url('add'),
        					'title' => lang('add'),
        					'icon' => 'plus',
        				    ),
        				array(
        					'url' 	=> admin_url('edit'),
        					'title' => lang('list'),
        					'icon' => 'list',
                            )
                        )*/
			    	));
$filters = array(
    array('param' => 'id',
        'value' => $filter['id'],
    ),
  /*  array(
        'param' => 'type', 'type' => 'select',
        'value' => $filter['type'], 'values_single' => $types, 'values_opts' => array('name_prefix' => 'tran_type_'),
    ),*/
   /* array('param' => 'user',
        'value' => $filter['user'],
    ),*/
    array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
        'value' => $filter['created'],
    ),
    array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
        'value' => $filter['created_to'],
    ),


    array('type' => 'sp'),
  /*  array('param' => 'tran_status', 'type' => 'select', 'name'=>lang('tran'),
        'value' => $filter['tran_status'], 'values_single' => $tran_statuss, 'values_opts' => array('name_prefix' => 'tran_status_'),
    ),
    array('name' => lang('payment'), 'param' => 'payment', 'type' => 'select',
        'value' => $filter['payment'], 'values' => $payments,
    ),
*/
);

echo macro('mr::table')->filters($filters);
$_id = '_' . random_string('unique');
?>
<div class="portlet">
    <div class="portlet-heading bg-primary">
        <div class="portlet-title">
            <h4>
                <i class="fa fa-list-ul"></i>
                <?php echo lang('list'); ?> <?php echo lang('invoice'); ?>
                <small class="text-white">(<?php echo lang('total'); ?>:<?php //echo $pages_config['total_rows']; ?>)
                </small>
            </h4>

        </div>
        <div class="portlet-widgets">
            <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $_id; ?>"><i
                    class="fa fa-chevron-down"></i></a>
            <span class="divider"></span>

        </div>
        <div class="clearfix"></div>
    </div>
    <div id="<?php echo $_id; ?>" class="panel-collapse collapse in">
        <div class="portlet-body no-padding">
            <table class="table table-bordered table-striped table-hover tc-table ">

                <thead>
                <tr>
                    <td style="width:10px;"><img src="<?php echo public_url('admin/images/icons/tableArrows.png'); ?>"/>
                    </td>
                    <td><?php echo lang('invoice_id'); ?></td>
                    <td><?php echo lang('receiver'); ?></td>
                    <td><?php echo lang('created_by'); ?></td>
                    <td><?php echo lang('amount'); ?></td>
                    <td><?php echo lang('status'); ?></td>
                    <td><?php echo lang('payment'); ?></td>
                    <td><?php echo lang('created'); ?></td>
                    <td><?php echo lang('action'); ?></td>
                </tr>
                </thead>


                <tbody class="list_item">
                <?php foreach ($list as $row): ?>
                    <tr>
                        <td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"/></td>

                        <td ><?php echo $row->_id; ?></td>

                        <td>
                            <a href="" _param="user" _value="<?php echo $row->user_id; ?>" class="view_of_field tipS"
                               title="<?php echo lang('view_of_user'); ?>:<br><?php echo $row->_user_name; ?>">
                                <?php echo $row->_user_name; ?>
                            </a>
                        </td>
                        <td class="textC"><?php echo $row->_admin_name; ?></td>
                        <td class="textR red"><?php echo $row->_amount; ?></td>
                        <td class="textC">
                            <?php echo macro()->status_color($row->status,lang('invoice_status_'.$row->status)) ?>
                        </td>
                        <td class="textC"><?php //echo $row->_tran->payment_name; ?></td>

                        <td class="textC"><?php echo $row->_created; ?></td>

                        <td class="textC">
                            <div class="btn-group btn-group-xs action-buttons">
                                <?php echo macro('mr::table')->action_row($row,array('view')); ?>
                              </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot class="auto_check_pages">
                <tr>
                    <td colspan="12">
                        <?php if (isset($actions)): ?>
                            <div class="list_action itemActions pull-left">

                                <div class="input-group">
                                    <select name="action" class="left mr10 form-control">
                                        <option value=""><?php echo lang('select_action'); ?></option>
                                        <?php foreach ($actions as $a => $u): ?>
                                            <option
                                                value="<?php echo $u; ?>"><?php echo lang('action_' . $a); ?></option>
                                        <?php endforeach; ?>
                                    </select>
								<span class="input-group-btn">
									<a href="#submit" id="submit" class="btn btn-primary">
                                        <i class="fa fa-send"></i> <?php echo lang('button_submit'); ?>
                                    </a>
								</span>
                                </div>


                            </div>
                        <?php endif; ?>

                        <?php //$this->widget->admin->pages($pages_config); ?>
                    </td>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>