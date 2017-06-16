
<!-- Common -->
<?php echo macro()->page(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<div class="widget">
		<div class="title">
			<span class="titleIcon"><input type="checkbox" id="titleCheck" name="titleCheck" /></span>
			<h6><?php echo lang('list'); ?> <?php echo lang('mod_partner'); ?></h6>
		 	<div class="num f12"><?php echo lang('total'); ?>: <b><?php echo $total; ?></b></div>
		</div>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable" id="checkAll">
			
			<thead class="filter"><tr><td colspan="20">
				<form class="list_filter form" action="<?php echo $action; ?>" method="get">
					<table cellpadding="0" cellspacing="0" width="100%"><tbody>
						<tr><td>
							<div class="row">
								<label class="mr5"><?php echo lang('id'); ?></label>
								<input name="id" value="<?php echo $filter['id']; ?>" type="text" style="width:100px;" />
							</div>
							
							<div class="row">
								<label class="mr5"><?php echo lang('key'); ?></label>
								<input name="key" value="<?php echo $filter['key']; ?>" type="text" style="width:200px;" />
							</div>
							
							<div class="row">
								<input type="submit" class="button blueB" value="<?php echo lang('search')?>" />
								<input type="reset" class="basic" value="Reset" onclick="window.location.href = '<?php echo $action; ?>'; ">
							</div>
						</td></tr>
					</tbody></table>
				</form>
			</td></tr></thead>
			
			<thead>
				<tr>
					<td style="width:10px;"><img src="<?php echo public_url('admin/images/icons/tableArrows.png'); ?>" /></td>
					<td><?php echo lang('id'); ?></td>
					<td><?php echo lang('name'); ?></td>
					<td><?php echo lang('email'); ?></td>
					<td><?php echo lang('phone'); ?></td>
					<td style="width:70px;"><?php echo lang('action'); ?></td>
				</tr>
			</thead>
			
 			<tfoot class="auto_check_pages">
				<tr>
					<td colspan="20">
						<?php if (count($actions)): ?>
							<div class="list_action itemActions">
								<select name="action" class="left mr10">
									<option value=""><?php echo lang('select_action'); ?></option>
									<?php foreach ($actions as $a => $u): ?>
										<option value="<?php echo $u; ?>"><?php echo lang('action_'.$a); ?></option>
									<?php endforeach; ?>
								</select>
								
								<a href="#submit" id="submit" class="button blueB">
									<span class="white"><?php echo lang('button_submit'); ?></span>
								</a>
							</div>
						<?php endif; ?>
						
						<?php $this->widget->admin->pages($pages_config); ?>
					</td>
				</tr>
			</tfoot>
			
			<tbody class="list_item">
			<?php foreach ($list as $row): ?>
				<tr>
					<td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>" /></td>
				
					<?php foreach (array('id', 'name', 'email', 'phone') as $p): ?>
						<td><?php echo $row->{$p}; ?></td>
					<?php endforeach; ?>
					
					<td class="option">
						<?php if ($row->_can_edit): ?>
							<a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>" class="tipE">
								<img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" />
							</a>
						<?php endif; ?>
						
						<?php if ($row->_can_del): ?>
							<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipE verify_action" 
								notice="<?php echo lang('notice_confirm_delete'); ?>"
							><img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" /></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			
		</table>
	</div>
	
</div>
        