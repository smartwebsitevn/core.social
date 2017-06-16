<!-- Common view -->
<?php $this->load->view('admin/question/_common'); ?>

<!-- Widget verify action -->
<?php $this->widget->admin->verify_action(); ?>

<!-- Main content wrapper -->
<div class="wrapper">
	<div class="widget">
	
		<div class="title">
			<span class="titleIcon"><input type="checkbox" id="titleCheck" name="titleCheck" /></span>
			<h6><?php echo lang('list'); ?> <?php echo lang('mod_question'); ?></h6>
		 	<div class="num f12"><?php echo lang('total'); ?>: <b><?php echo $pages_config['total_rows']; ?></b></div>
		</div>
		
		<form action="<?php echo $action; ?>" method="get" class="form" name="filter">
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable withCheck" id="checkAll">
			
			<thead>
				<tr>
					<td style="width:10px;"><img src="<?php echo public_url('admin'); ?>/images/icons/tableArrows.png" /></td>
					<td style="width:100px;"><?php echo lang('sort_order'); ?></td>
					<td><?php echo lang('title'); ?></td>
					<td style="width:80px;"><?php echo lang('created'); ?></td>
					<td style="width:10px;"><?php echo lang('action'); ?></td>
				</tr>
			</thead>

 			<tfoot class="auto_check_pages">
				<tr>
					<td colspan="6">
						<div class="itemActions">
							<select name="action" class="left mr10">
								<option value=""><?php echo lang('select_action'); ?></option>
								<?php foreach ($actions as $a): ?>
									<option value="<?php echo $a; ?>"><?php echo lang('action_'.$a); ?></option>
								<?php endforeach; ?>
							</select>
							
							<a href="#" id="submit_action" _action="<?php echo $actions_url; ?>" class="button blueB">
								<span style="color:white;"><?php echo lang('button_submit'); ?></span>
							</a>
						</div>
						
						<?php $this->widget->admin->pages($pages_config); ?>
					</td>
				</tr>
			</tfoot>
			
			<tbody>
				<!-- Filter -->
				<tr>
					<td></td>
					
					<td></td>
					
					<td></td>
					<td></td>
					
					<td>
						<input type="submit" class="button basic" value="<?php echo lang('filter')?>" style="width:70px;" />
					</td>
				</tr>
				
				<!-- List -->
				<?php foreach ($list as $row): ?>
					<tr>
						<td></td>
						
						<td class="textC"><?php echo $row->sort_order; ?></td>
						
						<td>
								<?php echo $row->title; ?>
						</td>
						
						<td class="textC"><?php echo $row->_created; ?></td>
						
						<td class="option">
							<a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>" class="tipS"><img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" /></a>
	
							<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action" 
								notice="<?php echo lang('notice_are_you_sure_want_to_delete'); ?>:<br><b><?php echo $row->title; ?></b>"
							>
								<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				
			</tbody>
		</table>
		</form>
		
	</div>
</div>
        