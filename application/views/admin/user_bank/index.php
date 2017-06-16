<?php

$mr = [];


$filters = array(
	array('name' => lang('id'), 'param' => 'id',
		  'value' => $filter['id'],
	),
	array('name' => lang('user'), 'param' => 'user',
		  'value' => $filter['user'],
	),
    
    array(
        'name' => lang('bank'), 'type' => 'select', 'param' => 'bank',
        'value' => $filter['bank'], 'values_row' => array($banks, 'id', 'name'),
    ),
    
	array('name' => lang('status'), 'param' => 'status', 'type' => 'select',
		  'value' => $filter['status'], 'values_single' => $order_statuss, 'values_opts' => array('name_prefix' => 'order_status_'),
	),

    array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
        'value' => $filter['created'],
    ),
    array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
        'value' => $filter['created_to'],
    ),
    
);

echo macro('mr::table')->filters($filters);
$_id = '_' . random_string('unique');
?>
<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4>
				<i class="fa fa-list-ul"></i>
				<?php echo lang('list'); ?> <?php echo lang('mod_user_bank'); ?>
				<small class="text-white">(<?php echo lang('total'); ?>:<?php echo $pages_config['total_rows']; ?>)
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
			<table class="table table-bordered table-striped table-hover tc-table">

				<thead>
					<tr>
						<th style="width:10px;">
							<img src="<?php echo public_url('admin/images/icons/tableArrows.png'); ?>"/>
						</th>
						<th><?php echo lang('user') ?></th>
        				<td><?php echo lang('bank'); ?></td>
        				<td><?php echo lang('bank_branch'); ?></td>
        				<td><?php echo lang('city'); ?></td>
        				<td><?php echo lang('bank_account'); ?></td>
        				<td><?php echo lang('bank_account_name'); ?></td>
        				<td><?php echo lang('created'); ?></td>
        				<td><?php echo lang('status'); ?></td>
        				<td><?php echo lang('action'); ?></td>
					</tr>
				</thead>


				<tbody class="list_item">

				<?php foreach ($list as $row): //pr($row);?>
					<tr>
						<td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"/></td>

						<td><?php echo '<a href="'.admin_url('user_bank').'?user='.$row->user_id.'">'.$row->user_email.'<br/>'.$row->user_phone.'</a>';; ?></td>
						
                        <td>
    						<?php echo $row->bank->name; ?>
    					</td>
    					
    					<td>
    						<?php echo $row->bank_branch; ?>
    					</td>
    					<td>
    						<?php echo $row->city->name; ?>
    					</td>
    					<td>
    						<?php echo $row->bank_account; ?>
    					</td>
    					<td>
    						<?php echo $row->bank_account_name; ?>
    					</td>
    					<td>
    						<?php echo get_date($row->created, 'full'); ?>
    					</td>
    					
    					<td>
    					    <font class="<?php echo mod('order')->status_name($row->status) ?>">
        						<?php echo macro()->status_color(mod('order')->status_name($row->status), lang('order_status_' . mod('order')->status_name($row->status))) ?>
        					</font>
    					</td>
    					
						<td class="option">
						
                            <?php if($row->status != mod('order')->status('completed')):?>
							<a href="" _url="<?php echo $row->_url_active; ?>"
							   title="<?php echo lang('notice_are_you_sure_want_to_active'); ?>"
							   class="btn btn-danger btn-xs verify_action"
							   notice="<?php echo lang('notice_are_you_sure_want_to_active'); ?>:<br><strong><?php echo  $row->bank->name; ?></strong>">
								<?php echo lang('button_active'); ?>
							</a>
							<?php endif;?>
							
							 <?php if($row->status != mod('order')->status('canceled')):?>
							<a href="" _url="<?php echo $row->_url_cancel; ?>"
							   title="<?php echo lang('notice_are_you_sure_want_to_cancel'); ?>"
							   class="btn btn-primary btn-xs  verify_action"
							   notice="<?php echo lang('notice_are_you_sure_want_to_cancel'); ?>:<br><strong><?php echo  $row->bank->name; ?></strong>"
								>
								<?php echo lang('button_cancel'); ?>
							</a>
                            <?php endif;?>
                            
							<a href="" _url="<?php echo $row->_url_del; ?>"
							   title="<?php echo lang('delete'); ?>"
							   class="btn btn-danger btn-xs  verify_action"
							   notice="<?php echo lang('notice_are_you_sure_want_to_delete'); ?>:<br><b><?php echo  $row->bank->name; ?></b>"
								>
								<?php echo lang('button_delete'); ?>
							</a>
							
						</td>
					</tr>
				<?php endforeach; ?>

				</tbody>


				<tfoot class="auto_check_pages">
				<tr>
					<td colspan="10">


						<?php if (count($actions)): ?>
							<div class="list_action itemActions pull-left">

								<div class="input-group">
									<select name="action" class="left mr10 form-control" style="width:100px">
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
						<?php $this->widget->admin->pages($pages_config); ?>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>