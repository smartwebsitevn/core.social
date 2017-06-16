<?php

$toolbar_addon =array(
    array(
        'url' 	=> $url_export,'title' => lang('button_export'),'icon' => 'plus',
        'attr'=>array('class'=>'btn btn-primary response_action',
            'notice'=>lang('notice_verify_export'),
            '_url' =>$url_export,
        ),
    ),

);

echo macro()->page(array('toolbar_addon' => $toolbar_addon));
$filters = array(
    array('name' => lang('id'), 'param' => 'id',
        'value' => $filter['id'],
    ),
    array('name' => lang('user'), 'param' => 'key',
        'value' => $filter['key'],
    ),
    array(
        'name' => lang('group'), 'type' => 'select', 'param' => 'user_group',
        'value' => $filter['user_group'], 'values_row' => array($user_groups, 'id', 'name'),
    ),

    array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
        'value' => $filter['created'],
    ),
    array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
        'value' => $filter['created_to'],
    ),

    array('name' => lang('status'), 'param' => 'blocked', 'type' => 'select',
        'value' => $filter['blocked'], 'values_single' => $verify, 'values_opts' => array('name_prefix' => 'blocked_'),
    ),

    array('name' => lang('status'), 'param' => 'verify', 'type' => 'select',
        'value' => $filter['verify'], 'values_single' => $user_verifies, 'values_opts' => array('name_prefix' => 'user_verify_'),
    ),

    /*array(  'name' 	=> lang('currency'),'param' => 'currency', 'type'=> 'select',
        'value' => $filter['currency'],'values_row' =>array($currency_list,'id','code'),
    ),*/
);

echo macro('mr::table')->filters($filters);
$_id = '_' . random_string('unique');
?>
<div class="portlet">
    <div class="portlet-heading bg-primary">
        <div class="portlet-title">
            <h4>
                <i class="fa fa-list-ul"></i>
                <?php echo lang('list'); ?> <?php echo lang('mod_user'); ?>
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
                    <td style="width:10px;"><img src="<?php echo public_url('admin/images/icons/tableArrows.png'); ?>"/>
                    </td>
                    <td><?php echo lang('no.'); ?></td>
                    <td><?php echo lang('user') ?></td>
                    <td><?php echo lang('name'); ?></td>
                    <td><?php echo lang('balance'); ?></td>
                    <td><?php echo lang('user_group'); ?></td>
                    <td><?php echo lang('verify'); ?></td>
                    <td style="width:40px;"><?php echo lang('status'); ?></td>
                    <td class=" text-center  "><?php echo lang('action'); ?></td>
                </tr>
                </thead>


                <tbody class="list_item">
                <?php foreach ($list as $row): //pr($row);?>
                    <tr>
                        <td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"/></td>

                        <td class="textC"><?php echo $row->id; ?></td>

                        <td>

                            <?php if (mod('user')->setting('username')): ?>
                                <?php echo $row->username ?: $row->email; ?>
                            <?php else: ?>
                                <span title="<?php echo $row->email; ?>" class="tipE">
								<?php echo character_limiter_len($row->email, 30); ?>
							</span>
                            <?php endif; ?>

                        </td>

                        <td><span title="<?php echo $row->name; ?>" class="tipE">
						<?php echo word_limiter($row->name, 5); ?>
					</span></td>

                        <td class="red">
                            <?php echo $row->_balance; ?>
                        </td>

                        <td class="textL f11">
                            <?php echo $row->user_group->name; ?>
                        </td>

                        <td class="textC f11 status">
                            <?php if ($row->_can_verify_view): ?>
                                <a href="<?php echo $row->_url_verify_view; ?>" class="tipE lightbox"
                                   title="<?php echo lang('view_verify_info'); ?>">
                                    <?php echo macro()->status_color($row->_verify,lang('user_verify_'.$row->_verify)) ?>
                                </a>
                            <?php else: ?>
                                <?php echo macro()->status_color($row->_verify,lang('user_verify_'.$row->_verify)) ?>
                            <?php endif; ?>
                        </td>

                        <td class="textC">
                                 <?php
                                 $status =$row->_blocked;
                                 echo macro()->status_color(($row->blocked?'off':'on'),lang($row->blocked ? 'blocked_yes' : 'blocked_no')) ?>
                        </td>

                        <td class="option">
                                <a href="<?php echo $row->_url_deposit; ?>" title="<?php echo lang('deposit'); ?>"
                                   class="btn btn-primary btn-xs "><?php echo lang('deposit'); ?></a>

                                <a href="<?php echo $row->_url_withdraw; ?>" title="<?php echo lang('withdraw'); ?>"
                                   class="btn btn-danger btn-xs "><?php echo lang('withdraw'); ?></a>

                                <div class="clear"></div>
                                <?php if ($row->_can_admin_login): ?>
                                    <a href="<?php echo $row->_url_admin_login; ?>" target="_blank"
                                       title="<?php echo lang('admin_login_user'); ?>" class="btn btn-primary btn-xs">
                                        <?php echo lang('button_login'); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($row->_can_block): ?>
                                    <a href="" _url="<?php echo $row->_url_block; ?>"
                                       title="<?php echo lang('block_user'); ?>"
                                       class="btn btn-danger btn-xs verify_action"
                                       notice="<?php echo lang('notice_are_you_sure_want_to_block'); ?>:<br><strong><?php echo $row->email; ?></strong>">
                                        <?php echo lang('button_block'); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($row->_can_unblock): ?>
                                    <a href="" _url="<?php echo $row->_url_unblock; ?>"
                                       title="<?php echo lang('unblock_user'); ?>"
                                       class="btn btn-primary btn-xs  verify_action"
                                       notice="<?php echo lang('notice_are_you_sure_want_to_unblock'); ?>:<br><strong><?php echo $row->email; ?></strong>"
                                        >
                                        <?php echo lang('button_unblock'); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($row->_can_edit): ?>
                                    <a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>"
                                       class="btn btn-warning btn-xs ">
                                        <?php echo lang('button_edit'); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($row->_can_del): ?>
                                    <a href="" _url="<?php echo $row->_url_del; ?>"
                                       title="<?php echo lang('delete'); ?>"
                                       class="btn btn-danger btn-xs  verify_action"
                                       notice="<?php echo lang('notice_are_you_sure_want_to_delete'); ?>:<br><b><?php echo $row->email; ?></b>"
                                        >
                                        <?php echo lang('button_delete'); ?>
                                    </a>
                                <?php endif; ?>
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