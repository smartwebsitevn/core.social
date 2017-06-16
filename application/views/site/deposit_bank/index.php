
<div class="t-box">
	<div class="box-title">
		<h1><?php echo lang('title_deposit_bank'); ?></h1>
	</div>

	<div class="box-content">
		<?php view('tpl::deposit_bank/_common')?>
		<div class=" mb10">
			<form class="t-form form-filter" action="<?php echo $action; ?>" method="get">
			
				<?php 
					$_statuss = array('' => '');
					foreach ($statuss as $v)
					{
						$_statuss[$v] = lang('order_status_'.$v);
					}
				?>
				<div class="form-group">
					<label><?php echo lang('status'); ?></label>
					<?php echo t('html')->select('status', $_statuss, $filter['status'], array('class' => 'form-control')); ?>

				</div>

				<div class="form-group">
					<label><?php echo lang('from_date'); ?></label>
					<input name="created" value="<?php echo $filter['created']; ?>"  type="text" class="form-control datepicker" />
				</div>

				<div class="form-group">
					<label><?php echo lang('to_date'); ?></label>
					<input name="created_to" value="<?php echo $filter['created_to']; ?>"  type="text" class="form-control datepicker" />
				</div>

				<div class="form-group">
					<input type="submit" value="<?php echo lang('button_search'); ?>" class="btn btn-default btn-sm" />
					<input type="reset" value="<?php echo lang('button_reset'); ?>" class="btn btn-default btn-sm" onclick="window.location.href = '<?php echo $action; ?>'; " />
				</div>
			</form>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered">
		<thead>
			<tr>
				<td><?php echo lang('id'); ?></td>
				<td><?php echo lang('transfer_bank'); ?></td>
				<td><?php echo lang('transfer_amount'); ?></td>
				<td><?php echo lang('status'); ?></td>
				<td><?php echo lang('created'); ?></td>
				<td><?php echo lang('action'); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $row): ?>
				<tr>
					<td><?php echo $row->id; ?></td>
					<td><?php echo $row->bank; ?></td>
					<td><?php echo $row->_amount; ?></td>
					<td><?php echo macro()->status_color($row->_status,lang('order_status_'.$row->_status)); ?></td>
					<td><?php echo $row->_created; ?></td>
					
					<td class="option link textC">
						<?php if ($row->_can_view): ?>
							<a href="<?php echo $row->_url_view; ?>">
								<?php echo lang('detail'); ?>
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			
		</tbody></table>
		<div class="clear"></div>
		
		<div class="auto_check_pages">
			<?php $this->widget->site->pages($pages_config); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
